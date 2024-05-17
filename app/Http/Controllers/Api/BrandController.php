<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Kategori, Brand, Tipe, Produk, Supplier};
use Illuminate\Support\Str;
class BrandController extends Controller
{
    public function getByCategory(Request $request)
    {
        $this->validate($request, [
            'kategori' => 'required|exists:kategori,nama',
        ]);
        
        $brands = Brand::select('id', 'nama','logo')
        ->category($request->kategori)
        ->withCount('topup')
        ->orderByDesc('topup_count')
        ->orderBy('nama', 'asc')->get();
        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diambil',
            'data' => $brands
        ], 200);
    }


    public function voucher(Request $request)
    {
        $this->validate($request, [
            'brand_id' => 'required|exists:brand,id',
        ]);
        
        $brand = Brand::findOrFail($request->brand_id);
        $vouchers = $brand->voucher_kosong()->select('id', 'harga', 'stok','tgl_kadaluarsa')
            ->where('stok', '>', 0)
        ->orderBy('harga', 'asc')->get();
        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diambil',
            'data' => $vouchers
        ], 200);
    }
}
