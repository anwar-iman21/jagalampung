<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReportStatus;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Blocklist;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportAdminController extends Controller
{
    public function index(Request $r)
    {
        $q = Report::visibleTo(auth()->user())
            ->when($r->filled('status'), fn ($x) => $x->where('status', $r->status))
            ->when($r->filled('verification'), fn ($x) => $x->where('verification', $r->verification))
            ->when($r->filled('category'), fn ($x) => $x->where('category', $r->category))
            ->when($r->filled('q'), fn ($x) => $x->where('tracking_code', 'like', '%'.$r->q.'%'))
            // prioritas tinggi dulu, lalu terbaru; id sebagai tie-breaker agar paginasi stabil
            ->orderByRaw("CASE priority WHEN 'tinggi' THEN 0 WHEN 'normal' THEN 1 ELSE 2 END")
            ->orderByDesc('created_at')->orderByDesc('id');

        $counts = [
            'baru' => Report::visibleTo(auth()->user())->where('verification', 'belum')->count(),
            'diproses' => Report::visibleTo(auth()->user())->where('status', 'diproses')->count(),
            'selesai' => Report::visibleTo(auth()->user())->where('status', 'selesai')->count(),
        ];

        return view('admin.reports', ['reports' => $q->paginate(15)->withQueryString(), 'counts' => $counts]);
    }

    private function authorizeReport(Report $report): void
    {
        abort_unless(Report::visibleTo(auth()->user())->whereKey($report->id)->exists(), 404);
    }

    public function show(Report $report)
    {
        $this->authorizeReport($report);
        return view('admin.report', [
            'report' => $report,
            'history' => DB::table('report_status_histories')->where('report_id', $report->id)->orderBy('id')->get(),
        ]);
    }

    public function update(Request $r, Report $report)
    {
        $this->authorizeReport($report);
        $d = $r->validate([
            'action' => 'required|in:valid,tidak_valid,spam,diproses,selesai',
            'note' => 'nullable|string|max:1000',
            'public_response' => 'nullable|string|max:1000',
            'block_device' => 'nullable|boolean',
        ]);
        if (in_array($d['action'], ['tidak_valid', 'spam'], true) && blank($d['note'] ?? null)) {
            return back()->withErrors(['note' => 'Alasan wajib diisi untuk Tidak valid / Spam.']);
        }

        try {
            DB::transaction(function () use ($report, $d) {
                $locked = Report::whereKey($report->id)->lockForUpdate()->firstOrFail();
                $from = $locked->status;
                $to = match ($d['action']) {
                    'diproses' => ReportStatus::Diproses,
                    'selesai' => ReportStatus::Selesai,
                    'tidak_valid', 'spam' => ReportStatus::Ditolak,
                    default => null, // 'valid' hanya mengubah verifikasi
                };
                if ($to) {
                    $locked->transitionTo($to); // melempar DomainException bila tidak sah
                }
                $verification = match ($d['action']) {
                    'valid' => 'valid', 'tidak_valid' => 'tidak_valid', 'spam' => 'spam', default => $locked->verification,
                };
                $locked->update(array_filter([
                    'verification' => $verification,
                    'verification_note' => $d['note'] ?? null,
                    'public_response' => $d['public_response'] ?? null,
                ], fn ($v) => $v !== null));

                if ($to) {
                    DB::table('report_status_histories')->insert([
                        'report_id' => $locked->id, 'from_status' => $from->value, 'to_status' => $to->value,
                        'user_id' => auth()->id(), 'note' => $d['note'] ?? null, 'created_at' => now(), 'updated_at' => now(),
                    ]);
                }
                if ($d['action'] === 'spam' && ! empty($d['block_device'])) {
                    Blocklist::create(['type' => 'device', 'value_hash' => $locked->device_hash, 'reason' => 'Spam: '.$locked->tracking_code,
                        'expires_at' => now()->addDays(7), 'created_by' => auth()->id()]);
                    AuditLog::record('blocklist.add', $locked, ['days' => 7]);
                }
                AuditLog::record('report.'.$d['action'], $locked, ['note' => $d['note'] ?? null]);
            });
        } catch (\DomainException $e) {
            return back()->withErrors(['action' => $e->getMessage()]);
        }

        return redirect()->route('admin.report', $report)->with('ok', 'Tersimpan.');
    }
}
