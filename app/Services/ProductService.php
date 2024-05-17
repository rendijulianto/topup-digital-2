<?php

namespace App\Services;

/**
 * Class ProductService.
 */
class ProductService
{
    // get selling_price
    // public static function getSellingPrice($product_id, $price = 0)
    // {
    //     try {
    //         $product = Produk::find($product_id);
    //         dd($product);
    //         $category = $product->kategori;
    //         $selling_price = 0;

    //         if ($amount == 0) {
    //             $amount = 10000;
    //         }
        

    //         switch (Str::lower($category->nama)) {
    //             case 'e-money':
    //                 $selling_price = ceil(($this->harga_e_wallet($product->harga, $amount)) / 500) * 500;
    //                 break;
    //             case 'pulsa':
    //                 $selling_price = ceil(($this->harga_pulsa($product->harga, $amount)) / 500) * 500;
    //                 break;
    //             case 'pln':
    //                 $selling_price = ceil(($this->harga_pln($product->harga, $amount)) / 500) * 500;
    //                 break;
    //             case 'aktivasi voucher':
    //                 $selling_price = ceil(($this->harga_aktivasi_voucher($product->harga)) / 500) * 500;
    //                 break;
    //             default:
    //                 $selling_price = $product->harga;
    //                 break;
    //         }

    //         return $selling_price;
    //     } catch (\Throwable $th) {
    //         return 0;
    //     }
    // }

    private function harga_e_wallet($price, $amount)
    {
        return $price + 1500;
    }

    public static function getSellingPrice($amount)
    {
        
        if ($amount < 100000) {
            $newHarga = $amount + 3000;
        } else if ($amount >= 100000 && $amount < 500000) {
            $newHarga = $amount + 5000;
        } else if ($amount >= 500000 && $amount < 1000000) {
            $newHarga = $amount + 7000;
        } else {
            $newHarga = $amount + 10000;
        } 

        return ceil($newHarga / 500) * 500;
    }
}
