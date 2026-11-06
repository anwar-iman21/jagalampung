<?php

use App\Services\Reports\DuplicateDetector;

it('haversine: 1 derajat lintang ~ 111.195 m', function () {
    // R=6371000, 1 derajat = 6371000 * pi/180 = 111194.93 m
    expect(DuplicateDetector::haversineMeters(0, 100, 1, 100))->toEqualWithDelta(111194.93, 1.0);
    expect(DuplicateDetector::haversineMeters(-5.4, 105.2, -5.4, 105.2))->toBe(0.0);
});

it('batas radius dan waktu bersifat inklusif', function () {
    $d = new DuplicateDetector();
    $base = ['category' => 'jalan_rusak', 'lat' => 0.0, 'lng' => 100.0, 'time' => 0];
    // 0.0009 derajat lintang ~ 100.07 m (0.0009 * 111194.93)
    $near = ['category' => 'jalan_rusak', 'lat' => 0.0008, 'lng' => 100.0, 'time' => 60]; // ~88.96 m
    $far = ['category' => 'jalan_rusak', 'lat' => 0.0009, 'lng' => 100.0, 'time' => 60];  // ~100.08 m
    expect($d->isSimilar($base, $near, 100, 120))->toBeTrue();
    expect($d->isSimilar($base, $far, 100, 120))->toBeFalse();
    expect($d->isSimilar($base, ['time' => 7200] + $near, 100, 120))->toBeTrue();   // tepat 120 menit
    expect($d->isSimilar($base, ['time' => 7201] + $near, 100, 120))->toBeFalse();  // lewat 1 detik
    expect($d->isSimilar($base, ['category' => 'sampah'] + $near, 100, 120))->toBeFalse();
});
