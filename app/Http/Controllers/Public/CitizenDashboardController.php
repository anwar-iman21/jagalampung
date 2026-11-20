<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CitizenDashboardController extends Controller
{
    public function index(Request $request)
    {
        $reports = Report::where('user_id', $request->user()->id)
            ->latest()->paginate(10);

        $counts = [
            'total' => Report::where('user_id', $request->user()->id)->count(),
            'diproses' => Report::where('user_id', $request->user()->id)->where('status', 'diproses')->count(),
            'selesai' => Report::where('user_id', $request->user()->id)->where('status', 'selesai')->count(),
        ];

        return view('public.dashboard-warga', compact('reports', 'counts'));
    }

    public function show(Request $request, Report $report)
    {
        abort_unless($report->user_id === $request->user()->id, 404);

        return view('public.detail-laporan-warga', [
            'report' => $report,
            'history' => DB::table('report_status_histories')
                ->where('report_id', $report->id)
                ->orderBy('id')
                ->get(),
        ]);
    }
}
