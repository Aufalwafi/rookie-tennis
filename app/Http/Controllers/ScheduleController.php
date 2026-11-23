<?php

namespace App\Http\Controllers;

use App\Models\TennisMatch;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function update(Request $request, $id)
    {
        $match = TennisMatch::findOrFail($id);

        $validated = $request->validate([
            'match_date' => 'required|date',
            'match_time' => 'required|string',
            'court' => 'required|string',
        ]);

        $match->update($validated);

        return back()->with('success', 'Jadwal pertandingan berhasil diperbarui!');
    }
}
