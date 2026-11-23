# TennisChallenge – Aplikasi Turnamen Tennis (Versi Laravel 12)

Aplikasi web turnamen tenis lengkap berbasis **Laravel 12** dengan arsitektur MVC, database SQLite, dan tampilan modern menggunakan Tailwind CSS.

---

## 🌟 Fitur Utama (Sesuai Permintaan)

1. **Membuat Turnamen (`Membuat turnamen`)**
   - Mendukung berbagai kategori: Tunggal Putra, Tunggal Putri, Ganda Putra, Ganda Putri, dan Ganda Campuran.
   - Pilihan permukaan lapangan tenis: **Hard Court**, **Clay (Tanah Liat)**, **Grass (Rumput)**, dan **Carpet**.
   - Pilihan format turnamen: **Fase Grup + Knockout**, **Sistem Gugur (Knockout Murni)**, dan **Round Robin Murni**.
   - Pengaturan aturan set: Best of 3 Sets, Best of 1 Set, Pro Set 8 Games.
   - Lokasi stadion dan alokasi lapangan (*Center Court, Court 1, Court 2*).

2. **Pendaftaran Peserta (`Pendaftaran peserta`)**
   - Registrasi pemain tunggal dan pasangan ganda.
   - Penentuan pemain unggulan (*Seed* #1 s/d #32), peringkat, klub/akademi, dan kota asal.
   - Fitur **Auto-Generate Pemain (+8 / +16 pemain)** untuk pengujian instan dengan profil pemain tenis nyata.
   - Fitur pencarian instan (*live search*) berdasarkan nama, klub, atau kota.

3. **Pembagian Grup (`Pembagian grup`)**
   - **Undian Grup Otomatis (*Snake Seeding Distribution*)**: Mendistribusikan pemain unggulan secara adil di grup berbeda dan mengacak pemain non-unggulan.
   - Generator jadwal round robin grup otomatis.
   - Tombol **"Loloskan ke Bagan Knockout"** untuk menghubungkan juara & runner-up grup langsung ke babak semifinal/final.

4. **Sistem Knockout (`Sistem knockout`)**
   - Visualisasi bagan turnamen interaktif (*Tournament Bracket Tree*): Perempat Final (QF) → Semifinal (SF) → Grand Final → Playoff Juara 3.
   - **Auto-Progression**: Pemenang setiap pertandingan otomatis maju mengisi slot babak berikutnya di bagan.
   - Animasi konfeti perayaan (*celebration confetti*) saat juara turnamen selesai dinobatkan.

5. **Jadwal Pertandingan (`Jadwal pertandingan`)**
   - Tampilan *Order of Play* profesional layaknya turnamen Grand Slam.
   - Filter pertandingan berdasarkan Tanggal, Lapangan, Babak (*Grup/QF/SF/Final*), dan Status (*Terjadwal, Live, Selesai*).
   - Modal edit jadwal untuk mengubah tanggal, jam tayang, dan lapangan pertandingan.

6. **Input Hasil Pertandingan (`Input hasil pertandingan`)**
   - Modal skor set-by-set (Set 1, Set 2, Set 3) dengan penanganan Tiebreak otomatis (misal: 7-6(5)).
   - Penanganan status Walkover (W.O).
   - Tombol preset skor cepat (*6-4, 6-3*, *6-2, 6-1*, *Super Tiebreak 10-8*, dll.).
   - **Mode Wasit Live (Live Umpire Scoreboard)**: Papan wasit interaktif dengan sistem hitung skor tenis asli: 0 → 15 → 30 → 40 → Deuce → Advantage → Game.

7. **Klasemen dan Bracket Turnamen (`Klasemen dan bracket turnamen`)**
   - Perhitungan klasemen grup tenis real-time:
     - Main (M), Menang (W), Kalah (L)
     - Set Menang-Kalah dan Selisih Set (+/-)
     - Game Menang-Kalah dan Selisih Game (+/-)
     - Total Poin
     - Kriteria *Tiebreaker*: Poin → Head-to-Head → Selisih Set → Selisih Game.
   - Badge status lolos kualifikasi ke babak gugur.
   - Fitur **Cetak Laporan / Simpan PDF** resmi dengan kop surat turnamen.

---

## 🚀 Cara Menjalankan Aplikasi di Komputer Anda

Pastikan Anda berada di direktori proyek `d:\tenis challange`:

### 1. Jalankan Server Laravel:
```bash
php artisan serve
```

Buka browser Anda dan akses:
👉 **`http://127.0.0.1:8000`**

### 2. (Opsional) Reset Data Contoh Kapan Saja:
Jika ingin mereset kembali database ke kondisi data contoh awal:
```bash
php artisan db:seed --force
```
*(Atau cukup klik tombol reload merah di navbar aplikasi)*

---

## 🗂️ Struktur File Laravel

```
app/
├── Http/Controllers/
│   ├── TournamentController.php   # Manajemen turnamen, dashboard & laporan
│   ├── ParticipantController.php  # Pendaftaran & generator peserta
│   ├── GroupStageController.php   # Undian grup & kualifikasi knockout
│   ├── BracketController.php      # Bagan sistem gugur
│   ├── ScheduleController.php     # Order of play & jadwal
│   └── ScoreController.php        # Input skor & wasit live
├── Models/
│   ├── Tournament.php             # Model turnamen tenis
│   ├── Participant.php            # Model peserta/tim
│   ├── TournamentGroup.php        # Model grup fase penyisihan
│   └── TennisMatch.php            # Model pertandingan & skor
└── Services/
    └── TennisService.php          # Logika bisnis klasemen, tiebreaker, & bracket

database/
├── database.sqlite                # Database SQLite lokal (tanpa perlu setting MySQL)
├── migrations/                    # Skema tabel turnamen, peserta, grup, pertandingan
└── seeders/DatabaseSeeder.php     # Data awal (Jakarta Open & Bali Clay Masters)

resources/views/
├── layouts/app.blade.php          # Layout utama & Tailwind CSS
└── tournaments/
    ├── show.blade.php             # Dashboard utama turnamen
    ├── report.blade.php           # Template cetak laporan PDF
    ├── empty.blade.php            # Tampilan jika belum ada turnamen
    ├── partials/
    │   ├── bracket.blade.php      # Bagan knockout visual
    │   ├── groups.blade.php       # Fase grup & tabel klasemen tenis
    │   ├── schedule.blade.php     # Jadwal pertandingan & filter
    │   ├── participants.blade.php # Tabel pendaftaran peserta
    │   └── match_card.blade.php   # Kartu pertandingan
    └── modals/
        └── all_modals.blade.php   # Modal buat/edit turnamen, input skor, wasit live
```
