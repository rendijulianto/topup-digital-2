<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\ActivityLog;
use Illuminate\Http\Request;



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
            'title' => ['required', 'string', 'max:50', 'unique:banners,title'],
            'image' => ['required', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
        ], [
            'title.required' => 'Judul tidak boleh kosong!',
            'title.string' => 'Judul harus berupa string!',
            'title.max' => 'Judul maksimal 50 karakter!',
            'title.unique' => 'Judul sudah terdaftar!',
            'image.required' => 'Gambar tidak boleh kosong!',
            'image.image' => 'Gambar harus berupa image!',
            'image.max' => 'Gambar maksimal 2MB!',
            'image.mimes' => 'Gambar harus berformat JPG, JPEG, PNG, atau WEBP!',

        ]);

        try {
          
            $imageName = time() . '.' . $request->image->extension();
            $request->file('image')->move('banners', $imageName);
            // $request->image->move(public_path('banners'), $imageName);
     

            Banner::create([
                'title' => $request->title,
                'image' => $imageName,
            ]);

            ActivityLog::create([
                'user_id' => auth()->guard()->user()->id,
                'ip' => $request->ip(),
                'note' => 'Menambahkan banner baru: ' . $request->title,
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
            'title' => ['required', 'string', 'max:50', 'unique:banners,title,' . $banner->id],
            'image' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
        ], [
            'title.required' => 'Judul tidak boleh kosong!',
            'title.string' => 'Judul harus berupa string!',
            'title.max' => 'Judul maksimal 50 karakter!',
            'title.unique' => 'Judul sudah terdaftar!',
            'image.image' => 'Gambar harus berupa image!',
            'image.max' => 'Gambar maksimal 2MB!',
            'image.mimes' => 'Gambar harus berformat JPG, JPEG, PNG, atau WEBP!',
        ]);

        try {
            if ($request->hasFile('image')) {
                if ($banner->image && file_exists(public_path("banners/{$banner->image}"))) {
                    unlink(public_path("banners/{$banner->image}"));
                }

                $imageName = time() . '.' . $request->image->extension();
                $request->file('image')->move('banners', $imageName);
            } else {
                $imageName = $banner->image;
            }

            $banner->update([
                'title' => $request->title,
                'image' => $imageName,
            ]);

            ActivityLog::create([
                'user_id' => auth()->guard()->user()->id,
                'ip' => $request->ip(),
                'note' => 'Mengubah banner: ' . $banner->title,
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
            ActivityLog::create([
                'user_id' => auth()->guard()->user()->id,
                'ip' => $request->ip(),
                'note' => 'Mengubah status banner: ' . $banner->title,
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
            if ($banner->image && file_exists(public_path("banners/{$banner->image}"))) {
                unlink(public_path("banners/{$banner->image}"));
            }

            $banner->delete();

            ActivityLog::create([
                'user_id' => auth()->guard()->user()->id,
                'ip' => $request->ip(),
                'note' => 'Menghapus banner: ' . $banner->title,
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
