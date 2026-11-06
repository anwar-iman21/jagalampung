<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Blocklist;
use App\Models\Report;
use Illuminate\Support\Str;
use App\Services\Reports\ReportTrustScorer;
use App\Services\Reports\TrackingCodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class ReportController extends Controller
{
    public const CATEGORIES = [
        'kecelakaan' => 'Kecelakaan / Darurat', 'jalan_rusak' => 'Jalan Rusak', 'bencana' => 'Bencana',
        'kriminalitas' => 'Kriminalitas', 'sampah' => 'Sampah', 'lainnya' => 'Lainnya',
    ];

    public function create(Request $r)
    {
        $r->session()->put('lapor_started', time());

        $res = response()->view('public.lapor', ['categories' => self::CATEGORIES, 'selected' => $r->query('kategori')]);

        return $r->cookie('jl_dev') ? $res : $res->cookie('jl_dev', Str::random(32), 60 * 24 * 365);
    }

    public function store(Request $r)
    {
        $cfg = config('spam');
        $data = $r->validate([
            'category' => ['required', 'in:'.implode(',', array_keys(self::CATEGORIES))],
            'description' => ['nullable', 'string', 'max:1000'],
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'accuracy' => ['nullable', 'integer', 'min:0'],
            'contact' => ['nullable', 'string', 'max:120'],
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'idem' => ['nullable', 'string', 'max:64'],
        ]);

        $salt = (string) config('app.key');
        $ipHash = hash('sha256', $r->ip().$salt);
        $deviceHash = hash('sha256', ($r->cookie('jl_dev') ?: $r->userAgent()).$salt);

        if (Blocklist::isBlocked($ipHash, $deviceHash)) {
            return back()->withErrors(['rate' => 'Perangkat Anda diblokir sementara. Dalam keadaan darurat hubungi 112.'])->withInput();
        }

        // Idempotensi: kirim ganda tidak membuat laporan ganda.
        if (! empty($data['idem']) && ($dup = Report::where('idempotency_key', $data['idem'])->first())) {
            return redirect()->route('lapor.done', $dup->tracking_code);
        }

        $key = 'lapor:'.$ipHash;
        $exceeded = RateLimiter::tooManyAttempts($key, $cfg['rate_limit']['per_hour']);
        if ($exceeded) {
            return back()->withErrors(['rate' => 'Terlalu banyak laporan. Jika mendesak, hubungi 112.'])->withInput();
        }
        RateLimiter::hit($key, 3600);

        $started = (int) $r->session()->get('lapor_started', 0);
        $b = $cfg['bbox'];
        $inBox = $data['lat'] >= $b['min_lat'] && $data['lat'] <= $b['max_lat']
            && $data['lng'] >= $b['min_lng'] && $data['lng'] <= $b['max_lng'];
        $acc = $data['accuracy'] ?? null;

        $result = (new ReportTrustScorer($cfg))->score([
            'photo_valid' => true,
            'in_coverage' => $inBox, 'out_of_coverage' => ! $inBox,
            'gps_good' => $acc !== null && $acc <= 50, 'gps_bad' => $acc !== null && $acc > 500,
            'has_contact' => ! empty($data['contact']),
            'honeypot' => filled($r->input('website')),
            'too_fast' => $started > 0 && (time() - $started) < $cfg['min_fill_seconds'],
            'device_spam_history' => Report::where('device_hash', $deviceHash)->where('verification', 'spam')->exists(),
            'device_verified_history' => Report::where('device_hash', $deviceHash)->where('verification', 'valid')->exists(),
            'url_in_text' => (bool) preg_match('~https?://|www\.~i', $data['description'] ?? ''),
        ], $data['category']);

        $file = $r->file('photo');
        $token = TrackingCodeGenerator::token();
        // Re-encode lewat GD akan membuang EXIF; fallback: simpan apa adanya (catat di docs).
        $path = $file->store('reports', 'public');

        $report = Report::create([
            'tracking_code' => TrackingCodeGenerator::code(),
            'token_hash' => Hash::make($token),
            'category' => $data['category'],
            'agency_id' => Agency::forCategory($data['category'])?->id,
            'description' => $data['description'] ?? null,
            'lat' => $data['lat'], 'lng' => $data['lng'], 'accuracy' => $acc,
            'trust_score' => $result['score'], 'trust_label' => $result['label'],
            'priority' => $result['priority'], 'trust_reasons' => $result['reasons'],
            'contact' => $data['contact'] ?? null,
            'device_hash' => $deviceHash, 'ip_hash' => $ipHash,
            'photo_path' => $path, 'photo_sha256' => hash_file('sha256', $file->getRealPath()),
            'idempotency_key' => $data['idem'] ?: null,
        ]);

        return redirect()->route('lapor.done', $report->tracking_code)->with('token', $token);
    }

    public function done(Request $r, string $code)
    {
        $report = Report::where('tracking_code', $code)->firstOrFail();

        return view('public.selesai', ['report' => $report, 'token' => session('token')]);
    }

    public function trackForm()
    {
        return view('public.lacak', ['report' => null]);
    }

    public function track(Request $r)
    {
        $in = $r->validate(['code' => 'required|string|max:30', 'token' => 'required|string|max:40']);
        $report = Report::where('tracking_code', strtoupper(trim($in['code'])))->first();
        // Pesan sama untuk kode salah maupun token salah (tidak membocorkan keberadaan).
        if (! $report || ! Hash::check($in['token'], $report->token_hash)) {
            return back()->withErrors(['code' => 'Kode atau token tidak cocok.'])->withInput();
        }

        return view('public.lacak', compact('report'));
    }
}
