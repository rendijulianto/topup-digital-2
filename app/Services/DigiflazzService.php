<?php

namespace App\Services;
use Gonon\Digiflazz\{Digiflazz, Transaction, PriceList, Balance};
set_time_limit(3600);
/**
 * Class DigiflazzService.
 */
class DigiflazzService
{
    
    public static function createTransaction($params) : array
    {
        try {
            Digiflazz::initDigiflazz(config('app.digiflazz.username'), config('app.env') == "production" ? config('app.digiflazz.api_key_production') : config('app.digiflazz.api_key'));
            $params['cb_url'] = route('digiflazz.callback');
            $createTrasaction = Transaction::createTransaction($params);
            return [
                'status' => true,
                'data' => [
                    'sn' => $createTrasaction->sn,
                    'status' => $createTrasaction->status,
                    'message' => $createTrasaction->message,
                ],
            ];
        } catch (\Gonon\Digiflazz\Exceptions\ApiException $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'code' => $e->getErrorCode(),
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage(),
                'code' => $th->getCode(),
            ];
        }
    }
    public static function validateEWallet($params): array
    {
        try {
            $params['buyer_sku_code'] = strtoupper($params['brand']);
            $params['buyer_sku_code'] = str_replace(' ', '', $params['buyer_sku_code']).'_CEK';
            $validateEWallet = DigiflazzService::createTransaction($params);
            return $validateEWallet;
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage(),
                'code' => $th->getCode(),
            ];
        }
    }

    public static function validateVoucher($params): array
    {
        try {
            $params['buyer_sku_code'] = strtoupper($params['brand']);
            $params['buyer_sku_code'] = str_replace(' ', '', $params['buyer_sku_code']).'_CEK';
            $validateEWallet = DigiflazzService::createTransaction($params);
            return $validateEWallet;
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage(),
                'code' => $th->getCode(),
            ];
        }
    }

    public static function validatePLN($params): array
    {
        try {
            Digiflazz::initDigiflazz(config('app.digiflazz.username'), config('app.env') == "production" ? config('app.digiflazz.api_key_production') : config('app.digiflazz.api_key'));
            $iquiryPLN = Transaction::inquiryPLN($params);
            if ($iquiryPLN->name == null) {
                throw new \Exception('Data tidak ditemukan!', 400);
            }
            return [
                'status' => true,
                'data' => [
                    'nomor' => $iquiryPLN->customer_no,
                    'nama' => $iquiryPLN->name,
                    'segment_power' => $iquiryPLN->segment_power,
                    'subscriber_id' => $iquiryPLN->subscriber_id,
                    'meter_no' => $iquiryPLN->meter_no,
                ],
            ];
        } catch (\Gonon\Digiflazz\Exceptions\ApiException $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'code' => $e->getErrorCode(),
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage(),
                'code' => $th->getCode(),
            ];
        }
    }

    public static function getProducts():array
    {
        try {
            Digiflazz::initDigiflazz(config('app.digiflazz.username'), config('app.env') == "production" ? config('app.digiflazz.api_key_production') : config('app.digiflazz.api_key'));
            $products = PriceList::getPrePaid();
            return [
                'status' => true,
                'data' => $products,
            ];
        } catch (\Gonon\Digiflazz\Exceptions\ApiException $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'code' => $e->getErrorCode(),
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage(),
                'code' => $th->getCode(),
            ];
        }
    }

    public static function getBalance():array
    {
        try {
            Digiflazz::initDigiflazz(config('app.digiflazz.username'), config('app.env') == "production" ? config('app.digiflazz.api_key_production') : config('app.digiflazz.api_key'));
            $profile = Balance::getBalance();
            return [
                'status' => true,
                'data' =>  [
                    'deposit' => $profile->deposit,
                ]
            ];
        } catch (\Gonon\Digiflazz\Exceptions\ApiException $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'code' => $e->getErrorCode(),
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage(),
                'code' => $th->getCode(),
            ];
        }
    }
}
