<?php
namespace App\Services;


/**
 * Class PiwapiService.
 */
class PiwapiService
{
 

    public static function sendMessage($no, $message) : array
    {
        try {
            if (substr($no, 0, 1) == '0') {
                $no = '62'.substr($no, 1);
            }

            $postData = [
                "secret" => config('app.piwapi.api_key'), 
                "account" => config('app.piwapi.device_id'),
                "recipient" => $no,
                "type" => "text",
                "priority" => 1,
                "message" => $message,
            ];
        
            $cURL = curl_init('https://piwapi.com/api/send/whatsapp');
            curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURL, CURLOPT_POSTFIELDS, $postData);
            $response = curl_exec($cURL);
            curl_close($cURL);

            $result = json_decode($response, true);

            if ($result['status'] != 200) {
                return [
                    'status' => false,
                    'message' => $result['message'],
                ];
            } else {
                return [
                    'status' => true,
                    'message' => $result['message'],
                ];
            }
           
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage(),
            ];
        }
    }
}
