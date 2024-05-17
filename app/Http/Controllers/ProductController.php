<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Brand, Kategori, Tipe, Produk, Supplier, SupplierProduk, LogAktivitas};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class ProductController extends Controller
{
    

    public function list(Request $request)
    {
        $products = Produk::with('kategori', 'brand', 'tipe')->category($request->category)->brand($request->brand)->type($request->type)->search($request->search)->paginate(config('app.pagination.default'));
        $categories = Kategori::orderBy('nama')->get();
        $brands = Brand::orderBy('nama')->get();
        $types = Tipe::orderBy('nama')->get();
        return view('web.pages.product.list', compact('categories', 'brands', 'types', 'products'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Produk::
        search($request)->
        with('kategori', 'brand', 'tipe')->paginate(config('app.pagination.default'));
        $categories = Kategori::orderBy('nama')->get();
        $brands = Brand::orderBy('nama')->get();
        $types = Tipe::orderBy('nama')->get();
        $suppliers = Supplier::orderBy('nama')->get();

        return view('web.pages.product.index', compact('categories', 'brands', 'types', 'products', 'suppliers'));
    }

    public function supplier(Request $request, Produk $product)
    {
        $products = $product->supplier_produk()->paginate(config('app.pagination.default'));
        return view('web.pages.product.supplier', compact('product', 'products'));
    }

    public function disruption(Request $request)
    {
        $products = Produk::
        search($request)->
        with('kategori', 'brand', 'tipe')->disruption()->paginate(config('app.pagination.default'));
        $categories = Kategori::orderBy('nama')->get();
        $brands = Brand::orderBy('nama')->get();
        $types = Tipe::orderBy('nama')->get();
        $suppliers = Supplier::orderBy('nama')->get();

        return view('web.pages.product.disruption', compact('categories', 'brands', 'types', 'products'));
    }

    // editMasal
    public function editMasal(Request $request)
    {
        $categories = Kategori::orderBy('nama')->get();
        return view('web.pages.product.edit-masal', compact('categories'));
    }

    //editSupplier
    public function editSupplier(SupplierProduk $supplierProduct)
    {
       
        $suppliers = Supplier::orderBy('nama')->get();
        return view('web.pages.product.edit-supplier', compact('supplierProduct', 'suppliers'));
    }

    // editCustomProfit
    public function editCustomProfit(Produk $product)
    {   
        if ($product->tipe->nama != "Custom") {
            return redirect()->back()->with('error', 'Produk bukan custom');
        }

        $profits = $product->profit_custom()->orderBy('profit')->get();
        return view('web.pages.product.edit-custom-profit', compact('product', 'profits'));
    }

    // editMasalCategory

    public function editMasalCategory(Kategori $category)
    {
        $brands = Brand::category($category->slug)->orderBy('nama')->get();
        return view('web.pages.product.edit-masal-category', compact('brands', 'category'));
    }

    // editMasalBrand
    public function editMasalBrand(Kategori $category, Brand $brand)
    {
        $types = Tipe::category($category->slug)->brand($brand->slug)->orderBy('nama')->get();
        return view('web.pages.product.edit-masal-brand', compact('types', 'category', 'brand'));
    }

    // editMasalType
    public function editMasalType(Kategori $category, Brand $brand, Tipe $type)
    {
        $products = Produk::category($category->slug)->brand($brand->slug)->type($type->slug)->orderBy('harga')->get();

        return view('web.pages.product.edit-masal-type', compact('products', 'category', 'brand', 'type'));
    }

    // updateMasal

    public function updateMasal(Request $request)
    {
        $this->validate($request, [
            'harga' => 'required|array',
            'harga.*' => 'required|numeric',
            'id' => 'required|array',
            'id.*' => 'required|exists:produk,id',
        ]);

        foreach ($request->id as $key => $id) {
            $product = Produk::find($id);
            $product->update([
                'harga' => $request->harga[$key]
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Berhasil mengubah data'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Kategori::orderBy('nama')->get();
        $brands = Brand::orderBy('nama')->get();
        $types = Tipe::orderBy('nama')->get();
        return view('web.pages.product.create', compact('categories', 'brands', 'types'));
    }

    public function createSupplier(Request $request, Produk $product)
    {
        $suppliers = Supplier::orderBy('nama')->get();
        return view('web.pages.product.create-supplier', compact('product', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required',
            'kategori_id' => 'required|exists:kategori,id',
            'tipe_id' => 'required|exists:tipe,id',
            'brand_id' => 'required|exists:brand,id',
            'harga' => 'required|numeric',
            'deskripsi' => 'required',
        ]);
        // dd($request->all());
        DB::beginTransaction();
        try {
            Produk::create($request->all());
            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Menambahkan produk ' . $request->nama,
                'user_agent' => $request->header('User-Agent'),
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil ditambahkan!'
            ], 201);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan data!'
            ], 500);
        }
    }

    public function storeSupplier(Request $request, Produk $product)
    {
        $this->validate($request, [
            'supplier_id' => 'required|exists:supplier,id',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
            'status' => 'required|boolean',
            'multi' => 'required|boolean',
            'jam_buka' => 'nullable|date_format:H:i',
            'jam_tutup' => 'nullable|date_format:H:i',
        ]);
        DB::beginTransaction();
        try {
            $product->supplier_produk()->create($request->all());
            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Menambahkan supplier produk ' . $product->nama,
                'user_agent' => $request->header('User-Agent'),
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Berhasil menambahkan supplier produk'
            ], 201);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan supplier produk'
            ], 500);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $product)
    {
        $categories = Kategori::orderBy('nama')->get();
        $brands = Brand::orderBy('nama')->get();
        $types = Tipe::orderBy('nama')->get();
        $suppliers = Supplier::orderBy('nama')->get();

        return view('web.pages.product.edit', compact('product', 'categories', 'brands', 'types', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $product)
    {
        $this->validate($request, [
            'nama' => 'required',
            'kategori_id' => 'required|exists:kategori,id',
            'tipe_id' => 'required|exists:tipe,id',
            'brand_id' => 'required|exists:brand,id',
            'harga' => 'required|numeric',
            'deskripsi' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $product->update($request->all());
            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Mengupdate produk ' . $request->nama,
                'user_agent' => $request->header('User-Agent'),
            ]);


            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diubah!'
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $th->getMessage() : 'Gagal mengubah data!'
            ], 500);
        }
    }

    public function updateSupplier(Request $request, SupplierProduk $supplierProduct)
    {
        $this->validate($request, [
            'supplier_id' => 'required|exists:supplier,id',
            'harga' => 'required',
            'stok' => 'required',
            'status' => 'required|boolean',
            'multi' => 'required|boolean',
        ], [
            'harga.numeric' => 'Harga harus berupa angka',
            'stok.numeric' => 'Stok harus berupa angka',
        ]);
        // validation.numeric

        DB::beginTransaction();
        try {

            $supplierProduct->update($request->all());
            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Mengupdate supplier produk ' . $supplierProduct->id,
                'user_agent' => $request->header('User-Agent'),
            ]);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Berhasil mengupdate data']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => config('app.debug') ? $th->getMessage() : 'Terjadi kesalahan saat mengupdate data']);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $product)
    {
        DB::beginTransaction();
        try {
            $product->delete();
            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => request()->ip(),
                'keterangan' => 'Menghapus produk ' . $product->nama,
                'user_agent' => request()->header('User-Agent'),
            ]);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Berhasil menghapus data']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => config('app.debug') ? $th->getMessage() : 'Terjadi kesalahan saat menghapus data']);
        }
    }

    public function destroySupplier(Request $request, SupplierProduk $supplierProduct)
    {
        DB::beginTransaction();
        try {
            $supplierProduct->delete();
            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Menghapus supplier produk ' . $supplierProduct->id,
                'user_agent' => $request->header('User-Agent'),
            ]);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Berhasil menghapus data']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => config('app.debug') ? $th->getMessage() : 'Terjadi kesalahan saat menghapus data']);
        }
    }
}
