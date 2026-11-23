<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TennisMatch extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'sets' => 'array',
        'live_score' => 'array',
        'match_date' => 'date',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function group()
    {
        return $this->belongsTo(TournamentGroup::class, 'tournament_group_id');
    }

    public function player1()
    {
        return $this->belongsTo(Participant::class, 'player1_id');
    }

    public function player2()
    {
        return $this->belongsTo(Participant::class, 'player2_id');
    }

    public function winner()
    {
        return $this->belongsTo(Participant::class, 'winner_id');
    }

    public function nextMatch()
    {
        return $this->belongsTo(TennisMatch::class, 'next_match_id');
    }

    public function getFormattedScoreAttribute(): string
    {
        if ($this->status === 'walkover') {
            return 'W.O';
        }

        if (empty($this->sets)) {
            return '-';
        }

        $scores = [];
        foreach ($this->sets as $set) {
            $p1 = $set['p1Games'] ?? $set['p1'] ?? 0;
            $p2 = $set['p2Games'] ?? $set['p2'] ?? 0;
            $s = "{$p1}-{$p2}";
            if (isset($set['tiebreakP1']) && isset($set['tiebreakP2'])) {
                $loserTb = min($set['tiebreakP1'], $set['tiebreakP2']);
                $s .= "({$loserTb})";
            }
            $scores[] = $s;
        }

        return implode(', ', $scores);
    }

    public function getStageLabelAttribute(): string
    {
        return match ($this->stage) {
            'group' => $this->group ? $this->group->name : 'Fase Grup',
            'quarterfinal' => 'Perempat Final',
            'semifinal' => 'Semifinal',
            'final' => 'Grand Final',
            'third_place' => 'Perebutan Juara 3',
            default => ucfirst(str_replace('_', ' ', $this->stage)),
        };
    }
}
