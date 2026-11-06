<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Report;

class MapController extends Controller
{
    public function index()
    {
        return view('public.peta');
    }

    /** Hanya laporan terverifikasi valid; koordinat diperhalus (~110 m), tanpa kontak/foto. */
    public function data()
    {
        return Report::where('verification', 'valid')->latest()->limit(500)->get()->map(fn ($r) => [
            'code' => $r->tracking_code, 'category' => $r->category, 'status' => $r->status->label(),
            'lat' => round((float) $r->lat, 3), 'lng' => round((float) $r->lng, 3),
            'time' => $r->created_at->timezone('Asia/Jakarta')->format('d M Y H:i').' WIB',
        ]);
    }
}
