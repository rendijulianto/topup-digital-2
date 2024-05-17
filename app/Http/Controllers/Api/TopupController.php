<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\{Topup, Pengguna, Produk,SupplierProduk,TopupApi,LogAktivitas};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\{CekNama, Brand, Kategori};
use Gonon\Digiflazz\{Digiflazz,Transaction};
use App\Services\{DigiflazzService, PiwapiService,ProductService, SocketService};
use Carbon\Carbon;
// Hash
use Illuminate\Support\Facades\Hash;
set_time_limit(3600);
class TopupController extends Controller
{
    
    // store
    public function store(Request $request)
    {
        $this->validate($request, [
           'produk_id' => 'required|exists:produk,id',
           'nomor' => 'required|numeric',
           'whatsapp' => 'nullable|numeric|digits_between:10,13',
        ], [
            'nomor.required' => 'Nomor tidak boleh kosong',
            'nomor.numeric' => 'Nomor tidak valid',
            'whatsapp.numeric' => 'Nomor whatsapp tidak valid',
            'whatsapp.digits_between' => 'Nomor whatsapp tidak valid',
            'produk_id.required' => 'Produk tidak boleh kosong',
            'produk_id.exists' => 'Produk tidak ditemukan',
        ]);
        
        DB::beginTransaction();
        try {
          
            $user= auth('pengguna')->user();
            
            $product = Produk::find($request->produk_id);
            
            if($product->kategori->nama == 'E-Money') {
                $tipe = 'e_wallet';
            } else if ($product->kategori->nama == 'PLN') {
                $tipe = 'token_listrik';
            } else {
                $tipe = 'seluler';
            }

            $topup = Topup::create([
                'produk_id' => $product->id,
                'kategori_id' => $product->kategori_id,
                'brand_id' => $product->brand_id,
                'tipe_id' => $product->tipe_id,
                'kasir_id' => $user->id,
                'harga_beli' => 0,
                'harga_jual' => $product->harga,
                'nomor' => $request->nomor,
                'whatsapp' => $request->whatsapp ?? $request->nomor,
                'tipe' => $tipe,
                'tgl_transaksi' => now(),
            ]);

            if($product->kategori->nama == 'E-Money') {
                $topup->e_wallet()->create([
                    'nama_pelanggan' => $request->nama_pelanggan ?? $topup->nomor,
                ]);
            } else if($product->kategori->nama == 'PLN') {
                $topup->token_listrik()->create([
                    'nama_pelanggan' => $request->nama_pelanggan ?? $topup->nomor,
                    'id_pelanggan' => $request->id_pelanggan ?? '-',
                    'nomor_meter' => $request->nomor_meter ?? '-',
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
            'produk_id' => 'required|exists:produk,id',
            'supplier_id' => 'required|exists:supplier_produk,id',
            'nomor' => 'required',
            'pin' => 'required|numeric',
            'tgl_kadaluwarsa' => 'required|date',
        ], [
            'nomor.required' => 'Nomor tidak boleh kosong',
            'nomor.numeric' => 'Nomor tidak valid',
            'produk_id.required' => 'Produk tidak boleh kosong',
            'produk_id.exists' => 'Produk tidak ditemukan',
            'supplier_id.required' => 'Supplier tidak boleh kosong',
            'supplier_id.exists' => 'Supplier tidak ditemukan',
            'pin.required' => 'Pin tidak boleh kosong',
            'pin.numeric' => 'Pin tidak valid',
            'tgl_kadaluwarsa.required' => 'Tanggal kadaluwarsa tidak boleh kosong',
            'tgl_kadaluwarsa.date' => 'Tanggal kadaluwarsa tidak valid',
        ]);

        // Langkah 0: Cek pin dan limit 3x
        if (!Hash::check($request->pin, Auth::guard('pengguna')->user()->pin->pin)) {
            return response()->json([
                'status' => false,
                'message' => 'Pin tidak valid',
            ], 401);
        }
    

        $product = Produk::find($request->produk_id);
        $supplierProduk = $product->supplier_produk()->where('id', $request->supplier_id)->firstOrFail();
        $nomor = $request->nomor;
        $user = auth('pengguna')->user();

        $data = [
            'nomor' => $nomor,
            'brand' => $product->brand->nama,
            'product' => $product->nama,
            'status' => 'Pending',
            'message' => 'Topup sedang diproses',
            'tanggal' => date('d-m-Y H:i:s'),
        ];

        DB::beginTransaction();
        try {
            $topup = Topup::where('nomor', $nomor)->whereIn('status', ['pending', 'sukses'])->first();
    
            if($topup) {
                $data['status'] = $topup->status_html;
                $data['message'] = $topup->keterangan;
                return response()->json([
                    'status' => false,
                    'message' => 'Target sudah pernah dipesan dan statusnya pending/sukses',
                    'data' => $data,
                ], 400);
            } else {
                $topup = Topup::create([
                    'produk_id' => $product->id,
                    'kategori_id' => $product->kategori_id,
                    'brand_id' => $product->brand_id,
                    'tipe_id' => $product->tipe_id,
                    'kasir_id' => null,
                    'harga_jual' => $product->harga,
                    'harga_beli' => $supplierProduk->harga,
                    'nomor' => $nomor,
                    'whatsapp' => null,
                    'tipe' => 'voucher',
                    'tgl_transaksi' => null,
                ]);
            }

          
            $topup->voucher()->updateOrCreate(
                ['topup_id' => $topup->id],
                ['tgl_kadaluarsa' => $request->tgl_kadaluwarsa]
            );

            $ref_id = time() . rand(100, 999);
            $params = [
                'buyer_sku_code' => $supplierProduk->produk_sku_code,
                'customer_no' => $nomor,
                'ref_id' => $ref_id,
            ];
            
            $digiflazzService = new DigiflazzService();
    
            $response = $digiflazzService->createTransaction($params);
        

            if(!$response['status']) {
                return response()->json([
                    'status' => false,
                    'message' => $response['message'],
                    'data' => $data,
                ], 500);
            }

            $topup->update([
                'status' => $response['data']['status'],
                'harga_beli' => $supplierProduk->harga,
                'keterangan' => $response['data']['message'] ?? '-',
            ]);
    
    
           $topup->topup_api()->create([
                'supplier_produk_id' => $supplierProduk->id,
                'supplier_id' => $supplierProduk->supplier_id,
                'pengguna_id' => Auth::guard('pengguna')->user()->id,
                'ref_id' => $ref_id,
                'keterangan' => $response['data']['message'],
                'status' => $response['data']['status']
            ]);

            $data['status'] = $response['data']['status'];
            $data['message'] = $response['data']['message'];
            LogAktivitas::create([
                'pengguna_id' => Auth::guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Melakukan Topup ID: ' . $topup->id . ' dengan supplier ' . $supplierProduk->supplier->nama . ' dengan harga beli ' . $supplierProduk->harga,
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
            'supplier_produk_id' => 'required|exists:supplier_produk,id',
            'pin' => 'required|numeric',
        ], [
            'supplier_produk_id.required' => 'Supplier produk tidak boleh kosong',
            'supplier_produk_id.exists' => 'Supplier produk tidak ditemukan',	
            'pin.required' => 'Pin tidak boleh kosong',
            'pin.numeric' => 'Pin tidak valid',
        ]);

        
        DB::beginTransaction();
        try {
            $user = auth('pengguna')->user();

            if (!Hash::check($request->pin, $user->pin->pin)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Pin tidak valid',
                ], 422);
            }

            $product = $topup->produk->supplier_produk()->where('id', $request->supplier_produk_id)->firstOrFail();

            if (!$product->multi) {
                $cek = $topup->topup_api()->where('supplier_produk_id', $request->supplier_produk_id)->exists();
                if ($cek) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Target sudah pernah ditopup dengan supplier yang sama',
                    ], 422);
                }
            }

