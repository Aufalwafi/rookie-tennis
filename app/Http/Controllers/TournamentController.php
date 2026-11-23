<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Participant;
use App\Models\TennisMatch;
use App\Services\TennisService;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    protected TennisService $tennisService;

    public function __construct(TennisService $tennisService)
    {
        $this->tennisService = $tennisService;
    }

    public function index(Request $request)
    {
        $tournaments = Tournament::latest()->get();

        if ($tournaments->isEmpty()) {
            return view('tournaments.empty');
        }

        $activeTournamentId = $request->query('tournament', $tournaments->first()->id);
        $activeTournament = Tournament::with(['participants', 'groups.participants', 'matches.player1', 'matches.player2', 'matches.winner'])->find($activeTournamentId) ?: $tournaments->first();

        // Calculate group standings if format includes groups
        $groupStandings = [];
        if ($activeTournament->groups->isNotEmpty()) {
            foreach ($activeTournament->groups as $group) {
                $groupStandings[$group->id] = $this->tennisService->calculateGroupStandings($group);
            }
        }

        $activeTab = $request->query('tab', 'home');

        return view('tournaments.show', compact('tournaments', 'activeTournament', 'groupStandings', 'activeTab'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'surface' => 'required|string',
            'format' => 'required|string',
            'match_rule' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'location' => 'nullable|string',
            'courts' => 'nullable|string',
            'groups_count' => 'nullable|integer|min:2|max:8',
            'advance_per_group' => 'nullable|integer|min:1|max:4',
            'has_third_place' => 'nullable|boolean',
        ]);

        $courts = !empty($validated['courts']) 
            ? array_map('trim', explode(',', $validated['courts']))
            : ['Center Court', 'Lapangan 1', 'Lapangan 2'];

        $tournament = Tournament::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'],
            'surface' => $validated['surface'],
            'format' => $validated['format'],
            'match_rule' => $validated['match_rule'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'location' => $validated['location'] ?? null,
            'courts' => $courts,
            'groups_count' => $validated['groups_count'] ?? 2,
            'advance_per_group' => $validated['advance_per_group'] ?? 2,
            'has_third_place' => $request->has('has_third_place'),
            'status' => 'registration',
        ]);

        return redirect()->route('tournaments.index', ['tournament' => $tournament->id])
            ->with('success', "Turnamen '{$tournament->name}' berhasil dibuat!");
    }

    public function update(Request $request, $id)
    {
        $tournament = Tournament::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'surface' => 'required|string',
            'format' => 'required|string',
            'match_rule' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'location' => 'nullable|string',
            'courts' => 'nullable|string',
            'groups_count' => 'nullable|integer|min:2|max:8',
            'advance_per_group' => 'nullable|integer|min:1|max:4',
        ]);

        $courts = !empty($validated['courts']) 
            ? array_map('trim', explode(',', $validated['courts']))
            : $tournament->courts;

        $tournament->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'],
            'surface' => $validated['surface'],
            'format' => $validated['format'],
            'match_rule' => $validated['match_rule'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'location' => $validated['location'] ?? null,
            'courts' => $courts,
            'groups_count' => $validated['groups_count'] ?? 2,
            'advance_per_group' => $validated['advance_per_group'] ?? 2,
            'has_third_place' => $request->has('has_third_place'),
        ]);

        return back()->with('success', 'Pengaturan turnamen berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $tournament = Tournament::findOrFail($id);
        $name = $tournament->name;
        $tournament->delete();

        return redirect()->route('tournaments.index')->with('success', "Turnamen '{$name}' telah dihapus.");
    }

    public function report($id)
    {
        $tournament = Tournament::with(['participants', 'groups.participants', 'matches.player1', 'matches.player2', 'matches.winner'])->findOrFail($id);
        
        $groupStandings = [];
        if ($tournament->groups->isNotEmpty()) {
            foreach ($tournament->groups as $group) {
                $groupStandings[$group->id] = $this->tennisService->calculateGroupStandings($group);
            }
        }

        $finalMatch = $tournament->matches->firstWhere('stage', 'final');
        $champion = $finalMatch && $finalMatch->winner_id ? $finalMatch->winner : null;

        return view('tournaments.report', compact('tournament', 'groupStandings', 'champion'));
    }

    public function resetDemo()
    {
        \Artisan::call('db:seed', ['--force' => true]);
        return redirect()->route('tournaments.index')->with('success', 'Data turnamen berhasil direset ke contoh default!');
    }
}
