@php
    $p1Winner = $match->winner_id && $match->winner_id == $match->player1_id;
    $p2Winner = $match->winner_id && $match->winner_id == $match->player2_id;
    $isLive = $match->status === 'live';
    $isCompleted = $match->status === 'completed' || $match->status === 'walkover';
@endphp

<div class="bracket-card" style="{{ $isLive ? 'border:2px solid #B8442D;' : '' }}min-width:270px">
    
    <!-- Match Card Header -->
    <div class="bracket-card-header">
        <span style="font-weight:600;color:#0B2E22">{{ $title ?? ($match->court ?: 'Lapangan') }}</span>
        @if($isLive)
            <span class="tag t-live">Berlangsung</span>
        @elseif($isCompleted)
            <span class="tag t-done">Selesai</span>
        @else
            <span class="tag t-open">{{ $match->match_time ? \Carbon\Carbon::parse($m->match_time ?? '')->format('H.i') : 'Terjadwal' }}</span>
        @endif
    </div>

    <!-- Players & Scores -->
    <div>
        <!-- Player 1 -->
        <div class="bracket-player-row {{ $p1Winner ? 'winner' : '' }}">
            <span class="bracket-player-seed">
                @if($match->player1 && $match->player1->seed)
                    #{{ $match->player1->seed }}
                @else
                    {{ $match->player1 ? '·' : '-' }}
                @endif
            </span>
            <span class="bracket-player-name" style="font-weight:{{ $p1Winner ? '700' : '600' }};color:#0B2E22">
                {{ $match->player1 ? $match->player1->display_name : 'Menunggu...' }}
                @if($p1Winner) <span style="color:#0B2E22;margin-left:4px">✓</span> @endif
            </span>
            <div style="display:flex;gap:4px">
                @if(!empty($match->sets))
                    @foreach($match->sets as $s)
                        @php $sP1 = (int)($s['p1Games'] ?? 0); $sP2 = (int)($s['p2Games'] ?? 0); @endphp
                        <span class="bracket-player-score" style="color:{{ $sP1 > $sP2 ? '#0B2E22' : '#8A9990' }};font-weight:{{ $sP1 > $sP2 ? '700' : '500' }}">
                            {{ $sP1 }}
                        </span>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Player 2 -->
        <div class="bracket-player-row {{ $p2Winner ? 'winner' : '' }}">
            <span class="bracket-player-seed">
                @if($match->player2 && $match->player2->seed)
                    #{{ $match->player2->seed }}
                @else
                    {{ $match->player2 ? '·' : '-' }}
                @endif
            </span>
            <span class="bracket-player-name" style="font-weight:{{ $p2Winner ? '700' : '600' }};color:#0B2E22">
                {{ $match->player2 ? $match->player2->display_name : 'Menunggu...' }}
                @if($p2Winner) <span style="color:#0B2E22;margin-left:4px">✓</span> @endif
            </span>
            <div style="display:flex;gap:4px">
                @if(!empty($match->sets))
                    @foreach($match->sets as $s)
                        @php $sP1 = (int)($s['p1Games'] ?? 0); $sP2 = (int)($s['p2Games'] ?? 0); @endphp
                        <span class="bracket-player-score" style="color:{{ $sP2 > $sP1 ? '#0B2E22' : '#8A9990' }};font-weight:{{ $sP2 > $sP1 ? '700' : '500' }}">
                            {{ $sP2 }}
                        </span>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Actions Footer -->
    @if($match->player1_id && $match->player2_id)
        <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 14px;background:#FAF8F2;border-top:1px solid #EFECE0">
            <span style="font-size:12px;color:#4A5A52;font-weight:500">
                {{ $match->court ?: 'Court 1' }}
            </span>
            <div style="display:flex;gap:6px">
                <button type="button" onclick="openLiveUmpireModal({{ $match->id }}, {{ $match->player1_id }}, {{ $match->player2_id }}, '{{ addslashes($match->player1->display_name ?? '') }}', '{{ addslashes($match->player2->display_name ?? '') }}', '{{ addslashes($match->court ?: 'Court') }}')"
                    class="btn btn-s" style="min-height:30px;padding:0 10px;font-size:11px;border-color:#B8442D;color:#B8442D">
                    🔴 Live
                </button>
                <button type="button" onclick="openScoreModal({{ $match->id }}, {{ $match->player1_id }}, {{ $match->player2_id }}, '{{ addslashes($match->player1->display_name ?? '') }}', '{{ addslashes($match->player2->display_name ?? '') }}', {{ json_encode($match->sets ?? []) }}, '{{ $match->winner_id }}', '{{ $match->status }}', '{{ addslashes($match->notes ?? '') }}')"
                    class="btn btn-p" style="min-height:30px;padding:0 12px;font-size:12px">
                    {{ $isCompleted ? 'Ubah' : 'Input' }}
                </button>
            </div>
        </div>
    @endif
</div>
