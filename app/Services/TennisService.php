<?php

namespace App\Services;

use App\Models\Tournament;
use App\Models\TournamentGroup;
use App\Models\Participant;
use App\Models\TennisMatch;
use Carbon\Carbon;

class TennisService
{
    /**
     * Calculate standings for a specific group according to tennis rules
     */
    public function calculateGroupStandings(TournamentGroup $group): array
    {
        $tournament = $group->tournament;
        $participants = $group->participants;
        $matches = TennisMatch::where('tournament_group_id', $group->id)
            ->whereIn('status', ['completed', 'walkover'])
            ->get();

        $standings = [];

        foreach ($participants as $p) {
            $standings[$p->id] = [
                'participant' => $p,
                'played' => 0,
                'won' => 0,
                'lost' => 0,
                'sets_won' => 0,
                'sets_lost' => 0,
                'sets_diff' => 0,
                'games_won' => 0,
                'games_lost' => 0,
                'games_diff' => 0,
                'points' => 0,
                'rank' => 1,
                'is_qualified' => false,
            ];
        }

        foreach ($matches as $match) {
            if (!$match->player1_id || !$match->player2_id || !$match->winner_id) {
                continue;
            }

            if (!isset($standings[$match->player1_id]) || !isset($standings[$match->player2_id])) {
                continue;
            }

            $standings[$match->player1_id]['played']++;
            $standings[$match->player2_id]['played']++;

            if ($match->winner_id == $match->player1_id) {
                $standings[$match->player1_id]['won']++;
                $standings[$match->player1_id]['points'] += $tournament->points_win;
                $standings[$match->player2_id]['lost']++;
                $standings[$match->player2_id]['points'] += $tournament->points_loss;
            } else {
                $standings[$match->player2_id]['won']++;
                $standings[$match->player2_id]['points'] += $tournament->points_win;
                $standings[$match->player1_id]['lost']++;
                $standings[$match->player1_id]['points'] += $tournament->points_loss;
            }

            if (!empty($match->sets)) {
                foreach ($match->sets as $set) {
                    $p1G = (int)($set['p1Games'] ?? $set['p1'] ?? 0);
                    $p2G = (int)($set['p2Games'] ?? $set['p2'] ?? 0);

                    $standings[$match->player1_id]['games_won'] += $p1G;
                    $standings[$match->player1_id]['games_lost'] += $p2G;
                    $standings[$match->player2_id]['games_won'] += $p2G;
                    $standings[$match->player2_id]['games_lost'] += $p1G;

                    if ($p1G > $p2G) {
                        $standings[$match->player1_id]['sets_won']++;
                        $standings[$match->player2_id]['sets_lost']++;
                    } elseif ($p2G > $p1G) {
                        $standings[$match->player2_id]['sets_won']++;
                        $standings[$match->player1_id]['sets_lost']++;
                    }
                }
            }
        }

        // Compute differences
        foreach ($standings as $id => &$s) {
            $s['sets_diff'] = $s['sets_won'] - $s['sets_lost'];
            $s['games_diff'] = $s['games_won'] - $s['games_lost'];
        }
        unset($s);

        // Sort by Points -> Head to Head -> Sets Diff -> Games Diff -> Games Won
        uasort($standings, function ($a, $b) use ($matches) {
            if ($b['points'] !== $a['points']) {
                return $b['points'] <=> $a['points'];
            }

            // Head to head
            $h2h = $matches->first(function ($m) use ($a, $b) {
                return ($m->player1_id == $a['participant']->id && $m->player2_id == $b['participant']->id) ||
                       ($m->player1_id == $b['participant']->id && $m->player2_id == $a['participant']->id);
            });
            if ($h2h && $h2h->winner_id) {
                if ($h2h->winner_id == $a['participant']->id) return -1;
                if ($h2h->winner_id == $b['participant']->id) return 1;
            }

            if ($b['sets_diff'] !== $a['sets_diff']) {
                return $b['sets_diff'] <=> $a['sets_diff'];
            }

            if ($b['games_diff'] !== $a['games_diff']) {
                return $b['games_diff'] <=> $a['games_diff'];
            }

            return $b['games_won'] <=> $a['games_won'];
        });

        // Assign ranks & qualification
        $rank = 1;
        $advance = $tournament->advance_per_group ?? 2;
        $result = [];
        foreach ($standings as $item) {
            $item['rank'] = $rank;
            $item['is_qualified'] = ($rank <= $advance);
            $result[] = $item;
            $rank++;
        }

        return $result;
    }

