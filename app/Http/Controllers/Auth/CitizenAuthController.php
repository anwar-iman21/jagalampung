<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CitizenAuthController extends Controller
{
    public function registerForm(Request $request)
    {
        $encryptedNik = $request->session()->getOldInput('nik_encrypted');

        return view('auth.register', [
            'oldNik' => $encryptedNik ? Crypt::decryptString($encryptedNik) : '',
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'nik' => ['required', 'digits:16', function ($attribute, $value, $fail) {
                $hash = hash_hmac('sha256', (string) $value, (string) config('app.key'));
                if (User::where('nik_hash', $hash)->exists()) {
                    $fail('NIK sudah digunakan. Gunakan akun Anda atau periksa kembali NIK.');
                }
            }],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 150 karakter.',
            'email.unique' => 'Email sudah digunakan.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari 16 angka.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.mixed' => 'Kata sandi harus memuat huruf besar dan kecil.',
            'password.numbers' => 'Kata sandi harus memuat angka.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);
        if ($validator->fails()) {
            $oldInput = $request->except(['nik', 'password', 'password_confirmation']);
            if ($request->filled('nik')) {
                $oldInput['nik_encrypted'] = Crypt::encryptString((string) $request->input('nik'));
            }

            return back()->withErrors($validator)
                ->withInput($oldInput);
        }
        $data = $validator->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'nik' => $data['nik'],
            'nik_hash' => hash_hmac('sha256', $data['nik'], (string) config('app.key')),
            'password' => Hash::make($data['password']),
        ]);
        $user->forceFill(['role' => 'warga', 'is_active' => true])->save();

        event(new Registered($user));
        Auth::login($user);
        AuditLog::record('citizen.register');

        return redirect()->route('warga.dashboard');
    }

}
