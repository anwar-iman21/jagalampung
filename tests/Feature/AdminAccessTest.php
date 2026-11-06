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
    $base = ['token_hash' => 'x', 'category' => 'sampah', 'lat' => -5.4, 'lng' => 105.2, 'device_hash' => 'd', 'ip_hash' => 'i'];
    Report::create($base + ['tracking_code' => 'JL-2026-AAAAAA', 'verification' => 'belum']);
    Report::create($base + ['tracking_code' => 'JL-2026-BBBBBB', 'verification' => 'valid']);
    $this->getJson('/peta/data')->assertOk()->assertJsonCount(1)->assertJsonPath('0.code', 'JL-2026-BBBBBB');
});
