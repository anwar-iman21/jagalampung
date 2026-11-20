<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $r)
    {
        if ($r->filled('nik')) {
            $c = $r->validate([
                'nik' => ['required', 'digits:16'],
            ], [
                'nik.required' => 'NIK wajib diisi.',
                'nik.digits' => 'NIK harus terdiri dari 16 angka.',
            ]);

            $nikHash = hash_hmac('sha256', $c['nik'], (string) config('app.key'));
            $user = User::where('nik_hash', $nikHash)
                ->where('role', 'warga')
                ->where('is_active', true)
                ->first();

            if (! $user) {
                return back()->withErrors(['nik' => 'NIK tidak ditemukan atau akun tidak aktif.'])->onlyInput();
            }

            $r->session()->regenerate();
            Auth::login($user);
            AuditLog::record('login');

            return redirect()->intended(route('warga.dashboard'));
        }

        $c = $r->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($c + ['is_active' => true], false)) {
            $r->session()->regenerate();
            AuditLog::record('login');

            return redirect()->intended(route('admin.reports'));
        }

        return back()->withErrors(['email' => 'Email atau kata sandi salah.'])->onlyInput('email');
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return redirect()->route('home');
    }
}
