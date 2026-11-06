<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\EmergencyContact;
use Illuminate\Database\Seeder;

class JagaLampungSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['Dinas Pekerjaan Umum', 'dinas', ['jalan_rusak']],
            ['BPBD', 'bpbd', ['bencana']],
            ['Dinas Lingkungan Hidup', 'dinas', ['sampah']],
            ['Kepolisian', 'polisi', ['kriminalitas']],
            ['Layanan Darurat Terpadu', 'darurat', ['kecelakaan', 'lainnya']],
        ] as [$n, $t, $c]) {
            Agency::firstOrCreate(['name' => $n], ['type' => $t, 'categories' => $c]);
        }
        // 112 = nomor layanan darurat nasional. Sumber & tanggal verifikasi belum diisi: tampil "perlu verifikasi".
        EmergencyContact::firstOrCreate(['number' => '112'], ['name' => 'Layanan Darurat Nasional', 'scope' => 'nasional']);

        $this->call(AdminSeeder::class);
    }
}
