<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Produk, Brand, Tipe, Kategori, Topup};
use Illuminate\Support\Str;
class ProductController extends Controller
{

    public function getBestSeller(Request $request)
    {
        $this->validate($request, [
            'jumlah' => 'required|integer',
        ]);

        $products = Produk::
        bestSeller($request)
        ->get();

        // replace nama Aktivasi
        $products = $products->map(function ($product) {
            $product->nama = Str::replaceFirst('Aktivasi ', '', $product->nama);
            return $product;
        });

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diambil',
            'data' => $products
        ], 200);
    }


    public function getPLN()
    {
        $products = Produk::select('id', 'nama', 'harga', 'deskripsi')
        ->category('pln')
        ->orderBy('harga', 'asc')
        ->withCount('topup')
        ->orderByDesc('topup_count')
        ->get();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diambil',
            'data' => $products
        ], 200);
    }

    public function getEwalletByBrand(Request $request)
    {
        $this->validate($request, [
            'brand' => 'required|exists:brand,nama',
        ]);

    

        $products = Produk::select('id', 'nama', 'harga', 'deskripsi', 'tipe_id', 'brand_id')
        ->category('e-money')
        ->brand($request->brand)
        ->orderBy('harga', 'asc')
        ->withCount('topup')
        ->orderByDesc('topup_count')
        ->get();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diambil',
            'data' => $products
        ], 200);
    }

    public function getSeluler(Request $request)
    {
        $this->validate($request, [
            'brand_id' => 'required|exists:brand,id',
            'tipe_id' => 'required|exists:tipe,id',
            'kategori' => 'required|exists:kategori,nama',
        ]);

        $products = Produk::select('id', 'nama', 'harga', 'deskripsi')
        ->category($request->kategori)
        ->type($request->tipe_id)
        ->brand($request->brand_id)
        ->orderBy('harga', 'asc')
        ->withCount('topup')
        ->orderByDesc('topup_count')
        ->get();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diambil',
            'data' => $products
        ], 200);
    }

    public function getActivationVoucher(Request $request)
    {
        $this->validate($request, [
            'brand_id' => 'required|exists:brand,id',
            'tipe_id' => 'required|exists:tipe,id',
        ]);

    

        $products = Produk::select('id', 'nama', 'harga', 'deskripsi')
        ->category('Aktivasi Voucher')
        ->brand($request->brand_id)
        ->type($request->tipe_id)
        ->orderBy('harga', 'asc')
        ->withCount('topup')
        ->orderByDesc('topup_count')
        ->get();


        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diambil',
            'data' => $products
        ], 200);
    }

    public function getVoucherFisikByBrand(Request $request)
    {
        $this->validate($request, [
            'brand' => 'required|exists:brand,nama',
        ]);
        // $products = Produk::select('id', 'nama', 'harga', 'deskripsi')
        // ->category('aktivasi voucher')
        $products = Topup::getProductVoucher($request)->get()->unique('produk_id')
        ->map(function ($item) {
            return [
                'id' => $item->produk->id,
                'nama' => Str::replaceFirst('Aktivasi ', '', $item->produk->nama),
                'harga' => $item->harga_jual,
                'brand_id' => $item->produk->brand_id,
                'deskripsi' => Str::replaceFirst('Aktivasi ', '', $item->produk->deskripsi),
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diambil',
            'data' => $products
        ], 200);
    }

    public function show(Produk $product)
    {

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diambil',
            'data' =>  [
                'product' => $product,
                'supplier' => $product->supplier_produk->map(function ($item, $key) {
                    return [
                        'nama' => $item->supplier->nama,
                        'harga' => $item->harga,
                        'stok' => $item->stok,
                        'multi' => $item->multi,
                        'status' => $item->status,
                    ];
                })
            ]
        ], 200);
    }

    public function getSupplierProduct(Produk $product)
    {
        
        return response()->json([
            'status' => true,
            'message' => 'Detail topup',
            'data' => $product->getSupplier()
        ]);
    }

    public function sellingPrice(Request $request, Produk $product)
    {
        $this->validate($request, [
            'amount' => 'required|integer',
        ]);

       
        $kategori = $product->kategori;

        $amount = $request->amount;
        $profit = $product->getProfit($amount);

        $newHarga = $amount + $profit;

        return response()->json([
            'status' => true,
            'message' => 'Harga berhasil diambil',
            'data' => [
                'harga_real' => $newHarga,
                'harga_bulat' => ceil($newHarga / 500) * 500,
            ]
        ], 200);
    }

    public function categoryBrandType(Request $request)
    {
      
        $this->validate($request, [
            'brand' => 'required|exists:brand,nama',
            'kategori' => 'required|exists:kategori,nama',
            'tipe' => 'required|exists:tipe,nama'
        ]);

        $products = Produk::select('id', 'nama', 'harga', 'deskripsi')
        ->category($request->kategori)
        ->brand($request->brand)
        ->type($request->tipe)
        ->addSelect(['harga_supplier' => function ($query) {
            $query->select('harga')->from('supplier_produk')->whereColumn('produk_id', 'produk.id')->where('status',1)->orderBy('harga', 'desc')->limit(1);
        }])
        ->orderBy('harga', 'asc')
        ->withCount('topup')
        ->orderByDesc('topup_count')
        ->get();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diambil',
            'data' => $products
        ], 200);
    }
}

