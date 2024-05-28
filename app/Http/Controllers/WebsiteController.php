<?php

namespace App\Http\Controllers;

use App\Models\{ActivityLog,Website};
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
            'name' => 'required|string|max:50',
            'address' => 'required|string|max:50',
            'telp' => 'required|string|max:15',
            'logo_website' => 'nullable|image|max:2048|mimes:jpg,jpeg,png,webp',
            'logo_print' => 'nullable|image|max:2048|mimes:jpg,jpeg,png,webp',
        ], [
            'name.required' => 'Nama tidak boleh kosong!',
            'name.string' => 'Nama harus berupa string!',
            'name.max' => 'Nama maksimal 50 karakter!',
            'name.unique' => 'Nama sudah terdaftar!',
            'address.required' => 'Alamat tidak boleh kosong!',
            'address.string' => 'Alamat harus berupa string!',
            'address.max' => 'Alamat maksimal 50 karakter!',
            'telp.required' => 'Nomor telepon tidak boleh kosong!',
            'telp.string' => 'Nomor telepon harus berupa string!',
            'telp.max' => 'Nomor telepon maksimal 15 karakter!',
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
                'name' => $request->name,
                'address' => $request->address,
                'telp' => $request->telp,
                'logo_website' => $request->hasFile('logo_website') ? $logo_website : $website->logo_website,
                'logo_print' => $request->hasFile('logo_print') ? $logo_print : $website->logo_print,
            ]);

            ActivityLog::create([
                'user_id' => auth()->guard()->user()->id,
                'ip' => $request->ip(),
                'note' => 'Melakukan perubahan data website',
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