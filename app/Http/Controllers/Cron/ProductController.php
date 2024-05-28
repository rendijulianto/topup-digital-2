<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;
use App\Models\{Category, Brand, Type, Product, Supplier, ProductSupplier};
use Illuminate\Support\Str;
use App\Services\DigiflazzService;
set_time_limit(3600);
class ProductController extends Controller
{
    
      private function harga_pulsa($harga, $layanan) {
        try {
            $pecah = explode(' ', $layanan);
            $nominal = $pecah[1] ?? "0";
            // convert nominal to int
            $nominal = str_replace('.', '', $nominal);
            $nominal = (int) $nominal;
    
            if ($nominal < 95000) {
                $newHarga = $nominal + 2500;
            } else if ($nominal > 95000 && $nominal < 200000) {
                $newHarga = $nominal + 3000;
            } else if ($nominal >= 200000) {
                $newHarga = $nominal + 5000;
            } else {
                $newHarga = $nominal + 10000;
            }
            if ($harga > $newHarga) {
                $newHarga = $harga+1000;
            }
            return $newHarga;
        } catch (\Throwable $th) {
            return 0;
        }
    
       
    }

    private function harga_e_wallet($harga, $layanan) 
    {
        try {
            $pecah = explode(' ', $layanan);
            $nominal = $pecah[count($pecah) - 1] ?? "0";
            $nominal = str_replace('.', '', $nominal);
            $nominal = (int) $nominal;
            if ($nominal < 40000) {
                $newHarga = $nominal + 3000;
            } else if ($nominal >= 40000 && $nominal < 50000) {
                $newHarga = $nominal + 4000;
            } else if ($nominal >= 50000 && $nominal <= 100000) {
                $newHarga = $nominal + 5000;
            } else if ($nominal > 100000 && $nominal <= 500000) {
                $newHarga = $nominal + 7000;
            } else if ($nominal > 500000 && $nominal < 1000000) {
                $newHarga = $nominal + 10000;
            } else {
                $newHarga = $nominal + 10000;
            } 
            
         
        
            if ($harga > $newHarga) {
                $newHarga = $harga+1000;
            }
            return $newHarga;
        } catch (\Throwable $th) {
            return 0;
        }
       
    }


    private function harga_pln($harga, $layanan) 
    {
        try {
            $pecah = explode(' ', $layanan);
            $nominal = $pecah[count($pecah) - 1] ?? "0";
            $nominal = str_replace('.', '', $nominal);
            $nominal = (int) $nominal;
            $newHarga = $nominal+3000;
        
            if ($harga > $newHarga) {
                $newHarga = $harga+1000;
            }
            return $newHarga;
        } catch (\Throwable $th) {
            return 0;
        }
       
    }

    private function harga_aktivasi_voucher($harga) 
    {
        try {   
            if($harga < 10000) {
                $newHarga = $harga + 1500;
            } else if ($harga >= 10000 && $harga < 50000) {
                $newHarga = $harga + 2000;
            } else {
                $newHarga = $harga + 3000;
            }
            return $newHarga;
        } catch (\Throwable $th) {
            return 0;
        }
       
    }


    public function update()
    {
        try {
            $priceList = DigiflazzService::getProducts();
            if (!$priceList['status']) {
                echo '['.date('Y-m-d H:i:s').'] Sinkronisasi data produk gagal'."<br>";
                return;
            }
            echo '['.date('Y-m-d H:i:s').'] Sinkronisasi data produk berhasil dimulai'."<br>";

            foreach ($priceList['data'] as $product) {
                 if (!Str::contains($product->category, ['Aktivasi Voucher','E-Money','Data','Masa Aktif','PLN','Pulsa'])) {
                    continue;
                }
                if (Str::contains($product->product_name, 'Cek Nama') || Str::contains($product->product_name, 'Cek Status')) {
                    continue;
                }

                $category = Category::firstOrCreate(['name' => $product->category]);
                $brand = Brand::firstOrCreate(['name' => $product->brand]);
                $type = Type::firstOrCreate(['name' => $product->type]);
                $supplier = Supplier::firstOrCreate(['name' => $product->seller_name]);
                $productData = Product::firstOrCreate(
                    [
                        'name' => $product->product_name,
                        'category_id' => $category->id,
                        'type_id' => $type->id,
                        'brand_id' => $brand->id,
                        'description' => $product->desc
                    ]
                );

                $status = $product->buyer_product_status == 1 ? $product->seller_product_status ? 1 : 0 : 0;
                $stock = $product->unlimited_stock == 1 ? 999999 : $product->stock;
                $productSupplier = ProductSupplier::where('product_sku_code', $product->buyer_sku_code)->first();
                if (!$productSupplier) {
                    $productSupplier = ProductSupplier::create(
                        [
                            'product_sku_code' => $product->buyer_sku_code,
                            'product_id' => $productData->id,
                            'supplier_id' => $supplier->id,
                            'price' => $product->price,
                            'stock' => $stock,
                            'status' => $status,
                            'multi' => $product->multi,
                            'start_cut_off' => $product->start_cut_off ? $product->start_cut_off : '00:00:00',
                            'end_cut_off' => $product->end_cut_off ? $product->end_cut_off : '00:00:00',
                        ]
                    );
                } else {
                    $productSupplier->product_sku_code = $product->buyer_sku_code;
                    $productSupplier->product_id = $productData->id;
                    $productSupplier->supplier_id = $supplier->id;
                    $productSupplier->price = $product->price;
                    $productSupplier->stock = $stock;
                    $productSupplier->status = $status;
                    $productSupplier->multi = $product->multi;
                    $productSupplier->start_cut_off = $product->start_cut_off ? $product->start_cut_off : '00:00:00';
                    $productSupplier->end_cut_off = $product->end_cut_off ? $product->end_cut_off : '00:00:00';
                    $productSupplier->save();
                }

                $profit =  $productData->price - $productSupplier->price;

                if ($profit < 2000) {
                    switch (Str::lower($category->nama)) {
                        case 'e-money':
                            $selling_price = ceil(($this->harga_e_wallet($product->price, $product->product_name)) / 500) * 500;
                            break;
                        case 'pulsa':
                            $selling_price = ceil(($this->harga_pulsa($product->price, $product->product_name)) / 500) * 500;
                            break;
                        case 'pln':
                            $selling_price = ceil(($this->harga_pln($product->price, $product->product_name)) / 500) * 500;
                            break;
                        case 'aktivasi voucher':
                            $selling_price = ceil(($this->harga_aktivasi_voucher($product->price)) / 500) * 500;
                            break;
                            default:
                            $selling_price = ceil(($product->price + 1500) / 500) * 500;
                            break;
                    }
                    $productData->update([
                        'price' => $selling_price,
                    ]);

                }
            }
            echo '['.date('Y-m-d H:i:s').'] Sinkronisasi data produk berhasil selesai'."<br>";
        } catch (\Throwable $th) {
            echo config('app.debug') ? 'Line '.$th->getLine().' in '.$th->getFile().': <b>'.$th->getMessage().'</b>'

            : 'Terjadi kesalahan sistem';
        }    

    }
}