            if ($product->stok == 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Stok produk habis',
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
                'buyer_sku_code' => $product->produk_sku_code,
                'customer_no' => $topup->nomor,
                'ref_id' => $ref_id,
            ];
            
            $digiflazzService = new DigiflazzService();             
            $response = $digiflazzService->createTransaction($params);
            $harga_beli =  $response['data']['price']?? $product->harga;


            if (!$response['status']) {
                return response()->json([
                    'status' => false,
                    'message' => $response['message'],
                ], 422);
            }

            $topup->update([
                'kasir_id' => $user->id,
                'status' => $response['data']['status'],
                'harga_beli' => $harga_beli,
                'keterangan' => $response['data']['sn'] ?? '-',
                'tgl_transaksi' =>  now(),
            ]);

            $topup->topup_api()->create([
                'supplier_produk_id' => $product->id,
                'supplier_id' => $product->supplier_id,
                'pengguna_id' => $user->id,
                'ref_id' => $ref_id,
                'keterangan' => $response['data']['message'],
                'status' => $response['data']['status'],
            ]);

            LogAktivitas::create([
                'pengguna_id' => $user->id,
                'ip' => $request->ip(),
                'keterangan' => 'Melakukan Topup ID: ' . $topup->id .' dengan supplier ' . $product->supplier->nama . ' dengan harga beli ' . $harga_beli,
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
            'data' => $topup->produk->getSupplier()
        ]);
    }


    // show
    public function show(Topup $topup)
    {
        $keterangan = $topup->keterangan;
        if($topup->tipe == 'e_wallet') {
            $detail = [
                'nama_pelanggan' => $topup->e_wallet->nama_pelanggan,
            ];
        } else if($topup->tipe == 'token_listrik') {
            $detail = [
                'nama_pelanggan' => $topup->token_listrik->nama_pelanggan,
                'id_pelanggan' => $topup->token_listrik->id_pelanggan,
                'nomor_meter' => $topup->token_listrik->nomor_meter,
                'segment_power' => $topup->token_listrik->segment_power,
            ];
            $keterangan = Str::before($keterangan, '/');
        } else if($topup->tipe == 'voucher') {
            $detail = [
                'tgl_kadaluwarsa' => $topup->voucher->tgl_kadaluarsa->format('d-m-Y H:i:s'),
            ];
        } else {
            $detail = null;
        }

        $result = [
            'id' => $topup->id,
            'nomor' => $topup->nomor,
            'harga' => "Rp " . number_format($topup->harga_jual, 0, ',', '.'),
            'harga_beli' => "Rp " . number_format($topup->harga_beli, 0, ',', '.'),
            'produk' => $topup->produk->nama,
            'keterangan' => $keterangan,
            'status' =>  Str::ucfirst($topup->status),
            'tipe' => $topup->tipe,
            'tgl_transaksi' => $topup->tgl_transaksi ?? '-',
            'whatsapp' => $topup->whatsapp,
            'detail' => $detail,
            'kasir' => $topup->kasir->nama ?? '-',
            'logs' => $topup->topup_api->map(function ($log) {
                return [
                    'id' => $log->id,
                    'supplier' => $log->supplier->nama,
                    'keterangan' => $log->keterangan,
                    'status' => Str::ucfirst($log->status),
                    'waktu' => $log->created_at->format('d-m-Y H:i:s'),
                    'user' => $log->user->nama ?? '-',
                ];
            })->sortByDesc('date')->values()->all()
        ];

        return response()->json([
            'status' => true,
            'message' => 'Detail topup',
            'data' => $result
        ]);
    }


    public function print(Topup $topup)
    {
        return view('print', compact('topup'));
    }

    //cekStatus -> ref_id anu dicek
    public function checkStatus(Request $request, Topup $topup)
    {
        DB::beginTransaction();
        try {
            $digiflazzService = new DigiflazzService();

            $params = [
                'ref_id' => $topup->topup_api->last()->ref_id,
                'buyer_sku_code' => $topup->topup_api->last()->supplier_produk->produk_sku_code,
                'customer_no' => $topup->nomor,
            ];

            if($topup->tipe == 'e_wallet_custom') {
                $response = $digiflazzService->createTransactionPostpaid($params);
            } else {
                $response = $digiflazzService->createTransaction($params);
            }
    
         
            if(!$response['status']) {
                throw new \Exception($response['message'], 422);
            }
            $status = $topup->status;

            // price
            if($topup->tipe_produk->slug == 'custom' && $response['data']['status'] == "Sukses") {
                $topup->update([
                    'harga_beli' => $response['data']['price']
                ]);
            }

            $topup->update([
                'status' => $response['data']['status'],
                'keterangan' => $response['data']['sn'] ?? '-',
                'tgl_transaksi' => $topup->tgl_transaksi ?? now(),
            ]);
            $topup->topup_api->last()->update([
                'pesan' => $response['data']['message'],
                'status' => $response['data']['status'],
                'response' => $topup->topup_api->last()->response . "\n" . json_encode($response['data']),
            ]);
            DB::commit();
            if(strtolower($response['data']['status']) != strtolower($status)) {
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
            $user = auth('pengguna')->user();
            $topup->update([
                'status' => 'gagal',
                'harga_beli' => 0,
                'keterangan' => 'Topup dibatalkan oleh kasir',
                'kasir_id' => $user->id,
            ]);
            LogAktivitas::create([
                'pengguna_id' => $user->id,
                'ip' => $request->ip(),
                'keterangan' => 'Membatalakan topup ID: ' . $topup->id,
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

    public function voucher(Request $request, $nomor)
    {
        $topup = Topup::where('nomor', $nomor)->orderBy('created_at', 'desc')->first();
        if(!$topup) {
            return response()->json([
                'status' => false,
                'message' => 'Voucher tidak ditemukan',
            ], 404);
        }
        $topup->load('voucher');
     
        $nama_produk = $topup->produk->nama;
        $nama_produk = Str::replaceFirst('Aktivasi ', '', $nama_produk);
        $harga = $topup->harga_jual;
        $status = $topup->kasir_id ? 'Sudah Terjual' : 'Belum Terjual';
        $tanggal_terjual = $topup->tgl_transaksi;
        $tanggal_kadaluarsa = $topup->voucher->tgl_kadaluarsa;
        $tanggal_dibuat = $topup->created_at;

        return response()->json([
            'status' => true,
            'message' => 'Data topup',
            'data' => [
                'id' => $topup->id,
                'nama_produk' => $nama_produk,
                'harga' => 'Rp ' . number_format($harga, 0, ',', '.'),
                'status' => $status,
                'tanggal_terjual' => $tanggal_terjual,
                'tanggal_kadaluarsa' => $tanggal_kadaluarsa->format('d-m-Y H:i:s'),
                'tanggal_dibuat' => $tanggal_dibuat->format('d-m-Y H:i:s'),
            ]
        ]);
    }

    public function sellVoucher(Request $request, Topup $topup)
    {
      
        DB::beginTransaction();
        try {
            $user = auth('pengguna')->user();

            if (!Hash::check($request->pin, $user->pin->pin)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Pin tidak valid',
                ], 401);
            }
            $topup->update([
                'kasir_id' => $user->id,
                'tgl_transaksi' => now(),
            ]);
            LogAktivitas::create([
                'pengguna_id' => $user->id,
                'ip' => $request->ip(),
                'keterangan' => 'Menjual voucher ID: ' . $topup->id . ' dengan no seri ' . $topup->nomor,
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
            $customer_no = $data['data']['customer_no'];
            $buyer_sku_code = $data['data']['buyer_sku_code'];
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
                    'pesan' => $message,
                    'response' => $topup->response . "\n" . json_encode($data),
                ]);

                $topup->topup->update([
                    'status' => $status,
                    'keterangan' => $sn?? '-',
                    'harga_beli' => $price,
                ]);

                if($topup->topup->tipe != 'voucher') {
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
