@php
    $participants = $activeTournament->participants;
    $isDoubles = str_contains($activeTournament->category, 'doubles');
    $maxSlots = 32;
    $pct = min(100, round(($participants->count() / $maxSlots) * 100));
    $remaining = max(0, $maxSlots - $participants->count());
@endphp

<div>
    <!-- Deep Green Hero Banner (from Pendaftaran Peserta.html) -->
    <div style="background:#0B2E22;color:#F6F3EA">
        <div style="max-width:1200px;margin:0 auto;padding:40px 24px 56px;display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:24px">
            <div style="display:flex;flex-direction:column;gap:12px;flex:1 1 420px">
                <span class="tag t-open" style="align-self:flex-start">Pendaftaran peserta aktif</span>
                <h1 style="font-size:clamp(34px,5vw,54px);line-height:1.05;font-weight:700">{{ $activeTournament->name }}</h1>
                <p style="font-size:17px;color:#DCE8DF">
                    {{ $activeTournament->location ?: 'Tennis Stadium' }} · {{ $activeTournament->category_label }} · {{ $activeTournament->format_label }}
                </p>
            </div>

            <!-- Quota Progress Indicator -->
            <div style="flex:1 1 280px;max-width:380px">
                <div style="display:flex;justify-content:space-between;font-weight:700;margin-bottom:8px;font-size:15px">
                    <span>Kuota peserta</span>
                    <span style="color:#D8F24B;font-family:'Space Grotesk',sans-serif">{{ $participants->count() }} / {{ $maxSlots }}</span>
                </div>
                <div style="height:10px;border-radius:999px;background:rgba(255,255,255,.2);overflow:hidden">
                    <div style="height:10px;border-radius:999px;background:#D8F24B;width:{{ $pct }}%"></div>
                </div>
                <p style="font-size:13px;color:#DCE8DF;margin-top:8px">
                    Tersisa {{ $remaining }} slot tersedia · Registrasi dibuka untuk umum
                </p>
            </div>
        </div>
    </div>

    <!-- Role Experience Callout (User / Admin / Guest) -->
    <div style="max-width:1200px;margin:28px auto 0;padding:0 24px">
        @if(auth()->check() && !auth()->user()->isAdmin())
            @if(isset($userParticipant) && $userParticipant)
                <div class="card" style="background:#F1F9C9;border:2px solid #0B2E22;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:16px;padding:20px 24px">
                    <div style="display:flex;align-items:center;gap:14px">
                        <div style="width:48px;height:48px;border-radius:999px;background:#0B2E22;color:#D8F24B;display:flex;align-items:center;justify-content:center;font-size:24px">✓</div>
                        <div>
                            <span class="tag" style="background:#0B2E22;color:#D8F24B;font-size:11px;font-weight:700">STATUS: TERDAFTAR</span>
                            <h3 style="font-size:20px;color:#0B2E22;margin:4px 0 2px">Anda terdaftar sebagai <strong>{{ $userParticipant->name }}</strong></h3>
                            <p style="font-size:13px;color:#4A5A52">Seed: {{ $userParticipant->seed ? '#' . $userParticipant->seed : 'Non-Seed' }} • Klub: {{ $userParticipant->club ?: 'Independen' }} • No. WA: {{ $userParticipant->phone }}</p>
                        </div>
                    </div>
                    <a href="{{ route('tournaments.index', ['tournament' => $activeTournament->id, 'tab' => 'schedule']) }}" class="btn btn-d" style="min-height:42px;font-size:14px">
                        🎾 Cek Jadwal Pertandingan Saya
                    </a>
                </div>
            @else
                <div class="card" style="background:#FFFFFF;border:2px solid #D8F24B;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:20px;padding:24px 28px;box-shadow:0 8px 24px rgba(11,46,34,0.08)">
                    <div style="flex:1 1 360px">
                        <span class="tag t-open" style="font-size:11px">REGISTRASI PESERTA TERBUKA</span>
                        <h3 style="font-size:24px;color:#0B2E22;margin:6px 0 4px">Daftar Ikut Turnamen Ini</h3>
                        <p style="font-size:14px;color:#4A5A52">
                            Daftar langsung menggunakan profil akun Anda: <strong>{{ auth()->user()->name }}</strong> (WhatsApp: {{ auth()->user()->phone ?: '-' }}). Slot tersisa: <strong>{{ $remaining }}</strong> pemain.
                        </p>
                    </div>
                    <form action="{{ route('participants.self_register', $activeTournament->id) }}" method="POST" style="display:flex;flex-wrap:wrap;align-items:center;gap:10px">
                        @csrf
                        <input type="text" name="club" placeholder="Klub / Asal (Opsional)" style="min-height:42px;width:180px;font-size:14px">
                        <button type="submit" class="btn btn-p" style="min-height:42px;font-size:14px">
                            🚀 Daftar Sekarang (1-Klik)
                        </button>
                    </form>
                </div>
            @endif
        @elseif(auth()->guest())
            <div class="card" style="background:#FAF8F2;border:1.5px solid #E3DFD0;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:16px;padding:20px 24px">
                <div>
                    <h3 style="font-size:18px;color:#0B2E22;margin:0 0 4px">Ingin ikut bertanding di turnamen ini?</h3>
                    <p style="font-size:14px;color:#4A5A52;margin:0">Masuk atau buat akun peserta baru untuk mendaftar secara online.</p>
                </div>
                <div style="display:flex;gap:10px">
                    <a href="{{ route('login') }}" class="btn btn-s" style="min-height:38px;padding:0 16px;font-size:13px">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-p" style="min-height:38px;padding:0 16px;font-size:13px">Daftar Akun Peserta</a>
                </div>
            </div>
        @endif
    </div>

    <!-- Toolbar: Search & Auto Generator Buttons -->
    <div style="max-width:1200px;margin:0 auto;padding:16px 24px 0;display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:16px 24px">
        <div style="flex:1 1 340px">
            <label for="participantSearchInput">Cari peserta</label>
            <input id="participantSearchInput" onkeyup="filterParticipants()" type="text" placeholder="Ketik nama pemain, klub, atau kota asal...">
        </div>

        @if(auth()->check() && auth()->user()->isAdmin())
            <div style="display:flex;flex-wrap:wrap;gap:10px;align-items:center">
                <!-- Sample Auto Generator -->
                <div style="display:flex;align-items:center;gap:6px;background:#FFFFFF;border:1px solid #E3DFD0;border-radius:999px;padding:4px 10px">
                    <span style="font-size:12px;font-weight:700;color:#4A5A52">Isi Otomatis:</span>
                    <form action="{{ route('participants.sample', $activeTournament->id) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="count" value="8">
                        <button type="submit" class="btn btn-s" style="min-height:30px;padding:0 10px;font-size:12px">
                            +8 Pemain
                        </button>
                    </form>
                    <form action="{{ route('participants.sample', $activeTournament->id) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="count" value="16">
                        <button type="submit" class="btn btn-s" style="min-height:30px;padding:0 10px;font-size:12px">
                            +16 Pemain
                        </button>
                    </form>
                </div>

                <button onclick="document.getElementById('modalAddParticipant').classList.remove('hidden')" class="btn btn-p">
                    + Tambah Peserta Baru
                </button>
            </div>
        @endif
    </div>

    <!-- Table of Participants -->
    <div style="max-width:1200px;margin:0 auto;padding:24px 24px 96px">
        <div class="card" style="padding:0;overflow:hidden">
            @if($participants->isEmpty())
                <div style="text-align:center;padding:64px 24px">
                    <div style="font-size:44px;margin-bottom:12px">🎾</div>
                    <h3 style="font-size:22px;color:#0B2E22;margin-bottom:6px">Belum Ada Peserta Terdaftar</h3>
                    <p style="color:#4A5A52;font-size:14px;max-width:380px;margin:0 auto 20px">
                        Tambahkan pemain secara manual melalui formulir atau gunakan tombol isi otomatis di atas untuk mengisi data simulasi turnamen.
                    </p>
                    <button onclick="document.getElementById('modalAddParticipant').classList.remove('hidden')" class="btn btn-p">
                        Tambah Peserta Pertama
                    </button>
                </div>
            @else
                <div style="overflow-x:auto">
                    <table id="participantsTable">
                        <thead>
                            <tr style="background:#FAF8F2">
                                <th style="width:70px">Seed</th>
                                <th>Nama Peserta / Pasangan</th>
                                <th>Klub &amp; Kota Asal</th>
                                <th>Nomor Kontak</th>
                                <th>Peringkat</th>
                                <th style="text-align:right;padding-right:24px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($participants as $p)
                                <tr class="participant-row">
                                    <td>
                                        @if($p->seed)
                                            <span class="tag" style="background:#0B2E22;color:#D8F24B;font-weight:700">
                                                #{{ $p->seed }}
                                            </span>
                                        @else
                                            <span style="color:#8A9990;font-size:13px">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div style="display:flex;align-items:center;gap:10px">
                                            <div style="width:36px;height:36px;border-radius:10px;background:#0B2E22;color:#D8F24B;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;font-family:'Space Grotesk',sans-serif">
                                                {{ strtoupper(substr($p->name, 0, 1)) }}{{ $p->partner_name ? strtoupper(substr($p->partner_name, 0, 1)) : strtoupper(substr(strrchr($p->name, ' ') ?: ' ', 1, 1)) }}
                                            </div>
                                            <div>
                                                <div class="participant-name" style="font-weight:700;color:#0B2E22;font-size:15px;display:flex;align-items:center;gap:6px">
                                                    <span>{{ $p->display_name }}</span>
                                                    @if(isset($userParticipant) && $userParticipant && $userParticipant->id === $p->id)
                                                        <span class="tag" style="background:#D8F24B;color:#0B2E22;font-size:10px;padding:1px 6px;font-weight:700">🌟 Anda</span>
                                                    @endif
                                                </div>
                                                @if($isDoubles && $p->partner_name)
                                                    <div style="font-size:12px;color:#4A5A52">Pasangan: {{ $p->partner_name }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="participant-club" style="font-weight:600;color:#0B2E22;font-size:14px">{{ $p->club ?: 'Independen' }}</div>
                                        @if($p->city)
                                            <div style="font-size:12px;color:#4A5A52">{{ $p->city }}</div>
                                        @endif
                                    </td>

                                    <td style="font-family:'JetBrains Mono',monospace;font-size:13px;color:#4A5A52">
                                        {{ $p->phone ?: '-' }}
                                    </td>

                                    <td style="font-family:'Space Grotesk',sans-serif;font-weight:600;color:#0B2E22">
                                        {{ $p->ranking ? 'Peringkat #' . $p->ranking : '-' }}
                                    </td>

                                    <td style="text-align:right;padding-right:24px">
                                        @if(auth()->check() && auth()->user()->isAdmin())
                                            <div style="display:flex;justify-content:flex-end;gap:8px">
                                                <button type="button" onclick="openEditParticipantModal({{ $p->id }}, '{{ addslashes($p->name) }}', '{{ addslashes($p->partner_name ?? '') }}', '{{ $p->seed }}', '{{ addslashes($p->club ?? '') }}', '{{ addslashes($p->city ?? '') }}', '{{ addslashes($p->phone ?? '') }}', '{{ $p->ranking }}')"
                                                    class="btn btn-s" style="min-height:32px;padding:0 12px;font-size:12px">
                                                    Edit
                                                </button>

                                                <form action="{{ route('participants.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus peserta ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-s" style="min-height:32px;padding:0 10px;font-size:12px;border-color:#B8442D;color:#B8442D">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="tag {{ $p->seed ? 't-live' : 't-open' }}" style="font-size:11px">
                                                {{ $p->seed ? 'Seed #' . $p->seed : 'Terdaftar' }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function filterParticipants() {
        const query = document.getElementById('participantSearchInput').value.toLowerCase();
        document.querySelectorAll('.participant-row').forEach(row => {
            const name = row.querySelector('.participant-name').innerText.toLowerCase();
            const club = row.querySelector('.participant-club').innerText.toLowerCase();
            row.style.display = (name.includes(query) || club.includes(query)) ? '' : 'none';
        });
    }
</script>
