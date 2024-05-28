<?php

namespace App\Http\Controllers;

use App\Models\{Type,ActivityLog};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $types = Type::search($request)->orderBy('name', 'asc')->paginate(config('app.pagination.default'));

        return view('web.pages.type.index', compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('web.pages.type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required','string','max:255','unique:types,name'],
        ], [
            'name.required' => 'Nama tidak boleh kosong!',
            'name.string' => 'Nama harus berupa string!',
            'name.max' => 'Nama maksimal 255 karakter!',
            'name.unique' => 'Nama sudah terdaftar!',
        ]);

        try {
            $type = Type::create([
                'name' => $request->name,
                
            ]);
            ActivityLog::create([
                'user_id' => auth()->guard()->user()->id,
                'ip' => $request->ip(),
                'note' => 'Menambahkan tipe baru: ' . $type->name,
                'user_agent' => $request->header('User-Agent'),
            ]);
           return response()->json([
                'status' => true,
                'message' => 'Data berhasil ditambahkan',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat menambahkan tipe!',
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Type $type)
    {
        return view('web.pages.type.edit', compact('type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Type $type)
    {
        $this->validate($request, [
            'name' => ['required','string','max:255','unique:types,name,' . $type->id],
        ], [
            'name.required' => 'Nama tidak boleh kosong!',
            'name.string' => 'Nama harus berupa string!',
            'name.max' => 'Nama maksimal 255 karakter!',
            'name.unique' => 'Nama sudah terdaftar!',
        ]);

        try {
            $type->update([
                'name' => $request->name,
            ]);
            ActivityLog::create([
                'user_id' => auth()->guard()->user()->id,
                'ip' => $request->ip(),
                'note' => 'Mengupdate tipe: ' . $type->name,
                'user_agent' => $request->header('User-Agent'),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diubah',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat mengubah tipe!',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type $type)
    {
        try {
            $type->delete();
            ActivityLog::create([
                'user_id' => auth()->guard()->user()->id,
                'ip' => request()->ip(),
                'note' => 'Menghapus tipe: ' . $type->name,
                'user_agent' => request()->header('User-Agent'),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat menghapus tipe!',
            ], 500);
        }
    }
}
