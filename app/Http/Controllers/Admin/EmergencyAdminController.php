<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\EmergencyContact;
use App\Models\EvacuationPoint;
use App\Models\RegionAlert;
use Illuminate\Http\Request;

class EmergencyAdminController extends Controller
{
    public function index()
    {
        return view('admin.darurat');
    }

    public function contact(Request $r)
    {
        $d = $r->validate(['name' => 'required|max:120', 'number' => ['required', 'regex:/^[0-9+\- ]{3,20}$/'],
            'scope' => 'required|max:60', 'source_url' => 'nullable|url|max:255', 'verified_at' => 'nullable|date']);
        AuditLog::record('emergency.contact', EmergencyContact::create($d));

        return back()->with('ok', 'Kontak disimpan.');
    }

    public function evac(Request $r)
    {
        $d = $r->validate(['name' => 'required|max:120', 'address' => 'nullable|max:200', 'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180', 'capacity' => 'nullable|integer|min:0', 'source' => 'nullable|max:200']);
        AuditLog::record('emergency.evac', EvacuationPoint::create($d));

        return back()->with('ok', 'Titik evakuasi disimpan.');
    }

    public function alert(Request $r)
    {
        $d = $r->validate(['title' => 'required|max:150', 'body' => 'required|max:2000',
            'severity' => 'required|in:info,waspada,bahaya', 'valid_until' => 'nullable|date']);
        AuditLog::record('emergency.alert', RegionAlert::create($d + ['origin' => 'manual', 'created_by' => auth()->id()]));

        return back()->with('ok', 'Peringatan (manual) diterbitkan.');
    }
}
