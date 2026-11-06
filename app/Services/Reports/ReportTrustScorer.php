<?php

namespace App\Services\Reports;

/** Service murni: tanpa HTTP/DB, mudah diuji. */
class ReportTrustScorer
{
    public const EMERGENCY = ['kecelakaan', 'bencana'];

    public function __construct(private array $config) {}

    /**
     * @param  array<string,bool>  $signals  nama sinyal => aktif?
     * @return array{score:int,label:string,priority:string,reasons:array<string,int>}
     */
    public function score(array $signals, string $category): array
    {
        $weights = $this->config['weights'];
        $score = $this->config['base_score'];
        $reasons = [];
        foreach ($signals as $name => $on) {
            if ($on && isset($weights[$name])) {
                $reasons[$name] = $weights[$name];
                $score += $weights[$name];
            }
        }
        $score = max(0, min(100, $score));
        $t = $this->config['thresholds'];
        $label = $score >= $t['tinggi'] ? 'tinggi' : ($score >= $t['perlu_dicek'] ? 'perlu_dicek' : 'berisiko_spam');
        // Laporan darurat tidak boleh terhambat skor rendah.
        $priority = in_array($category, self::EMERGENCY, true) ? 'tinggi' : ($label === 'tinggi' ? 'normal' : 'rendah');

        return compact('score', 'label', 'priority', 'reasons');
    }
}
