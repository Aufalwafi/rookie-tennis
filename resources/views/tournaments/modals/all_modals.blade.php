<!-- 1. Modal Buat Turnamen Baru (from Buat Turnamen.html) -->
<div id="modalCreateTournament" onclick="if(event.target===this) closeModal('modalCreateTournament')" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 transition-all" style="background:rgba(11,46,34,0.65);backdrop-filter:blur(5px)">
    <div class="modal-panel w-full max-w-2xl bg-white rounded-3xl overflow-hidden max-h-[92vh] flex flex-col shadow-2xl" style="border:1px solid #E3DFD0">
        <!-- Header -->
        <div style="background:#0B2E22;color:#F6F3EA;padding:20px 24px;display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:12px">
                <svg width="28" height="28" viewBox="0 0 32 32" aria-hidden="true">
                    <circle cx="16" cy="16" r="15" fill="#D8F24B"></circle>
                    <path d="M4 9c7 3 7 11 0 14M28 9c-7 3-7 11 0 14" stroke="#0B2E22" stroke-width="2.2" fill="none"></path>
                </svg>
                <div>
                    <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:20px;margin:0;color:#F6F3EA">Buat Turnamen Tenis Baru</h3>
                    <p style="font-size:13px;color:#DCE8DF;margin:2px 0 0">Atur kategori, permukaan lapangan, dan format pertandingan</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modalCreateTournament')" style="background:rgba(255,255,255,0.15);border:none;color:#F6F3EA;width:34px;height:34px;border-radius:999px;cursor:pointer;font-weight:700;display:flex;align-items:center;justify-content:center">✕</button>
        </div>

        <!-- Form Body -->
        <form action="{{ route('tournaments.store') }}" method="POST" style="padding:24px;overflow-y:auto;display:flex;flex-direction:column;gap:18px">
            @csrf
            <div>
                <label for="create_name">Nama Turnamen *</label>
                <input type="text" id="create_name" name="name" required placeholder="Contoh: Kejuaraan Tenis Terbuka 2026">
            </div>

            <div>
                <label for="create_desc">Deskripsi Singkat</label>
                <textarea id="create_desc" name="description" rows="2" placeholder="Kompetisi tenis amatir & prestasi level nasional..."></textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div>
                    <label for="create_category">Kategori Pertandingan</label>
                    <select id="create_category" name="category">
                        <option value="mens_singles">Tunggal Putra (Men's Singles)</option>
                        <option value="womens_singles">Tunggal Putri (Women's Singles)</option>
                        <option value="mens_doubles">Ganda Putra (Men's Doubles)</option>
                        <option value="womens_doubles">Ganda Putri (Women's Doubles)</option>
                        <option value="mixed_doubles">Ganda Campuran (Mixed Doubles)</option>
                    </select>
                </div>
                <div>
                    <label for="create_surface">Permukaan Lapangan</label>
                    <select id="create_surface" name="surface">
                        <option value="Hard Court">Hard Court (Lapangan Keras)</option>
                        <option value="Clay">Clay (Tanah Liat - Roland Garros)</option>
                        <option value="Grass">Grass (Rumput - Wimbledon)</option>
                        <option value="Carpet">Carpet (Indoor)</option>
                    </select>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div>
                    <label for="create_format">Format Turnamen</label>
                    <select id="create_format" name="format">
                        <option value="group_knockout">Fase Grup + Knockout (Rekomendasi)</option>
                        <option value="knockout">Sistem Gugur (Knockout Murni)</option>
                        <option value="round_robin">Round Robin</option>
                    </select>
                </div>
                <div>
                    <label for="create_match_rule">Aturan Set Pertandingan</label>
                    <select id="create_match_rule" name="match_rule">
                        <option value="best_of_3">Terbaik dari 3 Set (Best of 3)</option>
                        <option value="best_of_1">Terbaik dari 1 Set (Single Set)</option>
                        <option value="pro_set_8">Pro Set 8 Games (First to 8)</option>
                    </select>
                </div>
            </div>

            <!-- Group Stage Config -->
            <div style="background:#FAF8F2;border:1px solid #E3DFD0;border-radius:16px;padding:16px;display:flex;flex-direction:column;gap:10px">
                <span style="font-size:12px;font-weight:700;color:#0B2E22;text-transform:uppercase;letter-spacing:0.03em">
                    ⚡ Pengaturan Fase Grup (Bila format Grup digunakan)
                </span>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div>
                        <label for="create_groups_count">Jumlah Grup</label>
                        <input type="number" id="create_groups_count" name="groups_count" value="2" min="2" max="8" style="text-align:center">
                    </div>
                    <div>
                        <label for="create_advance">Lolos ke Knockout / Grup</label>
                        <input type="number" id="create_advance" name="advance_per_group" value="2" min="1" max="4" style="text-align:center">
                    </div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div>
                    <label for="create_start_date">Tanggal Mulai</label>
                    <input type="date" id="create_start_date" name="start_date" value="{{ date('Y-m-d') }}">
                </div>
                <div>
                    <label for="create_end_date">Tanggal Selesai</label>
                    <input type="date" id="create_end_date" name="end_date" value="{{ date('Y-m-d', strtotime('+3 days')) }}">
                </div>
            </div>

            <div>
                <label for="create_location">Lokasi Venue Lapangan</label>
                <input type="text" id="create_location" name="location" placeholder="Contoh: Gelora Bung Karno Tennis Stadium, Senayan">
            </div>

            <div>
                <label for="create_courts">Daftar Lapangan (Pisahkan dengan koma)</label>
                <input type="text" id="create_courts" name="courts" value="Center Court, Lapangan 1, Lapangan 2, Lapangan 3">
            </div>

            <div style="display:flex;align-items:center;gap:10px;padding:12px;background:#FAF8F2;border-radius:12px;border:1px solid #E3DFD0">
                <input type="checkbox" name="has_third_place" id="chk3rd" checked style="width:18px;height:18px;cursor:pointer">
                <label for="chk3rd" style="margin:0;cursor:pointer;color:#0B2E22;font-size:14px">Sertakan Pertandingan Perebutan Juara 3 (Bronze Medal Match)</label>
            </div>

            <!-- Footer Buttons -->
            <div style="display:flex;justify-content:flex-end;gap:12px;padding-top:14px;border-top:1px solid #EFECE0">
                <button type="button" onclick="closeModal('modalCreateTournament')" class="btn btn-s">Batal</button>
                <button type="submit" class="btn btn-p">Buat Turnamen Sekarang</button>
            </div>
        </form>
    </div>
</div>


@if(isset($activeTournament) && $activeTournament->id)
<!-- 2. Modal Edit Turnamen -->
<div id="modalEditTournament" onclick="if(event.target===this) closeModal('modalEditTournament')" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 transition-all" style="background:rgba(11,46,34,0.65);backdrop-filter:blur(5px)">
    <div class="modal-panel w-full max-w-2xl bg-white rounded-3xl overflow-hidden max-h-[92vh] flex flex-col shadow-2xl" style="border:1px solid #E3DFD0">
        <div style="background:#0B2E22;color:#F6F3EA;padding:20px 24px;display:flex;align-items:center;justify-content:space-between">
            <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:20px;margin:0;color:#F6F3EA">Pengaturan Turnamen</h3>
            <button type="button" onclick="closeModal('modalEditTournament')" style="background:rgba(255,255,255,0.15);border:none;color:#F6F3EA;width:34px;height:34px;border-radius:999px;cursor:pointer;font-weight:700">✕</button>
        </div>

        <form action="{{ route('tournaments.update', $activeTournament->id) }}" method="POST" style="padding:24px;overflow-y:auto;display:flex;flex-direction:column;gap:16px">
            @csrf
            @method('PUT')
            <div>
                <label>Nama Turnamen *</label>
                <input type="text" name="name" required value="{{ $activeTournament->name }}">
            </div>

            <div>
                <label>Deskripsi</label>
                <textarea name="description" rows="2">{{ $activeTournament->description }}</textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div>
                    <label>Kategori Pertandingan</label>
                    <select name="category">
                        <option value="mens_singles" {{ $activeTournament->category == 'mens_singles' ? 'selected' : '' }}>Tunggal Putra</option>
                        <option value="womens_singles" {{ $activeTournament->category == 'womens_singles' ? 'selected' : '' }}>Tunggal Putri</option>
                        <option value="mens_doubles" {{ $activeTournament->category == 'mens_doubles' ? 'selected' : '' }}>Ganda Putra</option>
                        <option value="womens_doubles" {{ $activeTournament->category == 'womens_doubles' ? 'selected' : '' }}>Ganda Putri</option>
                        <option value="mixed_doubles" {{ $activeTournament->category == 'mixed_doubles' ? 'selected' : '' }}>Ganda Campuran</option>
                    </select>
                </div>
                <div>
                    <label>Permukaan Lapangan</label>
                    <select name="surface">
                        <option value="Hard Court" {{ $activeTournament->surface == 'Hard Court' ? 'selected' : '' }}>Hard Court</option>
                        <option value="Clay" {{ $activeTournament->surface == 'Clay' ? 'selected' : '' }}>Clay</option>
                        <option value="Grass" {{ $activeTournament->surface == 'Grass' ? 'selected' : '' }}>Grass</option>
                        <option value="Carpet" {{ $activeTournament->surface == 'Carpet' ? 'selected' : '' }}>Carpet</option>
                    </select>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div>
                    <label>Format</label>
                    <select name="format">
                        <option value="group_knockout" {{ $activeTournament->format == 'group_knockout' ? 'selected' : '' }}>Fase Grup + Knockout</option>
                        <option value="knockout" {{ $activeTournament->format == 'knockout' ? 'selected' : '' }}>Knockout Murni</option>
                        <option value="round_robin" {{ $activeTournament->format == 'round_robin' ? 'selected' : '' }}>Round Robin</option>
                    </select>
                </div>
                <div>
                    <label>Aturan Set</label>
                    <select name="match_rule">
                        <option value="best_of_3" {{ $activeTournament->match_rule == 'best_of_3' ? 'selected' : '' }}>Best of 3 Sets</option>
                        <option value="best_of_1" {{ $activeTournament->match_rule == 'best_of_1' ? 'selected' : '' }}>Best of 1 Set</option>
                        <option value="pro_set_8" {{ $activeTournament->match_rule == 'pro_set_8' ? 'selected' : '' }}>Pro Set 8 Games</option>
                    </select>
                </div>
            </div>

            <div>
                <label>Lokasi Venue</label>
                <input type="text" name="location" value="{{ $activeTournament->location }}">
            </div>

            <div>
                <label>Daftar Lapangan</label>
                <input type="text" name="courts" value="{{ implode(', ', $activeTournament->courts ?: []) }}">
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;padding-top:14px;border-top:1px solid #EFECE0">
                <button type="button" onclick="if(confirm('Hapus seluruh turnamen ini beserta semua jadwal dan peserta?')){ document.getElementById('formDeleteTournament').submit(); }"
                    class="btn btn-s" style="border-color:#B8442D;color:#B8442D">
                    Hapus Turnamen Ini
                </button>
                <div style="display:flex;gap:10px">
                    <button type="button" onclick="closeModal('modalEditTournament')" class="btn btn-s">Batal</button>
                    <button type="submit" class="btn btn-p">Simpan Perubahan</button>
                </div>
            </div>
        </form>

        <form id="formDeleteTournament" action="{{ route('tournaments.destroy', $activeTournament->id) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>


<!-- 3. Modal Tambah Peserta -->
<div id="modalAddParticipant" onclick="if(event.target===this) closeModal('modalAddParticipant')" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 transition-all" style="background:rgba(11,46,34,0.65);backdrop-filter:blur(5px)">
    <div class="modal-panel w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl" style="border:1px solid #E3DFD0">
        <div style="background:#0B2E22;color:#F6F3EA;padding:18px 24px;display:flex;align-items:center;justify-content:space-between">
            <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:18px;margin:0;color:#F6F3EA">Pendaftaran Peserta</h3>
            <button type="button" onclick="closeModal('modalAddParticipant')" style="background:rgba(255,255,255,0.15);border:none;color:#F6F3EA;width:32px;height:32px;border-radius:999px;cursor:pointer;font-weight:700">✕</button>
        </div>

        <form action="{{ route('participants.store', $activeTournament->id) }}" method="POST" style="padding:22px;display:flex;flex-direction:column;gap:14px">
            @csrf
            <div>
                <label>Nama Pemain *</label>
                <input type="text" name="name" required placeholder="Contoh: Aldila Sutjiadi">
            </div>

            @if(str_contains($activeTournament->category, 'doubles'))
                <div>
                    <label>Nama Pasangan Ganda *</label>
                    <input type="text" name="partner_name" required placeholder="Contoh: Christopher Rungkat">
                </div>
            @endif

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                <div>
                    <label>Unggulan (Seed)</label>
                    <input type="number" name="seed" min="1" max="32" placeholder="1" style="text-align:center">
                </div>
                <div>
                    <label>Peringkat (Ranking)</label>
                    <input type="number" name="ranking" min="1" placeholder="5" style="text-align:center">
                </div>
            </div>

            <div>
                <label>Klub Tenis</label>
                <input type="text" name="club" placeholder="Senayan Tennis Academy">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                <div>
                    <label>Kota Asal</label>
                    <input type="text" name="city" placeholder="Jakarta">
                </div>
                <div>
                    <label>Nomor Telepon / WA</label>
                    <input type="text" name="phone" placeholder="0812-xxxx-xxxx">
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:12px;border-top:1px solid #EFECE0">
                <button type="button" onclick="closeModal('modalAddParticipant')" class="btn btn-s">Batal</button>
                <button type="submit" class="btn btn-p">Simpan Peserta</button>
            </div>
        </form>
    </div>
</div>


<!-- 4. Modal Edit Peserta -->
<div id="modalEditParticipant" onclick="if(event.target===this) closeModal('modalEditParticipant')" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 transition-all" style="background:rgba(11,46,34,0.65);backdrop-filter:blur(5px)">
    <div class="modal-panel w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl" style="border:1px solid #E3DFD0">
        <div style="background:#0B2E22;color:#F6F3EA;padding:18px 24px;display:flex;align-items:center;justify-content:space-between">
            <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:18px;margin:0;color:#F6F3EA">Edit Data Peserta</h3>
            <button type="button" onclick="closeModal('modalEditParticipant')" style="background:rgba(255,255,255,0.15);border:none;color:#F6F3EA;width:32px;height:32px;border-radius:999px;cursor:pointer;font-weight:700">✕</button>
        </div>

        <form id="formEditParticipant" method="POST" style="padding:22px;display:flex;flex-direction:column;gap:14px">
            @csrf
            @method('PUT')
            <div>
                <label>Nama Pemain *</label>
                <input type="text" id="editPartName" name="name" required>
            </div>

            <div>
                <label>Nama Pasangan Ganda (Opsional)</label>
                <input type="text" id="editPartPartner" name="partner_name">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                <div>
                    <label>Unggulan (Seed)</label>
                    <input type="number" id="editPartSeed" name="seed" min="1" max="32" style="text-align:center">
                </div>
                <div>
                    <label>Peringkat (Ranking)</label>
                    <input type="number" id="editPartRank" name="ranking" min="1" style="text-align:center">
                </div>
            </div>

            <div>
                <label>Klub Tenis</label>
                <input type="text" id="editPartClub" name="club">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                <div>
                    <label>Kota Asal</label>
                    <input type="text" id="editPartCity" name="city">
                </div>
                <div>
                    <label>Nomor Telepon / WA</label>
                    <input type="text" id="editPartPhone" name="phone">
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:12px;border-top:1px solid #EFECE0">
                <button type="button" onclick="closeModal('modalEditParticipant')" class="btn btn-s">Batal</button>
                <button type="submit" class="btn btn-p">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>


<!-- 5. Modal Atur Jadwal Pertandingan -->
<div id="modalEditSchedule" onclick="if(event.target===this) closeModal('modalEditSchedule')" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 transition-all" style="background:rgba(11,46,34,0.65);backdrop-filter:blur(5px)">
    <div class="modal-panel w-full max-w-sm bg-white rounded-3xl overflow-hidden shadow-2xl" style="border:1px solid #E3DFD0">
        <div style="background:#0B2E22;color:#F6F3EA;padding:18px 24px;display:flex;align-items:center;justify-content:space-between">
            <h4 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:18px;margin:0;color:#F6F3EA">Jadwalkan Pertandingan</h4>
            <button type="button" onclick="closeModal('modalEditSchedule')" style="background:rgba(255,255,255,0.15);border:none;color:#F6F3EA;width:32px;height:32px;border-radius:999px;cursor:pointer;font-weight:700">✕</button>
        </div>

        <form id="formEditSchedule" method="POST" style="padding:22px;display:flex;flex-direction:column;gap:14px">
            @csrf
            @method('PUT')
            <div>
                <label>Tanggal Pertandingan</label>
                <input type="date" id="editMatchDate" name="match_date" required>
            </div>

            <div>
                <label>Waktu / Jam Mulai</label>
                <input type="time" id="editMatchTime" name="match_time" required>
            </div>

            <div>
                <label>Alokasi Lapangan (Court)</label>
                <select id="editMatchCourt" name="court">
                    @foreach($activeTournament->courts ?: [] as $c)
                        <option value="{{ $c }}">{{ $c }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:12px;border-top:1px solid #EFECE0">
                <button type="button" onclick="closeModal('modalEditSchedule')" class="btn btn-s">Batal</button>
                <button type="submit" class="btn btn-p">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>


<!-- 6. Modal Input Skor Tenis Resmi -->
<div id="modalInputScore" onclick="if(event.target===this) closeModal('modalInputScore')" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 transition-all" style="background:rgba(11,46,34,0.65);backdrop-filter:blur(5px)">
    <div class="modal-panel w-full max-w-xl bg-white rounded-3xl overflow-hidden max-h-[92vh] flex flex-col shadow-2xl" style="border:1px solid #E3DFD0">
        <!-- Header -->
        <div style="background:#0B2E22;color:#F6F3EA;padding:20px 24px;display:flex;align-items:center;justify-content:space-between">
            <div>
                <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:20px;margin:0;color:#F6F3EA">Input Hasil Pertandingan</h3>
                <p style="font-size:13px;color:#DCE8DF;margin:2px 0 0">Masukkan game score per set atau gunakan preset skor cepat</p>
            </div>
            <button type="button" onclick="closeModal('modalInputScore')" style="background:rgba(255,255,255,0.15);border:none;color:#F6F3EA;width:34px;height:34px;border-radius:999px;cursor:pointer;font-weight:700">✕</button>
        </div>

        <form id="formInputScore" method="POST" style="padding:24px;overflow-y:auto;display:flex;flex-direction:column;gap:18px">
            @csrf

            <!-- Match Head-to-Head Banner -->
            <div style="display:grid;grid-template-columns:2fr 1fr 2fr;align-items:center;background:#FAF8F2;border:1px solid #E3DFD0;padding:16px 20px;border-radius:18px">
                <div style="text-align:left">
                    <span style="font-size:11px;font-weight:700;color:#8A9990;text-transform:uppercase;letter-spacing:0.04em;display:block">PLAYER 1</span>
                    <span id="scoreModalP1Name" style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:16px;color:#0B2E22;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">Pemain 1</span>
                </div>
                <div style="text-align:center">
                    <span class="tag t-open" style="font-size:12px;font-weight:700">VS</span>
                </div>
                <div style="text-align:right">
                    <span style="font-size:11px;font-weight:700;color:#8A9990;text-transform:uppercase;letter-spacing:0.04em;display:block">PLAYER 2</span>
                    <span id="scoreModalP2Name" style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:16px;color:#0B2E22;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">Pemain 2</span>
                </div>
            </div>

            <!-- Quick Score Presets -->
            <div>
                <label>Preset Skor Cepat (Klik untuk isi otomatis)</label>
                <div style="display:flex;flex-wrap:wrap;gap:8px">
                    <button type="button" onclick="setScorePreset(6,4, 6,3, 0,0)" class="chip">6-4, 6-3</button>
                    <button type="button" onclick="setScorePreset(6,2, 6,1, 0,0)" class="chip">6-2, 6-1</button>
                    <button type="button" onclick="setScorePreset(6,4, 4,6, 6,3)" class="chip">6-4, 4-6, 6-3</button>
                    <button type="button" onclick="setScorePreset(7,6, 6,4, 0,0)" class="chip">7-6, 6-4</button>
                    <button type="button" onclick="setScorePreset(6,3, 3,6, 10,8)" class="chip">Super TB (10-8)</button>
                </div>
            </div>

            <!-- Walkover Toggle -->
            <div style="background:#FFF9E6;border:1px solid #FFE082;border-radius:14px;padding:14px">
                <div style="display:flex;align-items:center;gap:10px">
                    <input type="checkbox" id="chkScoreWalkover" name="is_walkover" onchange="toggleWalkover(this.checked)" style="width:18px;height:18px;cursor:pointer">
                    <label for="chkScoreWalkover" style="margin:0;cursor:pointer;color:#8F6B00;font-weight:700;font-size:13px">Walkover (W.O) / Lawan Mengundurkan Diri (Retired)</label>
                </div>
                <div id="walkoverWinnerBox" class="hidden" style="margin-top:12px;padding-top:10px;border-top:1px solid #FFE082;display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <label style="display:flex;align-items:center;gap:8px;padding:8px 12px;background:#FFFFFF;border:1px solid #FFE082;border-radius:10px;cursor:pointer;font-weight:700">
                        <input type="radio" name="winner_id" id="woWinP1" value="">
                        <span id="woLabelP1">Menang P1</span>
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;padding:8px 12px;background:#FFFFFF;border:1px solid #FFE082;border-radius:10px;cursor:pointer;font-weight:700">
                        <input type="radio" name="winner_id" id="woWinP2" value="">
                        <span id="woLabelP2">Menang P2</span>
                    </label>
                </div>
            </div>

            <!-- Sets Input Grid -->
            <div id="setsContainer" style="display:flex;flex-direction:column;gap:12px">
                @foreach([1 => 'SET 1', 2 => 'SET 2', 3 => 'SET 3 (Deciding Set / Tiebreak)'] as $idx => $label)
                    <div style="background:#FAF8F2;border:1px solid #E3DFD0;border-radius:14px;padding:14px">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
                            <span style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:13px;color:#0B2E22">{{ $label }}</span>
                            <span style="font-size:12px;color:#8A9990">Game Score</span>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                            <div style="display:flex;align-items:center;justify-content:space-between;background:#FFFFFF;border:1px solid #E3DFD0;border-radius:10px;padding:8px 12px">
                                <span style="font-weight:700;color:#0B2E22;font-size:13px">P1</span>
                                <div style="display:flex;align-items:center;gap:6px">
                                    <button type="button" onclick="stepScore('s{{$idx}}p1', -1)" class="btn btn-s" style="min-height:30px;width:30px;padding:0;font-size:16px">-</button>
                                    <input type="number" id="s{{$idx}}p1" name="sets[{{$idx-1}}][p1Games]" value="0" min="0" max="15" style="width:48px;text-align:center;font-weight:700;font-size:18px;font-family:'Space Grotesk',monospace;color:#0B2E22">
                                    <button type="button" onclick="stepScore('s{{$idx}}p1', 1)" class="btn btn-p" style="min-height:30px;width:30px;padding:0;font-size:16px">+</button>
                                </div>
                            </div>

                            <div style="display:flex;align-items:center;justify-content:space-between;background:#FFFFFF;border:1px solid #E3DFD0;border-radius:10px;padding:8px 12px">
                                <span style="font-weight:700;color:#0B2E22;font-size:13px">P2</span>
                                <div style="display:flex;align-items:center;gap:6px">
                                    <button type="button" onclick="stepScore('s{{$idx}}p2', -1)" class="btn btn-s" style="min-height:30px;width:30px;padding:0;font-size:16px">-</button>
                                    <input type="number" id="s{{$idx}}p2" name="sets[{{$idx-1}}][p2Games]" value="0" min="0" max="15" style="width:48px;text-align:center;font-weight:700;font-size:18px;font-family:'Space Grotesk',monospace;color:#0B2E22">
                                    <button type="button" onclick="stepScore('s{{$idx}}p2', 1)" class="btn btn-p" style="min-height:30px;width:30px;padding:0;font-size:16px">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Notes -->
            <div>
                <label for="scoreModalNotes">Catatan Pertandingan (Opsional)</label>
                <input type="text" id="scoreModalNotes" name="notes" placeholder="Contoh: Tiebreak sengit 7-5, durasi 1 jam 45 menit">
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:14px;border-top:1px solid #EFECE0">
                <button type="button" onclick="closeModal('modalInputScore')" class="btn btn-s">Batal</button>
                <button type="submit" class="btn btn-p">Simpan Hasil Pertandingan</button>
            </div>
        </form>
    </div>
</div>


<!-- 7. Modal Live Umpire Scoreboard (Broadcast Grand Slam Style) -->
<div id="modalLiveUmpire" onclick="if(event.target===this) closeModal('modalLiveUmpire')" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 transition-all" style="background:rgba(11,46,34,0.85);backdrop-filter:blur(10px)">
    <div class="modal-panel w-full max-w-2xl bg-[#0B2E22] text-[#F6F3EA] rounded-3xl overflow-hidden shadow-2xl" style="border:2px solid #D8F24B">
        <!-- Stadium Header -->
        <div style="padding:18px 24px;border-bottom:1px solid rgba(255,255,255,0.12);display:flex;align-items:center;justify-content:space-between;background:#072118">
            <div style="display:flex;align-items:center;gap:12px">
                <span class="tag t-live" style="font-size:11px">LIVE BROADCAST</span>
                <div>
                    <h3 style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:18px;margin:0;color:#F6F3EA">Live Umpire Scoreboard</h3>
                    <p id="liveUmpireCourtText" style="font-size:13px;color:#D8F24B;margin:2px 0 0">Center Court</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modalLiveUmpire')" style="background:rgba(255,255,255,0.15);border:none;color:#F6F3EA;width:34px;height:34px;border-radius:999px;cursor:pointer;font-weight:700">✕</button>
        </div>

        <div style="padding:24px;display:flex;flex-direction:column;gap:20px">
            <!-- Stadium LED Matrix Scorecard -->
            <div style="background:#051711;border:1px solid rgba(255,255,255,0.15);border-radius:18px;padding:20px">
                <div style="display:grid;grid-template-columns:5fr 5fr 2fr;font-size:11px;font-weight:700;color:#DCE8DF;letter-spacing:0.04em;text-transform:uppercase;padding-bottom:10px;border-bottom:1px solid rgba(255,255,255,0.1)">
                    <div>PEMAIN / SERVER</div>
                    <div style="text-align:center">SET 1 &nbsp;•&nbsp; SET 2 &nbsp;•&nbsp; SET 3</div>
                    <div style="text-align:right">POINT</div>
                </div>

                <!-- Row P1 -->
                <div style="display:grid;grid-template-columns:5fr 5fr 2fr;align-items:center;padding:14px 0;border-bottom:1px solid rgba(255,255,255,0.08)">
                    <div style="display:flex;align-items:center;gap:10px">
                        <button type="button" onclick="setLiveServer(1)" title="Pindah Server" id="liveP1ServerBtn" style="background:none;border:none;cursor:pointer;padding:0">
                            <span id="liveP1Server" style="display:block;width:14px;height:14px;border-radius:999px;background:#D8F24B;box-shadow:0 0 10px #D8F24B"></span>
                        </button>
                        <span id="liveP1Name" style="font-weight:700;font-size:16px;color:#FFFFFF;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">Pemain 1</span>
                    </div>
                    <div style="display:flex;align-items:center;justify-content:center;gap:10px;font-family:'Space Grotesk',monospace;font-weight:700;font-size:18px">
                        <span id="liveP1Set1" style="width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,0.08);display:flex;align-items:center;justify-content:center;color:#D8F24B">0</span>
                        <span id="liveP1Set2" style="width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,0.08);display:flex;align-items:center;justify-content:center;color:#FFFFFF">0</span>
                        <span id="liveP1Set3" style="width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,0.08);display:flex;align-items:center;justify-content:center;color:#FFFFFF">0</span>
                    </div>
                    <div id="liveP1Point" style="text-align:right;font-family:'Space Grotesk',monospace;font-weight:700;font-size:36px;color:#D8F24B;line-height:1">0</div>
                </div>

                <!-- Row P2 -->
                <div style="display:grid;grid-template-columns:5fr 5fr 2fr;align-items:center;padding:14px 0">
                    <div style="display:flex;align-items:center;gap:10px">
                        <button type="button" onclick="setLiveServer(2)" title="Pindah Server" id="liveP2ServerBtn" style="background:none;border:none;cursor:pointer;padding:0">
                            <span id="liveP2Server" style="display:block;width:14px;height:14px;border-radius:999px;background:rgba(255,255,255,0.2)"></span>
                        </button>
                        <span id="liveP2Name" style="font-weight:700;font-size:16px;color:#FFFFFF;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">Pemain 2</span>
                    </div>
                    <div style="display:flex;align-items:center;justify-content:center;gap:10px;font-family:'Space Grotesk',monospace;font-weight:700;font-size:18px">
                        <span id="liveP2Set1" style="width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,0.08);display:flex;align-items:center;justify-content:center;color:#D8F24B">0</span>
                        <span id="liveP2Set2" style="width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,0.08);display:flex;align-items:center;justify-content:center;color:#FFFFFF">0</span>
                        <span id="liveP2Set3" style="width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,0.08);display:flex;align-items:center;justify-content:center;color:#FFFFFF">0</span>
                    </div>
                    <div id="liveP2Point" style="text-align:right;font-family:'Space Grotesk',monospace;font-weight:700;font-size:36px;color:#D8F24B;line-height:1">0</div>
                </div>
            </div>

            <!-- Big Umpire Point Buttons -->
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <button type="button" onclick="scoreLivePoint(1)"
                    style="background:#14503B;border:2px solid #D8F24B;color:#FFFFFF;padding:18px;border-radius:18px;text-align:left;cursor:pointer;transition:transform 0.1s" class="hover:scale-[1.02] active:scale-[0.98]">
                    <span style="font-size:12px;font-weight:700;color:#D8F24B;text-transform:uppercase;letter-spacing:0.04em;display:block">+ POIN UNTUK P1</span>
                    <span id="liveBtnP1Label" style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:18px;display:block;margin-top:4px">Pemain 1</span>
                </button>

                <button type="button" onclick="scoreLivePoint(2)"
                    style="background:#14503B;border:2px solid #D8F24B;color:#FFFFFF;padding:18px;border-radius:18px;text-align:left;cursor:pointer;transition:transform 0.1s" class="hover:scale-[1.02] active:scale-[0.98]">
                    <span style="font-size:12px;font-weight:700;color:#D8F24B;text-transform:uppercase;letter-spacing:0.04em;display:block">+ POIN UNTUK P2</span>
                    <span id="liveBtnP2Label" style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:18px;display:block;margin-top:4px">Pemain 2</span>
                </button>
            </div>

            <!-- Umpire Controls -->
            <div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:12px;padding-top:14px;border-top:1px solid rgba(255,255,255,0.1)">
                <div style="display:flex;gap:8px">
                    <button type="button" onclick="resetLiveGamePoints()" class="btn btn-o" style="min-height:38px;padding:0 14px;font-size:13px">Reset Poin Game</button>
                    <button type="button" onclick="setLiveServer(liveState.server === 1 ? 2 : 1)" class="btn btn-o" style="min-height:38px;padding:0 14px;font-size:13px;border-color:#D8F24B;color:#D8F24B">Ganti Server</button>
                </div>
                <div style="display:flex;gap:8px">
                    <button type="button" onclick="finishLiveMatch(1)" class="btn btn-p" style="min-height:38px;padding:0 16px;font-size:13px">Selesai: Menang P1</button>
                    <button type="button" onclick="finishLiveMatch(2)" class="btn btn-p" style="min-height:38px;padding:0 16px;font-size:13px">Selesai: Menang P2</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- JavaScript Handlers -->
<script>
    function openEditParticipantModal(id, name, partner, seed, club, city, phone, rank) {
        document.getElementById('formEditParticipant').action = '/participants/' + id;
        document.getElementById('editPartName').value = name;
        document.getElementById('editPartPartner').value = partner;
        document.getElementById('editPartSeed').value = seed || '';
        document.getElementById('editPartClub').value = club || '';
        document.getElementById('editPartCity').value = city || '';
        document.getElementById('editPartPhone').value = phone || '';
        document.getElementById('editPartRank').value = rank || '';
        openModal('modalEditParticipant');
    }

    function openEditScheduleModal(id, date, time, court) {
        document.getElementById('formEditSchedule').action = '/matches/' + id + '/schedule';
        document.getElementById('editMatchDate').value = date;
        document.getElementById('editMatchTime').value = time;
        document.getElementById('editMatchCourt').value = court;
        openModal('modalEditSchedule');
    }

    function openScoreModal(matchId, p1Id, p2Id, p1Name, p2Name, sets, winnerId, status, notes) {
        document.getElementById('formInputScore').action = '/matches/' + matchId + '/score';
        document.getElementById('scoreModalP1Name').innerText = p1Name;
        document.getElementById('scoreModalP2Name').innerText = p2Name;
        document.getElementById('scoreModalNotes').value = notes || '';

        document.getElementById('woWinP1').value = p1Id;
        document.getElementById('woWinP2').value = p2Id;
        document.getElementById('woLabelP1').innerText = p1Name;
        document.getElementById('woLabelP2').innerText = p2Name;
        document.getElementById('woWinP1').checked = (String(winnerId) === String(p1Id)) || !winnerId;
        document.getElementById('woWinP2').checked = (String(winnerId) === String(p2Id));

        const isWo = status === 'walkover';
        document.getElementById('chkScoreWalkover').checked = isWo;
        toggleWalkover(isWo);

        const s1 = sets && sets[0] ? sets[0] : { p1Games: 0, p2Games: 0 };
        const s2 = sets && sets[1] ? sets[1] : { p1Games: 0, p2Games: 0 };
        const s3 = sets && sets[2] ? sets[2] : { p1Games: 0, p2Games: 0 };

        document.getElementById('s1p1').value = s1.p1Games ?? 0;
        document.getElementById('s1p2').value = s1.p2Games ?? 0;
        document.getElementById('s2p1').value = s2.p1Games ?? 0;
        document.getElementById('s2p2').value = s2.p2Games ?? 0;
        document.getElementById('s3p1').value = s3.p1Games ?? 0;
        document.getElementById('s3p2').value = s3.p2Games ?? 0;

        openModal('modalInputScore');
    }

    function stepScore(inputId, delta) {
        const el = document.getElementById(inputId);
        if (!el) return;
        const next = Math.max(0, Math.min(15, (parseInt(el.value, 10) || 0) + delta));
        el.value = next;
    }

    function toggleWalkover(isWo) {
        const setsBox = document.getElementById('setsContainer');
        const woBox = document.getElementById('walkoverWinnerBox');
        setsBox.style.opacity = isWo ? '0.35' : '1';
        setsBox.style.pointerEvents = isWo ? 'none' : 'auto';
        if (isWo) {
            woBox.classList.remove('hidden');
            document.getElementById('woWinP1').disabled = false;
            document.getElementById('woWinP2').disabled = false;
        } else {
            woBox.classList.add('hidden');
            document.getElementById('woWinP1').disabled = true;
            document.getElementById('woWinP2').disabled = true;
        }
    }

    function setScorePreset(s1_1, s1_2, s2_1, s2_2, s3_1, s3_2) {
        document.getElementById('chkScoreWalkover').checked = false;
        toggleWalkover(false);
        document.getElementById('s1p1').value = s1_1 ?? 0;
        document.getElementById('s1p2').value = s1_2 ?? 0;
        document.getElementById('s2p1').value = s2_1 ?? 0;
        document.getElementById('s2p2').value = s2_2 ?? 0;
        document.getElementById('s3p1').value = s3_1 ?? 0;
        document.getElementById('s3p2').value = s3_2 ?? 0;
    }

    // Live Umpire State & Logic
    let liveState = {
        matchId: null,
        p1Id: null,
        p2Id: null,
        p1Name: '',
        p2Name: '',
        p1Point: '0',
        p2Point: '0',
        p1Games: [0, 0, 0],
        p2Games: [0, 0, 0],
        currentSet: 0,
        server: 1
    };

    function openLiveUmpireModal(matchId, p1Id, p2Id, p1Name, p2Name, court) {
        liveState.matchId = matchId;
        liveState.p1Id = p1Id;
        liveState.p2Id = p2Id;
        liveState.p1Name = p1Name;
        liveState.p2Name = p2Name;
        liveState.p1Point = '0';
        liveState.p2Point = '0';
        liveState.p1Games = [0, 0, 0];
        liveState.p2Games = [0, 0, 0];
        liveState.currentSet = 0;
        liveState.server = 1;

        document.getElementById('liveUmpireCourtText').innerText = court;
        document.getElementById('liveP1Name').innerText = p1Name;
        document.getElementById('liveP2Name').innerText = p2Name;
        document.getElementById('liveBtnP1Label').innerText = p1Name;
        document.getElementById('liveBtnP2Label').innerText = p2Name;

        updateLiveUI();
        openModal('modalLiveUmpire');
    }

    function setLiveServer(srv) {
        liveState.server = srv;
        updateLiveUI();
    }

    function updateLiveUI() {
        document.getElementById('liveP1Point').innerText = liveState.p1Point;
        document.getElementById('liveP2Point').innerText = liveState.p2Point;

        document.getElementById('liveP1Set1').innerText = liveState.p1Games[0];
        document.getElementById('liveP2Set1').innerText = liveState.p2Games[0];
        document.getElementById('liveP1Set2').innerText = liveState.p1Games[1];
        document.getElementById('liveP2Set2').innerText = liveState.p2Games[1];
        document.getElementById('liveP1Set3').innerText = liveState.p1Games[2];
        document.getElementById('liveP2Set3').innerText = liveState.p2Games[2];

        const s1El = document.getElementById('liveP1Server');
        const s2El = document.getElementById('liveP2Server');
        if (liveState.server === 1) {
            s1El.style.background = '#D8F24B';
            s1El.style.boxShadow = '0 0 10px #D8F24B';
            s2El.style.background = 'rgba(255,255,255,0.2)';
            s2El.style.boxShadow = 'none';
        } else {
            s1El.style.background = 'rgba(255,255,255,0.2)';
            s1El.style.boxShadow = 'none';
            s2El.style.background = '#D8F24B';
            s2El.style.boxShadow = '0 0 10px #D8F24B';
        }
    }

    function scoreLivePoint(scorer) {
        const points = ['0', '15', '30', '40'];
        let p1 = liveState.p1Point;
        let p2 = liveState.p2Point;

        if (scorer === 1) {
            if (p1 === '40') {
                if (p2 === '40') { liveState.p1Point = 'AD'; updateLiveUI(); return; }
                if (p2 === 'AD') { liveState.p2Point = '40'; updateLiveUI(); return; }
                winLiveGame(1); return;
            }
            if (p1 === 'AD') { winLiveGame(1); return; }
            liveState.p1Point = points[points.indexOf(p1) + 1] || '40';
        } else {
            if (p2 === '40') {
                if (p1 === '40') { liveState.p2Point = 'AD'; updateLiveUI(); return; }
                if (p1 === 'AD') { liveState.p1Point = '40'; updateLiveUI(); return; }
                winLiveGame(2); return;
            }
            if (p2 === 'AD') { winLiveGame(2); return; }
            liveState.p2Point = points[points.indexOf(p2) + 1] || '40';
        }

        updateLiveUI();
    }

    function winLiveGame(winner) {
        const setIdx = liveState.currentSet;
        if (winner === 1) {
            liveState.p1Games[setIdx]++;
        } else {
            liveState.p2Games[setIdx]++;
        }

        liveState.p1Point = '0';
        liveState.p2Point = '0';
        liveState.server = liveState.server === 1 ? 2 : 1;

        const g1 = liveState.p1Games[setIdx];
        const g2 = liveState.p2Games[setIdx];
        if ((g1 >= 6 && g1 - g2 >= 2) || g1 === 7 || (g2 >= 6 && g2 - g1 >= 2) || g2 === 7) {
            if (liveState.currentSet < 2) {
                liveState.currentSet++;
            }
        }

        updateLiveUI();
    }

    function resetLiveGamePoints() {
        liveState.p1Point = '0';
        liveState.p2Point = '0';
        updateLiveUI();
    }

    function finishLiveMatch(winnerChoice) {
        if (!confirm('Selesaikan pertandingan ini dan simpan hasilnya ke database?')) return;

        const sets = [
            { p1Games: liveState.p1Games[0], p2Games: liveState.p2Games[0] },
            { p1Games: liveState.p1Games[1], p2Games: liveState.p2Games[1] },
        ];
        if (liveState.currentSet >= 2 || liveState.p1Games[2] > 0 || liveState.p2Games[2] > 0) {
            sets.push({ p1Games: liveState.p1Games[2], p2Games: liveState.p2Games[2] });
        }

        const winnerId = winnerChoice === 1 ? liveState.p1Id : liveState.p2Id;

        fetch('/matches/' + liveState.matchId + '/live-umpire', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                sets: sets,
                winner_id: winnerId
            })
        }).then(() => {
            window.location.reload();
        }).catch(err => {
            alert('Gagal menyimpan hasil: ' + err);
        });
    }
</script>
