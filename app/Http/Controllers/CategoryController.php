<?php

namespace App\Http\Controllers;

use App\Models\{Category,ActivityLog};
use Illuminate\Http\Request;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::search($request)->orderBy('name', 'asc')->paginate(config('app.pagination.default'));

        return view('web.pages.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('web.pages.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required','string','max:255','unique:categories,name'],
        ], [
            'name.required' => 'Nama tidak boleh kosong!',
            'name.string' => 'Nama harus berupa string!',
            'name.max' => 'Nama maksimal 255 karakter!',
            'name.unique' => 'Nama sudah terdaftar!',
        ]);

        try {
            $category = Category::create([
                'name' => $request->name,
            ]);
            ActivityLog::create([
                'user_id' => auth()->guard()->user()->id,
                'ip' => $request->ip(),
                'note' => 'Menambahkan kategori baru: ' . $category->name,
                'user_agent' => $request->header('User-Agent'),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil ditambahkan',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat menambahkan kategori!',
            ], 500);
        }

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('web.pages.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $this->validate($request, [
            'name' => ['required','string','max:255','unique:categories,name,' . $category->id],
        ], [
            'name.required' => 'Nama tidak boleh kosong!',
            'name.string' => 'Nama harus berupa string!',
            'name.max' => 'Nama maksimal 255 karakter!',
            'name.unique' => 'Nama sudah terdaftar!',
        ]);

        try {
            $category->update([
                'name' => $request->name,
                
            ]);
            ActivityLog::create([
                'user_id' => auth()->guard()->user()->id,
                'ip' => $request->ip(),
                'note' => 'Mengubah kategori: ' . $category->name,
                'user_agent' => $request->header('User-Agent'),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diubah',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat mengubah kategori!',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            ActivityLog::create([
                'user_id' => auth()->guard()->user()->id,
                'ip' => request()->ip(),
                'note' => 'Menghapus kategori: ' . $category->name,
                'user_agent' => request()->header('User-Agent'),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat menghapus kategori!',
            ], 500);
        }
    }
}
