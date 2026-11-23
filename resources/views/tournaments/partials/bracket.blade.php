@php
    $koMatches = $activeTournament->matches->where('stage', '!=', 'group');
    $qfMatches = $koMatches->where('stage', 'quarterfinal')->sortBy('order');
    $sfMatches = $koMatches->where('stage', 'semifinal')->sortBy('order');
    $finalMatch = $koMatches->firstWhere('stage', 'final');
    $thirdPlaceMatch = $koMatches->firstWhere('stage', 'third_place');
    $champion = $finalMatch && $finalMatch->winner ? $finalMatch->winner : null;

    $isDone = $champion !== null;
    $hasLive = $koMatches->where('status', 'live')->count() > 0;
@endphp

<div>
    <!-- Deep Green Hero Banner (from Bracket Knockout.html) -->
    <div style="background:#0B2E22;color:#F6F3EA">
        <div style="max-width:1200px;margin:0 auto;padding:40px 24px 56px;display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:24px">
            <div style="display:flex;flex-direction:column;gap:12px;flex:1 1 420px">
                @if($isDone)
                    <span class="tag t-done" style="align-self:flex-start">Turnamen Selesai</span>
                @elseif($hasLive)
                    <span class="tag t-live" style="align-self:flex-start">Babak knockout berlangsung</span>
                @else
                    <span class="tag t-open" style="align-self:flex-start">Bagan Knockout Terjadwal</span>
                @endif

                <h1 style="font-size:clamp(34px,5vw,54px);line-height:1.05;font-weight:700">Bracket knockout</h1>
                <p style="font-size:17px;color:#DCE8DF">
                    {{ $activeTournament->name }} · {{ $activeTournament->category_label }} · {{ $activeTournament->match_rule_label }}
                </p>
            </div>

            <!-- Action Buttons -->
            <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:center">
                <a class="btn btn-l" href="{{ route('tournaments.index', ['tournament' => $activeTournament->id, 'tab' => 'group']) }}">
                    Lihat klasemen grup
                </a>
                <a class="btn btn-p" href="{{ route('tournaments.index', ['tournament' => $activeTournament->id, 'tab' => 'schedule']) }}">
                    Jadwal Pertandingan
                </a>
                @if(auth()->check() && auth()->user()->isAdmin())
                    <form action="{{ route('bracket.generate', $activeTournament->id) }}" method="POST" class="inline" onsubmit="return confirm('Generate ulang bagan sistem gugur?')">
                        @csrf
                        <button type="submit" class="btn btn-o" style="min-height:48px;padding:0 18px" title="Generate / Reset Bagan (Khusus Admin)">
                            ↻ Undi Bagan
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Bracket Body -->
    <div style="max-width:1200px;margin:0 auto;padding:40px 24px 96px">

        <!-- Champion Trophy Box (if Champion determined) -->
        @if($champion)
            <div class="card" style="margin-bottom:36px;background:linear-gradient(135deg, #FFFDEB 0%, #FFFFFF 100%);border:2px solid #D8F24B;text-align:center;padding:36px 24px;box-shadow:0 12px 30px rgba(11,46,34,0.08)">
                <div style="font-size:48px;margin-bottom:8px">🏆</div>
                <span class="tag t-open" style="font-size:12px;margin-bottom:8px">JUARA TURNAMEN TENNIS</span>
                <h2 style="font-size:clamp(32px,4vw,48px);font-weight:700;color:#0B2E22;margin:6px 0">
                    {{ $champion->display_name }}
                </h2>
                <p style="font-size:16px;color:#4A5A52;font-weight:500">
                    {{ $champion->club ?: 'Independen' }} · Selamat atas gelar juara di {{ $activeTournament->name }}!
                </p>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    try {
                        confetti({ particleCount: 100, spread: 80, origin: { y: 0.6 }, colors: ['#D8F24B','#0B2E22','#14503B','#B8442D'] });
                    } catch(e){}
                });
            </script>
        @endif

        @if($koMatches->isEmpty())
            <div class="card" style="text-align:center;padding:64px 24px;border:2px dashed #E3DFD0">
                <div style="font-size:48px;margin-bottom:12px">🎾</div>
                <h3 style="font-size:24px;color:#0B2E22;margin-bottom:8px">Belum Ada Bagan Knockout</h3>
                <p style="color:#4A5A52;font-size:15px;max-width:440px;margin:0 auto 24px">
                    Peserta belum diundi ke dalam bracket. Klik tombol di bawah ini untuk membuat bagan sistem gugur otomatis.
                </p>
                <form action="{{ route('bracket.generate', $activeTournament->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-p">
                        Generate Bagan Knockout Sekarang
                    </button>
                </form>
            </div>
        @else
            <!-- Scrollable Bracket Grid -->
            <div style="overflow-x:auto;padding-bottom:16px">
                <div style="display:flex;gap:28px;min-width:1160px;min-height:720px">

                    <!-- Quarterfinals (Perempat Final) -->
                    @if($qfMatches->isNotEmpty())
                        <div class="bracket-col">
                            <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:18px;text-align:center;color:#0B2E22;flex:none;margin-bottom:-6px">
                                Perempat final
                            </p>
                            @foreach($qfMatches as $m)
                                @include('tournaments.partials.match_card', ['match' => $m, 'title' => 'QF' . $loop->iteration . ' · ' . ($m->court ?: 'Court 1')])
                            @endforeach
                        </div>
                    @endif

                    <!-- Semifinals -->
                    @if($sfMatches->isNotEmpty())
                        <div class="bracket-col" style="justify-content:space-around">
                            <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:18px;text-align:center;color:#0B2E22;flex:none;margin-bottom:-6px">
                                Semifinal
                            </p>
                            @foreach($sfMatches as $m)
                                @include('tournaments.partials.match_card', ['match' => $m, 'title' => 'SF' . $loop->iteration . ' · ' . ($m->court ?: 'Court 1')])
                            @endforeach
                        </div>
                    @endif

                    <!-- Final & Perebutan Juara 3 -->
                    <div class="bracket-col" style="justify-content:center;gap:32px">
                        <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:18px;text-align:center;color:#0B2E22;flex:none;margin-bottom:-6px">
                            Final
                        </p>

                        @if($finalMatch)
                            <div style="position:relative">
                                <div style="margin-bottom:8px;text-align:center">
                                    <span class="tag t-open" style="font-size:12px">🏆 Perebutan Juara 1</span>
                                </div>
                                @include('tournaments.partials.match_card', ['match' => $finalMatch, 'title' => 'Grand Final · ' . ($finalMatch->court ?: 'Center Court')])
                            </div>
                        @endif

                        @if($thirdPlaceMatch)
                            <div style="position:relative;margin-top:12px">
                                <div style="margin-bottom:8px;text-align:center">
                                    <span class="tag" style="background:#E3DFD0;color:#0B2E22;font-size:12px">🥉 Perebutan Juara 3</span>
                                </div>
                                @include('tournaments.partials.match_card', ['match' => $thirdPlaceMatch, 'title' => 'Bronze Match · ' . ($thirdPlaceMatch->court ?: 'Court 2')])
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        @endif

    </div>
</div>
