@php
    $matches = $activeTournament->matches->sortBy([
        ['match_date', 'asc'],
        ['match_time', 'asc'],
        ['order', 'asc']
    ]);
    $uniqueDates = $matches->pluck('match_date')->filter()->unique()->sort();
@endphp

<div>
    <!-- Deep Green Hero Banner (from Jadwal dan Input Hasil.html) -->
    <div style="background:#0B2E22;color:#F6F3EA">
        <div style="max-width:1200px;margin:0 auto;padding:40px 24px 56px;display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:24px">
            <div style="display:flex;flex-direction:column;gap:12px;flex:1 1 420px">
                <span class="tag t-open" style="align-self:flex-start">Jadwal turnamen</span>
                <h1 style="font-size:clamp(34px,5vw,54px);line-height:1.05;font-weight:700">Jadwal &amp; input hasil</h1>
                <p style="font-size:17px;color:#DCE8DF">
                    {{ $activeTournament->name }} · {{ $matches->count() }} pertandingan · Atur jam dan alokasi lapangan tanpa bentrok
                </p>
            </div>
            <div>
                <a class="btn btn-p" href="{{ route('tournaments.index', ['tournament' => $activeTournament->id, 'tab' => 'bracket']) }}">
                    Lihat bracket knockout
                </a>
            </div>
        </div>
    </div>

    <!-- Filter & Toolbar (from Jadwal dan Input Hasil.html) -->
    <div style="max-width:1200px;margin:0 auto;padding:36px 24px 0;display:flex;flex-wrap:wrap;align-items:flex-end;gap:16px 24px">
        <!-- Date & Personal Chips -->
        <div style="display:flex;flex-wrap:wrap;gap:8px" id="scheduleDateChips">
            <button class="chip chip-on" onclick="filterScheduleByDate('all', this)">Semua tanggal</button>
            @if(isset($userParticipant) && $userParticipant)
                <button class="chip" id="myMatchesChip" onclick="toggleMyMatches(this, '{{ strtolower(addslashes($userParticipant->name)) }}')" style="border-color:#0B2E22;font-weight:700">
                    🎾 Pertandingan Saya
                </button>
            @endif
            @foreach($uniqueDates as $d)
                <button class="chip" onclick="filterScheduleByDate('{{ $d->format('Y-m-d') }}', this)">
                    {{ $d->format('d M') }}
                </button>
            @endforeach
        </div>

        <!-- Court Select -->
        <div style="flex:0 1 200px">
            <label for="filterCourtSelect">Lapangan</label>
            <select id="filterCourtSelect" onchange="applyScheduleFilters()">
                <option value="all">Semua lapangan</option>
                @foreach($activeTournament->courts ?: [] as $c)
                    <option value="{{ $c }}">{{ $c }}</option>
                @endforeach
            </select>
        </div>

        <!-- Stage Select -->
        <div style="flex:0 1 180px">
            <label for="filterStageSelect">Babak</label>
            <select id="filterStageSelect" onchange="applyScheduleFilters()">
                <option value="all">Semua babak</option>
                <option value="group">Fase Grup</option>
                <option value="quarterfinal">Perempat final</option>
                <option value="semifinal">Semifinal</option>
                <option value="final">Final</option>
                <option value="third_place">Juara 3</option>
            </select>
        </div>
    </div>

    <!-- Main Schedule Content Area (2 Columns or List) -->
    <div style="max-width:1200px;margin:0 auto;padding:24px 24px 96px;display:flex;flex-wrap:wrap;gap:24px;align-items:flex-start">
        
        <!-- Left: Match List Card -->
        <div class="card" style="flex:2 1 560px;padding:24px">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
                <h2 style="font-size:24px;color:#0B2E22">Daftar pertandingan</h2>
                <span style="font-size:14px;color:#4A5A52;font-weight:600">{{ $matches->count() }} match</span>
            </div>

            <div id="scheduleMatchesList" style="display:flex;flex-direction:column">
                @forelse($matches as $m)
                    @php
                        $isCompleted = $m->status === 'completed' || $m->status === 'walkover';
                        $isLive = $m->status === 'live';
                        $p1W = $m->winner_id && $m->winner_id == $m->player1_id;
                        $p2W = $m->winner_id && $m->winner_id == $m->player2_id;
                    @endphp

                    <div class="schedule-row" 
                        data-date="{{ $m->match_date ? $m->match_date->format('Y-m-d') : '' }}"
                        data-court="{{ $m->court }}"
                        data-stage="{{ $m->stage }}"
                        style="display:flex;flex-wrap:wrap;gap:16px;align-items:center;padding:16px 12px;border-bottom:1px solid #EFECE0;border-radius:12px;transition:background 0.2s;{{ $isLive ? 'background:#FBF0EC;' : '' }}">
                        
                        <!-- Time -->
                        <span style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:18px;width:64px;color:#0B2E22">
                            {{ $m->match_time ? \Carbon\Carbon::parse($m->match_time)->format('H.i') : '10.00' }}
                        </span>

                        <!-- Match info -->
                        <div style="flex:1;min-width:200px">
                            <p style="font-weight:700;font-size:15px;color:#0B2E22">
                                <span style="{{ $p1W ? 'color:#14503B;text-decoration:underline;' : '' }}">{{ $m->player1 ? $m->player1->display_name : 'TBD' }}</span>
                                <span style="color:#8A9990;font-weight:400;margin:0 4px">vs</span>
                                <span style="{{ $p2W ? 'color:#14503B;text-decoration:underline;' : '' }}">{{ $m->player2 ? $m->player2->display_name : 'TBD' }}</span>
                            </p>
                            <p style="font-size:13px;color:#4A5A52;margin-top:2px">
                                {{ $m->stage_label }} · {{ $m->court ?: 'Court 1' }}
                                @if($m->match_date) · {{ $m->match_date->format('d M Y') }} @endif
                                @if($m->score_display) · <strong style="color:#0B2E22">{{ $m->score_display }}</strong> @endif
                            </p>
                        </div>

                        <!-- Status Tag -->
                        @if($isLive)
                            <span class="tag t-live">Berlangsung</span>
                        @elseif($isCompleted)
                            <span class="tag t-done">Selesai</span>
                        @else
                            <span class="tag t-open">Terjadwal</span>
                        @endif

                        <!-- Action Buttons -->
                        <div style="display:flex;align-items:center;gap:8px">
                            @if(auth()->check() && auth()->user()->isAdmin())
                                <button onclick="openEditScheduleModal({{ $m->id }}, '{{ $m->match_date ? $m->match_date->format('Y-m-d') : '' }}', '{{ $m->match_time ?: '' }}', '{{ $m->court ?: '' }}')"
                                    class="btn btn-s" style="min-height:36px;padding:0 12px;font-size:13px" title="Atur Jam & Lapangan">
                                    📅 Jam
                                </button>

                                @if($m->player1_id && $m->player2_id)
                                    <button onclick="openLiveUmpireModal({{ $m->id }}, {{ $m->player1_id }}, {{ $m->player2_id }}, '{{ addslashes($m->player1->display_name ?? '') }}', '{{ addslashes($m->player2->display_name ?? '') }}', '{{ addslashes($m->court ?: 'Court') }}')"
                                        class="btn btn-s" style="min-height:36px;padding:0 12px;font-size:13px;border-color:#B8442D;color:#B8442D" title="Wasit Lapangan Langsung">
                                        🔴 Live
                                    </button>

                                    <button onclick="openScoreModal({{ $m->id }}, {{ $m->player1_id }}, {{ $m->player2_id }}, '{{ addslashes($m->player1->display_name ?? '') }}', '{{ addslashes($m->player2->display_name ?? '') }}', {{ json_encode($m->sets ?? []) }}, '{{ $m->winner_id }}', '{{ $m->status }}', '{{ addslashes($m->notes ?? '') }}')"
                                        class="btn {{ $isLive ? 'btn-d' : 'btn-s' }}" style="min-height:36px;padding:0 14px;font-size:13px">
                                        {{ $isCompleted ? 'Ubah hasil' : 'Input hasil' }}
                                    </button>
                                @endif
                            @else
                                @if(isset($userParticipant) && $userParticipant && ($m->player1_id == $userParticipant->id || $m->player2_id == $userParticipant->id))
                                    <span class="tag" style="background:#D8F24B;color:#0B2E22;font-size:11px;font-weight:700">🌟 Laga Anda</span>
                                @endif
                                <span class="tag {{ $isLive ? 't-live' : ($isCompleted ? 't-done' : 't-open') }}" style="font-size:11px">
                                    {{ $isLive ? '🔴 Live' : ($isCompleted ? 'Selesai' : 'Terjadwal') }}
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;padding:48px 16px;color:#8A9990">
                        Belum ada jadwal pertandingan. Buat pembagian grup atau bagan knockout untuk menjadwalkan.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right: Fast Scoring Quick Widget (from Jadwal dan Input Hasil.html) -->
        <div class="card" style="flex:1 1 340px;position:sticky;top:90px">
            <span class="tag t-open">Panduan Wasit &amp; Skor</span>
            <h2 style="font-size:24px;margin:12px 0 4px;color:#0B2E22">Input Hasil Pertandingan</h2>
            <p style="color:#4A5A52;font-size:14px;margin-bottom:18px;line-height:1.5">
                Klik tombol <strong>"Input hasil"</strong> pada pertandingan mana pun untuk mencatat skor game per set, atau <strong>"🔴 Live"</strong> untuk membuka scoreboard wasit lapangan profesional.
            </p>

            <div style="background:#FAF8F2;border:1px solid #E3DFD0;border-radius:14px;padding:16px;margin-bottom:18px">
                <p style="font-size:13px;font-weight:700;color:#0B2E22;margin-bottom:6px">Aturan Penentuan Juara:</p>
                <ul style="margin:0;padding-left:18px;font-size:13px;color:#4A5A52;line-height:1.6">
                    <li>Selesai setelah pemain mencapai 2 set kemenangan (Best of 3).</li>
                    <li>Pemenang babak knockout otomatis melaju ke bagan selanjutnya.</li>
                    <li>Klasemen grup diperbarui otomatis seketika pertandingan selesai.</li>
                </ul>
            </div>

            <a class="btn btn-d" href="{{ route('tournaments.index', ['tournament' => $activeTournament->id, 'tab' => 'bracket']) }}" style="width:100%">
                Buka Bagan Knockout
            </a>
        </div>

    </div>
