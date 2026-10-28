# Info Loker UMKM

Portal lowongan kerja untuk UMKM Indonesia — mirip JobStreet/KitaLulus tetapi khusus usaha mikro, kecil, dan menengah. Pelamar melihat loker tanpa perlu akun, lalu melamar **langsung via WhatsApp** ke pemilik usaha.

- **Stack:** Laravel 10 · PHP ≥ 8.1 · Blade · Tailwind (CDN) · Alpine.js · SQLite/MySQL
- **Repo:** private (`diluviumm/loker-umkm`)
- **Sumber awal:** `2411102441058-blip/pkl` (commit `b864841`)

---

## 1. Ringkasan produk

| Peran | Bisa apa |
|---|---|
| **Super Admin** | Login di `/admin/login`, memoderasi semua loker (setujui/tolak), menetapkan premium, menghapus loker, melihat data & dokumen pemilik usaha |
| **Pemilik Usaha** | Daftar sekali, melengkapi profil usaha, memasang loker, memantau status, menutup/menghapus/memperpanjang loker |
| **Pelamar / publik** | Mencari & memfilter loker, melihat detail, menyimpan loker (localStorage), melamar via WhatsApp — **tanpa login** |

Alur inti: pemilik usaha memasang loker → status `pending` → admin meninjau (dan memverifikasi bukti bayar bila premium) → `aktif` 30 hari → otomatis `kadaluarsa` setelah lewat masa tayang.

---

## 2. Kesesuaian dengan konsep

| Butir konsep | Status | Keterangan |
|---|---|---|
| Setujui gratis → urutan biasa | ✅ | Loker gratis masuk daftar normal |
| Setujui premium → selalu di halaman atas | ✅ | `scopeAktif()` mengurutkan `is_premium DESC` lalu `approved_at DESC` |
| Data pemilik usaha tersimpan, tidak perlu daftar ulang | ✅ | `business_profiles` terhubung 1-1 ke `users`; form loker mengisi otomatis nama usaha + WA |
| Data diri: nama pemilik, nama usaha, no. HP, medsos, KTP/SIM (opsional) | ✅ | Profil usaha + unggah dokumen opsional |
| 3 role pengguna | ⚠️ 2 dari 3 | `super_admin` & `pemilik_usaha` aktif. Role **pelamar** belum dibuat karena konsep juga menyatakan pelamar tidak perlu login — lihat §9 |
| Referensi JobStreet/KitaLulus untuk UMKM | ✅ | Struktur kategori, pencarian, filter lokasi, kartu loker, lamaran WA |
| Login pemilik usaha via nomor WhatsApp (Fonnte) | ❌ belum | Butuh akun & token Fonnte; saat ini login memakai email + password — lihat §9 |

### Revisi yang diminta → sudah dieksekusi

| Revisi | Implementasi |
|---|---|
| **PR persetujuan premium** hanya muncul sesuai status pembayaran | `JobListing::hasPaidPremium()`. Di dashboard admin: bila bukti bayar ada → tombol **Setujui Gratis** dinonaktifkan, hanya **Setujui Premium** aktif; bila belum ada bukti → sebaliknya. Server juga menolak kombinasi yang salah (`AdminDashboardController@approve`). |
| **UI/UX terlalu besar** | Skala layout & tombol diturunkan (header 112px → 64/80px, hero `text-8xl` → `text-5xl`, kartu `p-9` → `p-5`, tombol `py-4` → `py-2.5`), container `1700px` → `1280px`, tetap mempertahankan identitas pink. |
| **Halaman login admin khusus** | `/admin/login` (view `admin/login.blade.php`, tema gelap). Login biasa di `/login` menolak akun non-`super_admin`. |
| **Unggah KTP → foto selfie bersama KTP** | Kolom `foto_selfie_ktp`, field `foto_selfie_ktp`, preview di profil, dan tampil di modal verifikasi admin menggantikan tile KTP lama. |

---

## 3. Aturan premium (detail)

1. Pemilik usaha mencentang **Ajukan Premium (Rp 50.000)** dan **wajib** mengunggah bukti transfer (validasi `required_if`).
2. Saat disimpan: `premium_requested = true`, `bukti_pembayaran = <path>`, `is_premium = false`, status `pending`.
3. Admin melihat status pembayaran di kartu loker:
   - ada bukti → hanya tombol **Setujui Premium** yang aktif;
   - tidak ada → hanya **Setujui Gratis** yang aktif.
4. `approve()` menetapkan `is_premium` **berdasarkan bukti bayar**, bukan berdasarkan parameter request.
5. Loker `aktif` 30 hari (`expires_at`), lalu ditandai `kadaluarsa` oleh command harian `loker:check-expired`.

