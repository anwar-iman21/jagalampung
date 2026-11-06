<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /** Baca ADMIN_EMAIL & ADMIN_PASSWORD dari .env. Tidak ada password hardcode. */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $pass = env('ADMIN_PASSWORD');
        if (! $email || ! $pass) {
            $this->command?->warn('ADMIN_EMAIL / ADMIN_PASSWORD belum diisi di .env — Super Admin tidak dibuat.');

            return;
        }
        $u = User::firstOrNew(['email' => $email]);
        $u->name = $u->name ?: 'Super Admin';
        $u->password = Hash::make($pass);
        $u->forceFill(['role' => 'super_admin', 'is_active' => true])->save();
    }
}
