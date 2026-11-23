<?php

namespace Database\Seeders;

use App\Models\Participant;
use App\Models\TennisMatch;
use App\Models\Tournament;
use App\Models\TournamentGroup;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Clean existing tables
        DB::table('tennis_matches')->delete();
        DB::table('group_participant')->delete();
        DB::table('tournament_groups')->delete();
        DB::table('participants')->delete();
        DB::table('tournaments')->delete();

        // 1. Jakarta Open Tennis Championship 2026
        $t1 = Tournament::create([
            'name' => 'Jakarta Open Tennis Championship 2026',
            'description' => 'Kejuaraan tenis bergengsi tingkat nasional memperebutkan Piala Gubernur DKI Jakarta. Menggunakan sistem Fase Grup dilanjutkan Knockout Semifinal.',
            'category' => 'mens_singles',
            'surface' => 'Hard Court',
            'format' => 'group_knockout',
            'match_rule' => 'best_of_3',
            'start_date' => Carbon::now()->subDays(2)->toDateString(),
            'end_date' => Carbon::now()->addDays(3)->toDateString(),
            'location' => 'Senayan Tennis Indoor & Outdoor, Jakarta Pusat',
            'courts' => ['Center Court Senayan', 'Court 1 Outdoor', 'Court 2 Outdoor'],
            'status' => 'in_progress',
            'groups_count' => 2,
            'advance_per_group' => 2,
            'has_third_place' => true,
            'points_win' => 2,
            'points_loss' => 0,
        ]);

        $playersT1 = [
            ['name' => 'M. Rifqi Fitriadi', 'seed' => 1, 'club' => 'Jatim Tennis Club', 'city' => 'Surabaya', 'ranking' => 1],
            ['name' => 'Christopher Rungkat', 'seed' => 2, 'club' => 'Pelita Harapan Tennis', 'city' => 'Jakarta', 'ranking' => 2],
            ['name' => 'Justin Barki', 'seed' => 3, 'club' => 'Senayan Tennis Academy', 'city' => 'Jakarta', 'ranking' => 3],
            ['name' => 'David Agung Susanto', 'seed' => 4, 'club' => 'Djarum Tennis Kudus', 'city' => 'Semarang', 'ranking' => 4],
            ['name' => 'Anthony Susanto', 'seed' => null, 'club' => 'Surabaya Tennis Squad', 'city' => 'Surabaya', 'ranking' => 5],
            ['name' => 'Tegar Abdi Satrio', 'seed' => null, 'club' => 'Babel Tennis Center', 'city' => 'Pangkal Pinang', 'ranking' => 6],
            ['name' => 'Lucky Candra Kurniawan', 'seed' => null, 'club' => 'Lombok Smash Club', 'city' => 'Mataram', 'ranking' => 7],
            ['name' => 'Claudio Renardi Lumanauw', 'seed' => null, 'club' => 'Metro Jakarta Tennis', 'city' => 'Jakarta', 'ranking' => 8],
        ];

        $pT1Models = [];
        foreach ($playersT1 as $p) {
            $pT1Models[] = $t1->participants()->create([
                'name' => $p['name'],
                'seed' => $p['seed'],
                'club' => $p['club'],
                'city' => $p['city'],
                'phone' => '0812-3456-' . rand(1000, 9999),
                'ranking' => $p['ranking'],
            ]);
        }

        // Groups
        $grpA = $t1->groups()->create(['name' => 'Grup A']);
        $grpB = $t1->groups()->create(['name' => 'Grup B']);

        $grpA->participants()->attach([$pT1Models[0]->id, $pT1Models[3]->id, $pT1Models[4]->id, $pT1Models[6]->id]);
        $grpB->participants()->attach([$pT1Models[1]->id, $pT1Models[2]->id, $pT1Models[5]->id, $pT1Models[7]->id]);

        // Group A Matches
        $matchesGrpA = [
            [$pT1Models[0]->id, $pT1Models[3]->id, $pT1Models[0]->id, [['p1Games' => 6, 'p2Games' => 4], ['p1Games' => 7, 'p2Games' => 5]]],
            [$pT1Models[4]->id, $pT1Models[6]->id, $pT1Models[4]->id, [['p1Games' => 6, 'p2Games' => 2], ['p1Games' => 4, 'p2Games' => 6], ['p1Games' => 7, 'p2Games' => 6, 'tiebreakP1' => 7, 'tiebreakP2' => 5]]],
            [$pT1Models[0]->id, $pT1Models[4]->id, $pT1Models[0]->id, [['p1Games' => 6, 'p2Games' => 3], ['p1Games' => 6, 'p2Games' => 4]]],
            [$pT1Models[3]->id, $pT1Models[6]->id, $pT1Models[3]->id, [['p1Games' => 6, 'p2Games' => 1], ['p1Games' => 6, 'p2Games' => 3]]],
            [$pT1Models[0]->id, $pT1Models[6]->id, $pT1Models[0]->id, [['p1Games' => 6, 'p2Games' => 2], ['p1Games' => 6, 'p2Games' => 1]]],
            [$pT1Models[3]->id, $pT1Models[4]->id, $pT1Models[3]->id, [['p1Games' => 7, 'p2Games' => 5], ['p1Games' => 6, 'p2Games' => 4]]],
        ];

        foreach ($matchesGrpA as $idx => [$p1, $p2, $win, $sets]) {
            TennisMatch::create([
                'tournament_id' => $t1->id,
                'tournament_group_id' => $grpA->id,
                'stage' => 'group',
                'round' => 1,
                'order' => $idx + 1,
                'player1_id' => $p1,
                'player2_id' => $p2,
                'winner_id' => $win,
                'court' => 'Center Court Senayan',
                'match_date' => Carbon::now()->subDays(2)->toDateString(),
                'match_time' => sprintf('%02d:00', 8 + $idx * 2),
                'status' => 'completed',
                'sets' => $sets,
            ]);
        }

        // Group B Matches
        $matchesGrpB = [
            [$pT1Models[1]->id, $pT1Models[2]->id, $pT1Models[1]->id, [['p1Games' => 7, 'p2Games' => 6, 'tiebreakP1' => 7, 'tiebreakP2' => 4], ['p1Games' => 6, 'p2Games' => 4]]],
            [$pT1Models[5]->id, $pT1Models[7]->id, $pT1Models[5]->id, [['p1Games' => 6, 'p2Games' => 3], ['p1Games' => 6, 'p2Games' => 2]]],
            [$pT1Models[1]->id, $pT1Models[5]->id, $pT1Models[1]->id, [['p1Games' => 6, 'p2Games' => 1], ['p1Games' => 6, 'p2Games' => 3]]],
            [$pT1Models[2]->id, $pT1Models[7]->id, $pT1Models[2]->id, [['p1Games' => 6, 'p2Games' => 0], ['p1Games' => 6, 'p2Games' => 2]]],
            [$pT1Models[1]->id, $pT1Models[7]->id, $pT1Models[1]->id, [['p1Games' => 6, 'p2Games' => 4], ['p1Games' => 6, 'p2Games' => 2]]],
            [$pT1Models[2]->id, $pT1Models[5]->id, $pT1Models[2]->id, [['p1Games' => 6, 'p2Games' => 3], ['p1Games' => 7, 'p2Games' => 5]]],
        ];

        foreach ($matchesGrpB as $idx => [$p1, $p2, $win, $sets]) {
            TennisMatch::create([
                'tournament_id' => $t1->id,
                'tournament_group_id' => $grpB->id,
                'stage' => 'group',
                'round' => 1,
                'order' => $idx + 7,
                'player1_id' => $p1,
                'player2_id' => $p2,
                'winner_id' => $win,
                'court' => 'Court 1 Outdoor',
                'match_date' => Carbon::now()->subDay()->toDateString(),
                'match_time' => sprintf('%02d:00', 8 + $idx * 2),
                'status' => 'completed',
                'sets' => $sets,
            ]);
        }

        // Knockout Matches
        $final = TennisMatch::create([
            'tournament_id' => $t1->id,
            'stage' => 'final',
            'round' => 2,
            'order' => 1,
            'player1_id' => null,
            'player2_id' => null,
            'court' => 'Center Court Senayan',
            'match_date' => Carbon::now()->addDays(2)->toDateString(),
            'match_time' => '15:30',
            'status' => 'scheduled',
            'sets' => [],
        ]);

        // SF1: Rifqi Fitriadi (Juara Grup A) vs Justin Barki (Runner-up Grup B)
        TennisMatch::create([
            'tournament_id' => $t1->id,
            'stage' => 'semifinal',
            'round' => 1,
            'order' => 1,
            'player1_id' => $pT1Models[0]->id,
            'player2_id' => $pT1Models[2]->id,
            'court' => 'Center Court Senayan',
            'match_date' => Carbon::now()->toDateString(),
            'match_time' => '09:00',
            'status' => 'live',
            'sets' => [['p1Games' => 6, 'p2Games' => 4], ['p1Games' => 3, 'p2Games' => 4]],
            'live_score' => ['p1Points' => '40', 'p2Points' => '30', 'server' => 1, 'currentSet' => 2],
            'next_match_id' => $final->id,
            'next_match_slot' => 1,
        ]);

        // SF2: Christopher Rungkat (Juara Grup B) vs David Agung (Runner-up Grup A)
        TennisMatch::create([
            'tournament_id' => $t1->id,
            'stage' => 'semifinal',
            'round' => 1,
            'order' => 2,
            'player1_id' => $pT1Models[1]->id,
            'player2_id' => $pT1Models[3]->id,
            'court' => 'Center Court Senayan',
            'match_date' => Carbon::now()->toDateString(),
            'match_time' => '13:30',
            'status' => 'scheduled',
            'sets' => [],
            'next_match_id' => $final->id,
            'next_match_slot' => 2,
        ]);

        // 3rd place
        TennisMatch::create([
            'tournament_id' => $t1->id,
            'stage' => 'third_place',
            'round' => 2,
            'order' => 2,
            'player1_id' => null,
            'player2_id' => null,
            'court' => 'Court 1 Outdoor',
            'match_date' => Carbon::now()->addDays(2)->toDateString(),
            'match_time' => '10:00',
            'status' => 'scheduled',
            'sets' => [],
        ]);


        // 2. Bali Clay Court Masters 2026 (Direct Knockout)
        $t2 = Tournament::create([
            'name' => 'Bali Clay Court Masters 2026',
            'description' => 'Turnamen sistem Knockout murni di atas lapangan tanah liat (Red Clay) Sanur Bali. Pertandingan kompetitif sistem Best of 3 Sets.',
            'category' => 'mens_singles',
            'surface' => 'Clay',
            'format' => 'knockout',
            'match_rule' => 'best_of_3',
            'start_date' => Carbon::now()->toDateString(),
            'end_date' => Carbon::now()->addDays(4)->toDateString(),
            'location' => 'Sanur Tennis & Country Club, Denpasar, Bali',
            'courts' => ['Clay Court 1 (Stadium)', 'Clay Court 2'],
            'status' => 'in_progress',
            'groups_count' => 0,
            'advance_per_group' => 0,
            'has_third_place' => true,
            'points_win' => 2,
            'points_loss' => 0,
        ]);

        $playersT2 = [
            ['name' => 'Rafael Nadal Fans Club (Adi)', 'seed' => 1, 'club' => 'Sanur Tennis', 'city' => 'Denpasar'],
            ['name' => 'Bagus Wirawan', 'seed' => 2, 'club' => 'Kuta Smashers', 'city' => 'Badung'],
            ['name' => 'Putu Arya Gede', 'seed' => 3, 'club' => 'Ubud Topspin', 'city' => 'Gianyar'],
            ['name' => 'Wayan Hendra', 'seed' => 4, 'club' => 'Tabanan Ace', 'city' => 'Tabanan'],
            ['name' => 'Ketut Agus', 'seed' => null, 'club' => 'Sanur Tennis', 'city' => 'Denpasar'],
            ['name' => 'Made Suastika', 'seed' => null, 'club' => 'Singaraja Racquet', 'city' => 'Buleleng'],
            ['name' => 'Nyoman Triana', 'seed' => null, 'club' => 'Denpasar Club', 'city' => 'Denpasar'],
            ['name' => 'Gede Pramana', 'seed' => null, 'club' => 'Badung Tennis', 'city' => 'Badung'],
        ];

        $pT2Models = [];
        foreach ($playersT2 as $p) {
            $pT2Models[] = $t2->participants()->create([
                'name' => $p['name'],
                'seed' => $p['seed'],
                'club' => $p['club'],
                'city' => $p['city'],
                'phone' => '0813-2222-' . rand(1000, 9999),
            ]);
        }

        // Final
        $final2 = TennisMatch::create([
            'tournament_id' => $t2->id,
            'stage' => 'final',
            'round' => 4,
            'order' => 1,
            'player1_id' => null,
            'player2_id' => null,
            'court' => 'Clay Court 1 (Stadium)',
            'match_date' => Carbon::now()->addDays(3)->toDateString(),
            'match_time' => '16:00',
            'status' => 'scheduled',
            'sets' => [],
        ]);

        // Semifinals
        $sf2_1 = TennisMatch::create([
            'tournament_id' => $t2->id,
            'stage' => 'semifinal',
            'round' => 3,
            'order' => 1,
            'player1_id' => $pT2Models[0]->id,
            'player2_id' => $pT2Models[3]->id,
            'court' => 'Clay Court 1 (Stadium)',
            'match_date' => Carbon::now()->addDays(1)->toDateString(),
            'match_time' => '10:00',
            'status' => 'scheduled',
            'sets' => [],
            'next_match_id' => $final2->id,
            'next_match_slot' => 1,
        ]);

        $sf2_2 = TennisMatch::create([
            'tournament_id' => $t2->id,
            'stage' => 'semifinal',
            'round' => 3,
            'order' => 2,
            'player1_id' => $pT2Models[2]->id,
            'player2_id' => $pT2Models[1]->id,
            'court' => 'Clay Court 2',
            'match_date' => Carbon::now()->addDays(1)->toDateString(),
            'match_time' => '11:30',
            'status' => 'scheduled',
            'sets' => [],
            'next_match_id' => $final2->id,
            'next_match_slot' => 2,
        ]);

        // QF matches
        $qfs = [
            [$pT2Models[0]->id, $pT2Models[7]->id, $pT2Models[0]->id, [['p1Games' => 6, 'p2Games' => 2], ['p1Games' => 6, 'p2Games' => 1]], $sf2_1->id, 1],
            [$pT2Models[3]->id, $pT2Models[4]->id, $pT2Models[3]->id, [['p1Games' => 6, 'p2Games' => 4], ['p1Games' => 4, 'p2Games' => 6], ['p1Games' => 6, 'p2Games' => 3]], $sf2_1->id, 2],
            [$pT2Models[2]->id, $pT2Models[5]->id, $pT2Models[2]->id, [['p1Games' => 6, 'p2Games' => 3], ['p1Games' => 7, 'p2Games' => 5]], $sf2_2->id, 1],
            [$pT2Models[1]->id, $pT2Models[6]->id, $pT2Models[1]->id, [['p1Games' => 6, 'p2Games' => 0], ['p1Games' => 6, 'p2Games' => 1]], $sf2_2->id, 2],
        ];

        foreach ($qfs as $idx => [$p1, $p2, $win, $sets, $nextId, $slot]) {
            TennisMatch::create([
                'tournament_id' => $t2->id,
                'stage' => 'quarterfinal',
                'round' => 2,
                'order' => $idx + 1,
                'player1_id' => $p1,
                'player2_id' => $p2,
                'winner_id' => $win,
                'court' => 'Clay Court ' . (($idx % 2) + 1),
                'match_date' => Carbon::now()->toDateString(),
                'match_time' => sprintf('%02d:00', 8 + $idx * 2),
                'status' => 'completed',
                'sets' => $sets,
                'next_match_id' => $nextId,
                'next_match_slot' => $slot,
            ]);
        }
    }
}
