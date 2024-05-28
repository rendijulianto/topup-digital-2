<?php
namespace App\Services;


// library Curl
class SocketService
{

    public static function sendTrigger($topup) : array
    {
        try {

            $data = [
                'data' => [
                    'id' => $topup->id,
                    'status' => $topup['status_html'],
                    'message' => $topup['message'],
                    'date' => date('Y-m-d H:i:s'),
                    'nomor' => $topup->nomor,
                    'produk' => $topup->product_name,
                    'harga' => $topup->harga_jual,
                    'nama_pelanggan' => $topup->customer_name,
                    'tipe' => $topup->tipe,
                ]
            ];
            $topup = json_encode($data);
            $url = config('app.url_socket').'/trigger';
            $ch = curl_init();
            $headers = [
                'Content-Type: application/json',
            ];
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $topup);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $result = curl_exec($ch);
            curl_close($ch);
            return [
                'status' => true,
                'data' => $result,
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage(),
            ];
        }
    }
}