    /**
     * Automatic Snake Draw of seeded and unseeded players into groups
     */
    public function autoDrawGroups(Tournament $tournament): void
    {
        $participants = $tournament->participants;
        if ($participants->count() < 2) {
            return;
        }

        // Delete existing groups and group matches
        $tournament->groups()->delete();
        $tournament->matches()->where('stage', 'group')->delete();

        $groupsCount = $tournament->groups_count ?: 2;
        $groupNames = ['Grup A', 'Grup B', 'Grup C', 'Grup D', 'Grup E', 'Grup F', 'Grup G', 'Grup H'];

        $createdGroups = [];
        for ($i = 0; $i < $groupsCount; $i++) {
            $createdGroups[] = $tournament->groups()->create([
                'name' => $groupNames[$i] ?? ("Grup " . ($i + 1)),
            ]);
        }

        // Separate seeded & unseeded
        $seeded = $participants->whereNotNull('seed')->sortBy('seed')->values();
        $unseeded = $participants->whereNull('seed')->shuffle()->values();

        // Distribute seeded players
        foreach ($seeded as $idx => $player) {
            $gIndex = $idx % $groupsCount;
            $createdGroups[$gIndex]->participants()->attach($player->id);
        }

        // Distribute unseeded players
        foreach ($unseeded as $idx => $player) {
            $gIndex = ($idx + $seeded->count()) % $groupsCount;
            $createdGroups[$gIndex]->participants()->attach($player->id);
        }
    }

    /**
     * Generate round robin group matches
     */
    public function generateGroupMatches(Tournament $tournament): void
    {
        $tournament->matches()->where('stage', 'group')->delete();

        $courts = !empty($tournament->courts) ? $tournament->courts : ['Lapangan 1', 'Lapangan 2'];
        $startDate = $tournament->start_date ? Carbon::parse($tournament->start_date) : Carbon::today();

        $matchCounter = 1;

        foreach ($tournament->groups as $group) {
            $pIds = $group->participants->pluck('id')->all();
            $n = count($pIds);

            for ($i = 0; $i < $n; $i++) {
                for ($j = $i + 1; $j < $n; $j++) {
                    $court = $courts[($matchCounter - 1) % count($courts)];
                    $dayOffset = (int)floor(($matchCounter - 1) / 6);
                    $matchDate = (clone $startDate)->addDays($dayOffset)->toDateString();
                    $baseHour = 8 + (($matchCounter - 1) % 4) * 2;
                    $matchTime = sprintf('%02d:00', $baseHour);

                    TennisMatch::create([
                        'tournament_id' => $tournament->id,
                        'tournament_group_id' => $group->id,
                        'stage' => 'group',
                        'round' => 1,
                        'order' => $matchCounter++,
                        'player1_id' => $pIds[$i],
                        'player2_id' => $pIds[$j],
                        'court' => $court,
                        'match_date' => $matchDate,
                        'match_time' => $matchTime,
                        'status' => 'scheduled',
                        'sets' => [],
                    ]);
                }
            }
        }
    }

