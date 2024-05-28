<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Brand, Category, Type, Product, Supplier, ProductSupplier, ActivityLog};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class ProductController extends Controller
{
    

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::
        search($request)->
        with('category', 'brand', 'type')->paginate(config('app.pagination.default'));
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $types = Type::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('web.pages.product.index', compact('categories', 'brands', 'types', 'products', 'suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $types = Type::orderBy('name')->get();
        return view('web.pages.product.create', compact('categories', 'brands', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'type_id' => 'required|exists:types,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric',
            'description' => 'required',
        ]);
        // dd($request->all());
        DB::beginTransaction();
        try {
            Product::create($request->all());
            ActivityLog::create([
                'user_id' => auth()->guard()->user()->id,
                'ip' => $request->ip(),
                'note' => 'Menambahkan produk ' . $request->name,
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


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $types = Type::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('web.pages.product.edit', compact('product', 'categories', 'brands', 'types', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'type_id' => 'required|exists:types,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric',
            'description' => 'required',
        ]);
        DB::beginTransaction();
        try {
       
            $product->update($request->all());
            ActivityLog::create([
                'user_id' => auth()->guard()->user()->id,
                'ip' => $request->ip(),
                'note' => 'Mengupdate produk ' . $request->name,
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();
        try {
            $product->delete();
            ActivityLog::create([
                'user_id' => auth()->guard()->user()->id,
                'ip' => request()->ip(),
                'note' => 'Menghapus produk ' . $product->name,
                'user_agent' => request()->header('User-Agent'),
            ]);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Berhasil menghapus data']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => config('app.debug') ? $th->getMessage() : 'Terjadi kesalahan saat menghapus data']);
        }
    }


    public function supplier(Request $request, Product $product)
    {
        $products = $product->product_suppliers()->paginate(config('app.pagination.default'));
        return view('web.pages.product.supplier', compact('product', 'products'));
    }

    
    public function createSupplier(Request $request, Product $product)
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('web.pages.product.create-supplier', compact('product', 'suppliers'));
    }

    public function storeSupplier(Request $request, Product $product)
    {
        $this->validate($request, [
            'supplier_id' => 'required|exists:suppliers,id',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'status' => 'required|boolean',
            'multi' => 'required|boolean',
            'jam_buka' => 'nullable|date_format:H:i',
            'jam_tutup' => 'nullable|date_format:H:i',
        ]);
        DB::beginTransaction();
        try {
            $product->product_suppliers()->create($request->all());
            ActivityLog::create([
                'user_id' => auth()->guard()->user()->id,
                'ip' => $request->ip(),
                'note' => 'Menambahkan produk supplier ' . $product->name,
                'user_agent' => $request->header('User-Agent'),
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Berhasil menambahkan produk supplier'
            ], 201);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan produk supplier'
            ], 500);
        }
    }

    
    public function updateSupplier(Request $request, ProductSupplier $supplierProduct)
    {
        $this->validate($request, [
            'supplier_id' => 'required|exists:suppliers,id',
            'price' => 'required',
            'stock' => 'required',
            'status' => 'required|boolean',
            'multi' => 'required|boolean',
        ], [
            'price.numeric' => 'Harga harus berupa angka',
            'stock.numeric' => 'Stok harus berupa angka',
        ]);
        // validation.numeric

        DB::beginTransaction();
        try {

            $supplierProduct->update($request->all());
            ActivityLog::create([
                'user_id' => auth()->guard()->user()->id,
                'ip' => $request->ip(),
                'note' => 'Mengupdate produk supplier ' . $supplierProduct->id,
                'user_agent' => $request->header('User-Agent'),
            ]);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Berhasil mengupdate data']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => config('app.debug') ? $th->getMessage() : 'Terjadi kesalahan saat mengupdate data']);
        }
    }

    //editSupplier
    public function editSupplier(ProductSupplier $supplierProduct)
    {  
        $suppliers = Supplier::orderBy('name')->get();
        return view('web.pages.product.edit-supplier', compact('supplierProduct', 'suppliers'));
    }

    public function destroySupplier(Request $request, ProductSupplier $supplierProduct)
    {
        DB::beginTransaction();
        try {
            $supplierProduct->delete();
            ActivityLog::create([
                'user_id' => auth()->guard()->user()->id,
                'ip' => $request->ip(),
                'note' => 'Menghapus produk supplier ' . $supplierProduct->id,
                'user_agent' => $request->header('User-Agent'),
            ]);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Berhasil menghapus data']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => false, 'message' => config('app.debug') ? $th->getMessage() : 'Terjadi kesalahan saat menghapus data']);
        }
    }

    // editMasal
    public function editPrice(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        return view('web.pages.product.edit-masal', compact('categories'));
    }
    
    public function updatePrice(Request $request)
    {
        $this->validate($request, [
            'price' => 'required|array',
            'price.*' => 'required|numeric',
            'id' => 'required|array',
            'id.*' => 'required|exists:products,id',
        ]);

        foreach ($request->id as $key => $id) {
            $product = Product::find($id);
            $product->update([
                'price' => $request->price[$key]
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Berhasil mengubah data'
        ]);
    }
    
    
}
