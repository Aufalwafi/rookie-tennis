@extends('layouts.app')

@section('title', 'Daftar Akun — Rookie Tennis')

@section('content')
<div style="display:flex;flex-wrap:wrap;min-height:calc(100vh - 80px)">
    <!-- Left Hero Column (Forest Green) -->
    <div style="flex:1 1 480px;background:#0B2E22;color:#F6F3EA;display:flex;align-items:flex-start;justify-content:flex-end">
        <div style="max-width:560px;width:100%;padding:64px 32px;display:flex;flex-direction:column;gap:24px">
            <h1 style="font-size:clamp(36px,4.5vw,54px);line-height:1.05;font-weight:700">
                Satu akun, dua peran.
            </h1>
            <p style="font-size:18px;line-height:1.55;color:#DCE8DF">
                Pilih peran saat mendaftar. Kamu bisa menambah peran lain nanti dari pengaturan akun.
            </p>

            <!-- Role Card: Peserta -->
            <div style="background:rgba(255,255,255,.08);border-radius:20px;padding:24px;display:flex;flex-direction:column;gap:14px;border:1px solid rgba(255,255,255,0.1)">
                <h3 style="font-size:22px;color:#D8F24B;font-weight:700">🎾 Peserta</h3>
                <div class="ck"><b></b><span>Mendaftar ke turnamen dengan data tersimpan</span></div>
                <div class="ck"><b></b><span>Melihat grup, jadwal, dan lawan berikutnya</span></div>
                <div class="ck"><b></b><span>Riwayat hasil pertandingan pribadi</span></div>
            </div>

            <!-- Role Card: Penyelenggara -->
            <div style="background:rgba(255,255,255,.08);border-radius:20px;padding:24px;display:flex;flex-direction:column;gap:14px;border:1px solid rgba(255,255,255,0.1)">
                <h3 style="font-size:22px;color:#D8F24B;font-weight:700">👑 Penyelenggara (Admin)</h3>
                <div class="ck"><b></b><span>Membuat turnamen dan membuka pendaftaran</span></div>
                <div class="ck"><b></b><span>Mengonfirmasi peserta, membagi grup, dan mengatur jadwal</span></div>
                <div class="ck"><b></b><span>Menginput hasil pertandingan dan mengelola bracket</span></div>
            </div>
        </div>
    </div>

    <!-- Right Form Column -->
    <div style="flex:1 1 480px;display:flex;align-items:flex-start;justify-content:flex-start;background:#F6F3EA">
        <div style="max-width:520px;width:100%;padding:64px 32px;display:flex;flex-direction:column;gap:20px">
            <h2 style="font-size:34px;color:#0B2E22;font-weight:700">Buat akun baru</h2>

            @if($errors->any())
                <div style="padding:12px 16px;border-radius:12px;background:#FEE2E2;border:1px solid #EF4444;color:#991B1B;font-size:14px;font-weight:600">
                    <ul style="margin:0;padding-left:18px">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" style="display:flex;flex-direction:column;gap:18px">
                @csrf
                
                <!-- Role Selector -->
                <div>
                    <p style="font-size:14px;font-weight:600;margin-bottom:8px;color:#0B2E22">Saya mendaftar sebagai</p>
                    <div style="display:flex;flex-wrap:wrap;gap:12px">
                        <label class="opt {{ old('role', 'user') === 'user' ? 'opt-on' : '' }}" id="labelRoleUser" style="flex:1 1 180px">
                            <input id="r1" type="radio" name="role" value="user" {{ old('role', 'user') === 'user' ? 'checked' : '' }} onchange="updateRoleOpt()" style="accent-color:#0B2E22">
                            <div>
                                <span style="font-weight:700;display:block">🎾 Peserta</span>
                                <span style="display:block;font-weight:400;color:#4A5A52;margin-top:2px;font-size:13px">Ikut bertanding</span>
                            </div>
                        </label>
                        <label class="opt {{ old('role') === 'admin' ? 'opt-on' : '' }}" id="labelRoleAdmin" style="flex:1 1 180px">
                            <input id="r2" type="radio" name="role" value="admin" {{ old('role') === 'admin' ? 'checked' : '' }} onchange="updateRoleOpt()" style="accent-color:#0B2E22">
                            <div>
                                <span style="font-weight:700;display:block">👑 Penyelenggara</span>
                                <span style="display:block;font-weight:400;color:#4A5A52;margin-top:2px;font-size:13px">Mengelola turnamen</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="nm">Nama lengkap *</label>
                    <input id="nm" name="name" type="text" value="{{ old('name') }}" required placeholder="Contoh: Raka Pratama">
                </div>

                <div>
                    <label for="em">Email *</label>
                    <input id="em" name="email" type="email" value="{{ old('email') }}" required placeholder="nama@email.com">
                </div>

                <div>
                    <label for="wa">Nomor WhatsApp</label>
                    <input id="wa" name="phone" type="tel" value="{{ old('phone') }}" placeholder="08xx xxxx xxxx">
                </div>

                <div style="display:flex;flex-wrap:wrap;gap:16px">
                    <div style="flex:1 1 200px">
                        <label for="pw">Kata sandi *</label>
                        <input id="pw" name="password" type="password" required placeholder="Minimal 8 karakter">
                    </div>
                    <div style="flex:1 1 200px">
                        <label for="pw2">Ulangi kata sandi *</label>
                        <input id="pw2" name="password_confirmation" type="password" required placeholder="Ketik ulang">
                    </div>
                </div>

                <div style="display:flex;gap:12px;align-items:flex-start">
                    <input id="sk" type="checkbox" required checked>
                    <label for="sk" style="font-weight:400;margin:0;line-height:1.5;font-size:13px;color:#4A5A52;cursor:pointer">
                        Saya setuju dengan syarat penggunaan dan kebijakan privasi Rookie Tennis.
                    </label>
                </div>

                <button type="submit" class="btn btn-d" style="width:100%">
                    Buat akun
                </button>
            </form>

            <p style="font-size:14px;color:#4A5A52">
                Sudah punya akun? 
                <a href="{{ route('login') }}" style="text-decoration:underline;font-weight:600;color:#0B2E22">
                    Masuk
                </a>
            </p>
        </div>
    </div>
</div>

<script>
    function updateRoleOpt() {
        const isUser = document.getElementById('r1').checked;
        const lUser = document.getElementById('labelRoleUser');
        const lAdmin = document.getElementById('labelRoleAdmin');
        if (isUser) {
            lUser.classList.add('opt-on');
            lAdmin.classList.remove('opt-on');
        } else {
            lUser.classList.remove('opt-on');
            lAdmin.classList.add('opt-on');
        }
    }
</script>
@endsection
