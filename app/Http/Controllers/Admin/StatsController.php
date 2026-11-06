<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Services\Reports\StatisticsService as S;

class StatsController extends Controller
{
    public function index()
    {
        return view('admin.statistik', ['s' => (new S)->build(auth()->user())]);
    }

    /** Ekspor CSV tanpa kontak pelapor; hanya data yang boleh dilihat peran terkait. */
    public function csv()
    {
        $rows = Report::visibleTo(auth()->user())->orderBy('id')->cursor();

        return response()->streamDownload(function () use ($rows) {
            $o = fopen('php://output', 'w');
            fputcsv($o, ['kode', 'kategori', 'status', 'verifikasi', 'prioritas', 'skor', 'waktu_wib']);
            foreach ($rows as $r) {
                fputcsv($o, array_map([S::class, 'csvSafe'], [$r->tracking_code, $r->category, $r->status->value,
                    $r->verification, $r->priority, (string) $r->trust_score, $r->created_at->timezone('Asia/Jakarta')->format('Y-m-d H:i')]));
            }
            fclose($o);
        }, 'rekap-laporan.csv', ['Content-Type' => 'text/csv']);
    }
}
