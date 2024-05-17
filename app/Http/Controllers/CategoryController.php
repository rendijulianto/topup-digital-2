<?php

namespace App\Http\Controllers;

use App\Models\{Kategori,LogAktivitas};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Kategori::search($request)->orderBy('nama', 'asc')->paginate(config('app.pagination.default'));

        return view('web.pages.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('web.pages.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => ['required','string','max:255','unique:kategori,nama'],
        ], [
            'nama.required' => 'Nama tidak boleh kosong!',
            'nama.string' => 'Nama harus berupa string!',
            'nama.max' => 'Nama maksimal 255 karakter!',
            'nama.unique' => 'Nama sudah terdaftar!',
        ]);

        try {
            $category = Kategori::create([
                'nama' => $request->nama,
            ]);
            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Menambahkan kategori baru: ' . $category->nama,
                'user_agent' => $request->header('User-Agent'),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil ditambahkan',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat menambahkan kategori!',
            ], 500);
        }

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori $category)
    {
        return view('web.pages.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $category)
    {
        $this->validate($request, [
            'nama' => ['required','string','max:255','unique:kategori,nama,' . $category->id],
        ], [
            'nama.required' => 'Nama tidak boleh kosong!',
            'nama.string' => 'Nama harus berupa string!',
            'nama.max' => 'Nama maksimal 255 karakter!',
            'nama.unique' => 'Nama sudah terdaftar!',
        ]);

        try {
            $category->update([
                'nama' => $request->nama,
                
            ]);
            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Mengubah kategori: ' . $category->nama,
                'user_agent' => $request->header('User-Agent'),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diubah',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat mengubah kategori!',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $category)
    {
        try {
            $category->delete();
            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => request()->ip(),
                'keterangan' => 'Menghapus kategori: ' . $category->nama,
                'user_agent' => request()->header('User-Agent'),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat menghapus kategori!',
            ], 500);
        }
    }
}
