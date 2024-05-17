<?php

namespace App\Services;
use Gonon\Digiflazz\{Digiflazz, Transaction, PriceList, Balance};
set_time_limit(3600);
/**
 * Class DigiflazzService.
 */
class DigiflazzService
{
    public function __construct()
    {
        Digiflazz::initDigiflazz(config('app.digiflazz.username'), config('app.env') == "production" ? config('app.digiflazz.api_key_production') : config('app.digiflazz.api_key'));
        // dd(config('app.digiflazz.username'), config('app.env') == "production" ? config('app.digiflazz.api_key_production') : config('app.digiflazz.api_key'));
    }

    public function createTransaction($params) : array
    {
        try {
            $params['cb_url'] = route('digiflazz.callback');
            
            $createTrasaction = Transaction::createTransaction($params);
            $sn = $createTrasaction->sn;
            $status = $createTrasaction->status;
            $message = $createTrasaction->message;
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

    public function createTransactionPostpaid($params): array
    {
        try {
            $params['cb_url'] = route('digiflazz.callback');
            $pascaPay = Transaction::payPostpaid($params);
           
            if ($pascaPay->status == "Gagal") {
                return [
                    'status' => false,
                    'message' => $pascaPay->message,
                ];
            } else if ($pascaPay->status == "Pending") {
           
                return [
                    'status' => true,
                    'data' => [
                        'sn' => $pascaPay->sn ?? null,
                        'status' => $pascaPay->status,
                        'message' => $pascaPay->message,
                    ],
                ];
            } 
            
            return [
                'status' => true,
                'data' => [
                    'sn' => $pascaPay->sn ?? null,
                    'selling_price' => $pascaPay->selling_price??0,
                    'price' => $pascaPay->price??0,
                    'admin' => $pascaPay->admin??0,
                    'status' => $pascaPay->status,
                    'message' => $pascaPay->message,
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

    public function validateEWallet($params): array
    {
        try {

            // hapus spasi dan buat kapital
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

    public function validateVoucher($params): array
    {
        try {
            // hapus spasi dan buat kapital
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

    public function validatePLN($params): array
    {
        try {
           
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

    public function validatePostpaid($params): array
    {
        try {
            // $params['cb_url'] = route('digiflazz.callback');
            // dd($params);
            $pascaInquiry = Transaction::inquiryPostpaid($params);
            if ($pascaInquiry->status == "Gagal") {
                return [
                    'status' => false,
                    'message' => $pascaInquiry->message,
                ];
            }
           
            return [
                'status' => true,
                'data' => [
                    'status' => $pascaInquiry->status,
                    'customer_no' => $pascaInquiry->customer_no,
                    'admin' => $pascaInquiry->admin??0,
                    'message' => $pascaInquiry->message??null,
                    'price' => $pascaInquiry->price??0,
                    'selling_price' => $pascaInquiry->selling_price??0,
                    'desc' => $pascaInquiry->desc,
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

    public function getProducts():array
    {
        try {
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

    public function getBalance():array
    {
        try {
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
