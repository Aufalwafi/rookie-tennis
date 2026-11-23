<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function groups()
    {
        return $this->belongsToMany(TournamentGroup::class, 'group_participant');
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->partner_name) {
            return "{$this->name} & {$this->partner_name}";
        }
        return $this->name;
    }
}
