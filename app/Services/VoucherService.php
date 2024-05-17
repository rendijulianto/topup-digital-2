<?php

namespace App\Services;

/**
 * Class VoucherService.
 */
class VoucherService
{
    public static function validateVoucher($number, $brand, $ref_id): array
    {
        try {
            $service = '';
            switch ($brand) {
                case 'tri':
                    $service = "CVTHREE";
                    break;
                case 'telkomsel':
                    $service = "CVTSEL";
                    break;
                case 'indosat':
                    $service = "CVINDOSAT";
                    break;
                case 'xl':
                    $service = "CVXL";
                    break;
                case 'axis':
                    $service = "CVAXIS";
                    break;
                default:
                    throw new \Exception('Brand not found');
                    break;
            }
    
            $url = 'https://kebutuhansosialmedia.com/api.php?number=' . urlencode($number) . '&service=' . urlencode($service) . '&ref_id=' . urlencode($ref_id);

            $headers = array(
                'authority: kebutuhansosialmedia.com',
                'accept-language: en-US,en;q=0.9',
                'user-agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Mobile Safari/537.36'
            );
    
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($ch);
            curl_close($ch);
            
            // kelola response
            $json_decode = json_decode($response, true);
            if($json_decode['status'] == false) {
                $message = $json_decode['message'];
                if(str_contains($message, 'GAGAL')) {
                    throw new \Exception("Voucher Belum Aktivasi");
                } else {
                    throw new \Exception($message);
                }
            } else {
                // cek apakah statusnya pending
                if($json_decode['data']['status'] == 'pending') {
                    return [
                        'status' => true,
                        'message' => 'Sedang proses pengecekan',
                    ];
                } else {
                    if(str_contains($json_decode['data']['tanggal_inject'], 'NULL')) {
                        return [
                            'status' => false,
                            'message' => 'Voucher Belum Aktivasi',	
                        ];
                    } else {
                        return [
                            'status' => true,
                            'message' => 'Voucher Sudah Aktivasi',
                            'data' => [
                                'operator' => $json_decode['data']['operator'],
                                'produk' => $json_decode['data']['produk'],
                                'status' => $json_decode['data']['status'],
                                'expired_at' => $json_decode['data']['expired_at'],
                                'tanggal_inject' => $json_decode['data']['tanggal_inject'],
                                'tanggal_unlock' => $json_decode['data']['tanggal_unlock'],
                                'msisdn' => $json_decode['data']['msisdn'],
                            ]
                        ];
                    }
                }
            }
            
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage(),
            ];
        }
    }    
}