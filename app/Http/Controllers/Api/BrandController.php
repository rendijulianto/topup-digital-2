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
            'category' => 'required|exists:categories,name',
        ]);
    
        $brands = Brand::select('id', 'name','logo')
        ->category($request->category)
        ->withCount('topup')
        ->orderByDesc('topup_count')
        ->orderBy('name', 'asc')->get();
        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diambil',
            'data' => $brands
        ], 200);
    }
}
