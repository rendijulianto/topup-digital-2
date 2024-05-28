<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Product, Brand, Tipe, Kategori, Topup};
use Illuminate\Support\Str;
class ProductController extends Controller
{
    public function getBestSeller(Request $request)
    {
        $this->validate($request, [
            'quantity' => 'required|integer',
        ]);

        $products = Product::
        bestSeller($request)
        ->take($request->quantity)
        ->get();

        $products = $products->map(function ($product) {
            $product->name = Str::replaceFirst('Aktivasi ', '', $product->name);
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
        $products = Product::select('id', 'name', 'price', 'description')
        ->category('pln')
        ->orderBy('price', 'asc')
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
            'brand' => 'required|exists:brands,name',
        ]);

        $products = Product::select('id', 'name', 'price', 'description', 'type_id', 'brand_id')
        ->category('e-money')
        ->brand($request->brand)
        ->orderBy('price', 'asc')
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
            'brand_id' => 'required|exists:brands,id',
            'type_id' => 'required|exists:types,id',
            'category' => 'required|exists:categories,name',
        ]);

        $products = Product::
        select('id', 'name', 'price', 'description')
        ->category($request->category)
        ->type($request->type_id)
        ->brand($request->brand_id)
        ->orderBy('price', 'asc')
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
            'brand_id' => 'required|exists:brands,id',
            'type_id' => 'required|exists:types,id',
        ]);

        $products = Product::select('id', 'name', 'price', 'description')
        ->category('Aktivasi Voucher')
        ->brand($request->brand_id)
        ->type($request->type_id)
        ->orderBy('price', 'asc')
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
            'brand' => 'required|exists:brands,name',
        ]);
        
        $products = Topup::getProductVoucher($request)->get()->unique('produk_id')
        ->map(function ($item) {
            return [
                'id' => $item->product->id,
                'name' => Str::replaceFirst('Aktivasi ', '', $item->product->name),
                'price' => $item->price_sell,
                'brand_id' => $item->product->brand_id,
                'description' => Str::replaceFirst('Aktivasi ', '', $item->product->description),
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diambil',
            'data' => $products
        ], 200);
    }

    public function getSupplierProduct(Product $product)
    {
        return response()->json([
            'status' => true,
            'message' => 'Daftar Supplier',
            'data' => $product->getSupplier()
        ]);
    }

    public function getByCategoryBrandType(Request $request)
    {
      
        $this->validate($request, [
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'type_id' => 'required|exists:types,id'
        ]);

        $products = Product::select('id', 'name', 'price', 'description')
        ->category($request->category_id)
        ->brand($request->brand_id)
        ->type($request->type_id)
        ->addSelect(['price_supplier' => function ($query) {
            $query->select('price')->from('product_suppliers')->whereColumn('product_id', 'products.id')->where('status',1)->orderBy('price', 'desc')->limit(1);
        }])
        ->orderBy('price', 'asc')
        ->withCount('topup')
        ->orderByDesc('topup_count')
        ->get();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diambil',
            'data' => $products
        ], 200);
    }
    
    public function show(Product $product)
    {
        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diambil',
            'data' =>  [
                'product' => $product,
                'supplier' => $product->product_supplier->map(function ($item, $key) {
                    return [
                        'name' => $item->supplier->name,
                        'price' => $item->price,
                        'stok' => $item->stok,
                        'multi' => $item->multi,
                        'status' => $item->status,
                    ];
                })
            ]
        ], 200);
    }

}

