<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Tournament;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function store(Request $request, $tournamentId)
    {
        $tournament = Tournament::findOrFail($tournamentId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'partner_name' => 'nullable|string|max:255',
            'seed' => 'nullable|integer|min:1|max:32',
            'club' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'ranking' => 'nullable|integer|min:1',
        ]);

        $tournament->participants()->create($validated);

        return redirect()->route('tournaments.index', ['tournament' => $tournamentId, 'tab' => 'participants'])
            ->with('success', 'Peserta berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $participant = Participant::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'partner_name' => 'nullable|string|max:255',
            'seed' => 'nullable|integer|min:1|max:32',
            'club' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'ranking' => 'nullable|integer|min:1',
        ]);

        $participant->update($validated);

        return redirect()->route('tournaments.index', ['tournament' => $participant->tournament_id, 'tab' => 'participants'])
            ->with('success', 'Data peserta berhasil diperbarui!');
    }

    public function destroy($id)
    {
        if (!$this->checkAdmin()) {
            return back()->with('error', 'Hanya Penyelenggara yang dapat menghapus peserta.');
        }

        $participant = Participant::findOrFail($id);
        $tournamentId = $participant->tournament_id;
        $participant->delete();

        return redirect()->route('tournaments.index', ['tournament' => $tournamentId, 'tab' => 'participants'])
            ->with('success', 'Peserta berhasil dihapus.');
    }

    public function selfRegister(Request $request, $tournamentId)
    {
        $tournament = Tournament::findOrFail($tournamentId);
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu untuk mendaftar turnamen.');
        }

        // Check if already registered
        $existing = $tournament->participants->first(function ($p) use ($user) {
            return strtolower(trim($p->name)) === strtolower(trim($user->name))
                || (!empty($p->phone) && !empty($user->phone) && trim($p->phone) === trim($user->phone));
        });

        if ($existing) {
            return back()->with('error', "Anda sudah terdaftar dalam turnamen ini sebagai '{$existing->name}'.");
        }

        if ($tournament->participants()->count() >= 32) {
            return back()->with('error', 'Maaf, kuota turnamen ini sudah penuh (maksimal 32 peserta).');
        }

        $validated = $request->validate([
            'partner_name' => 'nullable|string|max:255',
            'club' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
        ]);

        $tournament->participants()->create([
            'name' => $user->name,
            'partner_name' => $validated['partner_name'] ?? null,
            'club' => $validated['club'] ?: 'Independen',
            'city' => $validated['city'] ?: 'Jakarta',
            'phone' => $user->phone ?: '081234567890',
            'seed' => null,
            'ranking' => null,
        ]);

        return redirect()->route('tournaments.index', ['tournament' => $tournamentId, 'tab' => 'participants'])
            ->with('success', "Selamat {$user->name}! Anda berhasil mendaftar di turnamen {$tournament->name}.");
    }

    public function generateSample(Request $request, $tournamentId)
    {
        if (!$this->checkAdmin()) {
            return back()->with('error', 'Hanya Penyelenggara yang dapat menggunakan fitur isi otomatis.');
        }

        $tournament = Tournament::findOrFail($tournamentId);
        $count = (int)$request->input('count', 8);

        $singlesRoster = [
            ['name' => 'Muhammad Rifqi Fitriadi', 'club' => 'Jatim Tennis Club', 'city' => 'Surabaya'],
            ['name' => 'Christopher Rungkat', 'club' => 'Pelita Tennis Jakarta', 'city' => 'Jakarta'],
            ['name' => 'Justin Barki', 'club' => 'Senayan Racquet Club', 'city' => 'Jakarta'],
            ['name' => 'David Agung Susanto', 'club' => 'Djarum Tennis', 'city' => 'Semarang'],
            ['name' => 'Anthony Susanto', 'club' => 'Surabaya Tennis Squad', 'city' => 'Surabaya'],
            ['name' => 'Tegar Abdi Satrio', 'club' => 'Babel Tennis Center', 'city' => 'Pangkal Pinang'],
            ['name' => 'Lucky Candra Kurniawan', 'club' => 'Lombok Smash Club', 'city' => 'Mataram'],
            ['name' => 'Claudio Renardi Lumanauw', 'club' => 'Metro Jakarta Tennis', 'city' => 'Jakarta'],
            ['name' => 'Aldila Sutjiadi', 'club' => 'Elite Tennis Club', 'city' => 'Jakarta'],
            ['name' => 'Priska Madelyn Nugroho', 'club' => 'Nusantara Tennis', 'city' => 'Jakarta'],
            ['name' => 'Janice Tjen', 'club' => 'Bandung Smashers', 'city' => 'Bandung'],
            ['name' => 'Fitriani Sabatini', 'club' => 'Banten Tennis', 'city' => 'Tangerang'],
            ['name' => 'Beatrice Gumulya', 'club' => 'Jakarta Tennis Club', 'city' => 'Jakarta'],
            ['name' => 'Jessy Rompies', 'club' => 'Bali Tennis Pro', 'city' => 'Denpasar'],
            ['name' => 'Faried Widyarohmadhiansyah', 'club' => 'Yogya Racquet', 'city' => 'Yogyakarta'],
            ['name' => 'Arief Rahman', 'club' => 'Surakarta Tennis', 'city' => 'Solo'],
        ];

        $doublesRoster = [
            ['name' => 'Fajar Alfian', 'partner_name' => 'Rian Ardianto', 'club' => 'PB Jaya Raya', 'city' => 'Jakarta'],
            ['name' => 'Hendra Setiawan', 'partner_name' => 'Mohammad Ahsan', 'club' => 'Djarum Tennis', 'city' => 'Kudus'],
            ['name' => 'Leo Rolly Carnando', 'partner_name' => 'Daniel Marthin', 'club' => 'PB Djarum', 'city' => 'Jakarta'],
            ['name' => 'Bagas Maulana', 'partner_name' => 'M. Shohibul Fikri', 'club' => 'Kudus Smash Club', 'city' => 'Semarang'],
            ['name' => 'Kevin Sanjaya', 'partner_name' => 'Marcus Gideon', 'club' => 'Suryanaga', 'city' => 'Surabaya'],
            ['name' => 'Pramudya Kusumawardana', 'partner_name' => 'Yeremia Rambitan', 'club' => 'Jakarta Tennis', 'city' => 'Jakarta'],
            ['name' => 'Sabar Karyaman', 'partner_name' => 'Reza Pahlevi', 'club' => 'Bandung Tennis', 'city' => 'Bandung'],
            ['name' => 'Bona Septano', 'partner_name' => 'Fran Kurniawan', 'club' => 'Veteran Club', 'city' => 'Solo'],
        ];

        $isDoubles = str_contains($tournament->category, 'doubles');
        $roster = $isDoubles ? $doublesRoster : $singlesRoster;
        $selected = array_slice($roster, 0, $count);

        foreach ($selected as $idx => $item) {
            $tournament->participants()->create([
                'name' => $item['name'],
                'partner_name' => $item['partner_name'] ?? null,
                'seed' => ($idx < 4) ? ($idx + 1) : null,
                'club' => $item['club'],
                'city' => $item['city'],
                'phone' => '0812-' . rand(1000, 9999) . '-' . rand(1000, 9999),
                'ranking' => $idx + 1,
            ]);
        }

        return redirect()->route('tournaments.index', ['tournament' => $tournamentId, 'tab' => 'participants'])
            ->with('success', "{$count} peserta contoh berhasil ditambahkan!");
    }
}
