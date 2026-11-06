<?php

namespace App\Http\Controllers\Public;

use App\Enums\ReportStatus;
use App\Http\Controllers\Controller;
use App\Models\Report;

class HomeController extends Controller
{
    public function index()
    {
        // Angka nyata dari DB; nol yang jujur bila belum ada data.
        $verified = Report::where('verification', 'valid');
        $stats = [
            'total' => (clone $verified)->count(),
            'selesai' => (clone $verified)->where('status', ReportStatus::Selesai->value)->count(),
        ];

        return view('public.home', compact('stats'));
    }
}
