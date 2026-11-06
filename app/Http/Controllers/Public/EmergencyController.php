<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\EmergencyContact;
use App\Models\EvacuationPoint;
use App\Models\RegionAlert;

class EmergencyController extends Controller
{
    public function index()
    {
        return view('public.darurat', [
            'contacts' => EmergencyContact::orderBy('name')->get(),
            'points' => EvacuationPoint::orderBy('name')->get(),
            'alerts' => RegionAlert::where(fn ($q) => $q->whereNull('valid_until')->orWhere('valid_until', '>', now()))->latest()->get(),
        ]);
    }
}
