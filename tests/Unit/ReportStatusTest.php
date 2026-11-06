<?php

use App\Enums\ReportStatus as S;

it('hanya mengizinkan transisi yang sah', function (S $from, S $to, bool $ok) {
    expect($from->canTransitionTo($to))->toBe($ok);
})->with([
    [S::Dilaporkan, S::Diproses, true], [S::Dilaporkan, S::Ditolak, true], [S::Dilaporkan, S::Selesai, false],
    [S::Diproses, S::Selesai, true], [S::Diproses, S::Ditolak, true], [S::Diproses, S::Dilaporkan, false],
    [S::Selesai, S::Diproses, false], [S::Ditolak, S::Diproses, false], [S::Selesai, S::Selesai, false],
]);
