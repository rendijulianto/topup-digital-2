<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Type, Prefix};
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

        $prefix = Prefix::where('number', $prefix)->first();

        if (!$prefix) {
            return response()->json([
                'status' => false,
                'message' => 'Prefix tidak ditemukan'
            ], 404);
        }

        $types = Type::brand($prefix->brand_id)->category($request->category)->get();
        
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
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id'
        ]);

        $types = Type::select('id', 'name')
        ->brand($request->brand_id)
        ->category($request->category_id)->get();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diambil',
            'data' => [
                'types' => $types
            ]
        ], 200);
    }
}