    /**
     * Generate Direct Knockout Bracket (8, 16 players)
     */
    public function generateDirectKnockout(Tournament $tournament): void
    {
        $tournament->matches()->where('stage', '!=', 'group')->delete();

        $participants = $tournament->participants->sortBy(function ($p) {
            return $p->seed ?: 999;
        })->values();

        $courts = !empty($tournament->courts) ? $tournament->courts : ['Lapangan Utama'];
        $startDate = $tournament->start_date ? Carbon::parse($tournament->start_date)->toDateString() : Carbon::today()->toDateString();

        // Final match
        $finalMatch = TennisMatch::create([
            'tournament_id' => $tournament->id,
            'stage' => 'final',
            'round' => 4,
            'order' => 1,
            'player1_id' => null,
            'player2_id' => null,
            'court' => $courts[0],
            'match_date' => $startDate,
            'match_time' => '16:00',
            'status' => 'scheduled',
            'sets' => [],
        ]);

        // 3rd place match if configured
        if ($tournament->has_third_place) {
            TennisMatch::create([
                'tournament_id' => $tournament->id,
                'stage' => 'third_place',
                'round' => 4,
                'order' => 2,
                'player1_id' => null,
                'player2_id' => null,
                'court' => $courts[1 % count($courts)],
                'match_date' => $startDate,
                'match_time' => '14:00',
                'status' => 'scheduled',
                'sets' => [],
            ]);
        }

        // Semifinals (2 matches)
        $sf1 = TennisMatch::create([
            'tournament_id' => $tournament->id,
            'stage' => 'semifinal',
            'round' => 3,
            'order' => 1,
            'player1_id' => null,
            'player2_id' => null,
            'court' => $courts[0],
            'match_date' => $startDate,
            'match_time' => '10:00',
            'status' => 'scheduled',
            'sets' => [],
            'next_match_id' => $finalMatch->id,
            'next_match_slot' => 1,
        ]);

        $sf2 = TennisMatch::create([
            'tournament_id' => $tournament->id,
            'stage' => 'semifinal',
            'round' => 3,
            'order' => 2,
            'player1_id' => null,
            'player2_id' => null,
            'court' => $courts[1 % count($courts)],
            'match_date' => $startDate,
            'match_time' => '11:30',
            'status' => 'scheduled',
            'sets' => [],
            'next_match_id' => $finalMatch->id,
            'next_match_slot' => 2,
        ]);

        if ($participants->count() <= 4) {
            $sf1->update([
                'player1_id' => $participants[0]->id ?? null,
                'player2_id' => $participants[3]->id ?? null,
            ]);
            $sf2->update([
                'player1_id' => $participants[1]->id ?? null,
                'player2_id' => $participants[2]->id ?? null,
            ]);
            return;
        }

        // Quarterfinals (4 matches for 8 players)
        $pairSlots = [
            [0, 7, $sf1->id, 1], // Seed 1 vs Seed 8 -> SF1 slot 1
            [3, 4, $sf1->id, 2], // Seed 4 vs Seed 5 -> SF1 slot 2
            [2, 5, $sf2->id, 1], // Seed 3 vs Seed 6 -> SF2 slot 1
            [1, 6, $sf2->id, 2], // Seed 2 vs Seed 7 -> SF2 slot 2
        ];

        foreach ($pairSlots as $idx => [$p1Idx, $p2Idx, $nextId, $slot]) {
            TennisMatch::create([
                'tournament_id' => $tournament->id,
                'stage' => 'quarterfinal',
                'round' => 2,
                'order' => $idx + 1,
                'player1_id' => $participants[$p1Idx]->id ?? null,
                'player2_id' => $participants[$p2Idx]->id ?? null,
                'court' => $courts[$idx % count($courts)],
                'match_date' => $startDate,
                'match_time' => sprintf('%02d:00', 8 + $idx * 2),
                'status' => 'scheduled',
                'sets' => [],
                'next_match_id' => $nextId,
                'next_match_slot' => $slot,
            ]);
        }
    }

