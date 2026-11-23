<?php

use App\Http\Controllers\BracketController;
use App\Http\Controllers\GroupStageController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TournamentController::class, 'index'])->name('tournaments.index');
Route::post('/tournaments', [TournamentController::class, 'store'])->name('tournaments.store');
Route::put('/tournaments/{id}', [TournamentController::class, 'update'])->name('tournaments.update');
Route::delete('/tournaments/{id}', [TournamentController::class, 'destroy'])->name('tournaments.destroy');
Route::get('/tournaments/{id}/report', [TournamentController::class, 'report'])->name('tournaments.report');
Route::post('/tournaments/reset-demo', [TournamentController::class, 'resetDemo'])->name('tournaments.reset_demo');

// Participants
Route::post('/tournaments/{id}/participants', [ParticipantController::class, 'store'])->name('participants.store');
Route::put('/participants/{id}', [ParticipantController::class, 'update'])->name('participants.update');
Route::delete('/participants/{id}', [ParticipantController::class, 'destroy'])->name('participants.destroy');
Route::post('/tournaments/{id}/participants/sample', [ParticipantController::class, 'generateSample'])->name('participants.sample');

// Group Stage
Route::post('/tournaments/{id}/groups/draw', [GroupStageController::class, 'draw'])->name('groups.draw');
Route::post('/tournaments/{id}/groups/matches', [GroupStageController::class, 'generateMatches'])->name('groups.matches');
Route::post('/tournaments/{id}/groups/advance', [GroupStageController::class, 'advanceToKnockout'])->name('groups.advance');

// Bracket
Route::post('/tournaments/{id}/bracket/generate', [BracketController::class, 'generate'])->name('bracket.generate');

// Schedule & Matches
Route::put('/matches/{id}/schedule', [ScheduleController::class, 'update'])->name('matches.schedule');
Route::post('/matches/{id}/score', [ScoreController::class, 'update'])->name('matches.score');
Route::post('/matches/{id}/live-umpire', [ScoreController::class, 'liveUmpire'])->name('matches.live_umpire');
