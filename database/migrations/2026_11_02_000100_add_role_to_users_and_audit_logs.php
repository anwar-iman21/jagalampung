<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->string('role')->default('warga')->index();
            $t->boolean('is_active')->default(true);
        });
        Schema::table('reports', function (Blueprint $t) {
            $t->text('verification_note')->nullable();
            $t->text('public_response')->nullable();
        });
        Schema::create('audit_logs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->index();
            $t->string('action')->index();
            $t->string('entity')->nullable();
            $t->unsignedBigInteger('entity_id')->nullable();
            $t->json('meta')->nullable();
            $t->string('ip_hash', 64)->nullable();
            $t->timestamps();
        });
        Schema::create('report_status_histories', function (Blueprint $t) {
            $t->id();
            $t->foreignId('report_id')->constrained()->cascadeOnDelete();
            $t->string('from_status')->nullable();
            $t->string('to_status');
            $t->foreignId('user_id')->nullable();
            $t->text('note')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_status_histories');
        Schema::dropIfExists('audit_logs');
        Schema::table('reports', fn (Blueprint $t) => $t->dropColumn(['verification_note', 'public_response']));
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn(['role', 'is_active']));
    }
};
