<?php

use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function mkUser(string $role): User
{
    $u = User::factory()->create();
    $u->forceFill(['role' => $role, 'is_active' => true])->save();

    return $u;
}

it('anonim tidak bisa membuka antrean admin', function () {
    $this->get('/admin/laporan')->assertRedirect('/masuk');
});

it('warga mendapat 403 di rute admin', function () {
    $this->actingAs(mkUser('warga'))->get('/admin/laporan')->assertForbidden();
});

it('admin bisa membuka antrean', function () {
    $this->actingAs(mkUser('admin'))->get('/admin/laporan')->assertOk();
});

it('peta publik hanya memuat laporan valid', function () {
    $base = ['category' => 'sampah', 'lat' => -5.4, 'lng' => 105.2, 'device_hash' => 'd', 'ip_hash' => 'i'];
    Report::create($base + ['tracking_code' => 'JL-2026-AAAAAA', 'verification' => 'belum']);
    Report::create($base + ['tracking_code' => 'JL-2026-BBBBBB', 'verification' => 'valid']);
    $this->getJson('/peta/data')->assertOk()->assertJsonCount(1)->assertJsonPath('0.code', 'JL-2026-BBBBBB');
});

it('warga masuk dengan NIK dan melihat riwayat laporannya saja', function () {
    $nik = '3271041234567890';
    $user = User::factory()->create([
        'role' => 'warga',
        'is_active' => true,
        'nik' => $nik,
        'nik_hash' => hash_hmac('sha256', $nik, (string) config('app.key')),
    ]);

    $this->post('/masuk', ['nik' => $nik])
        ->assertRedirect('/warga/dashboard');

    $base = [
        'category' => 'sampah',
        'lat' => -5.4,
        'lng' => 105.2,
        'device_hash' => 'd',
        'ip_hash' => 'i',
    ];

    $own = Report::create($base + [
        'tracking_code' => 'JL-2026-OWNOWN',
        'user_id' => $user->id,
    ]);
    Report::create($base + [
        'tracking_code' => 'JL-2026-ANONYM',
        'user_id' => null,
    ]);

    $this->get('/warga/dashboard')
        ->assertOk()
        ->assertSee($own->tracking_code)
        ->assertDontSee('JL-2026-ANONYM');
});

it('warga dapat membuka detail laporan miliknya dan tidak dapat membuka laporan orang lain', function () {
    $user = mkUser('warga');
    $other = mkUser('warga');
    $base = [
        'category' => 'jalan_rusak',
        'lat' => -5.4,
        'lng' => 105.2,
        'device_hash' => 'd',
        'ip_hash' => 'i',
    ];
    $own = Report::create($base + ['tracking_code' => 'JL-2026-DETAIL1', 'user_id' => $user->id]);
    $otherReport = Report::create($base + ['tracking_code' => 'JL-2026-DETAIL2', 'user_id' => $other->id]);

    $this->actingAs($user)
        ->get('/warga/laporan/'.$own->id)
        ->assertOk()
        ->assertSee($own->tracking_code);

    $this->actingAs($user)
        ->get('/warga/laporan/'.$otherReport->id)
        ->assertNotFound();
});
