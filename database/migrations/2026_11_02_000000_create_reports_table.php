<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $t) {
            $t->id();
            $t->string('tracking_code')->unique();
            $t->string('token_hash');
            $t->string('category')->index();
            $t->text('description')->nullable();
            $t->decimal('lat', 10, 7);
            $t->decimal('lng', 10, 7);
            $t->unsignedInteger('accuracy')->nullable();
            $t->string('status')->default('dilaporkan')->index();
            $t->string('verification')->default('belum')->index();
            $t->unsignedTinyInteger('trust_score')->default(0);
            $t->string('trust_label')->default('perlu_dicek');
            $t->string('priority')->default('normal')->index();
            $t->json('trust_reasons')->nullable();
            $t->text('contact')->nullable();
            $t->string('device_hash', 64)->index();
            $t->string('ip_hash', 64)->index();
            $t->string('photo_path')->nullable();
            $t->string('photo_sha256', 64)->nullable()->index();
            $t->string('idempotency_key', 64)->nullable()->unique();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
