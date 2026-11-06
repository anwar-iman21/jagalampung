<?php

namespace App\Services\Reports;

class DuplicateDetector
{
    public static function haversineMeters(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $r = 6371000.0;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        return 2 * $r * asin(min(1, sqrt($a)));
    }

    /** Serupa jika kategori sama, jarak <= radius, dan selisih waktu <= jendela (batas inklusif). */
    public function isSimilar(array $a, array $b, float $radiusM, int $windowMinutes): bool
    {
        if ($a['category'] !== $b['category']) {
            return false;
        }
        if (abs($a['time'] - $b['time']) > $windowMinutes * 60) {
            return false;
        }

        return self::haversineMeters($a['lat'], $a['lng'], $b['lat'], $b['lng']) <= $radiusM;
    }
}
