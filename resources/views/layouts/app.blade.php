<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Rookie Tennis — Aplikasi Turnamen Tenis')</title>

    <!-- Google Fonts: Inter & Space Grotesk -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    <!-- Tailwind CDN for layout utilities -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        display: ['Space Grotesk', 'Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'ui-monospace', 'monospace'],
                    },
                    colors: {
                        tc: {
                            dark: '#0B2E22',
                            darkHover: '#14503B',
                            lime: '#D8F24B',
                            limeHover: '#C9E636',
                            bg: '#F6F3EA',
                            card: '#FFFFFF',
                            border: '#E3DFD0',
                            borderLight: '#EFECE0',
                            muted: '#4A5A52',
                            lightText: '#DCE8DF',
                            live: '#B8442D',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Canvas Confetti for champion celebrations -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.4/dist/confetti.browser.min.js"></script>

    <style>
        body {
            margin: 0;
            background: #F6F3EA;
            color: #0B2E22;
            font-family: 'Inter', system-ui, sans-serif;
        }
        * { box-sizing: border-box; }
        h1, h2, h3, h4, .font-display {
            font-family: 'Space Grotesk', 'Inter', sans-serif;
            letter-spacing: -0.02em;
        }
        p { margin: 0; }
        a { color: inherit; text-decoration: none; }

        /* Navigation pills */
        .nl {
            padding: 0 16px;
            border-radius: 999px;
            font-size: 15px;
            font-weight: 500;
            color: #DCE8DF;
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            transition: all 0.2s ease;
        }
        .nl:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #FFFFFF;
        }
        .nl.on {
            background: #D8F24B;
            color: #0B2E22;
            font-weight: 700;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 0 24px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 15px;
            border: 0;
            cursor: pointer;
            font-family: 'Space Grotesk', inherit;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .btn-p {
            background: #D8F24B;
            color: #0B2E22;
        }
        .btn-p:hover {
            background: #C9E636;
            transform: translateY(-1px);
        }
        .btn-d {
            background: #0B2E22;
            color: #F6F3EA;
        }
        .btn-d:hover {
            background: #14503B;
            color: #FFFFFF;
        }
        .btn-o, .btn-l {
            border: 1.5px solid #F6F3EA;
            color: #F6F3EA;
            background: transparent;
        }
        .btn-o:hover, .btn-l:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #FFFFFF;
        }
        .btn-s {
            min-height: 40px;
            padding: 0 16px;
            font-size: 14px;
            border: 1.5px solid #0B2E22;
            color: #0B2E22;
            background: transparent;
            border-radius: 999px;
            font-weight: 600;
        }
        .btn-s:hover {
            background: #F1F9C9;
        }

        /* Cards */
        .card {
            background: #FFFFFF;
            border-radius: 20px;
            padding: 28px;
            border: 1px solid #E3DFD0;
            box-shadow: 0 1px 4px rgba(11, 46, 34, 0.04);
        }

        /* Status Tags / Badges */
        .tag {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.01em;
        }
        .t-open { background: #D8F24B; color: #0B2E22; }
        .t-live { background: #B8442D; color: #FFFFFF; }
        .t-done { background: #E3DFD0; color: #3A4A42; }

        /* Filter Chips */
        .chip {
            display: inline-flex;
            align-items: center;
            min-height: 40px;
            padding: 0 16px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 600;
            border: 1px solid #E3DFD0;
            background: #FFFFFF;
            color: #0B2E22;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .chip:hover:not(.chip-on) {
            background: #EFECE0;
        }
        .chip.chip-on {
            background: #0B2E22;
            color: #F6F3EA;
            border-color: #0B2E22;
        }

        /* Tables */
        table { border-collapse: collapse; width: 100%; }
        th { text-align: left; font-size: 13px; font-weight: 700; color: #4A5A52; padding: 10px 12px; border-bottom: 1px solid #E3DFD0; }
        td { padding: 13px 12px; border-bottom: 1px solid #EFECE0; font-size: 15px; color: #0B2E22; }

        /* Inputs & Selects */
        input[type="text"], input[type="date"], input[type="time"], input[type="number"], select, textarea {
            border: 1px solid #E3DFD0;
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 15px;
            font-family: inherit;
            background: #FFFFFF;
            color: #0B2E22;
            outline: none;
            transition: border-color 0.2s;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #0B2E22;
            box-shadow: 0 0 0 2px rgba(11, 46, 34, 0.1);
        }
        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #4A5A52;
            margin-bottom: 6px;
        }

        /* Bracket Column & Match Cards */
        .bracket-col {
            flex: 1 0 280px;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            gap: 20px;
        }
        .bracket-card {
            background: #FFFFFF;
            border: 1px solid #E3DFD0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: all 0.2s ease;
        }
        .bracket-card:hover {
            box-shadow: 0 4px 12px rgba(11, 46, 34, 0.08);
        }
        .bracket-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 14px;
            font-size: 13px;
            font-weight: 600;
            color: #4A5A52;
            border-bottom: 1px solid #EFECE0;
            background: #FAF8F2;
        }
        .bracket-player-row {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 14px;
            font-size: 15px;
            color: #4A5A52;
            border-bottom: 1px solid #FAF8F2;
            transition: background 0.15s;
        }
        .bracket-player-row:last-child {
            border-bottom: none;
        }
        .bracket-player-row.winner {
            background: #F1F9C9;
            color: #0B2E22;
            font-weight: 700;
        }
        .bracket-player-seed {
            width: 26px;
            font-size: 12px;
            font-weight: 700;
            color: #8C7F6A;
        }
        .bracket-player-name {
            flex: 1;
            truncate;
        }
        .bracket-player-score {
            width: 24px;
            text-align: center;
            font-variant-numeric: tabular-nums;
            font-family: 'Space Grotesk', monospace;
            font-weight: 700;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #EFECE0; }
        ::-webkit-scrollbar-thumb { background: #C5BFB0; border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: #9E9888; }

        @media print {
            .no-print { display: none !important; }
            body { background: white !important; color: black !important; }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased selection:bg-[#D8F24B] selection:text-[#0B2E22]">

    <!-- Top Navigation Bar (Rookie Tennis Header) -->
    <header class="no-print sticky top-0 z-50 shadow-md" style="background:#0B2E22">
        <div style="max-width:1200px;margin:0 auto;padding:14px 24px;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px 24px">
            
            <!-- Brand Logo -->
            <a href="{{ route('tournaments.index', ['tab' => 'home']) }}" style="display:flex;align-items:center;gap:12px;color:#F6F3EA;min-height:44px" class="group">
                <!-- Tennis Ball SVG -->
                <svg width="34" height="34" viewBox="0 0 32 32" aria-hidden="true" class="transition-transform group-hover:rotate-45 duration-300">
                    <circle cx="16" cy="16" r="15" fill="#D8F24B"></circle>
                    <path d="M4 9c7 3 7 11 0 14M28 9c-7 3-7 11 0 14" stroke="#0B2E22" stroke-width="2.2" fill="none"></path>
                </svg>
                <div class="flex items-center gap-2">
                    <span style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:22px;letter-spacing:-0.02em">Rookie <span style="color:#D8F24B">Tennis</span></span>
                    <span class="tag" style="border:1.5px solid #D8F24B;color:#D8F24B;font-size:11px;padding:2px 8px">Turnamen</span>
                </div>
            </a>

            <!-- Navigation Links -->
            @php
                $curTab = $activeTab ?? request('tab', 'home');
                $tId = isset($activeTournament) ? $activeTournament->id : (isset($tournaments) && $tournaments->isNotEmpty() ? $tournaments->first()->id : null);
            @endphp
            <nav style="display:flex;flex-wrap:wrap;align-items:center;gap:4px">
                <a class="nl {{ $curTab === 'home' ? 'on' : '' }}" href="{{ route('tournaments.index', array_filter(['tournament' => $tId, 'tab' => 'home'])) }}">Beranda</a>
                <a class="nl {{ $curTab === 'tournaments' ? 'on' : '' }}" href="{{ route('tournaments.index', array_filter(['tournament' => $tId, 'tab' => 'tournaments'])) }}">Turnamen</a>
                <a class="nl {{ $curTab === 'participants' ? 'on' : '' }}" href="{{ route('tournaments.index', array_filter(['tournament' => $tId, 'tab' => 'participants'])) }}">Peserta</a>
                <a class="nl {{ $curTab === 'group' ? 'on' : '' }}" href="{{ route('tournaments.index', array_filter(['tournament' => $tId, 'tab' => 'group'])) }}">Grup</a>
                <a class="nl {{ $curTab === 'bracket' ? 'on' : '' }}" href="{{ route('tournaments.index', array_filter(['tournament' => $tId, 'tab' => 'bracket'])) }}">Bracket</a>
                <a class="nl {{ $curTab === 'schedule' ? 'on' : '' }}" href="{{ route('tournaments.index', array_filter(['tournament' => $tId, 'tab' => 'schedule'])) }}">Jadwal</a>
            </nav>

            <!-- Right Controls: Tournament Selector, Settings & Create Button -->
            <div style="display:flex;align-items:center;gap:10px">
                @if(isset($tournaments) && $tournaments->isNotEmpty())
                    <div class="relative">
                        <select onchange="window.location.href = '{{ route('tournaments.index') }}?tournament=' + this.value + '&tab={{ $curTab }}'"
                            style="background:#14503B;color:#F6F3EA;border:1px solid rgba(255,255,255,0.18);border-radius:999px;padding:8px 30px 8px 14px;font-size:13px;font-weight:600;cursor:pointer;outline:none;"
                            class="hover:bg-[#1B6149] transition-colors appearance-none">
                            @foreach($tournaments as $t)
                                <option value="{{ $t->id }}" {{ (isset($activeTournament) && $activeTournament->id == $t->id) ? 'selected' : '' }} style="background:#0B2E22;color:#F6F3EA">
                                    🎾 {{ Str::limit($t->name, 22) }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="absolute right-3 top-3 w-3.5 h-3.5 text-[#DCE8DF] pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                    </div>

                    @if(isset($activeTournament))
                        <button type="button" onclick="openModal('modalEditTournament')" class="btn btn-o hover:bg-white/10 hover:text-white" title="Pengaturan & Hapus Turnamen" style="min-height:38px;padding:0 14px;font-size:13px;border-color:rgba(255,255,255,0.25);color:#DCE8DF">
                            ⚙️ Atur / Hapus
                        </button>
                    @endif
                @endif

                <button onclick="openModal('modalCreateTournament')" class="btn btn-p" style="font-size:14px;min-height:44px;padding:0 20px">
                    + Buat Turnamen
                </button>
            </div>

        </div>
    </header>

    <!-- Flash Messages -->
    <div style="max-width:1200px;margin:16px auto 0;padding:0 24px;width:100%" class="no-print">
        @if(session('success'))
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 18px;border-radius:14px;background:#D8F24B;color:#0B2E22;font-weight:600;font-size:14px;box-shadow:0 2px 8px rgba(0,0,0,0.06)">
                <div style="display:flex;align-items:center;gap:10px">
                    <span style="font-size:16px">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" style="background:none;border:none;color:#0B2E22;font-weight:700;font-size:16px;cursor:pointer">✕</button>
            </div>
        @endif
        @if(session('error'))
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 18px;border-radius:14px;background:#B8442D;color:#FFFFFF;font-weight:600;font-size:14px;box-shadow:0 2px 8px rgba(0,0,0,0.06)">
                <div style="display:flex;align-items:center;gap:10px">
                    <span style="font-size:16px">⚠</span>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" style="background:none;border:none;color:#FFFFFF;font-weight:700;font-size:16px;cursor:pointer">✕</button>
            </div>
        @endif
    </div>

    <!-- Main Content Body -->
    <main class="flex-1 w-full">
        @yield('content')
    </main>

    <!-- Footer (Rookie Tennis) -->
    <footer class="no-print" style="background:#0B2E22;color:#DCE8DF;margin-top:auto">
        <div style="max-width:1200px;margin:0 auto;padding:48px 24px 36px">
            <div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:24px;border-bottom:1px solid rgba(255,255,255,0.1);padding-bottom:28px">
                <div style="display:flex;align-items:center;gap:12px">
                    <svg width="28" height="28" viewBox="0 0 32 32" aria-hidden="true">
                        <circle cx="16" cy="16" r="15" fill="#D8F24B"></circle>
                        <path d="M4 9c7 3 7 11 0 14M28 9c-7 3-7 11 0 14" stroke="#0B2E22" stroke-width="2.2" fill="none"></path>
                    </svg>
                    <span style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:20px;color:#F6F3EA">Rookie <span style="color:#D8F24B">Tennis</span></span>
                </div>

                <div style="display:flex;flex-wrap:wrap;gap:20px;font-size:14px;font-weight:500">
                    <a href="{{ route('tournaments.index', array_filter(['tournament' => $tId, 'tab' => 'home'])) }}" class="hover:text-white transition-colors">Beranda</a>
                    <a href="{{ route('tournaments.index', array_filter(['tournament' => $tId, 'tab' => 'tournaments'])) }}" class="hover:text-white transition-colors">Turnamen</a>
                    <a href="{{ route('tournaments.index', array_filter(['tournament' => $tId, 'tab' => 'participants'])) }}" class="hover:text-white transition-colors">Peserta</a>
                    <a href="{{ route('tournaments.index', array_filter(['tournament' => $tId, 'tab' => 'group'])) }}" class="hover:text-white transition-colors">Grup</a>
                    <a href="{{ route('tournaments.index', array_filter(['tournament' => $tId, 'tab' => 'bracket'])) }}" class="hover:text-white transition-colors">Bracket</a>
                    <a href="{{ route('tournaments.index', array_filter(['tournament' => $tId, 'tab' => 'schedule'])) }}" class="hover:text-white transition-colors">Jadwal</a>
                </div>

                @if(isset($activeTournament))
                    <a href="{{ route('tournaments.report', $activeTournament->id) }}" target="_blank" class="btn btn-o" style="min-height:38px;padding:0 16px;font-size:13px">
                        Cetak Laporan
                    </a>
                @endif
            </div>

            <div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:12px;padding-top:24px;font-size:13px;color:#8F9E96">
                <span>© 2026 Rookie Tennis. Aplikasi Turnamen Tenis.</span>
                <div style="display:flex;gap:16px">
                    <span>🎾 Multi-Surface</span>
                    <span>⚡ Group & Knockout</span>
                    <span>📢 Live Score</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Global Modal Helpers -->
    <script>
        function openModal(id) {
            const m = document.getElementById(id);
            if (m) {
                m.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }
        function closeModal(id) {
            const m = document.getElementById(id);
            if (m) {
                m.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                document.querySelectorAll('[id^="modal"]').forEach(m => {
                    if (!m.classList.contains('hidden')) m.classList.add('hidden');
                });
                document.body.style.overflow = '';
            }
        });
    </script>

    @stack('modals')
    @stack('scripts')
</body>
</html>
