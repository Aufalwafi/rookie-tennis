<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'courts' => 'array',
        'has_third_place' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function groups()
    {
        return $this->hasMany(TournamentGroup::class);
    }

    public function matches()
    {
        return $this->hasMany(TennisMatch::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'mens_singles' => "Tunggal Putra (Men's Singles)",
            'womens_singles' => "Tunggal Putri (Women's Singles)",
            'mens_doubles' => "Ganda Putra (Men's Doubles)",
            'womens_doubles' => "Ganda Putri (Women's Doubles)",
            'mixed_doubles' => "Ganda Campuran (Mixed Doubles)",
            default => $this->category,
        };
    }

    public function getFormatLabelAttribute(): string
    {
        return match ($this->format) {
            'group_knockout' => 'Fase Grup + Knockout',
            'round_robin' => 'Round Robin Murni',
            default => 'Sistem Gugur (Knockout)',
        };
    }

    public function getSurfaceBadgeAttribute(): array
    {
        return match ($this->surface) {
            'Clay' => ['label' => 'Tanah Liat (Clay)', 'class' => 'bg-amber-950/80 text-amber-300 border-amber-600/40'],
            'Grass' => ['label' => 'Rumput (Grass)', 'class' => 'bg-emerald-950/80 text-emerald-300 border-emerald-600/40'],
            'Carpet' => ['label' => 'Karpet (Carpet)', 'class' => 'bg-purple-950/80 text-purple-300 border-purple-600/40'],
            default => ['label' => 'Lapangan Keras (Hard)', 'class' => 'bg-blue-950/80 text-blue-300 border-blue-600/40'],
        };
    }
}
