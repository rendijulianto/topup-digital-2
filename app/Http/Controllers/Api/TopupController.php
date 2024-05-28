<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\{Topup,  Product,TopupApi,ActivityLog};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\{DigiflazzService, PiwapiService, SocketService};

use Illuminate\Support\Facades\Hash;
set_time_limit(3600);
class TopupController extends Controller
{
    
    // store
    public function store(Request $request)
    {
        $this->validate($request, [
           'product_id' => 'required|exists:products,id',
           'target' => 'required|numeric',
           'whatsapp' => 'nullable|numeric|digits_between:10,13',
        ], [
            'target.required' => 'Nomor tidak boleh kosong',
            'target.numeric' => 'Nomor tidak valid',
            'whatsapp.numeric' => 'Nomor whatsapp tidak valid',
            'whatsapp.digits_between' => 'Nomor whatsapp tidak valid',
            'product_id.required' => 'Product tidak boleh kosong',
            'product_id.exists' => 'Product tidak ditemukan',
        ]);
        
        DB::beginTransaction();
        try {
          
            $user= auth()->user();
            
            $product = Product::find($request->product_id);
            
            if($product->category->name == 'E-Money') {
                $type = 'e_wallet';
            } else if ($product->category->name == 'PLN') {
                $type = 'token_listrik';
            } else {
                $type = 'seluler';
            }

            $topup = Topup::create([
                'product_id' => $product->id,
                'category_id' => $product->category_id,
                'brand_id' => $product->brand_id,
                'type_id' => $product->type_id,
                'cashier_id' => $user->id,
                'price_buy' => 0,
                'price_sell' => $product->price,
                'target' => $request->target,
                'whatsapp' => $request->whatsapp ?? $request->target,
                'type' => $type,
                'transacted_at' => now(),
            ]);

            if($product->category->name == 'E-Money') {
                $topup->e_wallet()->create([
                    'customer_name' => $request->customer_name ?? $topup->target,
                ]);
            } else if($product->category->name == 'PLN') {
                $topup->token_listrik()->create([
                    'customer_name' => $request->customer_name ?? $topup->target,
                    'subscriber_id' => $request->subscriber_id ?? '-',
                    'meter_no' => $request->meter_no ?? '-',
                    'segment_power' => $request->segment_power ?? '-',
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Topup berhasil dibuat',
                'data' => $topup,
            ], 201);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => env('APP_DEBUG') ? $th->getMessage() : 'Topup gagal dibuat',
            ], 500);
        }
    }

    public function storeVoucher(Request $request)
    {
       
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:product_suppliers,id',
            'target' => 'required',
            'pin' => 'required|numeric',
            'expired_at' => 'required|date',
        ], [
            'target.required' => 'Nomor tidak boleh kosong',
            'target.numeric' => 'Nomor tidak valid',
            'product_id.required' => 'Product tidak boleh kosong',
            'product_id.exists' => 'Product tidak ditemukan',
            'supplier_id.required' => 'Supplier tidak boleh kosong',
            'supplier_id.exists' => 'Supplier tidak ditemukan',
            'pin.required' => 'Pin tidak boleh kosong',
            'pin.numeric' => 'Pin tidak valid',
            'expired_at.required' => 'Tanggal kadaluwarsa tidak boleh kosong',
            'expired_at.date' => 'Tanggal kadaluwarsa tidak valid',
        ]);

        // Langkah 0: Cek pin dan limit 3x
        if (!Hash::check($request->pin, Auth::guard()->user()->pin->pin)) {
            return response()->json([
                'status' => false,
                'message' => 'Pin tidak valid',
            ], 401);
        }
    

        $product = Product::find($request->product_id);
        $supplierProduct = $product->product_suppliers()->where('id', $request->supplier_id)->firstOrFail();
        $target = $request->target;
        $user = auth()->user();

        $data = [
            'target' => $target,
            'brand' => $product->brand->name,
            'product' => $product->name,
            'status' => 'Pending',
            'message' => 'Topup sedang diproses',
            'tanggal' => date('d-m-Y H:i:s'),
        ];

        DB::beginTransaction();
        try {
            $topup = Topup::where('target', $target)->whereIn('status', ['pending', 'sukses'])->first();
    
            if($topup) {
                $data['status'] = $topup->status_html;
                $data['message'] = $topup->note;
                return response()->json([
                    'status' => false,
                    'message' => 'Nomor Tujuan sudah pernah dipesan dan statusnya pending/sukses',
                    'data' => $data,
                ], 400);
            } else {
                $topup = Topup::create([
                    'product_id' => $product->id,
                    'category_id' => $product->category_id,
                    'brand_id' => $product->brand_id,
                    'type_id' => $product->type_id,
                    'cashier_id' => null,
                    'price_sell' => $product->price,
                    'price_buy' => $supplierProduct->price,
                    'target' => $target,
                    'whatsapp' => null,
                    'type' => 'voucher',
                    'transacted_at' => null,
                ]);
            }

          
            $topup->voucher()->updateOrCreate(
                ['topup_id' => $topup->id],
                ['expired_at' => $request->expired_at]
            );

            $ref_id = time() . rand(100, 999);
            $params = [
                'buyer_sku_code' => $supplierProduct->product_sku_code,
                'customer_no' => $target,
                'ref_id' => $ref_id,
            ];
            
            
    
            $response = DigiflazzService::createTransaction($params);
        

            if(!$response['status']) {
                return response()->json([
                    'status' => false,
                    'message' => $response['message'],
                    'data' => $data,
                ], 500);
            }

            $topup->update([
                'status' => $response['data']['status'],
                'price_buy' => $supplierProduct->price,
                'note' => $response['data']['message'] ?? '-',
            ]);
    
    
           $topup->topup_api()->create([
                'product_supplier_id' => $supplierProduct->id,
                'supplier_id' => $supplierProduct->supplier_id,
                'user_id' => Auth::guard()->user()->id,
                'ref_id' => $ref_id,
                'note' => $response['data']['message'],
                'status' => $response['data']['status']
            ]);

            $data['status'] = $response['data']['status'];
            $data['message'] = $response['data']['message'];
            ActivityLog::create([
                'user_id' => Auth::guard()->user()->id,
                'ip' => $request->ip(),
                'note' => 'Melakukan Topup ID: ' . $topup->id . ' dengan supplier ' . $supplierProduct->supplier->name . ' dengan harga beli ' . $supplierProduct->harga,
                'user_agent' => $request->header('User-Agent'),
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Topup berhasil dibuat',
                'data' => $data,
            ], 201);

            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'message' => env('APP_DEBUG') ? $th->getMessage() : 'Topup gagal dibuat',
                ], 500);
            }
    }

    public function storeTopup(Request $request, Topup $topup)
    {
        $this->validate($request, [
            'product_supplier_id' => 'required|exists:product_suppliers,id',
            'pin' => 'required|numeric',
        ], [
            'product_supplier_id.required' => 'Supplier product tidak boleh kosong',
            'product_supplier_id.exists' => 'Supplier product tidak ditemukan',	
            'pin.required' => 'Pin tidak boleh kosong',
            'pin.numeric' => 'Pin tidak valid',
        ]);

        
        DB::beginTransaction();
        try {
            $user = auth()->user();

            if (!Hash::check($request->pin, $user->pin->pin)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Pin tidak valid',
                ], 422);
            }

            $product = $topup->product->product_suppliers()->where('id', $request->product_supplier_id)->firstOrFail();

            if (!$product->multi) {
                $cek = $topup->topup_api()->where('product_supplier_id', $request->product_supplier_id)->exists();
                if ($cek) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Target sudah pernah ditopup dengan supplier yang sama',
                    ], 422);
                }
            }


            // cek start_cut_off dan end_cut_off
            // sekarang jam berapa
            $time = Carbon::now()->format('H:i:s');
            if ($time > $product->start_cut_off || $time < $product->end_cut_off) {
                return response()->json([
                    'status' => false,
                    'message' => 'Produk sedang dalam masa cut off',
                ], 422);
            }

            if ($product->stock == 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Stok product habis',
                ], 422);
            }

            if ($product->status == 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Supplier tidak aktif',
                ], 422);
            }


            if($topup->topup_api()->where('status', 'pending')->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Masih ada pesanan pending',
                ], 422);
            }

            $ref_id = time() . rand(100, 999);
            $params = [
                'buyer_sku_code' => $product->product_sku_code,
                'customer_no' => $topup->target,
                'ref_id' => $ref_id,
            ];
            
                         
            $response = DigiflazzService::createTransaction($params);
            $price_buy =  $response['data']['price']?? $product->price;


            if (!$response['status']) {
                return response()->json([
                    'status' => false,
                    'message' => $response['message'],
                ], 422);
            }

            $topup->update([
                'cashier_id' => $user->id,
                'status' => $response['data']['status'],
                'price_buy' => $price_buy,
                'note' => $response['data']['sn'] ?? '-',
                'transacted_at' => now(),
            ]);

            $topup->topup_api()->create([
                'product_supplier_id' => $product->id,
                'supplier_id' => $product->supplier_id,
                'user_id' => $user->id,
                'ref_id' => $ref_id,
                'note' => $response['data']['message'],
                'status' => $response['data']['status'],
            ]);

            ActivityLog::create([
                'user_id' => $user->id,
                'ip' => $request->ip(),
                'note' => 'Melakukan Topup ID: ' . $topup->id .' dengan supplier ' . $product->supplier->name . ' dengan harga beli ' . $price_buy,
                'user_agent' => $request->header('User-Agent'),
            ]);

            DB::commit();
            PiwapiService::sendMessage($topup->whatsapp, $topup->whatsapp_message);
            return response()->json([
                'status' => true,
                'message' => 'Topup berhasil diproses',
                'data' => $topup,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => env('APP_DEBUG') ? $th->getMessage() : 'Topup gagal diproses',
            ], 500);
        }
    }

    // process
    public function supplier(Topup $topup)
    {
        return response()->json([
            'status' => true,
            'message' => 'Detail topup',
            'data' => $topup->product->getSupplier()
        ]);
    }


    // show
    public function show(Topup $topup)
    {
        $note = $topup->note;
        if($topup->type == 'e_wallet') {
            $detail = [
                'customer_name' => $topup->e_wallet->customer_name,
            ];
        } else if($topup->type == 'token_listrik') {
            $detail = [
                'customer_name' => $topup->token_listrik->customer_name,
                'subscriber_id' => $topup->token_listrik->subscriber_id,
                'meter_no' => $topup->token_listrik->meter_no,
                'segment_power' => $topup->token_listrik->segment_power,
            ];
            $note = Str::before($note, '/');
        } else if($topup->type == 'voucher') {
            $detail = [
                'expired_at' => $topup->voucher->expired_at->format('d-m-Y H:i:s'),
            ];
        } else {
            $detail = null;
        }

        $result = [
            'id' => $topup->id,
            'target' => $topup->target,
            'price_sell' => "Rp " . number_format($topup->price_sell, 0, ',', '.'),
            'price_buy' => "Rp " . number_format($topup->price_buy, 0, ',', '.'),
            'product' => $topup->product->name,
            'note' => $note,
            'status' =>  Str::ucfirst($topup->status),
            'type' => $topup->type,
            'transacted_at' => $topup->transacted_at ?? '-',
            'whatsapp' => $topup->whatsapp,
            'detail' => $detail,
            'cashier' => $topup->cashier->name ?? '-',
            'logs' => $topup->topup_api->map(function ($log) {
                return [
                    'id' => $log->id,
                    'supplier' => $log->supplier->name,
                    'note' => $log->note,
                    'status' => Str::ucfirst($log->status),
                    'created_at' => $log->created_at->format('d-m-Y H:i:s'),
                    'user' => $log->user->name ?? '-',
                ];
            })->sortByDesc('date')->values()->all()
        ];

        return response()->json([
            'status' => true,
            'message' => 'Detail topup',
            'data' => $result
        ]);
    }


    //cekStatus -> ref_id anu dicek
    public function checkStatus(Request $request, Topup $topup)
    {
        DB::beginTransaction();
        try {
           
            $params = [
                'ref_id' => $topup->topup_api->last()->ref_id,
                'buyer_sku_code' => $topup->topup_api->last()->product_suppliers->product_sku_code,
                'customer_no' => $topup->target,
            ];
            $response = DigiflazzService::createTransaction($params);
         
            if(!$response['status']) {
                throw new \Exception($response['message'], 422);
            }
            $topup->update([
                'status' => $response['data']['status'],
                'note' => $response['data']['sn'] ?? '-',
                'transacted_at' => $topup->transacted_at ?? now(),
            ]);
            $topup->topup_api->last()->update([
                'note' => $response['data']['message'],
                'status' => $response['data']['status'],
                'response' => $topup->topup_api->last()->response . "\n" . json_encode($response['data']),
            ]);
            DB::commit();
            if(strtolower($response['data']['status']) != strtolower($topup->status)) {
                PiwapiService::sendMessage($topup->whatsapp, $topup->whatsapp_message);
            }
            return response()->json([
                'status' => true,
                'message' => 'Topup berhasil diproses',
                'data' =>  [
                    'status' => $response['data']['status'],
                    'message' => $response['data']['message'],
                ]
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => env('APP_DEBUG') ? $th->getMessage() : 'Topup gagal diproses',
            ], 500);
        }
    }

    // cancel
    public function cancel(Request $request, Topup $topup)
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();
            $topup->update([
                'status' => 'gagal',
                'price_buy' => 0,
                'note' => 'Top up dibatalkan oleh kasir',
                'cashier_id' => $user->id,
            ]);
            ActivityLog::create([
                'user_id' => $user->id,
                'ip' => $request->ip(),
                'note' => 'Membatalakan topup ID: ' . $topup->id,
                'user_agent' => $request->header('User-Agent'),
            ]);
            DB::commit();
            PiwapiService::sendMessage($topup->whatsapp, $topup->whatsapp_message);
            return response()->json([
                'status' => true,
                'message' => 'Topup berhasil dibatalkan',
                'data' => $topup,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => env('APP_DEBUG') ? $th->getMessage() : 'Topup gagal dibatalkan',
            ], 500);
        }
    }

    public function voucher(Request $request, $target)
    {
        $topup = Topup::where('target', $target)->orderBy('created_at', 'desc')->first();
        if(!$topup) {
            return response()->json([
                'status' => false,
                'message' => 'Voucher tidak ditemukan',
            ], 404);
        }
        $topup->load('voucher');
     
        $name = $topup->product->name;
        $name = Str::replaceFirst('Aktivasi ', '', $name);
        $status = $topup->cashier_id ? 'Sudah Terjual' : 'Belum Terjual';
        return response()->json([
            'status' => true,
            'message' => 'Detail Produk',
            'data' => [
                'id' => $topup->id,
                'name' => $name,
                'price_sell' => 'Rp ' . number_format($topup->price_sell, 0, ',', '.'),
                'status' => $status,
                'transacted_at' => $topup->transacted_at,
                'expired_at' => $topup->voucher->expired_at->format('d-m-Y H:i:s'),
                'created_at' => $topup->created_at->format('d-m-Y H:i:s'),
            ]
        ]);
    }

    public function sellVoucher(Request $request, Topup $topup)
    {
      
        DB::beginTransaction();
        try {
            $user = auth()->user();

            if (!Hash::check($request->pin, $user->pin->pin)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Pin tidak valid',
                ], 401);
            }
            $topup->update([
                'cashier_id' => $user->id,
                'transacted_at' => now(),
            ]);
            ActivityLog::create([
                'user_id' => $user->id,
                'ip' => $request->ip(),
                'note' => 'Menjual voucher ID: ' . $topup->id . ' dengan no seri ' . $topup->target,
                'user_agent' => $request->header('User-Agent'),
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Voucher berhasil dijual',
                'data' => $topup,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => env('APP_DEBUG') ? $th->getMessage() : 'Voucher gagal dijual',
            ], 500);
        }
    }

    // updateStatus
    public function updateStatus(Request $request, Topup $topup)
    {
        $this->validate($request, [
            'status' => 'required|in:sukses,gagal,pending',
        ], [
            'status.required' => 'Status tidak boleh kosong',
            'status.in' => 'Status tidak valid',
        ]);

        DB::beginTransaction();
        try {
            $topup->update([
                'status' => $request->status,
            ]);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Status topup berhasil diubah',
                'data' => $topup,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => env('APP_DEBUG') ? $th->getMessage() : 'Status topup gagal diubah',
            ], 500);
        }
    }

    // updateApiStatus
    public function updateApiStatus(Request $request, TopupApi $topup)
    {
        $this->validate($request, [
            'status' => 'required|in:sukses,gagal,pending',
        ], [
            'status.required' => 'Status tidak boleh kosong',
            'status.in' => 'Status tidak valid',
        ]);

        DB::beginTransaction();
        try {
            $topup->update([
                'status' => $request->status,
            ]);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Status topup berhasil diubah',
                'data' => $topup,
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => env('APP_DEBUG') ? $th->getMessage() : 'Status topup gagal diubah',
            ], 500);
        }
    }


    public function callBack(Request $request)
    {
        $secret = config('app.digiflazz.private_key');

        $post_data = file_get_contents('php://input');
        $signature = hash_hmac('sha1', $post_data, $secret);
        // dd($signature);
        \Log::info($signature);
        try {
        if ($request->header('X-Hub-Signature') == 'sha1='.$signature) {
            \Log::info(json_decode($request->getContent(), true));
            $typeCallBack = $request->header('X-Digiflazz-Event');
            $data = json_decode($request->getContent(), true);
            $trx_id = $data['data']['trx_id'];
            $ref_id = $data['data']['ref_id'];
            $message = $data['data']['message'];
            $status = $data['data']['status'];
            $rc = $data['data']['rc'];
            $buyer_last_saldo = $data['data']['buyer_last_saldo'];
            $sn = $data['data']['sn'];
            $price = $data['data']['price'];
            $tele = $data['data']['tele'];
            $wa = $data['data']['wa'];
            if($typeCallBack == 'create') {
                $topup = TopupApi::with('topup')->where('ref_id', $ref_id)->where('status', 'pending')->first();
            }  else  {
                $topup = TopupApi::with('topup')->where('ref_id', $ref_id)->first();
            }
           
            if ($topup) {
                $topup->update([
                    'trx_id' => $trx_id,
                    'status' => $status,
                    'message' => $message,
                    'response' => $topup->response . "\n" . json_encode($data),
                ]);

                $topup->topup->update([
                    'status' => $status,
                    'note' => $sn?? '-',
                    'price_buy' => $price,
                ]);

                if($topup->topup->type != 'voucher') {
                    PiwapiService::sendMessage($topup->topup->whatsapp, $topup->topup->whatsapp_message);
                }
                SocketService::sendTrigger($topup->topup);
                echo 'Data berhasil ditemukan';
            } else {
                echo 'Data tidak ditemukan';
            }
        } else {
            echo 'Signature tidak valid';
        }
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }
}
