<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $names = [
        'Dinas Pekerjaan Umum',
        'BPBD',
        'Dinas Lingkungan Hidup',
        'Kepolisian',
        'Layanan Darurat Terpadu',
    ];

    public function up(): void
    {
        foreach ($this->names as $name) {
            DB::table('agencies')->where('name', $name.' (Demo)')->update(['name' => $name]);
        }
    }

    public function down(): void
    {
        foreach ($this->names as $name) {
            DB::table('agencies')->where('name', $name)->update(['name' => $name.' (Demo)']);
        }
    }
};
