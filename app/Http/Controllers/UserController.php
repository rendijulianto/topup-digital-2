<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Pengguna, LogAktivitas};

class UserController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = Pengguna::search($request)->orderBy('nama', 'asc')->paginate(config('app.pagination.default'));
        return view('web.pages.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('web.pages.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => ['required','string','max:50'],
            'email' => ['required','string','max:30','unique:pengguna,email'],
            'password' => ['required','string','max:64'],
            'jabatan' => ['required','string','in:admin,kasir,injector'],
            'pin' => ['required_if:jabatan,kasir','nullable','numeric','digits:6'],
        ], [
            'nama.required' => 'Nama tidak boleh kosong!',
            'nama.string' => 'Nama harus berupa string!',
            'nama.max' => 'Nama maksimal 50 karakter!',
            'email.required' => 'Nama pengguna tidak boleh kosong!',
            'email.string' => 'Nama pengguna harus berupa string!',
            'email.max' => 'Nama pengguna maksimal 30 karakter!',
            'email.unique' => 'Nama pengguna sudah terdaftar!',
            'password.required' => 'Kata sandi tidak boleh kosong!',
            'password.string' => 'Kata sandi harus berupa string!',
            'password.max' => 'Kata sandi maksimal 64 karakter!',
            'jabatan.required' => 'Jabatan tidak boleh kosong!',
            'jabatan.string' => 'Jabatan harus berupa string!',
            'jabatan.in' => 'Jabatan harus admin atau kasir!',
            'pin.required_if' => 'Pin tidak boleh kosong!',
            'pin.numeric' => 'Pin harus berupa angka!',
            'pin.digits' => 'Pin harus 6 digit!',

        ]);

        try {
            $user = Pengguna::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'jabatan' => $request->jabatan,
            ]);

            if ($request->jabatan == 'kasir') {
                $this->validate($request, [
                    'pin' => ['required','numeric','digits:6'],
                ], [
                    'pin.required' => 'Pin tidak boleh kosong!',
                    'pin.numeric' => 'Pin harus berupa angka!',
                    'pin.digits' => 'Pin harus 6 digit!',
                ]);
                $user->pin()->create([
                    'pin' => bcrypt($request->pin)
                ]);
            }
            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Menambahkan pengguna baru dengan id '.$user->id,
                'user_agent' => $request->header('User-Agent'),
            ]);
           return response()->json([
                'status' => true,
                'message' => 'Data berhasil ditambahkan',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat menambahkan pengguna!',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengguna $user)
    {
        return view('web.pages.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengguna $user)
    {
        return view('web.pages.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengguna $user)
    {
        $this->validate($request, [
            'nama' => ['required','string','max:50'],
            'email' => ['required','string','max:30','unique:pengguna,email,'.$user->id],
            'password' => ['nullable','string','max:64'],
            'jabatan' => ['required','string','in:admin,kasir'],
        ], [
            'nama.required' => 'Nama tidak boleh kosong!',
            'nama.string' => 'Nama harus berupa string!',
            'nama.max' => 'Nama maksimal 50 karakter!',
            'email.required' => 'Nama pengguna tidak boleh kosong!',
            'email.string' => 'Nama pengguna harus berupa string!',
            'email.max' => 'Nama pengguna maksimal 30 karakter!',
            'email.unique' => 'Nama pengguna sudah terdaftar!',
            'password.string' => 'Kata sandi harus berupa string!',
            'password.max' => 'Kata sandi maksimal 64 karakter!',
            'jabatan.required' => 'Jabatan tidak boleh kosong!',
            'jabatan.string' => 'Jabatan harus berupa string!',
            'jabatan.in' => 'Jabatan harus admin atau kasir!',
        ]);
    

        try {
            $user->update([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => $request->password ? bcrypt($request->password) : $user->password,
                'jabatan' => $request->jabatan
            ]);

            if ($request->jabatan == 'kasir') {
               if ($request->pin) {
                    $this->validate($request, [
                        'pin' => ['required','numeric','digits:6'],
                    ], [
                        'pin.required' => 'Pin tidak boleh kosong!',
                        'pin.numeric' => 'Pin harus berupa angka!',
                        'pin.digits' => 'Pin harus 6 digit!',
                    ]);
                    $user->pin()->update([
                        'pin' => bcrypt($request->pin)
                    ]);
                } else {
                    $user->pin()->delete();
                }
            } else {
                $user->pin()->delete();
            }
            LogAktivitas::create([
                'pengguna_id' => auth()->guard('pengguna')->user()->id,
                'ip' => $request->ip(),
                'keterangan' => 'Mengubah pengguna dengan id '.$user->id,
                'user_agent' => $request->header('User-Agent'),
            ]);
           return response()->json([
                'status' => true,
                'message' => 'Data berhasil diubah',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat mengubah pengguna!',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengguna $user)
    {
        try {
            $user->delete();
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat menghapus pengguna!',
            ], 500);
        }
    }

    public function profile()
    {
        $user = auth()->guard('pengguna')->user();
        return view('web.pages.user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'nama' => ['required','string','max:50'],
            'pin' => ['nullable','numeric','digits:6'],
            'pin_baru' => ['nullable','numeric','digits:6'],
            'kata_sandi' => ['nullable','string','max:64'],
            'kata_sandi_baru' => ['nullable','string','max:64'],
        ], [
            'nama.required' => 'Nama tidak boleh kosong!',
            'nama.string' => 'Nama harus berupa string!',
            'nama.max' => 'Nama maksimal 50 karakter!',
            'pin.numeric' => 'Pin harus berupa angka!',
            'pin.digits' => 'Pin harus 6 digit!',
            'pin_baru.numeric' => 'Pin baru harus berupa angka!',
            'pin_baru.digits' => 'Pin baru harus 6 digit!',
            'kata_sandi.string' => 'Kata sandi harus berupa string!',
            'kata_sandi.max' => 'Kata sandi maksimal 64 karakter!',
            'kata_sandi_baru.string' => 'Kata sandi baru harus berupa string!',
            'kata_sandi_baru.max' => 'Kata sandi baru maksimal 64 karakter!',
        ]);

        try {
            $pengguna = Pengguna::find(auth()->guard('pengguna')->user()->id);

            if ($request->kata_sandi) {
                if (!password_verify($request->kata_sandi, $pengguna->password)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Kata sandi lama tidak sesuai!'
                    ],401);
                }
                $pengguna->update([
                    'password' => bcrypt($request->kata_sandi_baru)
                ]);
            }

            if ($pengguna->jabatan == 'kasir' || $pengguna->jabatan == 'injector') {
                if ($request->pin) {
                    if (!password_verify($request->pin, $pengguna->pin->pin)) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Pin lama tidak sesuai!'
                        ],401);
                    }
                    $pengguna->pin()->update([
                        'pin' => bcrypt($request->pin_baru)
                    ]);
                }
            }

            $pengguna->update([
                'nama' => $request->nama
            ]);

            LogAktivitas::create([
                'pengguna_id' => $pengguna->id,
                'ip' => $request->ip(),
                'keterangan' => 'Mengupdate profil',
                'user_agent' => $request->header('User-Agent'),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengupdate profil!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat mengupdate profil!'
            ], 500);
        }
    }
}
