<?php

namespace App\Services\Reports;

use App\Models\Report;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsService
{
    public static function median(array $v): ?float
    {
        if (! $v) {
            return null;
        }
        sort($v);
        $n = count($v);
        $m = intdiv($n, 2);

        return $n % 2 ? (float) $v[$m] : ($v[$m - 1] + $v[$m]) / 2;
    }

    public static function mean(array $v): ?float
    {
        return $v ? array_sum($v) / count($v) : null;
    }

    /** Netralkan CSV injection: awalan = + - @ tab CR diberi apostrof. */
    public static function csvSafe(?string $v): string
    {
        $v = (string) $v;

        return preg_match('/^[=+\-@\t\r]/', $v) ? "'".$v : $v;
    }

    public static function percent(int $part, int $total): float
    {
        return $total === 0 ? 0.0 : round($part / $total * 100, 1);
    }

    public function build($user): array
    {
        $base = fn () => Report::visibleTo($user);
        $total = $base()->count();
        $spam = $base()->whereIn('verification', ['spam', 'tidak_valid'])->count();

        $minutes = DB::table('report_status_histories as h')->join('reports as r', 'r.id', '=', 'h.report_id')
            ->where('h.to_status', 'diproses')->whereIn('h.report_id', $base()->select('id'))
            ->get(['h.created_at as h', 'r.created_at as r'])
            ->map(fn ($x) => Carbon::parse($x->r)->diffInMinutes(Carbon::parse($x->h)))->all();

        $daily = $base()->where('created_at', '>=', now()->subDays(30))->pluck('created_at')
            ->groupBy(fn ($d) => $d->timezone('Asia/Jakarta')->format('Y-m-d'))->map->count()->sortKeys();

        return [
            'total' => $total,
            'per_category' => $base()->select('category', DB::raw('count(*) as n'))->groupBy('category')->pluck('n', 'category'),
            'per_status' => $base()->select('status', DB::raw('count(*) as n'))->groupBy('status')->pluck('n', 'status'),
            'spam_pct' => self::percent($spam, $total),
            'mean_min' => self::mean($minutes),
            'median_min' => self::median($minutes),
            'daily' => $daily,
        ];
    }
}
