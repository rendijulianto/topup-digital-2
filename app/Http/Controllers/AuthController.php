<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;
use App\Models\ActivityLog;
class AuthController extends Controller
{


    public function login()
    {
        return view('web.pages.auth.login');
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|max:191',
            'password' => 'required|min:6|max:191',
        ], [
            'email.required' => 'Email tidak boleh kosong!',
            'email.max' => 'Email terlalu panjang!',
            'password.required' => 'Password tidak boleh kosong!',
            'password.min' => 'Password terlalu pendek',
            'password.max' => 'Password terlalu panjang',
        ]);
        $credentials = $request->only('email', 'password');
    
      
        if(Auth::guard()->attempt($credentials)) {
            ActivityLog::create([
                'user_id' => auth()->user()->id,
                'ip' => $request->ip(),
                'note' => 'Masuk ke sistem',
                'user_agent' => $request->header('User-Agent'),
            ]);
           if (auth()->user()->role == 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif (auth()->user()->role == 'kasir') {
                return redirect()->route('cashier.dashboard');
            } elseif (auth()->user()->role == 'injector') {
                return redirect()->route('injector.dashboard');
            }
        }	
        return redirect()->back()->with('error', 'email atau kata sandi salah!');
    }

    public function forgotPassword()
    {
        return view('web.pages.auth.forgotPassword');
    }

    public function postForgotPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:191',
        ], [
            'email.required' => 'Email tidak boleh kosong!',
            'email.email' => 'Email tidak valid!',
            'email.max' => 'Email terlalu panjang!',
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $token = Str::random(64);
            $newPassword = Str::random(8);
            $user->update([
                'token' => $token,
            ]);
            $details = [
                'link' => route('verifyResetPassword', ['token' => $token]),
                'newPassword' => $newPassword,	
            ];

            \Mail::to($user->email)->send(new ResetPasswordMail($details));

            return redirect()->back()->with('success', 'Silahkan cek email anda untuk mereset password!');
        }
        return redirect()->back()->with('error', 'Email tidak ditemukan!');
    }

    public function verifyResetPassword(Request $request, $token)
    {
        $this->validate($request, [
            'newPassword' => 'required|min:6|max:191',
        ], [
            'newPassword.required' => 'Password baru tidak boleh kosong!',
            'newPassword.min' => 'Password baru terlalu pendek!',
            'newPassword.max' => 'Password baru terlalu panjang!',
        ]);
        $user = User::where('token', $token)->first();
        if ($user) {
            $user->update([
                'password' => bcrypt($request->newPassword),
                'token' => null,
            ]);
            return redirect()->route('login')->with('success', 'Password berhasil direset!');
        }
        return redirect()->route('login')->with('error', 'Token tidak valid!');
    }

    // logout
    public function logout(Request $request)
    {
        if (auth()->check()) {
            ActivityLog::create([
                'user_id' => auth()->user()->id,
                'ip' => $request->ip(),
                'note' => 'Keluar dari sistem',
                'user_agent' => $request->header('User-Agent'),
            ]);
            auth()->logout();
        }
        return redirect()->route('login')->with('success', 'Berhasil logout!');
    }

}
