<?php

use App\Services\Reports\StatisticsService as S;

it('median: ganjil, genap, kosong', function () {
    expect(S::median([3, 1, 2]))->toBe(2.0)        // urut 1,2,3 -> 2
        ->and(S::median([4, 1, 3, 2]))->toBe(2.5)  // urut 1,2,3,4 -> (2+3)/2
        ->and(S::median([]))->toBeNull();
});

it('mean dan persen menangani data kosong / pembagian nol', function () {
    expect(S::mean([10, 20]))->toBe(15.0)->and(S::mean([]))->toBeNull()
        ->and(S::percent(1, 4))->toBe(25.0)->and(S::percent(0, 0))->toBe(0.0);
});

it('menetralkan CSV injection', function (string $in, string $out) {
    expect(S::csvSafe($in))->toBe($out);
})->with([['=SUM(A1)', "'=SUM(A1)"], ['+1', "'+1"], ['-2', "'-2"], ['@x', "'@x"], ['aman', 'aman'], ['', '']]);
