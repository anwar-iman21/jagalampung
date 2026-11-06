<?php

namespace App\Services\Reports;

class TrackingCodeGenerator
{
    private const ALPHABET = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

    public static function code(?int $year = null): string
    {
        $s = '';
        for ($i = 0; $i < 6; $i++) {
            $s .= self::ALPHABET[random_int(0, strlen(self::ALPHABET) - 1)];
        }

        return 'JL-'.($year ?? date('Y')).'-'.$s;
    }

    public static function token(): string
    {
        return bin2hex(random_bytes(8));
    }
}
