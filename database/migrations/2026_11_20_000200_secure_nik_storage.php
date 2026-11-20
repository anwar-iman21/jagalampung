<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['nik']);
            $table->dropColumn('nik');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->text('nik')->nullable();
            $table->string('nik_hash', 64)->nullable()->unique();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['nik_hash']);
            $table->dropColumn('nik_hash');
        });

        DB::table('users')->whereNotNull('nik')->orderBy('id')->chunkById(100, function ($users) {
            foreach ($users as $user) {
                DB::table('users')->where('id', $user->id)->update([
                    'nik' => Crypt::decryptString($user->nik),
                ]);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 16)->nullable()->change();
            $table->unique('nik');
        });
    }
};
