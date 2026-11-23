@extends('layouts.app')

@section('title', 'Masuk — Rookie Tennis')

@section('content')
<div style="display:flex;flex-wrap:wrap;min-height:calc(100vh - 80px)">
    <!-- Left Hero Column (Forest Green) -->
    <div style="flex:1 1 480px;background:#0B2E22;color:#F6F3EA;display:flex;align-items:center;justify-content:flex-end">
        <div style="max-width:560px;width:100%;padding:64px 32px;display:flex;flex-direction:column;gap:24px">
            <svg width="64" height="64" viewBox="0 0 32 32" aria-hidden="true">
                <circle cx="16" cy="16" r="15" fill="#D8F24B"></circle>
                <path d="M4 9c7 3 7 11 0 14M28 9c-7 3-7 11 0 14" stroke="#0B2E22" stroke-width="2" fill="none"></path>
            </svg>
            <h1 style="font-size:clamp(38px,5vw,60px);line-height:1.04;font-weight:700">
                Selamat datang kembali.
            </h1>
            <p style="font-size:18px;line-height:1.55;color:#DCE8DF">
                Masuk untuk mengelola turnamen sebagai penyelenggara, atau melihat jadwal dan hasil pertandinganmu sebagai peserta.
            </p>

            <!-- Demo Account Pills for Quick Testing -->
            <div style="margin-top:16px;background:rgba(255,255,255,0.08);border-radius:18px;padding:20px;border:1px solid rgba(255,255,255,0.12)">
                <span style="font-size:12px;font-weight:700;letter-spacing:0.05em;color:#D8F24B;text-transform:uppercase;display:block;margin-bottom:10px">
                    ⚡ Akun Demo Siap Pakai:
                </span>
                <div style="display:flex;flex-direction:column;gap:10px">
                    <button type="button" onclick="fillDemo('admin@rookietennis.com', 'admin123')"
                        class="text-left px-3 py-2 rounded-xl bg-white/10 hover:bg-white/20 transition-all text-xs text-white flex items-center justify-between">
                        <div>
                            <span class="font-bold text-[#D8F24B]">👑 Penyelenggara (Admin)</span>
                            <span class="block text-[11px] text-[#DCE8DF]">admin@rookietennis.com • admin123</span>
                        </div>
                        <span class="text-[11px] font-bold underline text-[#D8F24B]">Gunakan</span>
                    </button>
                    <button type="button" onclick="fillDemo('peserta@rookietennis.com', 'user123')"
                        class="text-left px-3 py-2 rounded-xl bg-white/10 hover:bg-white/20 transition-all text-xs text-white flex items-center justify-between">
                        <div>
                            <span class="font-bold text-white">🎾 Peserta (User)</span>
                            <span class="block text-[11px] text-[#DCE8DF]">peserta@rookietennis.com • user123</span>
                        </div>
                        <span class="text-[11px] font-bold underline text-[#D8F24B]">Gunakan</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Form Column -->
    <div style="flex:1 1 480px;display:flex;align-items:center;justify-content:flex-start;background:#F6F3EA">
        <div style="max-width:520px;width:100%;padding:64px 32px;display:flex;flex-direction:column;gap:20px">
            <h2 style="font-size:34px;color:#0B2E22;font-weight:700">Masuk ke akun</h2>

            @if($errors->any())
                <div style="padding:12px 16px;border-radius:12px;background:#FEE2E2;border:1px solid #EF4444;color:#991B1B;font-size:14px;font-weight:600">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" style="display:flex;flex-direction:column;gap:18px">
                @csrf
                <div>
                    <label for="em">Email</label>
                    <input id="em" name="email" type="email" value="{{ old('email') }}" required placeholder="nama@email.com" autofocus>
                </div>

                <div>
                    <label for="pw">Kata sandi</label>
                    <input id="pw" name="password" type="password" required placeholder="Masukkan kata sandi">
                </div>

                <div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:12px">
                    <div style="display:flex;align-items:center;gap:10px">
                        <input id="ing" name="remember" type="checkbox">
                        <label for="ing" style="margin:0;font-weight:500;cursor:pointer">Ingat saya</label>
                    </div>
                    <span style="font-size:14px;color:#4A5A52">Bantuan masuk</span>
                </div>

                <button type="submit" class="btn btn-d" style="width:100%">
                    Masuk
                </button>
            </form>

            <div style="display:flex;align-items:center;gap:12px;color:#4A5A52;font-size:14px">
                <span style="flex:1;height:1px;background:#CFCBB8"></span>atau<span style="flex:1;height:1px;background:#CFCBB8"></span>
            </div>

            <a class="btn btn-l" href="{{ route('register') }}" style="text-align:center">
                Buat akun baru
            </a>

            <p style="font-size:14px;color:#4A5A52;line-height:1.5">
                Hanya ingin melihat jadwal dan klasemen? Tidak perlu masuk, 
                <a href="{{ route('tournaments.index', ['tab' => 'tournaments']) }}" style="text-decoration:underline;font-weight:600;color:#0B2E22">
                    lihat turnamen
                </a>.
            </p>
        </div>
    </div>
</div>

<script>
    function fillDemo(email, password) {
        document.getElementById('em').value = email;
        document.getElementById('pw').value = password;
    }
</script>
@endsection
