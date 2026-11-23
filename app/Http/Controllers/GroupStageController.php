<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Services\TennisService;
use Illuminate\Http\Request;

class GroupStageController extends Controller
{
    protected TennisService $tennisService;

    public function __construct(TennisService $tennisService)
    {
        $this->tennisService = $tennisService;
    }

    public function draw($tournamentId)
    {
        $tournament = Tournament::findOrFail($tournamentId);
        
        if ($tournament->participants->count() < 2) {
            return back()->with('error', 'Minimal dibutuhkan 2 peserta terdaftar untuk mengundi grup.');
        }

        $this->tennisService->autoDrawGroups($tournament);

        return redirect()->route('tournaments.index', ['tournament' => $tournamentId, 'tab' => 'group'])
            ->with('success', 'Pembagian grup secara otomatis berhasil dilakukan!');
    }

    public function generateMatches($tournamentId)
    {
        $tournament = Tournament::findOrFail($tournamentId);

        if ($tournament->groups->isEmpty()) {
            return back()->with('error', 'Silakan undi grup terlebih dahulu.');
        }

        $this->tennisService->generateGroupMatches($tournament);

        return redirect()->route('tournaments.index', ['tournament' => $tournamentId, 'tab' => 'group'])
            ->with('success', 'Jadwal pertandingan fase grup berhasil dibuat!');
    }

    public function advanceToKnockout($tournamentId)
    {
        $tournament = Tournament::findOrFail($tournamentId);
        $this->tennisService->generateKnockoutFromGroups($tournament);

        return redirect()->route('tournaments.index', ['tournament' => $tournamentId, 'tab' => 'bracket'])
            ->with('success', 'Peserta lolos grup berhasil dihubungkan ke Bagan Knockout!');
    }
}
