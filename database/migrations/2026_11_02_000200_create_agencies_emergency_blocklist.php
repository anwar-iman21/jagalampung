<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agencies', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('type')->nullable();
            $t->json('categories')->nullable();
            $t->timestamps();
        });
        Schema::table('users', fn (Blueprint $t) => $t->unsignedBigInteger('agency_id')->nullable()->index());
        Schema::table('reports', fn (Blueprint $t) => $t->unsignedBigInteger('agency_id')->nullable()->index());

        Schema::create('emergency_contacts', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('number');
            $t->string('scope')->default('nasional');
            $t->string('source_url')->nullable();
            $t->date('verified_at')->nullable();
            $t->boolean('is_demo')->default(false);
            $t->timestamps();
        });
        Schema::create('evacuation_points', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('address')->nullable();
            $t->decimal('lat', 10, 7);
            $t->decimal('lng', 10, 7);
            $t->unsignedInteger('capacity')->nullable();
            $t->string('source')->nullable();
            $t->boolean('is_demo')->default(false);
            $t->timestamps();
        });
        Schema::create('region_alerts', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->text('body');
            $t->string('severity')->default('info'); // info|waspada|bahaya
            $t->string('origin')->default('manual'); // manual|feed
            $t->timestamp('valid_until')->nullable();
            $t->foreignId('created_by')->nullable();
            $t->timestamps();
        });
        Schema::create('blocklists', function (Blueprint $t) {
            $t->id();
            $t->string('type'); // device|ip
            $t->string('value_hash', 64)->index();
            $t->string('reason')->nullable();
            $t->timestamp('expires_at')->nullable();
            $t->foreignId('created_by')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['blocklists', 'region_alerts', 'evacuation_points', 'emergency_contacts'] as $x) {
            Schema::dropIfExists($x);
        }
        Schema::table('reports', fn (Blueprint $t) => $t->dropColumn('agency_id'));
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('agency_id'));
        Schema::dropIfExists('agencies');
    }
};
