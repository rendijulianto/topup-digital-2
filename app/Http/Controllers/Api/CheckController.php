<?php 
namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;

use App\Models\{NameCheck, Topup,VoucherCheck};
use Illuminate\Http\Request;
use App\Services\DigiflazzService;
set_time_limit(3600);
class CheckController extends Controller
{

    public function eWallet(Request $request)
    {   
        $this->validate($request, [
            'target' => 'required|numeric',
            'brand_id' => 'required|exists:brands,id',
            'brand' => 'required|string|exists:brands,name',
        ], [
            'target.required' => 'Nomor tidak valid!',
            'target.numeric' => 'Nomor tidak valid!',
            'brand_id.required' => 'Brand tidak valid!',
            'brand_id.exists' => 'Brand tidak valid!',
        ]);
        
        $nameCheck = NameCheck::where([
            'target' => $request->target,
            'brand_id' => $request->brand_id,
            'status' => 'sukses',
        ])->whereDate('created_at', '>=', now()->subYear())->first();
    
        if ($nameCheck) {
            return response()->json([
                'status' => true,
                'data' => [
                    'target' => $nameCheck->target,
                    'name' => $nameCheck->name,
                ],
            ]);
        }
    
        $ref_id = time().rand(100, 999);
    
        $nameCheck = NameCheck::firstOrCreate([
            'brand_id' => $request->brand_id,
            'target' => $request->target,
            'status' => 'pending',
        ], [
            'ref_id' => $ref_id,
            'name' => '-',
        ]);

        $params = [
            'brand' => $request->brand,
            'customer_no' => $request->target,
            'ref_id' => $ref_id
        ];

    
        $startTime = microtime(true);
        $timeLimit = 20;
        while (true) {
            
            $validateEWallet = DigiflazzService::validateEWallet($params);

            if (!$validateEWallet['status']) {
                if ($validateEWallet['message'] == "SKU tidak di temukan atau Non-Aktif" || $validateEWallet['message'] == "Produk sedang Gangguan (Non Aktif)") {
                    return response()->json([
                        'status' => true,
                        'data' => [
                            'target' => $nameCheck->target,
                            'name' => '-',
                        ],
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => $validateEWallet['message'],
                    ]);
                }
            }
    
            if ($validateEWallet['data']['status'] == "Sukses") {
                $nameCheck->update([
                    'name' => $validateEWallet['data']['sn'],
                    'status' => 'sukses',
                ]);
                break;
            } else if ($validateEWallet['data']['status'] == "Gagal") {
                return response()->json([
                    'status' => false,
                    'message' => $validateEWallet['data']['message'],
                ]);
            } else if (microtime(true) - $startTime >= $timeLimit) {
                return response()->json([
                    'status' => false,
                    'message' => "Silahkan coba beberapa saat lagi!",
                ]);
            }
    
            sleep(2);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'target' => $nameCheck->target,
                'name' => $nameCheck->name,
            ],
        ]);
    }

    public function voucher(Request $request)
    {

     
        $this->validate($request, [
            'target' => 'required|numeric',
            'brand_id' => 'required|exists:brands,id',
            'brand' => 'required|string|exists:brands,name',
        ], [
            'target.required' => 'Nomor tidak valid!',
            'target.numeric' => 'Nomor tidak valid!',
            'brand_id.required' => 'Brand tidak valid!',
            'brand_id.exists' => 'Brand tidak valid!',
            'brand.required' => 'Brand tidak valid!',
            'brand.exists' => 'Brand tidak valid!',
        ]);
        
       try {
        $voucher = Topup::where('target', $request->target)->whereIn('status', ['pending', 'sukses'])->where('type', 'voucher')->first();

    
        if(!$voucher) {
            return response()->json([
                'status' => false,
                'message' => 'Voucher tidak ditemukan!',
            ]);
        }
    
        if($request->brand == "telkomsel") {
            $target = substr($request->target, 0, 12);
        } else {
            $target = $request->target;
        }


    
 
        $voucherCheck = VoucherCheck::create([
            'brand_id' => $request->brand_id,
            'target' => $request->target,
            'status' => 'pending',
            'ref_id' => time().rand(100, 999),
            'note' => '-'
        ]);

        $params = [
            'brand' => $request->brand,
            'customer_no' => $target,
            'ref_id' => $voucherCheck->ref_id
        ];
    
    
        $startTime = microtime(true);
        $timeLimit = 20;
        
        while (true) {
            $validateVoucher = DigiflazzService::validateVoucher($params);
            if (!$validateVoucher['status']) {
                return response()->json([
                    'status' => false,
                    'message' => $validateVoucher['message'],
                ]);
            }
            if ($validateVoucher['data']['status'] == "Sukses") {
                $voucherCheck->update([
                    'note' => $validateVoucher['data']['sn'],
                    'status' => 'sukses',
                ]);
                break;
            } else if ($validateVoucher['data']['status'] == "Gagal") {
                $voucherCheck->update([
                    'note' => $validateVoucher['data']['message'],
                    'status' => 'gagal',
                ]);
                return response()->json([
                    'status' => false,
                    'message' => $validateVoucher['data']['message'],
                ]);
            } else if (microtime(true) - $startTime >= $timeLimit) {
                return response()->json([
                    'status' => false,
                    'message' => "Silahkan coba beberapa saat lagi!",
                ]);
            }
            sleep(2);
        }
        $histories = VoucherCheck::where([
            'target' => $request->target,
            'brand_id' => $request->brand_id,
        ])->orderBy('id', 'desc')->get(['note', 'status', 'created_at'])->map(function($item) {
            return [
                'note' => $item->note,
                'status' => $item->status,
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
            ];
        });
        return response()->json([
            'status' => true,
            'data' => [
                'target' => $voucherCheck->target,
                'histories' => $histories,
                'voucher' => [
                    'target' => $voucher->target,
                    'product' => $voucher->product->name,
                    'note' => $voucher->note,
                    'price_sell' => $voucher->price_sell,
                ]
            ]
        ]);
       } catch (\Throwable $th) {
              return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
              ], 500);
       }
    }
    
    public function pln(Request $request)
    {
        $this->validate($request, [
             'target' => 'required|numeric|digits_between:9,15',
        ], [
            'target.digits_between' => 'Nomor harus diantara 9 sampai 15 digit!',
            'target.numeric' => 'Nomor tidak valid!',
            'target.required' => 'Nomor tidak valid!',
        ]);
        try {
            $params = [
                'customer_no' => $request->target,
            ];

            $iquiryPLN = DigiflazzService::validatePLN($params);
            if ($iquiryPLN['status'] == false) {
                return response()->json([
                    'status' => false,
                    'message' => $iquiryPLN['message'],
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $iquiryPLN['data'],
            ]);
            
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function balance()
    {
        try {
            $digiflazz = new DigiflazzService();
            return response()->json([
                'status' => true,
                'data' => [
                    'deposit' => $digiflazz->getBalance()['data']['deposit']
                ],
            ]);
        } catch (\Gonon\Digiflazz\Exceptions\ApiException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'code' => $e->getErrorCode(),
            ], 500);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}