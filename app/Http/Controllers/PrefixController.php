<?php

namespace App\Http\Controllers;

use App\Models\{Brand, Prefix};
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class PrefixController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $prefixes = Prefix::search($request)
        ->orderBy('nomor', 'asc')->paginate(config('app.pagination.default'));
        $brands = Brand::category('pulsa')->orderBy('nama', 'asc')->get();
        return view('web.pages.prefix.index', compact('prefixes', 'brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::category('pulsa')->orderBy('nama', 'asc')->get();

        return view('web.pages.prefix.create', compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nomor' => ['required','string','max:5','unique:prefix,nomor'],
            'brand_id' => ['required','exists:brand,id'],
        ], [
            'nomor.required' => 'Nomor tidak boleh kosong!',
            'nomor.string' => 'Nomor harus berupa string!',
            'nomor.max' => 'Nomor maksimal 5 karakter!',
            'nomor.unique' => 'Nomor sudah terdaftar!',
            'brand_id.required' => 'Brand tidak boleh kosong!',
            'brand_id.exists' => 'Brand tidak ditemukan!',
        ]);

        try {
            $prefix = Prefix::create([
                'nomor' => $request->nomor,
                'brand_id' => $request->brand_id,
            ]);
            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Menambahkan prefix baru: ' . $prefix->nomor,
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
    public function show(Prefix $prefix)
    {
        return view('web.pages.prefix.show', compact('prefix'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prefix $prefix)
    {
        $brands = Brand::category('pulsa')->orderBy('nama', 'asc')->get();
        return view('web.pages.prefix.edit', compact('prefix', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prefix $prefix)
    {
        $this->validate($request, [
            'nomor' => ['required','string','max:5','unique:prefix,nomor,' . $prefix->id],
            'brand_id' => ['nullable','exists:brand,id'],
        ], [
            'nomor.required' => 'Nomor tidak boleh kosong!',
            'nomor.string' => 'Nomor harus berupa string!',
            'nomor.max' => 'Nomor maksimal 5 karakter!',
            'nomor.unique' => 'Nomor sudah terdaftar!',
            'brand_id.exists' => 'Brand tidak ditemukan!',
        ]);

        try {
            $prefix->update([
                'nomor' => $request->nomor,
                'brand_id' => $request->brand_id,
            ]);
            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Mengubah prefix: ' . $prefix->nomor,
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
            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => request()->ip(),
                'keterangan' => 'Menghapus prefix: ' . $prefix->nomor,
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
