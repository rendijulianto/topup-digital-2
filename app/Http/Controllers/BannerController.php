<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic;
use Webp;


class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::
        latest()->
        paginate(config('app.pagination.default'));

        return view('web.pages.banner.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('web.pages.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'judul' => ['required', 'string', 'max:50', 'unique:banner,judul'],
            'gambar' => ['required', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
        ], [
            'judul.required' => 'Judul tidak boleh kosong!',
            'judul.string' => 'Judul harus berupa string!',
            'judul.max' => 'Judul maksimal 50 karakter!',
            'judul.unique' => 'Judul sudah terdaftar!',
            'gambar.required' => 'Gambar tidak boleh kosong!',
            'gambar.image' => 'Gambar harus berupa gambar!',
            'gambar.max' => 'Gambar maksimal 2MB!',
            'gambar.mimes' => 'Gambar harus berformat JPG, JPEG, PNG, atau WEBP!',

        ]);

        try {
          
            $imageName = time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('banners'), $imageName);
     

            Banner::create([
                'judul' => $request->judul,
                'gambar' => $imageName,
            ]);

            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Menambahkan banner baru: ' . $request->judul,
                'user_agent' => $request->header('User-Agent'),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil ditambahkan',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat menambahkan data!',
            ], 500);
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        return view('web.pages.banner.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $this->validate($request, [
            'judul' => ['required', 'string', 'max:50', 'unique:banner,judul,' . $banner->id],
            'gambar' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
        ], [
            'judul.required' => 'Judul tidak boleh kosong!',
            'judul.string' => 'Judul harus berupa string!',
            'judul.max' => 'Judul maksimal 50 karakter!',
            'judul.unique' => 'Judul sudah terdaftar!',
            'gambar.image' => 'Gambar harus berupa gambar!',
            'gambar.max' => 'Gambar maksimal 2MB!',
            'gambar.mimes' => 'Gambar harus berformat JPG, JPEG, PNG, atau WEBP!',
        ]);

        try {
            if ($request->hasFile('gambar')) {
                if ($banner->gambar && file_exists(public_path("banners/{$banner->gambar}"))) {
                    unlink(public_path("banners/{$banner->gambar}"));
                }

                $imageName = time() . '.' . $request->gambar->extension();
                $request->gambar->move(public_path('banners'), $imageName);
            } else {
                $imageName = $banner->gambar;
            }

            $banner->update([
                'judul' => $request->judul,
                'gambar' => $imageName,
            ]);

            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Mengubah banner: ' . $banner->judul,
                'user_agent' => $request->header('User-Agent'),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diubah',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat mengubah data!',
            ], 500);
        }
    }

    public function updateStatus(Request $request, Banner $banner)
    {
        $this->validate($request, [
            'status' => ['boolean'],
        ], [
            'status.required' => 'Status tidak boleh kosong!',
            'status.boolean' => 'Status harus berupa Aktif atau Tidak Aktif!',
        ]);
        try {
            $banner->update([
                'status' => !$banner->status,
            ]);
            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Mengubah status banner: ' . $banner->judul,
                'user_agent' => $request->header('User-Agent'),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Status banner berhasil diubah!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Galat saat mengubah status banner!',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Banner $banner)
    {
        try {
            if ($banner->gambar && file_exists(public_path("banners/{$banner->gambar}"))) {
                unlink(public_path("banners/{$banner->gambar}"));
            }

            $banner->delete();

            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Menghapus banner: ' . $banner->judul,
                'user_agent' => $request->header('User-Agent'),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat menghapus data!',
            ], 500);
        }
    }
}
