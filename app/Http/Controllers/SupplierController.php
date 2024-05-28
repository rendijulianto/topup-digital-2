<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\{Supplier,ActivityLog};
class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::orderBy('name')->
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
              'name' => 'required|string|max:50|unique:suppliers,name',
        ], [
            'name.required' => 'Nama tidak boleh kosong!',
            'name.unique' => 'Nama sudah terdaftar!',
        ]);

        try {
            Supplier::create([
                'name' => $request->name,
            ]);

            ActivityLog::create([
                'user_id' => auth()->guard()->user()->id,
                'ip' => $request->ip(),
                'note' => 'Menambahkan supplier baru: ' . $request->name,
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
              'name' => 'required|string|max:255|unique:suppliers,name,' . $supplier->id,
        ], [
            'name.required' => 'Nama tidak boleh kosong!',
            'name.unique' => 'Nama sudah terdaftar!'
        ]);
        
        try {
            $supplier->update([
                'name' => $request->name
            ]);
            ActivityLog::create([
                'user_id' => auth()->guard()->user()->id,
                'ip' => $request->ip(),
                'note' => 'Mengubah supplier: ' . $supplier->name,
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
            ActivityLog::create([
                'user_id' => auth()->guard()->user()->id,
                'ip' => $request->ip(),
                'note' => 'Menghapus supplier: ' . $supplier->name,
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
        if ($request->start && $request->end) {
            $start = Carbon::parse($request->start);
            $end = Carbon::parse($request->end);
        } else {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
            $request->start = $start->format('Y-m-d');
            $request->end = $end->format('Y-m-d');
        }
        $suppliers = Supplier::
        performance($request)
        ->orderBy('total_transaksi','desc')->
        get();
        return view('web.pages.supplier.performance', compact('suppliers','start','end'));
    }


}
