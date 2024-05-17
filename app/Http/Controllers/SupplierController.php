<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Supplier,LogAktivitas};
class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::orderBy('nama')->
        paginate(config('app.pagination.default'));

        return view('web.pages.supplier.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('web.pages.supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
              'nama' => 'required|string|max:50|unique:supplier,nama',
        ], [
            'nama.required' => 'Nama tidak boleh kosong!',
            'nama.unique' => 'Nama sudah terdaftar!',
        ]);

        try {
            Supplier::create([
                'nama' => $request->nama,
            ]);

            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Menambahkan supplier baru: ' . $request->nama,
                'user_agent' => $request->header('User-Agent'),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil ditambahkan',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat menambahkan supplier baru!',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return view('web.pages.supplier.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return view('web.pages.supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $this->validate($request, [
              'nama' => 'required|string|max:255|unique:supplier,nama,' . $supplier->id,
        ], [
            'nama.required' => 'Nama tidak boleh kosong!',
            'nama.unique' => 'Nama sudah terdaftar!'
        ]);
        
        try {
            $supplier->update([
                'nama' => $request->nama
            ]);
            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Mengubah supplier: ' . $supplier->nama,
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Supplier $supplier)
    {
        try {
            $supplier->delete();
            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Menghapus supplier: ' . $supplier->nama,
                'user_agent' => $request->header('User-Agent'),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return redirect()->route('admin.suppliers.index')->with('error',  config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat menghapus data!');
        }
    }


    // performance
    public function report(Request $request)
    {
    
        $suppliers = Supplier::
        performance($request)
        ->orderBy('total_transaksi','desc')->
        get();
        
        return view('web.pages.supplier.performance', compact('suppliers'));
    }


}