</div>

<script>
    let currentScheduleDate = 'all';
    let filterOnlyMine = false;
    let myNameFilter = '';

    function toggleMyMatches(btn, name) {
        filterOnlyMine = !filterOnlyMine;
        myNameFilter = name;
        if (filterOnlyMine) {
            btn.classList.add('chip-on');
        } else {
            btn.classList.remove('chip-on');
        }
        applyScheduleFilters();
    }

    function filterScheduleByDate(date, btn) {
        currentScheduleDate = date;
        document.querySelectorAll('#scheduleDateChips .chip').forEach(c => c.classList.remove('chip-on'));
        btn.classList.add('chip-on');
        applyScheduleFilters();
    }

    function applyScheduleFilters() {
        const court = document.getElementById('filterCourtSelect').value;
        const stage = document.getElementById('filterStageSelect').value;

        document.querySelectorAll('.schedule-row').forEach(row => {
            const rowDate = row.dataset.date;
            const rowCourt = row.dataset.court;
            const rowStage = row.dataset.stage;

            const dateMatch = (currentScheduleDate === 'all' || rowDate === currentScheduleDate);
            const courtMatch = (court === 'all' || rowCourt === court);
            const stageMatch = (stage === 'all' || rowStage === stage);
            const mineMatch = !filterOnlyMine || row.innerText.toLowerCase().includes(myNameFilter);

            row.style.display = (dateMatch && courtMatch && stageMatch && mineMatch) ? 'flex' : 'none';
        });
    }
</script>
