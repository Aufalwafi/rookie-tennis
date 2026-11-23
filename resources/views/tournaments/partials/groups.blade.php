<div>
    <!-- Deep Green Hero Banner (from Grup dan Klasemen.html) -->
    <div style="background:#0B2E22;color:#F6F3EA">
        <div style="max-width:1200px;margin:0 auto;padding:40px 24px 56px;display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:24px">
            <div style="display:flex;flex-direction:column;gap:12px;flex:1 1 420px">
                <span class="tag t-open" style="align-self:flex-start">Babak grup aktif</span>
                <h1 style="font-size:clamp(34px,5vw,54px);line-height:1.05;font-weight:700">Grup &amp; klasemen</h1>
                <p style="font-size:17px;color:#DCE8DF">
                    {{ $activeTournament->name }} · {{ $activeTournament->groups->count() }} grup · {{ $activeTournament->advance_per_group }} pemain teratas tiap grup lolos ke perempat final
                </p>
            </div>

            <!-- Group Management Buttons -->
            <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:center">
                <form action="{{ route('groups.draw', $activeTournament->id) }}" method="POST" onsubmit="return confirm('Undi ulang pembagian grup?')">
                    @csrf
                    <button type="submit" class="btn btn-l">
                        Undi ulang grup
                    </button>
                </form>

                <form action="{{ route('groups.matches', $activeTournament->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-l">
                        Generate jadwal grup
                    </button>
                </form>

                @if($activeTournament->format === 'group_knockout')
                    <form action="{{ route('groups.advance', $activeTournament->id) }}" method="POST" onsubmit="return confirm('Loloskan juara & runner-up grup ke bagan knockout?')">
                        @csrf
                        <button type="submit" class="btn btn-p">
                            Loloskan ke knockout →
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content Container -->
    <div style="max-width:1200px;margin:0 auto;padding:40px 24px 96px">
        @if($activeTournament->groups->isEmpty())
            <div class="card" style="text-align:center;padding:64px 24px;border:2px dashed #E3DFD0">
                <div style="font-size:48px;margin-bottom:12px">👥</div>
                <h3 style="font-size:24px;color:#0B2E22;margin-bottom:8px">Belum Ada Pembagian Grup</h3>
                <p style="color:#4A5A52;font-size:15px;max-width:440px;margin:0 auto 24px">
                    Undi peserta ke dalam grup secara otomatis dengan memisahkan pemain unggulan (seed) secara adil.
                </p>
                <form action="{{ route('groups.draw', $activeTournament->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-p">
                        Undi Pembagian Grup Sekarang
                    </button>
                </form>
            </div>
        @else
            <div style="display:flex;flex-direction:column;gap:36px">
                @foreach($activeTournament->groups as $group)
                    @php
                        $standings = $groupStandings[$group->id] ?? [];
                        $gMatches  = $activeTournament->matches->where('group_id', $group->id);
                        $gCompleted = $gMatches->whereIn('status', ['completed', 'walkover'])->count();
                    @endphp

                    <div class="card" style="padding:0;overflow:hidden">
                        <!-- Group Card Top Header -->
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:20px 28px;border-bottom:1px solid #E3DFD0;background:#FAF8F2">
                            <div>
                                <h3 style="font-size:24px;color:#0B2E22;font-weight:700">
                                    {{ $group->name }}
                                </h3>
                                <p style="font-size:13px;color:#4A5A52;margin-top:2px">
                                    {{ count($standings) }} pemain · Top {{ $activeTournament->advance_per_group }} lolos ke babak gugur
                                </p>
                            </div>

                            <div style="display:flex;align-items:center;gap:12px">
                                <span class="tag {{ $gCompleted === $gMatches->count() && $gMatches->count() > 0 ? 't-done' : 't-open' }}">
                                    {{ $gCompleted }}/{{ $gMatches->count() }} Pertandingan
                                </span>
                            </div>
                        </div>

                        <!-- Standings Table -->
                        <div style="overflow-x:auto">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width:40px">#</th>
                                        <th>Pemain / Pasangan</th>
                                        <th style="text-align:center">Main</th>
                                        <th style="text-align:center">M</th>
                                        <th style="text-align:center">K</th>
                                        <th style="text-align:center">Set</th>
                                        <th style="text-align:center">±Set</th>
                                        <th style="text-align:center">Game</th>
                                        <th style="text-align:center;font-weight:700">Poin</th>
                                        <th style="text-align:right;padding-right:24px">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($standings as $row)
                                        <tr style="{{ $row['is_qualified'] ? 'background:#F1F9C9;' : '' }}">
                                            <td style="font-weight:700;color:{{ $row['is_qualified'] ? '#0B2E22' : '#8A9990' }}">
                                                {{ $row['rank'] }}
                                            </td>
                                            <td>
                                                <div style="display:flex;align-items:center;gap:8px">
                                                    @if($row['participant']->seed)
                                                        <span class="tag" style="background:#0B2E22;color:#D8F24B;padding:2px 7px;font-size:11px">#{{ $row['participant']->seed }}</span>
                                                    @endif
                                                    <span style="font-weight:{{ $row['is_qualified'] ? '700' : '600' }};color:#0B2E22">
                                                        {{ $row['participant']->display_name }}
                                                    </span>
                                                </div>
                                                @if($row['participant']->club)
                                                    <div style="font-size:12px;color:#4A5A52;margin-top:2px">{{ $row['participant']->club }}</div>
                                                @endif
                                            </td>
                                            <td style="text-align:center;font-weight:600">{{ $row['played'] }}</td>
                                            <td style="text-align:center;font-weight:700;color:#0B2E22">{{ $row['won'] }}</td>
                                            <td style="text-align:center;color:#B8442D;font-weight:600">{{ $row['lost'] }}</td>
                                            <td style="text-align:center;font-family:'Space Grotesk',monospace;font-size:13px">{{ $row['sets_won'] }}-{{ $row['sets_lost'] }}</td>
                                            <td style="text-align:center;font-weight:700;color:{{ $row['sets_diff'] > 0 ? '#0B2E22' : ($row['sets_diff'] < 0 ? '#B8442D' : '#8A9990') }}">
                                                {{ $row['sets_diff'] > 0 ? '+'.$row['sets_diff'] : $row['sets_diff'] }}
                                            </td>
                                            <td style="text-align:center;font-family:'Space Grotesk',monospace;font-size:13px">{{ $row['games_won'] }}-{{ $row['games_lost'] }}</td>
                                            <td style="text-align:center;font-weight:700;font-size:16px;color:#0B2E22">
                                                {{ $row['points'] }}
                                            </td>
                                            <td style="text-align:right;padding-right:24px">
                                                @if($row['is_qualified'])
                                                    <span class="tag t-open" style="font-size:11px">✓ Lolos</span>
                                                @else
                                                    <span style="font-size:12px;color:#8A9990">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="10" style="text-align:center;padding:24px;color:#8A9990">Belum ada peserta dalam grup ini.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Group Matches Fixtures -->
                        @if($gMatches->isNotEmpty())
                            <div style="padding:24px 28px;background:#FAF8F2;border-top:1px solid #E3DFD0">
                                <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:16px;color:#0B2E22;margin-bottom:14px">
                                    Pertandingan {{ $group->name }}
                                </p>
                                <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));gap:16px">
                                    @foreach($gMatches as $m)
                                        @include('tournaments.partials.match_card', ['match' => $m, 'title' => ($m->court ?: 'Court 1') . ' · ' . ($m->match_time ? \Carbon\Carbon::parse($m->match_time)->format('H.i') : 'TBD')])
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
