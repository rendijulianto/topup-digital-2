<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\{Kategori, Brand, Tipe, Produk, Supplier,TopupApi};
use Illuminate\Support\Str;
// set_time_limit(3600);
use App\Services\{DigiflazzService,PiwapiService,SocketService}
use Gonon\Digiflazz\{Digiflazz,Transaction,PriceList,Balance};

// TopupApi
use App\Models\TopupApi;
class TopupController extends Controller
{ 

    public function index()
    {
        $params = [
            'ref_id' => '1710669943561',
            'buyer_sku_code' => 'avt151h2',
            'customer_no' => '082166555',
        ];
        
        $createTransaction = new DigiflazzService();
        $response = $createTransaction->createTransaction($params);
        dd($params, $response);
    }
    public function update()
    {
        $topupApi = TopupApi::where('status', 'pending')->take(10)->orderBy('updated_at', 'asc')->get();
        foreach ($topupApi as $api) {	
            try {
                    $topup = $api->topup;
                    $number = $topup->nomor;
                    $ref_id = $api->ref_id;

                    $params = [
                        'ref_id' => $ref_id,
                        'buyer_sku_code' => $api->supplier_produk->produk_sku_code,
                        'customer_no' => $number,
                    ];
                    $digiflazzService = new DigiflazzService();
                    $createTransaction = $digiflazzService->createTransaction($params);
                    if ($createTransaction['status']) {
                        $api->status = $createTransaction['data']['status'];
                        $api->keterangan = $createTransaction['data']['message'];
                        $api->save();
                        $topup->status = $createTransaction['data']['status'];
                        $topup->keterangan = $createTransaction['data']['sn'] ?? $createTransaction['data']['message'];
                        if (strtolower($createTransaction['data']['status']) == 'sukses') {
                            if($topup->tipe != 'voucher') {
                                $topup->tgl_transaksi = date('Y-m-d H:i:s');
                                PiwapiService::sendMessage($topup->whatsapp, $topup->whatsapp_message);
                            }
                        }
                        $topup->save();
                    } else {
                        // dd($createTransaction);
                        $api->status = 'gagal';
                        $api->keterangan = $createTransaction['message'];
                        $api->save();
                        $topup->status = 'gagal';
                        $topup->save();
                    }
                    SocketService::sendTrigger($topup->topup);
                    echo '['.date('Y-m-d H:i:s').'] Sinkronisasi data topup berhasil dimulai'."<br>";
            } catch (\Throwable $th) {
                echo config('app.debug') ? 'Line '.$th->getLine().' in '.$th->getFile().': <b>'.$th->getMessage().'</b>'
                : 'Terjadi kesalahan sistem';
            }   
        }
    }
}
