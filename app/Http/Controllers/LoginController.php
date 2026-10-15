<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'role'     => ['required', 'in:admin,staff'],
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Peran yang dipilih ikut dicocokkan dengan peran akun di database.
        // Peran yang sebenarnya tetap ditentukan oleh database, bukan oleh pilihan di form.
        $berhasil = Auth::attempt(
            [
                'username' => $data['username'],
                'password' => $data['password'],
                'role'     => $data['role'],
            ],
            $request->boolean('remember')
        );

        if (! $berhasil) {
            // Satu pesan umum supaya tidak membocorkan mana yang salah.
            return back()
                ->withErrors(['login' => 'Peran, username, atau kata sandi tidak cocok.'])
                ->onlyInput('username', 'role');
        }

        $request->session()->regenerate();

           return redirect()->intended('/dashboard')->with('baru_login', true);
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