    /**
     * Advance group qualifiers to knockout bracket
     */
    public function generateKnockoutFromGroups(Tournament $tournament): void
    {
        $groups = $tournament->groups;
        if ($groups->count() < 2) return;

        $gAStandings = $this->calculateGroupStandings($groups[0]);
        $gBStandings = $this->calculateGroupStandings($groups[1]);

        $winnerA = $gAStandings[0]['participant']->id ?? null;
        $runnerA = $gAStandings[1]['participant']->id ?? null;
        $winnerB = $gBStandings[0]['participant']->id ?? null;
        $runnerB = $gBStandings[1]['participant']->id ?? null;

        $courts = !empty($tournament->courts) ? $tournament->courts : ['Center Court', 'Court 1'];
        $startDate = $tournament->start_date ? Carbon::parse($tournament->start_date)->toDateString() : Carbon::today()->toDateString();

        $existingFinal = $tournament->matches()->where('stage', 'final')->first();
        if ($existingFinal) {
            // Update existing semifinal slots
            $sf1 = $tournament->matches()->where('stage', 'semifinal')->where('order', 1)->first();
            $sf2 = $tournament->matches()->where('stage', 'semifinal')->where('order', 2)->first();

            if ($sf1) $sf1->update(['player1_id' => $winnerA, 'player2_id' => $runnerB]);
            if ($sf2) $sf2->update(['player1_id' => $winnerB, 'player2_id' => $runnerA]);
            return;
        }

        // Create fresh Final
        $final = TennisMatch::create([
            'tournament_id' => $tournament->id,
            'stage' => 'final',
            'round' => 2,
            'order' => 1,
            'player1_id' => null,
            'player2_id' => null,
            'court' => $courts[0],
            'match_date' => $startDate,
            'match_time' => '16:00',
            'status' => 'scheduled',
            'sets' => [],
        ]);

        if ($tournament->has_third_place) {
            TennisMatch::create([
                'tournament_id' => $tournament->id,
                'stage' => 'third_place',
                'round' => 2,
                'order' => 2,
                'player1_id' => null,
                'player2_id' => null,
                'court' => $courts[1 % count($courts)],
                'match_date' => $startDate,
                'match_time' => '14:00',
                'status' => 'scheduled',
                'sets' => [],
            ]);
        }

        // Semifinal 1: Winner Group A vs Runner-up Group B
        TennisMatch::create([
            'tournament_id' => $tournament->id,
            'stage' => 'semifinal',
            'round' => 1,
            'order' => 1,
            'player1_id' => $winnerA,
            'player2_id' => $runnerB,
            'court' => $courts[0],
            'match_date' => $startDate,
            'match_time' => '09:00',
            'status' => 'scheduled',
            'sets' => [],
            'next_match_id' => $final->id,
            'next_match_slot' => 1,
        ]);

        // Semifinal 2: Winner Group B vs Runner-up Group A
        TennisMatch::create([
            'tournament_id' => $tournament->id,
            'stage' => 'semifinal',
            'round' => 1,
            'order' => 2,
            'player1_id' => $winnerB,
            'player2_id' => $runnerA,
            'court' => $courts[1 % count($courts)],
            'match_date' => $startDate,
            'match_time' => '11:00',
            'status' => 'scheduled',
            'sets' => [],
            'next_match_id' => $final->id,
            'next_match_slot' => 2,
        ]);
    }

    /**
     * Advance match winner to next bracket match slot
     */
    public function advanceWinner(TennisMatch $match): void
    {
        if (!$match->next_match_id || !$match->winner_id) {
            return;
        }

        $nextMatch = TennisMatch::find($match->next_match_id);
        if ($nextMatch) {
            if ($match->next_match_slot == 1) {
                $nextMatch->update(['player1_id' => $match->winner_id]);
            } elseif ($match->next_match_slot == 2) {
                $nextMatch->update(['player2_id' => $match->winner_id]);
            }
        }
    }
}
