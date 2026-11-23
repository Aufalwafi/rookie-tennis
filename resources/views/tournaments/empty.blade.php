@extends('layouts.app')

@section('title', 'Rookie Tennis — Aplikasi Turnamen Tenis')

@section('content')
<div style="background:#0B2E22;color:#F6F3EA;min-height:80vh;display:flex;align-items:center;justify-content:center;padding:64px 24px">
    <div style="max-width:760px;width:100%;text-align:center;display:flex;flex-direction:column;align-items:center;gap:24px">
        
        <!-- Logo SVG -->
        <div style="width:76px;height:76px;border-radius:24px;background:rgba(216,242,75,0.15);border:2px solid #D8F24B;display:flex;align-items:center;justify-content:center;box-shadow:0 0 30px rgba(216,242,75,0.2)">
            <svg width="44" height="44" viewBox="0 0 32 32" aria-hidden="true">
                <circle cx="16" cy="16" r="15" fill="#D8F24B"></circle>
                <path d="M4 9c7 3 7 11 0 14M28 9c-7 3-7 11 0 14" stroke="#0B2E22" stroke-width="2.2" fill="none"></path>
            </svg>
        </div>

        <span class="tag" style="border:1.5px solid #D8F24B;color:#D8F24B;background:transparent;padding:6px 16px;font-size:13px">
            Platform Turnamen Tenis
        </span>

        <h1 style="font-size:clamp(36px,5vw,56px);line-height:1.1;font-weight:700;color:#F6F3EA;margin:0">
            Belum Ada Turnamen Tenis
        </h1>

        <p style="font-size:18px;line-height:1.6;color:#DCE8DF;max-width:540px;margin:0">
            Mulai kelola kompetisi tenis Anda dengan mudah. Buat turnamen baru dengan format grup dan sistem knockout, atau muat data sampel siap pakai lengkap dengan bagan dan klasemen.
        </p>

        <!-- CTA Action Buttons -->
        <div style="display:flex;flex-wrap:wrap;gap:16px;justify-content:center;margin-top:12px">
            <button onclick="openModal('modalCreateTournament')" class="btn btn-p" style="font-size:16px;min-height:52px;padding:0 28px">
                + Buat Turnamen Baru
            </button>
            <form action="{{ route('tournaments.reset_demo') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-o" style="font-size:16px;min-height:52px;padding:0 28px">
                    Muat Contoh Data Turnamen
                </button>
            </form>
        </div>

        <!-- 3 Highlights from Design -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;width:100%;margin-top:36px;text-align:left">
            <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:18px;padding:20px">
                <p style="font-weight:700;color:#D8F24B;font-size:14px;margin-bottom:4px">🎾 Multi-Surface</p>
                <p style="font-size:13px;color:#DCE8DF;line-height:1.5">Hard Court, Tanah Liat Roland Garros, Rumput Wimbledon & Karpet Indoor.</p>
            </div>
            <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:18px;padding:20px">
                <p style="font-weight:700;color:#D8F24B;font-size:14px;margin-bottom:4px">⚡ Grup &amp; Knockout</p>
                <p style="font-size:13px;color:#DCE8DF;line-height:1.5">Auto snake-draw grup, klasemen tiebreaker resmi, dan bagan gugur.</p>
            </div>
            <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:18px;padding:20px">
                <p style="font-weight:700;color:#D8F24B;font-size:14px;margin-bottom:4px">📢 Live Scoreboard</p>
                <p style="font-size:13px;color:#DCE8DF;line-height:1.5">Scoreboard wasit lapangan langsung (points, games, sets, advantage).</p>
            </div>
        </div>

    </div>
</div>

@push('modals')
    @include('tournaments.modals.all_modals', ['activeTournament' => new \App\Models\Tournament()])
@endpush
@endsection
