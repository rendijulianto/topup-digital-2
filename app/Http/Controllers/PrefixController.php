<?php

namespace App\Http\Controllers;

use App\Models\{Brand, Prefix};
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PrefixController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $prefixes = Prefix::search($request)
        ->orderBy('number', 'asc')->paginate(config('app.pagination.default'));
        $brands = Brand::category('pulsa')->orderBy('name', 'asc')->get();
        return view('web.pages.prefix.index', compact('prefixes', 'brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::category('pulsa')->orderBy('name', 'asc')->get();

        return view('web.pages.prefix.create', compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'number' => ['required','string','max:5','unique:prefixs,number'],
            'brand_id' => ['required','exists:brands,id'],
        ], [
            'number.required' => 'Nomor tidak boleh kosong!',
            'number.string' => 'Nomor harus berupa string!',
            'number.max' => 'Nomor maksimal 5 karakter!',
            'number.unique' => 'Nomor sudah terdaftar!',
            'brand_id.required' => 'Brand tidak boleh kosong!',
            'brand_id.exists' => 'Brand tidak ditemukan!',
        ]);

        try {
            $prefix = Prefix::create([
                'number' => $request->number,
                'brand_id' => $request->brand_id,
            ]);
            ActivityLog::create([
                'user_id' => auth()->guard()->user()->id,
                'ip' => $request->ip(),
                'note' => 'Menambahkan prefix baru: ' . $prefix->number,
                'user_agent' => $request->header('User-Agent'),
            ]);
           return response()->json([
                'status' => true,
                'message' => 'Berhasil menambahkan data!'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat menambahkan data!'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prefix $prefix)
    {
        $brands = Brand::category('pulsa')->orderBy('name', 'asc')->get();
        return view('web.pages.prefix.edit', compact('prefix', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prefix $prefix)
    {
        $this->validate($request, [
            'number' => ['required','string','max:5','unique:prefixs,number,' . $prefix->id],
            'brand_id' => ['nullable','exists:brands,id'],
        ], [
            'number.required' => 'Nomor tidak boleh kosong!',
            'number.string' => 'Nomor harus berupa string!',
            'number.max' => 'Nomor maksimal 5 karakter!',
            'number.unique' => 'Nomor sudah terdaftar!',
            'brand_id.exists' => 'Brand tidak ditemukan!',
        ]);

        try {
            $prefix->update([
                'number' => $request->number,
                'brand_id' => $request->brand_id,
            ]);
            ActivityLog::create([
                'user_id' => auth()->guard()->user()->id,
                'ip' => $request->ip(),
                'note' => 'Mengubah prefix: ' . $prefix->number,
                'user_agent' => $request->header('User-Agent'),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengubah data!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat mengubah data!'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prefix $prefix)
    {
        try {
            $prefix->delete();
            ActivityLog::create([
                'user_id' => auth()->guard()->user()->id,
                'ip' => request()->ip(),
                'note' => 'Menghapus prefix: ' . $prefix->number,
                'user_agent' => request()->header('User-Agent'),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Berhasil menghapus data!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat menghapus data!'
            ], 500);
        }
    }
}
