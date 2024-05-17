<?php

namespace App\Http\Controllers;

use App\Models\{Pelanggan,Brand};
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Pelanggan::orderBy('nama')->paginate(config('app.pagination.default'));

        return view('web.pages.customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::orderBy('nama')->get();
        return view('web.pages.customer.create', compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|string|max:50',
            'nomor' => 'required|string|max:15',
            'brand_id' => 'required|exists:brand,id',
        ], [
            'nama.required' => 'Nama tidak boleh kosong!',
            'nama.string' => 'Nama harus berupa string!',
            'nama.max' => 'Nama maksimal 50 karakter!',
            'nomor.required' => 'Nomor tidak boleh kosong!',
            'nomor.string' => 'Nomor harus berupa string!',
            'nomor.max' => 'Nomor maksimal 15 karakter!',
            'brand_id.required' => 'Brand tidak boleh kosong!',
            'brand_id.exists' => 'Brand tidak valid!',
        ]);

        try {
            Pelanggan::create([
                'nama' => $request->nama,
                'nomor' => $request->nomor,
                'brand_id' => $request->brand_id,
            ]);
            return redirect()->route('admin.customers.index')->with('success', 'Data berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->route('admin.customers.index')->with('error', config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat menambahkan pelanggan baru!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pelanggan $customer)
    {
        return view('web.pages.customer.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pelanggan $customer)
    {
        $brands = Brand::orderBy('nama')->get();
        return view('web.pages.customer.edit', compact('customer', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pelanggan $customer)
    {
        $this->validate($request, [
            'nama' => 'required|string|max:50',
            'nomor' => 'required|string|max:15',
            'brand_id' => 'required|exists:brand,id',
        ], [
            'nama.required' => 'Nama tidak boleh kosong!',
            'nama.string' => 'Nama harus berupa string!',
            'nama.max' => 'Nama maksimal 50 karakter!',
            'nomor.required' => 'Nomor tidak boleh kosong!',
            'nomor.string' => 'Nomor harus berupa string!',
            'nomor.max' => 'Nomor maksimal 15 karakter!',
            'brand_id.required' => 'Brand tidak boleh kosong!',
            'brand_id.exists' => 'Brand tidak valid!',
        ]);

        try {
            $customer->update([
                'nama' => $request->nama,
                'nomor' => $request->nomor,
                'brand_id' => $request->brand_id,
            ]);
            return redirect()->route('admin.customers.index')->with('success', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('admin.customers.index')->with('error', config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat memperbarui pelanggan!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pelanggan $customer)
    {
        try {
            $customer->delete();
            return redirect()->route('admin.customers.index')->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.customers.index')->with('error', config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat menghapus pelanggan!');
        }
    }
}
