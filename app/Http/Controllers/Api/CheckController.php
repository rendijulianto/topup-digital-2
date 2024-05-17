<?php 
namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;

use Gonon\Digiflazz\{Digiflazz,Transaction,Balance};
use App\Models\{CekNama, Brand, Kategori, Topup,CekVoucher};
use Illuminate\Support\Str;
use Illuminate\Http\Request;
// service Digiflazz
use App\Services\DigiflazzService;
set_time_limit(3600);
class CheckController extends Controller
{


    public function eWallet(Request $request)
    {   
        $this->validate($request, [
            'nomor' => 'required|numeric',
            'brand_id' => 'required|exists:brand,id',
            'brand' => 'required|string|exists:brand,nama',
        ], [
            'nomor.required' => 'Nomor tidak valid!',
            'nomor.numeric' => 'Nomor tidak valid!',
            'brand_id.required' => 'Brand tidak valid!',
            'brand_id.exists' => 'Brand tidak valid!',
        ]);
        
        $cekNama = CekNama::where([
            'nomor' => $request->nomor,
            'brand_id' => $request->brand_id,
            'status' => 'sukses',
        ])->whereDate('created_at', '>=', now()->subYear())->first();
    
        if ($cekNama) {
            return response()->json([
                'status' => true,
                'data' => [
                    'nomor' => $cekNama->nomor,
                    'nama' => $cekNama->nama,
                ],
            ]);
        }
    
        $ref_id = time().rand(100, 999);
    
        $cekNama = CekNama::firstOrCreate([
            'brand_id' => $request->brand_id,
            'nomor' => $request->nomor,
            'status' => 'pending',
        ], [
            'ref_id' => $ref_id,
            'nama' => '-',
        ]);

        $params = [
            'brand' => $request->brand,
            'customer_no' => $request->nomor,
            'ref_id' => $ref_id
        ];

    
        $startTime = microtime(true);
        $timeLimit = 20;
        $digiflazzService = new DigiflazzService();
        while (true) {
            
            $validateEWallet = $digiflazzService->validateEWallet($params);

            if (!$validateEWallet['status']) {
                if ($validateEWallet['message'] == "SKU tidak di temukan atau Non-Aktif") {
                    return response()->json([
                        'status' => true,
                        'data' => [
                            'nomor' => $cekNama->nomor,
                            'nama' => '-',
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
                $cekNama->update([
                    'nama' => $validateEWallet['data']['sn'],
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
                'nomor' => $cekNama->nomor,
                'nama' => $cekNama->nama,
            ],
        ]);
    }

    public function voucher(Request $request)
    {

      
        $this->validate($request, [
            'nomor' => 'required|numeric',
            'brand_id' => 'required|exists:brand,id',
            'brand' => 'required|string|exists:brand,nama',
        ], [
            'nomor.required' => 'Nomor tidak valid!',
            'nomor.numeric' => 'Nomor tidak valid!',
            'brand_id.required' => 'Brand tidak valid!',
            'brand_id.exists' => 'Brand tidak valid!',
            'brand.required' => 'Brand tidak valid!',
            'brand.exists' => 'Brand tidak valid!',
        ]);
        
       try {
        $voucher = Topup::where('nomor', $request->nomor)
                ->whereIn('status', ['pending', 'sukses'])->where('tipe', 'voucher')->first();
        if(!$voucher) {
            return response()->json([
                'status' => false,
                'message' => 'Voucher tidak ditemukan!',
            ]);
        }
    
        if($request->brand == "telkomsel") {
            $nomor = substr($request->nomor, 0, 12);
        } else {
            $nomor = $request->nomor;
        }

    
 
        $cekVoucher = CekVoucher::create([
            'brand_id' => $request->brand_id,
            'nomor' => $request->nomor,
            'status' => 'pending',
            'ref_id' => time().rand(100, 999),
            'keterangan' => '-'
        ]);

        $params = [
            'brand' => $request->brand,
            'customer_no' => $nomor,
            'ref_id' => $cekVoucher->ref_id
        ];
    
    
        $startTime = microtime(true);
        $timeLimit = 20;
        $digiflazzService = new DigiflazzService();
        while (true) {
            $validateVoucher = $digiflazzService->validateVoucher($params);
            if (!$validateVoucher['status']) {
                return response()->json([
                    'status' => false,
                    'message' => $validateVoucher['message'],
                ]);
            }
            if ($validateVoucher['data']['status'] == "Sukses") {
                $cekVoucher->update([
                    'keterangan' => $validateVoucher['data']['sn'],
                    'status' => 'sukses',
                ]);
                break;
            } else if ($validateVoucher['data']['status'] == "Gagal") {
                $cekVoucher->update([
                    'keterangan' => $validateVoucher['data']['message'],
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
        $histories = CekVoucher::where([
            'nomor' => $request->nomor,
            'brand_id' => $request->brand_id,
        ])->orderBy('id', 'desc')->get(['keterangan', 'status', 'created_at'])->map(function($item) {
            return [
                'keterangan' => $item->keterangan,
                'status' => $item->status,
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
            ];
        });
        return response()->json([
            'status' => true,
            'data' => [
                'nomor' => $cekVoucher->nomor,
                'riwayat' => $histories,
                'voucher' => [
                    'nomor' => $voucher->nomor,
                    'produk' => $voucher->produk->nama,
                    'keterangan' => $voucher->keterangan,
                    'harga_jual' => $voucher->harga_jual,
                ]
            ],
        ]);
       } catch (\Throwable $th) {
              return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
              ], $th->getCode());
       }

    }
    
    public function pln(Request $request)
    {
        $this->validate($request, [
             'nomor' => 'required|numeric|digits_between:9,15',
        ], [
            'nomor.digits_between' => 'Nomor harus diantara 9 sampai 15 digit!',
            'nomor.numeric' => 'Nomor tidak valid!',
            'nomor.required' => 'Nomor tidak valid!',
        ]);
        try {
            $params = [
                'customer_no' => $request->nomor,
            ];

            $digiflazzService = new DigiflazzService();

            $iquiryPLN = $digiflazzService->validatePLN($params);
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

    public function profile()
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