> Catatan: pembayaran masih manual (transfer + unggah bukti). Integrasi payment gateway belum ada.

---

## 4. Skema database

| Tabel | Kolom penting |
|---|---|
| `users` | `name`, `email`, `password`, `role` (`super_admin`/`pemilik_usaha`) |
| `business_profiles` | `user_id`, `nama_pemilik`, `nama_usaha`, `no_hp`, `alamat_usaha`, `kategori_usaha`, `deskripsi_usaha`, medsos, `website`, `foto_ktp` (legacy), **`foto_selfie_ktp`**, `foto_sim`, `profil_lengkap` |
| `categories` | `nama`, `slug`, `icon` (FontAwesome) |
| `job_listings` | `user_id`, `category_id`, `nama_usaha`, `posisi`, `slug`, `deskripsi`, `syarat`, `lokasi`, `tipe_pekerjaan`, `gaji`, `nomor_wa`, `is_premium`, **`premium_requested`**, `bukti_pembayaran`, `status`, `approved_at`, `expires_at`, `views_count`, `clicks_count` |

Status loker: `pending` → `aktif` / `ditolak` → `kadaluarsa`.

---

## 5. Rute utama

| Method | URL | Middleware | Fungsi |
|---|---|---|---|
| GET | `/` | – | Beranda + pencarian + filter |
| GET | `/loker/{slug}` | – | Detail loker (hanya `aktif` & belum kedaluwarsa) |
| POST | `/loker/{job}/track-click` | throttle 30/menit | Analitik klik WhatsApp |
| GET/POST | `/login`, `/daftar` | guest + throttle 5/menit | Auth pemilik usaha |
| GET | `/usaha/dashboard` | auth + role | Dashboard pemilik |
| GET/POST | `/usaha/profil` | auth + role | Profil usaha + selfie KTP |
| GET/POST | `/usaha/pasang-loker` | auth + role | Form & simpan loker |
| POST | `/usaha/loker/{job}/close\|renew` · DELETE | auth + role | Tutup / perpanjang / hapus |
| GET | `/admin/login` | guest | Login admin |
| GET | `/admin/dashboard` | auth + role super_admin | Moderasi |
| POST | `/admin/loker/{job}/approve\|reject\|toggle-premium` | auth + role | Keputusan admin |

---

## 6. Menjalankan lokal

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite          # default SQLite
php artisan migrate:fresh --seed
php artisan storage:link                # wajib: foto & bukti bayar
php artisan serve
```

Buka `http://127.0.0.1:8000`.

### Kredensial demo (hasil seeder)

| Peran | Email | Password |
|---|---|---|
| Super Admin | `admin@loker.com` | `password123` |
| Pemilik Usaha | `umkm@loker.com` | `password123` |

> Seeder juga membuat 11 kategori dan 7 loker contoh (2 premium).

### MySQL (opsional)

Ubah `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=loker_umkm
DB_USERNAME=root
DB_PASSWORD=
```

---

## 7. Penjadwalan & email

- **Auto-kadaluarsa:** `php artisan loker:check-expired` (terjadwal harian di `App\Console\Kernel`).
  Produksi butuh cron: `* * * * * cd /path/app && php artisan schedule:run >> /dev/null 2>&1`
- **Email status loker:** `App\Mail\JobStatusMail`, view `emails/job-status.blade.php`.
  Kegagalan SMTP tidak lagi membuat request 500 — dicatat ke log (`Log::warning`).
  Default `.env.example` memakai `MAIL_MAILER=log` agar aman saat demo.

---

## 8. Testing

```bash
php artisan test
```

20 test / 62 assertion mencakup: loker pending & kedaluwarsa tidak bocor ke publik, urutan premium, counter klik, otorisasi role, aturan persetujuan premium, validasi unggah selfie+KTP, dan validasi bukti bayar.

---

## 9. Gap & rekomendasi lanjutan

1. **Role pelamar + login pelamar** — konsep menyebut 3 role, tetapi juga menyebut pelamar tidak perlu login. Perlu keputusan produk sebelum dibuat.
2. **Login pemilik usaha via WhatsApp (Fonnte)** — butuh token Fonnte, endpoint OTP, dan tabel `otp_codes`. Belum diimplementasikan.
3. **Payment gateway** untuk premium (Midtrans/Xendit) agar tidak lagi verifikasi manual.
4. **Tailwind CDN** hanya cocok untuk prototipe; untuk produksi jalankan `npm install && npm run build` dan hapus CDN.
5. **Storage publik** menyimpan KTP/selfie di `storage/app/public` (bisa diakses lewat URL bila path diketahui). Untuk produksi: disk privat + route ber-otorisasi.
6. **Seeder produksi** — jangan pakai kredensial demo; ganti sebelum deploy.
