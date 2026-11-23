<?php

namespace App\Http\Controllers;

use App\Models\TennisMatch;
use App\Services\TennisService;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    protected TennisService $tennisService;

    public function __construct(TennisService $tennisService)
    {
        $this->tennisService = $tennisService;
    }

    public function update(Request $request, $id)
    {
        $match = TennisMatch::findOrFail($id);

        $isWalkover = $request->has('is_walkover');
        $winnerId = $request->input('winner_id');
        $notes = $request->input('notes');

        if ($isWalkover) {
            $match->update([
                'status' => 'walkover',
                'winner_id' => $winnerId,
                'sets' => [],
                'notes' => $notes ?: 'Pemenang ditentukan melalui Walkover (W.O)',
            ]);
        } else {
            // Process sets
            $rawSets = $request->input('sets', []);
            $sets = [];
            $p1Sets = 0;
            $p2Sets = 0;

            foreach ($rawSets as $s) {
                $p1G = (int)($s['p1Games'] ?? 0);
                $p2G = (int)($s['p2Games'] ?? 0);
                $tb1 = isset($s['tiebreakP1']) && $s['tiebreakP1'] !== '' ? (int)$s['tiebreakP1'] : null;
                $tb2 = isset($s['tiebreakP2']) && $s['tiebreakP2'] !== '' ? (int)$s['tiebreakP2'] : null;

                $sets[] = [
                    'p1Games' => $p1G,
                    'p2Games' => $p2G,
                    'tiebreakP1' => $tb1,
                    'tiebreakP2' => $tb2,
                ];

                if ($p1G > $p2G) {
                    $p1Sets++;
                } elseif ($p2G > $p1G) {
                    $p2Sets++;
                }
            }

            // Determine winner if not manually selected
            if (!$winnerId) {
                if ($p1Sets > $p2Sets) {
                    $winnerId = $match->player1_id;
                } elseif ($p2Sets > $p1Sets) {
                    $winnerId = $match->player2_id;
                }
            }

            $match->update([
                'status' => $winnerId ? 'completed' : 'live',
                'winner_id' => $winnerId,
                'sets' => $sets,
                'notes' => $notes,
            ]);
        }

        // Advance winner in knockout bracket if applicable
        if ($match->winner_id) {
            $this->tennisService->advanceWinner($match);
        }

        return back()->with('success', 'Skor pertandingan berhasil disimpan dan bagan turnamen telah diperbarui!');
    }

    public function liveUmpire(Request $request, $id)
    {
        $match = TennisMatch::findOrFail($id);

        $winnerId = $request->input('winner_id');
        $sets = $request->input('sets', []);

        $match->update([
            'status' => 'completed',
            'winner_id' => $winnerId,
            'sets' => $sets,
            'live_score' => null,
        ]);

        if ($match->winner_id) {
            $this->tennisService->advanceWinner($match);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pertandingan selesai dan hasil telah disimpan.',
        ]);
    }
}
