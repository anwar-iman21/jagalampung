<?php

use App\Services\Reports\ReportTrustScorer;

// Konfigurasi uji independen (bukan menyalin config/spam.php)
$cfg = ['base_score' => 50, 'weights' => ['a' => 10, 'b' => 20, 'bad' => -30, 'huge' => 200, 'neg' => -200],
    'thresholds' => ['tinggi' => 70, 'perlu_dicek' => 40]];

it('menghitung skor dan label di batas ambang', function (array $sig, int $score, string $label) use ($cfg) {
    $r = (new ReportTrustScorer($cfg))->score($sig, 'sampah');
    expect($r['score'])->toBe($score)->and($r['label'])->toBe($label);
})->with([
    // 50 + 10 + 20 = 80 -> tinggi
    [['a' => true, 'b' => true], 80, 'tinggi'],
    // 50 + 20 = 70 -> tepat di ambang tinggi
    [['b' => true], 70, 'tinggi'],
    // 50 + 10 = 60 -> perlu_dicek
    [['a' => true], 60, 'perlu_dicek'],
    // 50 - 30 = 20 -> berisiko
    [['bad' => true], 20, 'berisiko_spam'],
    // 50 + 10 - 30 = 30 -> berisiko
    [['a' => true, 'bad' => true], 30, 'berisiko_spam'],
]);

it('membatasi skor 0-100', function () use ($cfg) {
    $s = new ReportTrustScorer($cfg);
    expect($s->score(['huge' => true], 'sampah')['score'])->toBe(100)   // 250 -> 100
        ->and($s->score(['neg' => true], 'sampah')['score'])->toBe(0);   // -150 -> 0
});

it('menyimpan alasan sesuai kontribusi', function () use ($cfg) {
    $r = (new ReportTrustScorer($cfg))->score(['a' => true, 'bad' => true, 'b' => false], 'sampah');
    expect($r['reasons'])->toBe(['a' => 10, 'bad' => -30]);
});

it('kategori darurat tetap prioritas tinggi walau skor rendah', function () use ($cfg) {
    $r = (new ReportTrustScorer($cfg))->score(['bad' => true], 'kecelakaan');
    expect($r['score'])->toBe(20)->and($r['priority'])->toBe('tinggi');
});
