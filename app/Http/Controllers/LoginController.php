<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;

class LoginController extends Controller
{
    public function index()
    {
        // melihat halaman login
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        // pengambilan data user sesuai dengan email yang di input
        $user = User::where('email', $request->email)->first();

        // pengkondisian jika data yang dicari tidak ditemukan
        if ($user == null)
        {
            // maka pengguna akan kembali ke halaman semula dengan pesan Email tidak terdaftar
            return redirect()->back()->with('ERR', 'Email tidak terdaftar');
        }

        // pengecekkan credentials apakah password sesuai atau tidak
        $credentials = $request->only('email', 'password');

        // pengkondisian jika password yang dimasukkan tidak sesuai
        if (!Auth::attempt($credentials))
        {
            // maka pengguna akan kembali ke halaman semula dengan pesan Email dan password tidak cocok
            return redirect()->back()->with('ERR', 'Email dan password tidak cocok');
        }

        // jika pengguna dapat melewati 2 pengkondisian diatas, maka pengguna akan dianggap berhasil login dan menuju halaman dashboard
        return redirect()->route('dashboard.index')->with('OK', 'Berhasil masuk ke dashboard');
    }

    public function logout()
    {
        // proses penghapusan session pada Authenticated
        Auth::logout();

        // pengguna akan menuju halaman login
        return redirect()->route('login')->with('OK', 'Berhasil keluar dari dashboard');
    }
}
