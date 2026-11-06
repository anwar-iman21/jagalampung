<?php

namespace App\Models;

use App\Enums\ReportStatus;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => ReportStatus::class,
            'trust_reasons' => 'array',
            'contact' => 'encrypted',
        ];
    }

    /** Super Admin: semua. Admin Instansi: hanya laporan instansinya (tanpa instansi = tidak melihat apa pun). */
    public function scopeVisibleTo($q, $user)
    {
        if ($user->role === 'super_admin') {
            return $q;
        }

        return $user->agency_id ? $q->where('agency_id', $user->agency_id) : $q->whereRaw('1 = 0');
    }

    public function transitionTo(ReportStatus $to): void
    {
        if (! $this->status->canTransitionTo($to)) {
            throw new \DomainException("Transisi {$this->status->value} -> {$to->value} tidak diizinkan.");
        }
        $this->update(['status' => $to]);
    }
}
