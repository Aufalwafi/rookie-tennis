<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Resmi Turnamen — {{ $tournament->name }} — Rookie Tennis</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background: #0B2E22; color: #0B2E22; }
        h1, h2, h3, .font-display { font-family: 'Space Grotesk', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; color: #000 !important; }
            .report-sheet { box-shadow: none !important; border: 1px solid #cbd5e1 !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="p-4 sm:p-8 max-w-4xl mx-auto space-y-6">

    <!-- Print Action Bar -->
    <div class="flex items-center justify-between bg-[#072118] text-white p-4 rounded-2xl border border-white/10 shadow-xl no-print">
        <div class="flex items-center gap-3">
            <span class="w-3 h-3 rounded-full bg-[#D8F24B] shadow-[0_0_8px_#D8F24B]"></span>
            <div>
                <span class="font-black text-sm font-display tracking-wide text-white block">Dokumen Resmi Turnamen Tenis</span>
                <span class="text-[11px] text-[#DCE8DF]">Rookie Tennis · Hasil Pertandingan & Klasemen</span>
            </div>
        </div>
        <div class="flex items-center gap-2.5">
            <button onclick="window.print()" class="px-5 py-2.5 bg-[#D8F24B] text-[#0B2E22] font-black text-xs rounded-xl hover:bg-[#C9E636] shadow-md transition-all flex items-center gap-1.5 font-display">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak / Simpan PDF</span>
            </button>
            <button onclick="window.close()" class="px-4 py-2.5 bg-white/10 text-white text-xs font-bold rounded-xl hover:bg-white/20 transition-colors">
                Tutup
            </button>
        </div>
    </div>

    <!-- Official Report Dossier Sheet (White Paper Design for crisp printing) -->
    <div class="report-sheet bg-white rounded-3xl border border-slate-200 shadow-xl p-8 sm:p-10 space-y-8 text-slate-800">
        <!-- Letterhead -->
        <div class="text-center border-b-2 border-slate-900 pb-6">
            <div class="text-[11px] font-black uppercase tracking-[0.25em] text-[#0B2E22] mb-1.5 flex items-center justify-center gap-1.5">
                <span>OFFICIAL TOURNAMENT BULLETIN</span>
                <span>•</span>
                <span>ROOKIE TENNIS</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-black uppercase tracking-tight text-slate-950 font-display">{{ $tournament->name }}</h1>
            <p class="text-xs text-slate-600 mt-2 font-medium">
                Venue: <strong>{{ $tournament->location ?: 'Stadion Tenis' }}</strong> • Tanggal: 
                {{ $tournament->start_date ? $tournament->start_date->format('d M Y') : '-' }} s/d {{ $tournament->end_date ? $tournament->end_date->format('d M Y') : '-' }}
            </p>
            <div class="flex flex-wrap items-center justify-center gap-2 text-[11px] font-bold mt-4 text-slate-800">
                <span class="px-3 py-1 rounded-md bg-slate-100 border border-slate-200">Kategori: {{ $tournament->category_label }}</span>
                <span class="px-3 py-1 rounded-md bg-slate-100 border border-slate-200">Lapangan: {{ $tournament->surface }}</span>
                <span class="px-3 py-1 rounded-md bg-slate-100 border border-slate-200">Format: {{ $tournament->format_label }}</span>
                <span class="px-3 py-1 rounded-md bg-slate-100 border border-slate-200">Peserta: {{ $tournament->participants->count() }} Pemain</span>
            </div>
        </div>

        <!-- Champion Highlight Podium -->
        @if($champion)
            <div class="p-6 rounded-2xl bg-gradient-to-r from-emerald-900 via-slate-900 to-emerald-950 text-white text-center shadow-md">
                <div class="text-[10px] uppercase font-black tracking-[0.25em] text-[#CCFF00] mb-1">JUARA 1 TURNAMEN (CHAMPION)</div>
                <div class="text-3xl font-black font-display">{{ $champion->display_name }}</div>
                <div class="text-xs text-emerald-200/90 mt-1 font-medium">{{ $champion->club ?: 'Klub Tenis' }} {{ $champion->city ? '• '.$champion->city : '' }}</div>
            </div>
        @endif

        <!-- Group Standings Table -->
        @if($tournament->groups->isNotEmpty())
            <div class="space-y-4">
                <h2 class="text-sm font-black uppercase tracking-wider text-slate-950 border-b border-slate-300 pb-2">
                    Klasemen Akhir Fase Grup (Standings)
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($tournament->groups as $group)
                        @php $standings = $groupStandings[$group->id] ?? []; @endphp
                        <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                            <div class="font-black text-xs uppercase px-3 py-2 bg-slate-900 text-[#CCFF00] flex items-center justify-between">
                                <span>{{ $group->name }}</span>
                                <span class="text-[10px] text-slate-400 font-semibold">{{ count($standings) }} Peserta</span>
                            </div>
                            <table class="w-full text-[11px] text-left">
                                <thead>
                                    <tr class="border-b border-slate-200 bg-slate-100 text-slate-700 font-black">
                                        <th class="px-3 py-2">Pos</th>
                                        <th class="px-3 py-2">Peserta</th>
                                        <th class="px-2 py-2 text-center">M</th>
                                        <th class="px-2 py-2 text-center">W</th>
                                        <th class="px-2 py-2 text-center">L</th>
                                        <th class="px-2 py-2 text-center">Sets</th>
                                        <th class="px-2.5 py-2 text-center bg-slate-200/60 font-black text-slate-950">PTS</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-medium">
                                    @foreach($standings as $s)
                                        <tr class="{{ $s['rank'] <= ($tournament->advance_per_group ?? 2) ? 'bg-emerald-50/50' : '' }}">
                                            <td class="px-3 py-2 font-black">#{{ $s['rank'] }}</td>
                                            <td class="px-3 py-2 font-bold text-slate-950">{{ $s['participant']->display_name }}</td>
                                            <td class="px-2 py-2 text-center">{{ $s['played'] }}</td>
                                            <td class="px-2 py-2 text-center font-bold text-emerald-700">{{ $s['won'] }}</td>
                                            <td class="px-2 py-2 text-center text-slate-500">{{ $s['lost'] }}</td>
                                            <td class="px-2 py-2 text-center font-mono">{{ $s['sets_won'] }}-{{ $s['sets_lost'] }}</td>
                                            <td class="px-2.5 py-2 text-center font-mono font-black text-xs bg-slate-100 text-slate-950">{{ $s['points'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Knockout Matches / Recap -->
        <div class="space-y-4">
            <h2 class="text-sm font-black uppercase tracking-wider text-slate-950 border-b border-slate-300 pb-2">
                Rekap Hasil Pertandingan Turnamen
            </h2>
            <div class="divide-y divide-slate-200 border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                @forelse($tournament->matches as $m)
                    <div class="p-3.5 flex items-center justify-between text-xs hover:bg-slate-50">
                        <div class="flex items-center gap-3">
                            <span class="px-2 py-1 rounded bg-slate-100 font-black text-[10px] uppercase text-slate-700">
                                {{ strtoupper($m->stage) }}
                            </span>
                            <div>
                                <span class="font-bold {{ $m->winner_id === $m->player1_id ? 'text-slate-950 font-black' : 'text-slate-600' }}">
                                    {{ $m->player1 ? $m->player1->display_name : 'TBD' }}
                                </span>
                                <span class="text-slate-400 font-bold mx-1.5">vs</span>
                                <span class="font-bold {{ $m->winner_id === $m->player2_id ? 'text-slate-950 font-black' : 'text-slate-600' }}">
                                    {{ $m->player2 ? $m->player2->display_name : 'TBD' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            @if($m->is_completed)
                                <span class="font-mono font-black text-xs px-2.5 py-1 rounded bg-emerald-100 text-emerald-900 border border-emerald-200">
                                    {{ $m->score_summary ?: 'Completed' }}
                                </span>
                            @else
                                <span class="text-[11px] font-semibold text-slate-400">Belum Selesai</span>
                            @endif
                            <span class="text-[10px] text-slate-500 font-medium">
                                {{ $m->court ?: 'Court' }} • {{ $m->match_time ? substr($m->match_time, 0, 5) : '-' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-400 text-xs">Belum ada pertandingan dibuat.</div>
                @endforelse
            </div>
        </div>

        <!-- Signatures & Verification -->
        <div class="pt-8 border-t-2 border-slate-900 grid grid-cols-2 gap-8 text-center text-xs">
            <div>
                <p class="text-slate-500 font-medium mb-16">Mengetahui,<br><strong class="text-slate-900">Tournament Director</strong></p>
                <div class="w-48 border-b border-slate-900 mx-auto"></div>
                <p class="mt-1.5 font-bold text-slate-800">Direktur Turnamen</p>
            </div>
            <div>
                <p class="text-slate-500 font-medium mb-16">Diverifikasi oleh,<br><strong class="text-slate-900">Head Referee / ITF Umpire</strong></p>
                <div class="w-48 border-b border-slate-900 mx-auto"></div>
                <p class="mt-1.5 font-bold text-slate-800">Wasit Utama Turnamen</p>
            </div>
        </div>
    </div>

</body>
</html>
