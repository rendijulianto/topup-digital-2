<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Brand};
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
}
