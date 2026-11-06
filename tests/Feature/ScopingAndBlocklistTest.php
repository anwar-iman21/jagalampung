<?php

use App\Models\Agency;
use App\Models\Blocklist;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function mkAdmin(?int $agency, string $role = 'admin'): User
{
    $u = User::factory()->create();
    $u->forceFill(['role' => $role, 'is_active' => true, 'agency_id' => $agency])->save();

    return $u;
}

function mkReport(int $agency, string $code): Report
{
    return Report::create(['tracking_code' => $code, 'token_hash' => 'x', 'category' => 'sampah', 'lat' => -5.4, 'lng' => 105.2,
        'device_hash' => 'd', 'ip_hash' => 'i', 'agency_id' => $agency]);
}

it('admin instansi A tidak bisa membuka laporan instansi B (404), super admin bisa', function () {
    $a = Agency::create(['name' => 'A']);
    $b = Agency::create(['name' => 'B']);
    $rb = mkReport($b->id, 'JL-2026-BBBBBB');

    $this->actingAs(mkAdmin($a->id))->get("/admin/laporan/{$rb->id}")->assertNotFound();
    $this->actingAs(mkAdmin($b->id))->get("/admin/laporan/{$rb->id}")->assertOk();
    $this->actingAs(mkAdmin(null, 'super_admin'))->get("/admin/laporan/{$rb->id}")->assertOk();
});

it('admin bukan super admin tidak bisa membuka manajemen pengguna', function () {
    $this->actingAs(mkAdmin(Agency::create(['name' => 'A'])->id))->get('/admin/pengguna')->assertForbidden();
});

it('blocklist: aktif memblokir, kedaluwarsa tidak', function () {
    Blocklist::create(['type' => 'device', 'value_hash' => 'h1', 'expires_at' => now()->addDay()]);
    Blocklist::create(['type' => 'device', 'value_hash' => 'h2', 'expires_at' => now()->subMinute()]);
    expect(Blocklist::isBlocked('h1'))->toBeTrue()->and(Blocklist::isBlocked('h2'))->toBeFalse()->and(Blocklist::isBlocked('zzz'))->toBeFalse();
});

it('ekspor CSV tidak memuat kontak pelapor', function () {
    $a = Agency::create(['name' => 'A']);
    $r = mkReport($a->id, 'JL-2026-CCCCCC');
    $r->update(['contact' => 'rahasia-08123']);
    $res = $this->actingAs(mkAdmin($a->id))->get('/admin/statistik/csv')->assertOk();
    expect($res->streamedContent())->toContain('JL-2026-CCCCCC')->not->toContain('rahasia-08123');
});
