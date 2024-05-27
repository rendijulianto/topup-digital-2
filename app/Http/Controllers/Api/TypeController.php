<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Tipe, Prefix};
use Illuminate\Support\Str;
class TypeController extends Controller
{
    public function getByCategoryPrefix(Request $request)
    {
        $this->validate($request, [
            'prefix' => 'required'
        ]);

        if (strlen($request->prefix) < 4) {
            return response()->json([
                'status' => false,
                'message' => 'Minimal 4 karakter'
            ], 400);
        }
        
        $prefix = Str::substr($request->prefix, 0, 4);

        $prefix = Prefix::where('nomor', $prefix)->first();

        if (!$prefix) {
            return response()->json([
                'status' => false,
                'message' => 'Prefix tidak ditemukan'
            ], 404);
        }

        $types = Tipe::brand($prefix->brand_id)->category($request->kategori)->get();
        
        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diambil',
            'data' => [
                'brand' => [
                    'id' => $prefix->brand->id,
                    'logo' => $prefix->brand->logo_url,
                ],
                'types' => $types
            ]
        ], 200);
    }

    public function getByCategoryBrand(Request $request)
    {
        $this->validate($request, [
            'kategori_id' => 'required|exists:kategori,id',
            'brand_id' => 'required|exists:brand,id'
        ]);

        $types = Tipe::select('id', 'nama')
        ->brand($request->brand_id)
        ->category($request->kategori_id)->get();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diambil',
            'data' => [
                'types' => $types
            ]
        ], 200);
    }
}
