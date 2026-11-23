@php
    $totalMatches = $activeTournament->matches->count();
    $completedMatches = $activeTournament->matches->whereIn('status', ['completed', 'walkover'])->count();
    $liveMatches = $activeTournament->matches->where('status', 'live');
    $firstGroup = $activeTournament->groups->first();
    $secondGroup = $activeTournament->groups->skip(1)->first() ?? $firstGroup;
    $firstGroupStandings = $firstGroup && isset($groupStandings[$firstGroup->id]) ? collect($groupStandings[$firstGroup->id]) : collect();
    $secondGroupStandings = $secondGroup && isset($groupStandings[$secondGroup->id]) ? collect($groupStandings[$secondGroup->id]) : collect();
    $todayMatches = $activeTournament->matches->take(5);
@endphp

<div>
    <!-- Hero Banner (Rookie Tennis Heritage Green) -->
    <div style="background:#0B2E22;color:#F6F3EA">
        <div style="max-width:1200px;margin:0 auto;padding:64px 24px 84px;display:flex;flex-wrap:wrap;align-items:center;gap:48px">
            
            <!-- Left Hero Content -->
            <div style="flex:1 1 480px;display:flex;flex-direction:column;gap:26px">
                <span class="tag" style="align-self:flex-start;border:1.5px solid #D8F24B;color:#D8F24B;background:transparent;padding:6px 14px;font-size:13px">
                    🎾 Platform turnamen tenis profesional
                </span>
                
                <h1 style="font-size:clamp(38px,5.5vw,72px);line-height:1.03;font-weight:700;color:#F6F3EA">
                    Turnamen tenis tanpa spreadsheet.
                </h1>
                
                <p style="font-size:18px;line-height:1.6;color:#DCE8DF;max-width:540px">
                    Buat turnamen, kelola pendaftaran peserta, undi pembagian grup, atur jadwal lapangan, catat hasil game & set, serta pantau klasemen dan bracket knockout yang terbarukan otomatis.
                </p>

                <!-- Action CTA Buttons -->
                <div style="display:flex;flex-wrap:wrap;gap:14px;align-items:center">
                    <button onclick="openModal('modalCreateTournament')" class="btn btn-p">
                        + Buat Turnamen
                    </button>
                    <a class="btn btn-o" href="{{ route('tournaments.index', ['tournament' => $activeTournament->id, 'tab' => 'tournaments']) }}">
                        Lihat Turnamen
                    </a>
                    <a class="btn btn-o" href="{{ route('tournaments.index', ['tournament' => $activeTournament->id, 'tab' => 'bracket']) }}">
                        Bagan Knockout
                    </a>
                </div>

                <!-- Active Tournament Pill -->
                <div style="display:flex;align-items:center;gap:10px;padding-top:12px;color:#DCE8DF;font-size:14px">
                    <span style="display:inline-block;width:10px;height:10px;border-radius:999px;background:#D8F24B"></span>
                    <span>Turnamen Aktif: <strong style="color:#FFFFFF">{{ $activeTournament->name }}</strong> ({{ $activeTournament->category_label }} • {{ $activeTournament->surface }})</span>
                </div>
            </div>

            <!-- Right Floating Live Standings & Score Preview -->
            <div style="flex:1 1 420px;position:relative;padding-bottom:28px">
                <div style="background:#F6F3EA;color:#0B2E22;border-radius:28px;padding:28px;box-shadow:0 20px 40px rgba(0,0,0,0.3)">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px">
                        <h3 style="font-size:22px;font-weight:700">
                            {{ $firstGroup ? 'Klasemen ' . $firstGroup->name : 'Klasemen Grup A' }}
                        </h3>
                        <span class="tag t-live">Berlangsung</span>
                    </div>

                    @if($firstGroupStandings->isNotEmpty())
                        <table>
                            <thead>
                                <tr>
                                    <th style="width:36px">#</th>
                                    <th>Pemain</th>
                                    <th style="text-align:center">M</th>
                                    <th style="text-align:center">Set</th>
                                    <th style="text-align:center">Poin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($firstGroupStandings->take(4) as $idx => $st)
                                    <tr style="{{ $idx === 3 ? 'border-bottom:0' : '' }}">
                                        <td style="font-weight:700;color:{{ $idx < 2 ? '#0B2E22' : '#8A9990' }}">
                                            {{ $idx + 1 }}
                                        </td>
                                        <td style="font-weight:{{ $idx < 2 ? '700' : '500' }}">
                                            {{ $st['participant']->display_name }}
                                            @if($idx < 2)
                                                <span style="font-size:11px;color:#14503B;font-weight:700;margin-left:4px">✓ Lolos</span>
                                            @endif
                                        </td>
                                        <td style="text-align:center;font-weight:600">{{ $st['won'] }}</td>
                                        <td style="text-align:center;font-family:'Space Grotesk',monospace;font-size:13px">{{ $st['sets_won'] }}-{{ $st['sets_lost'] }}</td>
                                        <td style="text-align:center;font-weight:700;color:#0B2E22">{{ $st['points'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <!-- Demo fallback for preview if empty -->
                        <table>
                            <thead>
                                <tr><th style="width:36px">#</th><th>Pemain</th><th style="text-align:center">M</th><th style="text-align:center">Set</th><th style="text-align:center">Poin</th></tr>
                            </thead>
                            <tbody>
                                <tr><td style="font-weight:700">1</td><td style="font-weight:700">Raka Pratama</td><td style="text-align:center">3</td><td style="text-align:center">6-1</td><td style="text-align:center;font-weight:700">6</td></tr>
                                <tr><td style="font-weight:700">2</td><td style="font-weight:700">Dimas Wicaksono</td><td style="text-align:center">3</td><td style="text-align:center">5-3</td><td style="text-align:center;font-weight:700">4</td></tr>
                                <tr><td style="font-weight:700">3</td><td>Bagas Nugroho</td><td style="text-align:center">3</td><td style="text-align:center">3-5</td><td style="text-align:center;font-weight:700">2</td></tr>
                                <tr style="border-bottom:0"><td style="font-weight:700">4</td><td>Fajar Ramadhan</td><td style="text-align:center">3</td><td style="text-align:center">1-6</td><td style="text-align:center;font-weight:700">0</td></tr>
                            </tbody>
                        </table>
                    @endif
                </div>

                <!-- Floating Upcoming Match Badge -->
                <div style="position:absolute;right:-10px;bottom:0;background:#D8F24B;color:#0B2E22;border-radius:18px;padding:14px 20px;box-shadow:0 12px 30px rgba(0,0,0,0.25);border:2px solid #0B2E22">
                    <p style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.04em">
                        @if($liveMatches->count() > 0)
                            🔴 Live Match · {{ $liveMatches->first()->court ?: 'Court 1' }}
                        @else
                            Semifinal · Center Court
                        @endif
                    </p>
                    <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:19px;margin-top:2px">
                        @if($liveMatches->count() > 0)
                            {{ $liveMatches->first()->player1->display_name ?? 'P1' }} vs {{ $liveMatches->first()->player2->display_name ?? 'P2' }}
                        @else
                            Raka Pratama vs Galih Permana
                        @endif
                    </p>
                </div>
            </div>

        </div>
    </div>

    <!-- Section 2: 6 Features (Semua yang dibutuhkan penyelenggara, dalam satu alur) -->
    <div style="max-width:1200px;margin:0 auto;padding:84px 24px 40px">
        <h2 style="font-size:clamp(30px,4vw,46px);line-height:1.1;max-width:720px;color:#0B2E22">
            Semua yang dibutuhkan penyelenggara, dalam satu alur.
        </h2>
        
        <div style="display:flex;flex-wrap:wrap;gap:20px;margin-top:44px">
            
            <a href="#" onclick="openModal('modalCreateTournament'); return false;" class="card hover:shadow-lg transition-all" style="flex:1 1 340px;text-decoration:none">
                <span style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:14px;background:#D8F24B;color:#0B2E22;display:inline-block;padding:4px 12px;border-radius:999px">01</span>
                <h3 style="font-size:24px;margin:16px 0 8px;color:#0B2E22">Buat turnamen</h3>
                <p style="color:#4A5A52;line-height:1.55;font-size:15px">Atur nama, tanggal, venue, kategori, dan format pertandingan (Group + Knockout, Sistem Gugur, atau Round Robin) dalam beberapa langkah singkat.</p>
            </a>

            <a href="{{ route('tournaments.index', ['tournament' => $activeTournament->id, 'tab' => 'participants']) }}" class="card hover:shadow-lg transition-all" style="flex:1 1 340px;text-decoration:none">
                <span style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:14px;background:#D8F24B;color:#0B2E22;display:inline-block;padding:4px 12px;border-radius:999px">02</span>
                <h3 style="font-size:24px;margin:16px 0 8px;color:#0B2E22">Pendaftaran peserta</h3>
                <p style="color:#4A5A52;line-height:1.55;font-size:15px">Daftarkan pemain tunggal atau ganda, tentukan seed unggulan dan ranking, kuota kuota otomatis, serta auto-generator untuk tes kilat.</p>
            </a>

            <a href="{{ route('tournaments.index', ['tournament' => $activeTournament->id, 'tab' => 'group']) }}" class="card hover:shadow-lg transition-all" style="flex:1 1 340px;text-decoration:none">
                <span style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:14px;background:#D8F24B;color:#0B2E22;display:inline-block;padding:4px 12px;border-radius:999px">03</span>
                <h3 style="font-size:24px;margin:16px 0 8px;color:#0B2E22">Pembagian grup</h3>
                <p style="color:#4A5A52;line-height:1.55;font-size:15px">Acak atau atur seed pemain ke dalam grup secara adil dengan algoritma snake-draw resmi, lalu bentuk jadwal pertemuan grup.</p>
            </a>

            <a href="{{ route('tournaments.index', ['tournament' => $activeTournament->id, 'tab' => 'bracket']) }}" class="card hover:shadow-lg transition-all" style="flex:1 1 340px;text-decoration:none">
                <span style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:14px;background:#D8F24B;color:#0B2E22;display:inline-block;padding:4px 12px;border-radius:999px">04</span>
                <h3 style="font-size:24px;margin:16px 0 8px;color:#0B2E22">Sistem knockout</h3>
                <p style="color:#4A5A52;line-height:1.55;font-size:15px">Pemain terbaik tiap grup lolos otomatis ke bagan gugur, mulai babak perempat final, semifinal, hingga partai final perebutan juara.</p>
            </a>

            <a href="{{ route('tournaments.index', ['tournament' => $activeTournament->id, 'tab' => 'schedule']) }}" class="card hover:shadow-lg transition-all" style="flex:1 1 340px;text-decoration:none">
                <span style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:14px;background:#D8F24B;color:#0B2E22;display:inline-block;padding:4px 12px;border-radius:999px">05</span>
                <h3 style="font-size:24px;margin:16px 0 8px;color:#0B2E22">Jadwal pertandingan</h3>
                <p style="color:#4A5A52;line-height:1.55;font-size:15px">Susun jam tanding dan alokasi lapangan tanpa bentrok, lengkapi status Terjadwal, Berlangsung, maupun Selesai secara real-time.</p>
            </a>

            <a href="{{ route('tournaments.index', ['tournament' => $activeTournament->id, 'tab' => 'schedule']) }}" class="card hover:shadow-lg transition-all" style="flex:1 1 340px;text-decoration:none">
                <span style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:14px;background:#D8F24B;color:#0B2E22;display:inline-block;padding:4px 12px;border-radius:999px">06</span>
                <h3 style="font-size:24px;margin:16px 0 8px;color:#0B2E22">Hasil &amp; klasemen</h3>
                <p style="color:#4A5A52;line-height:1.55;font-size:15px">Skor set dan tiebreak tersimpan akurat, klasemen poin terhitung otomatis, dan pemenang langsung melaju ke babak berikutnya.</p>
            </a>

        </div>
    </div>

    <!-- Section 3: 4-Step Alur Turnamen (Dark Green Box) -->
    <div style="max-width:1200px;margin:0 auto;padding:48px 24px">
        <div style="background:#0B2E22;color:#F6F3EA;border-radius:28px;padding:48px 40px">
            <h2 style="font-size:clamp(28px,3.5vw,42px);color:#F6F3EA">Alur turnamen</h2>
            <div style="display:flex;flex-wrap:wrap;gap:32px;margin-top:36px">
                <div style="flex:1 1 220px">
                    <p style="font-family:'Space Grotesk',sans-serif;font-size:48px;font-weight:700;color:#D8F24B;line-height:1">1</p>
                    <h3 style="font-size:20px;margin:10px 0 6px;color:#F6F3EA">Buat &amp; buka pendaftaran</h3>
                    <p style="color:#DCE8DF;line-height:1.5;font-size:14px">Tentukan format, permukaan lapangan, aturan set, dan kuota pemain.</p>
                </div>
                <div style="flex:1 1 220px">
                    <p style="font-family:'Space Grotesk',sans-serif;font-size:48px;font-weight:700;color:#D8F24B;line-height:1">2</p>
                    <h3 style="font-size:20px;margin:10px 0 6px;color:#F6F3EA">Bagi grup</h3>
                    <p style="color:#DCE8DF;line-height:1.5;font-size:14px">Peserta terkonfirmasi masuk ke grup dengan sistem seeding yang berimbang.</p>
                </div>
                <div style="flex:1 1 220px">
                    <p style="font-family:'Space Grotesk',sans-serif;font-size:48px;font-weight:700;color:#D8F24B;line-height:1">3</p>
                    <h3 style="font-size:20px;margin:10px 0 6px;color:#F6F3EA">Mainkan &amp; input skor</h3>
                    <p style="color:#DCE8DF;line-height:1.5;font-size:14px">Ikuti jadwal pertandingan di lapangan, catat hasil game dan set wasit lapangan.</p>
                </div>
                <div style="flex:1 1 220px">
                    <p style="font-family:'Space Grotesk',sans-serif;font-size:48px;font-weight:700;color:#D8F24B;line-height:1">4</p>
                    <h3 style="font-size:20px;margin:10px 0 6px;color:#F6F3EA">Knockout &amp; juara</h3>
                    <p style="color:#DCE8DF;line-height:1.5;font-size:14px">Bracket terbentuk otomatis dari juara dan runner-up grup sampai meraih trofi.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 4: Hari Pertandingan (Jadwal Hari Ini & Klasemen Grup B) -->
    <div style="max-width:1200px;margin:0 auto;padding:40px 24px 84px">
        <h2 style="font-size:clamp(28px,3.5vw,40px);margin-bottom:28px;color:#0B2E22">Hari pertandingan</h2>
        
        <div style="display:flex;flex-wrap:wrap;gap:24px">
            
            <!-- Jadwal Hari Ini Card -->
            <div class="card" style="flex:1 1 440px">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
                    <h3 style="font-size:22px;color:#0B2E22">Jadwal pertandingan</h3>
                    <a href="{{ route('tournaments.index', ['tournament' => $activeTournament->id, 'tab' => 'schedule']) }}" style="font-weight:700;text-decoration:underline;color:#0B2E22;font-size:14px">
                        Lihat semua
                    </a>
                </div>

                @if($todayMatches->isNotEmpty())
                    @foreach($todayMatches->take(4) as $m)
                        <div style="display:flex;gap:16px;align-items:center;padding:14px 0;border-bottom:1px solid #EFECE0">
                            <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:18px;width:64px;color:#0B2E22">
                                {{ $m->match_time ? \Carbon\Carbon::parse($m->match_time)->format('H.i') : '10.00' }}
                            </p>
                            <div style="flex:1">
                                <p style="font-weight:700;color:#0B2E22;font-size:15px">
                                    {{ $m->player1->display_name ?? 'Pemain 1' }} vs {{ $m->player2->display_name ?? 'Pemain 2' }}
                                </p>
                                <p style="font-size:13px;color:#4A5A52">
                                    {{ $m->round_label }} · {{ $m->court ?: 'Center Court' }}
                                    @if($m->score_display) · <strong style="color:#0B2E22">{{ $m->score_display }}</strong> @endif
                                </p>
                            </div>
                            @if($m->status === 'completed' || $m->status === 'walkover')
                                <span class="tag t-done">Selesai</span>
                            @elseif($m->status === 'live')
                                <span class="tag t-live">Berlangsung</span>
                            @else
                                <span class="tag t-open">Terjadwal</span>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div style="display:flex;gap:16px;align-items:center;padding:16px 0;border-bottom:1px solid #EFECE0">
                        <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:20px;width:64px">09.00</p>
                        <div style="flex:1"><p style="font-weight:600">Arif Setiawan vs Dimas Wicaksono</p><p style="font-size:14px;color:#4A5A52">Perempat final · Lapangan 2</p></div>
                        <span class="tag t-done">Selesai</span>
                    </div>
                    <div style="display:flex;gap:16px;align-items:center;padding:16px 0;border-bottom:1px solid #EFECE0">
                        <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:20px;width:64px">11.00</p>
                        <div style="flex:1"><p style="font-weight:600">Farhan Alamsyah vs Eka Putra</p><p style="font-size:14px;color:#4A5A52">Perempat final · Lapangan 1</p></div>
                        <span class="tag t-live">Berlangsung</span>
                    </div>
                    <div style="display:flex;gap:16px;align-items:center;padding:16px 0">
                        <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:20px;width:64px">15.00</p>
                        <div style="flex:1"><p style="font-weight:600">Raka Pratama vs Galih Permana</p><p style="font-size:14px;color:#4A5A52">Semifinal · Lapangan 1</p></div>
                        <span class="tag t-open">Terjadwal</span>
                    </div>
                @endif
            </div>

            <!-- Klasemen Grup B Card -->
            <div class="card" style="flex:1 1 440px">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
                    <h3 style="font-size:22px;color:#0B2E22">
                        {{ $secondGroup ? 'Klasemen ' . $secondGroup->name : 'Klasemen Grup B' }}
                    </h3>
                    <a href="{{ route('tournaments.index', ['tournament' => $activeTournament->id, 'tab' => 'group']) }}" style="font-weight:700;text-decoration:underline;color:#0B2E22;font-size:14px">
                        Semua grup
                    </a>
                </div>

                @if($secondGroupStandings->isNotEmpty())
                    <table>
                        <thead>
                            <tr>
                                <th style="width:36px">#</th>
                                <th>Pemain</th>
                                <th style="text-align:center">M</th>
                                <th style="text-align:center">Set</th>
                                <th style="text-align:center">Poin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($secondGroupStandings->take(4) as $idx => $st)
                                <tr style="{{ $idx === 3 ? 'border-bottom:0' : '' }}">
                                    <td style="font-weight:700;color:{{ $idx < 2 ? '#0B2E22' : '#8A9990' }}">
                                        {{ $idx + 1 }}
                                    </td>
                                    <td style="font-weight:{{ $idx < 2 ? '700' : '500' }}">
                                        {{ $st['participant']->display_name }}
                                        @if($idx < 2)
                                            <span style="font-size:11px;color:#14503B;font-weight:700;margin-left:4px">✓ Lolos</span>
                                        @endif
                                    </td>
                                    <td style="text-align:center;font-weight:600">{{ $st['won'] }}</td>
                                    <td style="text-align:center;font-family:'Space Grotesk',monospace;font-size:13px">{{ $st['sets_won'] }}-{{ $st['sets_lost'] }}</td>
                                    <td style="text-align:center;font-weight:700;color:#0B2E22">{{ $st['points'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <table>
                        <thead>
                            <tr><th style="width:36px">#</th><th>Pemain</th><th style="text-align:center">M</th><th style="text-align:center">Set</th><th style="text-align:center">Poin</th></tr>
                        </thead>
                        <tbody>
                            <tr><td style="font-weight:700">1</td><td style="font-weight:700">Arif Setiawan</td><td style="text-align:center">3</td><td style="text-align:center">6-2</td><td style="text-align:center;font-weight:700">6</td></tr>
                            <tr><td style="font-weight:700">2</td><td style="font-weight:700">Yoga Saputra</td><td style="text-align:center">3</td><td style="text-align:center">5-3</td><td style="text-align:center;font-weight:700">4</td></tr>
                            <tr><td style="font-weight:700">3</td><td>Eko Prasetyo</td><td style="text-align:center">3</td><td style="text-align:center">3-5</td><td style="text-align:center;font-weight:700">2</td></tr>
                            <tr style="border-bottom:0"><td style="font-weight:700">4</td><td>Hendra Kusuma</td><td style="text-align:center">3</td><td style="text-align:center">2-6</td><td style="text-align:center;font-weight:700">0</td></tr>
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>

    <!-- Section 5: Bottom CTA Banner (Lime #D8F24B) -->
    <div style="background:#D8F24B">
        <div style="max-width:1200px;margin:0 auto;padding:64px 24px;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:24px">
            <h2 style="font-size:clamp(28px,4vw,44px);max-width:620px;color:#0B2E22;font-weight:700">
                Siap menggelar turnamen pertamamu?
            </h2>
            <button onclick="openModal('modalCreateTournament')" class="btn btn-d">
                Buat Turnamen Sekarang
            </button>
        </div>
    </div>
</div>
