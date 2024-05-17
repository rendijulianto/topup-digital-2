<?php
namespace App\Services;

use Rendijulianto\Piwapi\Main as Piwapi;

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
            // dd(config('app.piwapi.api_key'), config('app.piwapi.device_id'));
            $piwapi = new Piwapi('https://piwapi.com/api/send/whatsapp', config('app.piwapi.api_key'), config('app.piwapi.device_id'));
            $send = $piwapi->sendMessage($no, $message);
            return [
                'status' => true,
                'data' => $send,
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage(),
            ];
        }
    }
}
