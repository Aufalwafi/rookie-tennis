<div>
    <!-- Deep Green Page Header -->
    <div style="background:#0B2E22;color:#F6F3EA">
        <div style="max-width:1200px;margin:0 auto;padding:40px 24px 56px;display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:24px">
            <div style="display:flex;flex-direction:column;gap:12px">
                <span class="tag" style="border:1.5px solid #D8F24B;color:#D8F24B;background:transparent;align-self:flex-start">
                    Daftar Kompetisi Tenis
                </span>
                <h1 style="font-size:clamp(36px,5vw,56px);line-height:1.05;font-weight:700">Turnamen</h1>
                <p style="font-size:18px;color:#DCE8DF">Pilih turnamen untuk didaftari atau pantau jalannya pertandingan.</p>
            </div>
            @if(auth()->check() && auth()->user()->isAdmin())
                <button onclick="openModal('modalCreateTournament')" class="btn btn-p">
                    + Buat Turnamen Baru
                </button>
            @else
                <a class="btn btn-p" href="{{ route('tournaments.index', ['tournament' => $activeTournament->id, 'tab' => 'participants']) }}">
                    🎾 Daftar Turnamen Ini
                </a>
            @endif
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div style="max-width:1200px;margin:0 auto;padding:36px 24px 0;display:flex;flex-wrap:wrap;align-items:flex-end;gap:20px 24px">
        <div style="flex:1 1 320px">
            <label for="searchTournamentInput">Cari turnamen</label>
            <input id="searchTournamentInput" onkeyup="filterTournamentCards()" type="text" placeholder="Nama turnamen, kategori, atau lokasi venue...">
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:8px" id="statusFilterChips">
            <button class="chip chip-on" onclick="filterByStatus('all', this)">Semua ({{ $tournaments->count() }})</button>
            <button class="chip" onclick="filterByStatus('open', this)">Pendaftaran dibuka</button>
            <button class="chip" onclick="filterByStatus('live', this)">Berlangsung</button>
            <button class="chip" onclick="filterByStatus('done', this)">Selesai</button>
        </div>
    </div>

    <!-- Tournament Cards Grid -->
    <div style="max-width:1200px;margin:0 auto;padding:32px 24px 96px;display:flex;flex-wrap:wrap;gap:24px">
        @foreach($tournaments as $t)
            @php
                $matchesTotal = $t->matches->count();
                $matchesDone = $t->matches->whereIn('status', ['completed', 'walkover'])->count();
                $isLive = $t->matches->where('status', 'live')->count() > 0;
                $isDone = $matchesTotal > 0 && $matchesDone === $matchesTotal;
                
                $statusSlug = $isDone ? 'done' : ($isLive || $matchesDone > 0 ? 'live' : 'open');
                $statusLabel = $isDone ? 'Selesai' : ($isLive ? 'Berlangsung' : ($matchesDone > 0 ? 'Fase Knockout' : 'Pendaftaran dibuka'));
                $statusClass = $isDone ? 't-done' : ($isLive ? 't-live' : 't-open');

                $maxSlots = 32;
                $partCount = $t->participants->count();
                $pct = min(100, round(($partCount / $maxSlots) * 100));
            @endphp
            <div class="card tournament-card hover:shadow-xl transition-all" data-status="{{ $statusSlug }}" style="flex:1 1 340px;max-width:100%;display:flex;flex-direction:column;gap:16px;position:relative">
                <div style="display:flex;justify-content:space-between;align-items:center;gap:12px">
                    <span class="tag {{ $statusClass }}">{{ $statusLabel }}</span>
                    <span style="font-size:13px;color:#4A5A52;font-weight:600">{{ $t->category_label }}</span>
                </div>

                <div>
                    <h3 class="tournament-name" style="font-size:24px;line-height:1.2;margin-bottom:6px;color:#0B2E22">
                        <a href="{{ route('tournaments.index', ['tournament' => $t->id, 'tab' => 'bracket']) }}" class="hover:underline">
                            {{ $t->name }}
                        </a>
                    </h3>
                    <p style="font-size:13px;color:#14503B;font-weight:700">Permukaan: {{ $t->surface }}</p>
                </div>

                <div class="tournament-meta" style="display:flex;flex-direction:column;gap:6px;font-size:14px;color:#4A5A52">
                    <p>📅 {{ $t->start_date ? $t->start_date->format('d M Y') : 'Jadwal ditentukan' }} @if($t->end_date) – {{ $t->end_date->format('d M Y') }} @endif</p>
                    <p>📍 {{ $t->location ?: 'Tennis Stadium' }}</p>
                    <p>⚡ Format: {{ $t->format_label }}</p>
                </div>

                <div style="margin-top:auto;padding-top:10px">
                    <div style="display:flex;justify-content:space-between;font-size:13px;font-weight:700;margin-bottom:6px;color:#0B2E22">
                        <span>Peserta Terdaftar</span>
                        <span>{{ $partCount }} / {{ $maxSlots }} pemain</span>
                    </div>
                    <div style="height:8px;border-radius:999px;background:#E3DFD0;overflow:hidden">
                        <div style="height:8px;border-radius:999px;background:#14503B;width:{{ $pct }}%"></div>
                    </div>
                </div>

                <div style="display:flex;gap:10px;margin-top:6px;align-items:center">
                    <a class="btn btn-d" href="{{ route('tournaments.index', ['tournament' => $t->id, 'tab' => 'bracket']) }}" style="flex:1;min-height:44px;font-size:14px">
                        Buka Turnamen
                    </a>
                    @if(auth()->check() && auth()->user()->isAdmin())
                        <form action="{{ route('tournaments.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus turnamen \'{{ addslashes($t->name) }}\'? Seluruh data bagan, jadwal, dan pesertanya akan ikut terhapus permanen.');" style="margin:0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-s" title="Hapus Turnamen Ini" style="min-height:44px;padding:0 14px;border-color:#E3DFD0;color:#B8442D" onmouseover="this.style.background='#FEE2E2';this.style.borderColor='#EF4444'" onmouseout="this.style.background='';this.style.borderColor='#E3DFD0'">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    @endif
                    @if($activeTournament->id == $t->id)
                        <span class="tag t-open" style="font-size:11px">Aktif</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    function filterTournamentCards() {
        const query = document.getElementById('searchTournamentInput').value.toLowerCase();
        document.querySelectorAll('.tournament-card').forEach(card => {
            const name = card.querySelector('.tournament-name').innerText.toLowerCase();
            const meta = card.querySelector('.tournament-meta').innerText.toLowerCase();
            card.style.display = (name.includes(query) || meta.includes(query)) ? 'flex' : 'none';
        });
    }

    function filterByStatus(status, btn) {
        document.querySelectorAll('#statusFilterChips .chip').forEach(c => c.classList.remove('chip-on'));
        btn.classList.add('chip-on');

        document.querySelectorAll('.tournament-card').forEach(card => {
            if (status === 'all' || card.dataset.status === status) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>
