<?php

namespace App\Enums;

enum ReportStatus: string
{
    case Dilaporkan = 'dilaporkan';
    case Diproses = 'diproses';
    case Selesai = 'selesai';
    case Ditolak = 'ditolak';

    /** Matriks transisi yang diizinkan (state machine). */
    public function allowedNext(): array
    {
        return match ($this) {
            self::Dilaporkan => [self::Diproses, self::Ditolak],
            self::Diproses => [self::Selesai, self::Ditolak],
            self::Selesai, self::Ditolak => [],
        };
    }

    public function canTransitionTo(self $to): bool
    {
        return in_array($to, $this->allowedNext(), true);
    }

    public function label(): string
    {
        return match ($this) {
            self::Dilaporkan => 'Dilaporkan', self::Diproses => 'Diproses',
            self::Selesai => 'Selesai', self::Ditolak => 'Laporan tidak dapat diproses',
        };
    }
}
