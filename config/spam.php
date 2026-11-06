<?php

return [
    'rate_limit' => ['per_hour' => 5, 'per_day' => 15],
    'min_fill_seconds' => 3,
    'duplicate' => ['radius_m' => 100, 'window_minutes' => 120],
    'base_score' => 50,
    'weights' => [
        'captcha_ok' => 10, 'photo_valid' => 10, 'in_coverage' => 10, 'gps_good' => 5,
        'has_contact' => 5, 'similar_other_device' => 15, 'device_verified_history' => 10,
        'honeypot' => -60, 'too_fast' => -25, 'photo_duplicate' => -20, 'out_of_coverage' => -30,
        'gps_bad' => -10, 'url_in_text' => -15, 'device_spam_history' => -30, 'rate_exceeded' => -25,
    ],
    'thresholds' => ['tinggi' => 70, 'perlu_dicek' => 40],
    // Bounding box kasar Provinsi Lampung (validasi awal, bukan batas resmi)
    'bbox' => ['min_lat' => -6.2, 'max_lat' => -3.7, 'min_lng' => 103.6, 'max_lng' => 106.2],
];
