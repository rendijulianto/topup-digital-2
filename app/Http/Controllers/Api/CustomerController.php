<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\{Pelanggan};


class CustomerController extends Controller
{

    // store
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'nama' => 'required',
                'tipe' => 'required',
                'nomor' => 'required|unique:pelanggan,nomor,NULL,id,tipe,' . $request->tipe
            ], [
                'nama.required' => 'Nama tidak boleh kosong',
                'nomor.required' => 'Nomor tidak boleh kosong',
                'nomor.unique' => 'Nomor sudah terdaftar',
            ]);

            $pelanggan = Pelanggan::create([
                'nama' => $request->nama,
                'nomor' => $request->nomor,
                'tipe' => $request->tipe,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menambahkan pelanggan',
                'data' => $pelanggan
            ]);
            
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $th->getMessage() : 'Terjadi kesalahan'
            ]);
        }
    }
}
