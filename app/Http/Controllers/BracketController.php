<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Services\TennisService;
use Illuminate\Http\Request;

class BracketController extends Controller
{
    protected TennisService $tennisService;

    public function __construct(TennisService $tennisService)
    {
        $this->tennisService = $tennisService;
    }

    public function generate($tournamentId)
    {
        if (!$this->checkAdmin()) {
            return back()->with('error', 'Hanya Penyelenggara yang dapat mengundi bagan knockout.');
        }

        $tournament = Tournament::findOrFail($tournamentId);

        if ($tournament->participants->count() < 2) {
            return back()->with('error', 'Minimal dibutuhkan 2 peserta terdaftar untuk membentuk bagan.');
        }

        if ($tournament->format === 'group_knockout') {
            $this->tennisService->generateKnockoutFromGroups($tournament);
        } else {
            $this->tennisService->generateDirectKnockout($tournament);
        }

        return redirect()->route('tournaments.index', ['tournament' => $tournamentId, 'tab' => 'bracket'])
            ->with('success', 'Bagan sistem knockout berhasil diperbarui!');
    }
}
