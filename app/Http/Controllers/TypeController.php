<?php

namespace App\Http\Controllers;

use App\Models\{Tipe,LogAktivitas};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $types = Tipe::search($request)->orderBy('nama', 'asc')->paginate(config('app.pagination.default'));

        return view('web.pages.type.index', compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('web.pages.type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => ['required','string','max:255','unique:tipe,nama'],
        ], [
            'nama.required' => 'Nama tidak boleh kosong!',
            'nama.string' => 'Nama harus berupa string!',
            'nama.max' => 'Nama maksimal 255 karakter!',
            'nama.unique' => 'Nama sudah terdaftar!',
        ]);

        try {
            $type = Tipe::create([
                'nama' => $request->nama,
                
            ]);
            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Menambahkan tipe baru: ' . $type->nama,
                'user_agent' => $request->header('User-Agent'),
            ]);
           return response()->json([
                'status' => true,
                'message' => 'Data berhasil ditambahkan',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat menambahkan tipe!',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tipe $type)
    {
        return view('web.pages.type.show', compact('type'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tipe $type)
    {
        return view('web.pages.type.edit', compact('type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tipe $type)
    {
        $this->validate($request, [
            'nama' => ['required','string','max:255','unique:tipe,nama,' . $type->id],
        ], [
            'nama.required' => 'Nama tidak boleh kosong!',
            'nama.string' => 'Nama harus berupa string!',
            'nama.max' => 'Nama maksimal 255 karakter!',
            'nama.unique' => 'Nama sudah terdaftar!',
        ]);

        try {
            $type->update([
                'nama' => $request->nama,
            ]);
            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Mengupdate tipe: ' . $type->nama,
                'user_agent' => $request->header('User-Agent'),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diubah',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat mengubah tipe!',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tipe $type)
    {
        try {
            $type->delete();
            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => request()->ip(),
                'keterangan' => 'Menghapus tipe: ' . $type->nama,
                'user_agent' => request()->header('User-Agent'),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat menghapus tipe!',
            ], 500);
        }
    }
}
