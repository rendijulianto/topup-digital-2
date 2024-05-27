<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\LogAktivitas;
class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $brands = Brand::search($request)->orderBy('nama', 'asc')->paginate(config('app.pagination.default'));

        return view('web.pages.brand.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('web.pages.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      
        $this->validate($request, [
            'nama' => ['required','string','max:255','unique:brand,nama'],
            'logo' => ['required','image','max:2048','mimes:jpg,jpeg,png,webp'],
        ], [
            'nama.required' => 'Nama tidak boleh kosong!',
            'nama.string' => 'Nama harus berupa string!',
            'nama.max' => 'Nama maksimal 255 karakter!',
            'nama.unique' => 'Nama sudah terdaftar!',
            'logo.required' => 'Logo tidak boleh kosong!',
            'logo.image' => 'Logo harus berupa gambar!',
            'logo.max' => 'Logo maksimal 2MB!',
            'logo.mimes' => 'Logo harus berupa JPG, JPEG, PNG, atau WEBP!',
        ]);
 

        try {

            $logo_nama = time() . '.'.$request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move('brands', $logo_nama);
        
            // Simpan brand
            Brand::create([
                'nama' => $request->nama,
                'logo' => $logo_nama,
            ]);
            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Menambahkan brand baru dengan nama '.$request->nama,
                'user_agent' => $request->header('User-Agent'),
            ]);
           return response()->json([
                'status' => true,
                'message' => 'Berhasil menambah data',
           ],201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $th->getMessage() : 'Oops, terjadi kesalahan!',
            ],500);
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        return view('web.pages.brand.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $this->validate($request, [
            'nama' => ['required','string','max:255','unique:brand,nama,' . $brand->id],
            'logo' => ['nullable','image','max:2048','mimes:jpg,jpeg,png'],
        ], [
            'nama.required' => 'Nama tidak boleh kosong!',
            'nama.string' => 'Nama harus berupa string!',
            'nama.max' => 'Nama maksimal 255 karakter!',
            'nama.unique' => 'Nama sudah terdaftar!',
            'logo.image' => 'Logo harus berupa gambar!',
            'logo.max' => 'Logo maksimal 2MB!',
            'logo.mimes' => 'Logo harus berupa JPG, JPEG, atau PNG!',
        ]);

        try {
            
            if ($request->file('logo')) {
                // Hapus logo lama jika ada
                if ($brand->logo && file_exists(public_path("brands/{$brand->logo}"))) {
                    unlink(public_path("brands/{$brand->logo}"));
                }
                
                $logo_nama = time() . '.'.$request->file('logo')->getClientOriginalExtension();
                $request->file('logo')->move('brands', $logo_nama);
        
                // Update brand
                $brand->update([
                    'nama' => $request->nama,
                    'logo' => $logo_nama,
                ]);
            } else {
                // Update brand tanpa logo
                $brand->update([
                    'nama' => $request->nama,
                ]);
            }

            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Mengubah brand dengan id '.$brand->id,
                'user_agent' => $request->header('User-Agent'),
            ]);
           
           return response()->json([
                 'status' => true,
                 'message' => 'Berhasil mengubah data',
              ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $th->getMessage() : 'Oops, terjadi kesalahan!',
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Brand $brand)
    {
        try {
            if ($brand->logo && file_exists(public_path("brands/{$brand->logo}"))) {
                unlink(public_path("brands/{$brand->logo}"));
            }
            $brand->delete();
            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Menghapus brand dengan id '.$brand->id,
                'user_agent' => $request->header('User-Agent'),
            ]);
           return response()->json([
                'status' => true,
                'message' => 'Berhasil menghapus data',
           ],200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Oops, terjadi kesalahan!',
            ],500);
        }
    }
}
