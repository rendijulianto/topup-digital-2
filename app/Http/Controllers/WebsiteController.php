<?php

namespace App\Http\Controllers;

use App\Models\{LogAktivitas,Website};
use Illuminate\Http\Request;


class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $website = Website::first();
        return view('web.pages.website.index', compact('website'));
    }

    public function update(Request $request, Website $website)
    {
        $this->validate($request, [
            'nama' => 'required|string|max:50',
            'alamat' => 'required|string|max:50',
            'nomor_telepon' => 'required|string|max:15',
            'logo_website' => 'nullable|image|max:2048|mimes:jpg,jpeg,png,webp',
            'logo_print' => 'nullable|image|max:2048|mimes:jpg,jpeg,png,webp',
        ], [
            'nama.required' => 'Nama tidak boleh kosong!',
            'nama.string' => 'Nama harus berupa string!',
            'nama.max' => 'Nama maksimal 50 karakter!',
            'nama.unique' => 'Nama sudah terdaftar!',
            'alamat.required' => 'Alamat tidak boleh kosong!',
            'alamat.string' => 'Alamat harus berupa string!',
            'alamat.max' => 'Alamat maksimal 50 karakter!',
            'nomor_telepon.required' => 'Nomor telepon tidak boleh kosong!',
            'nomor_telepon.string' => 'Nomor telepon harus berupa string!',
            'nomor_telepon.max' => 'Nomor telepon maksimal 15 karakter!',
            'logo_website.image' => 'Logo website harus berupa gambar!',
            'logo_website.max' => 'Logo website maksimal 2MB!',
            'logo_website.mimes' => 'Logo website harus berupa JPG, JPEG, PNG, atau WEBP!',
            'logo_print.image' => 'Logo print harus berupa gambar!',
            'logo_print.max' => 'Logo print maksimal 2MB!',
            'logo_print.mimes' => 'Logo print harus berupa JPG, JPEG, PNG, atau WEBP!', 
        ]);

        try {

            if ($request->hasFile('logo_website')) {
                $logo_website = time() . '.' . $request->file('logo_website')->getClientOriginalExtension();
                $request->file('logo_website')->move('websites', $logo_website);
            }

            if ($request->hasFile('logo_print')) {
                $logo_print = time() . '.' . $request->file('logo_print')->getClientOriginalExtension();
                $request->file('logo_print')->move('websites', $logo_print);
            }


            $website->update([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'nomor_telepon' => $request->nomor_telepon,
                'logo_website' => $request->hasFile('logo_website') ? $logo_website : $website->logo_website,
                'logo_print' => $request->hasFile('logo_print') ? $logo_print : $website->logo_print,
            ]);

            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Melakukan perubahan data website',
                'user_agent' => $request->header('User-Agent'),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diubah!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat menambahkan website baru!',
            ], 500);
        }
    }

}