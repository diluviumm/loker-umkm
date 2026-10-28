# Info Loker UMKM

Portal lowongan kerja untuk UMKM Indonesia — mirip JobStreet/KitaLulus tetapi khusus usaha mikro, kecil, dan menengah. Pelamar melihat loker tanpa perlu akun, lalu melamar **langsung via WhatsApp** ke pemilik usaha.

- **Stack:** Laravel 10 · PHP ≥ 8.1 · Blade · Tailwind (build Vite) · Alpine.js · SQLite/MySQL
- **Repo:** private (`diluviumm/loker-umkm`)
- **Demo publik:** <https://loker.naygars.site> (Cloudflare Tunnel → laptop, `loker-umkm.service`)
- **Sumber awal:** `2411102441058-blip/pkl` (commit `b864841`)

---

## 1. Ringkasan produk

| Peran | Bisa apa |
|---|---|
| **Super Admin** | Login di `/admin/login`, memoderasi semua loker (setujui/tolak), menetapkan premium, menghapus loker, melihat data & dokumen pemilik usaha |
| **Pemilik Usaha** | Daftar sekali, melengkapi profil usaha, memasang loker, memantau status, menutup/menghapus/memperpanjang loker |
| **Pelamar** | Daftar (opsional), login via email atau **OTP WhatsApp**, profil + CV (PDF), menyimpan loker favorit, melamar via WhatsApp |
| **Publik / tamu** | Mencari & memfilter loker, melihat detail, menyimpan loker di browser (localStorage), melamar via WhatsApp — tanpa login |

Alur inti: pemilik usaha memasang loker → status `pending` → admin meninjau (dan memverifikasi bukti bayar bila premium) → `aktif` 30 hari → otomatis `kadaluarsa` setelah lewat masa tayang.

---

## 2. Kesesuaian dengan konsep

| Butir konsep | Status | Keterangan |
|---|---|---|
| Setujui gratis → urutan biasa | ✅ | Loker gratis masuk daftar normal |
| Setujui premium → selalu di halaman atas | ✅ | `scopeAktif()` mengurutkan `is_premium DESC` lalu `approved_at DESC` |
| Data pemilik usaha tersimpan, tidak perlu daftar ulang | ✅ | `business_profiles` 1-1 ke `users`; form loker mengisi otomatis nama usaha + WA |
| Data diri: nama pemilik, nama usaha, no. HP, medsos, KTP/SIM (opsional) | ✅ | Profil usaha + unggah dokumen opsional |
| 3 role pengguna | ✅ | `super_admin`, `pemilik_usaha`, `pelamar` |
| Referensi JobStreet/KitaLulus untuk UMKM | ✅ | Kategori, pencarian, filter lokasi, kartu loker, lamaran WA |
| Login pemilik usaha via nomor WhatsApp (Fonnte) | ✅ kode siap | Endpoint + OTP + tabel `otp_codes`. **Butuh `FONNTE_TOKEN`** untuk benar-benar mengirim; tanpa token, OTP hanya di log (lokal) |

### Revisi yang diminta → sudah dieksekusi

| Revisi | Implementasi |
|---|---|
| **PR persetujuan premium** hanya muncul sesuai status pembayaran | `JobListing::hasPaidPremium()`. Bila bukti bayar ada → hanya **Setujui Premium** aktif; bila tidak ada → hanya **Setujui Gratis**. Server menolak kombinasi yang salah. |
| **UI/UX terlalu besar** | Skala layout & tombol diturunkan (header 112 → 64/80px, hero `text-8xl` → `text-5xl`, container 1700 → 1280px). |
| **Halaman login admin khusus** | `/admin/login` (tema gelap), akun non-admin ditolak. |
| **Unggah KTP → foto selfie bersama KTP** | Kolom `foto_selfie_ktp`, preview di profil, tampil di modal verifikasi admin. |

---

## 3. Aturan premium

1. Pemilik mencentang **Ajukan Premium (Rp 50.000)** dan **wajib** mengunggah bukti transfer.
2. Tersimpan sebagai `premium_requested = true`, `bukti_pembayaran = private/pembayaran/...`, `is_premium = false`, status `pending`.
3. Admin melihat status pembayaran: hanya satu tombol persetujuan yang aktif.
4. `approve()` menetapkan `is_premium` **berdasarkan bukti bayar**, bukan parameter request.
5. Loker aktif 30 hari; command harian `loker:check-expired` menandai `kadaluarsa`.

> Pembayaran masih manual (transfer + unggah bukti). Payment gateway belum ada — lihat §9.

---

## 4. Skema database

| Tabel | Kolom penting |
|---|---|
| `users` | `name`, `email`, **`phone`** (unik, format 62xxx), `password`, `role` (`super_admin`/`pemilik_usaha`/`pelamar`) |
| `business_profiles` | `user_id`, `nama_pemilik`, `nama_usaha`, `no_hp`, alamat, kategori, deskripsi, medsos, `website`, `foto_ktp` (legacy), **`foto_selfie_ktp`**, `foto_sim`, `profil_lengkap` |
| `categories` | `nama`, `slug`, `icon` |
| `job_listings` | `user_id`, `category_id`, `posisi`, `slug`, `deskripsi`, `syarat`, `lokasi`, `tipe_pekerjaan`, `gaji`, `nomor_wa`, `is_premium`, **`premium_requested`**, `bukti_pembayaran`, `status`, `approved_at`, `expires_at`, `views_count`, `clicks_count` |
| **`saved_jobs`** | `user_id`, `job_listing_id` (unik berpasangan) — loker tersimpan pelamar |
| **`otp_codes`** | `phone`, `code_hash`, `expires_at`, `attempts`, `consumed_at`, `ip` — OTP login WhatsApp |

Status loker: `pending` → `aktif` / `ditolak` → `kadaluarsa`.
Semua dokumen (selfie+KTP, SIM, bukti bayar) disimpan di **disk privat**, bukan `public/`.

---

## 5. Rute utama

| Method | URL | Middleware | Fungsi |
|---|---|---|---|
| GET | `/` | – | Beranda + pencarian + filter |
| GET | `/loker/{slug}` | – | Detail loker (hanya `aktif` & belum kedaluwarsa) |
| POST | `/loker/{job}/track-click` | throttle 30/menit | Analitik klik WhatsApp |
| GET/POST | `/login`, `/daftar` | guest + throttle 5/menit | Auth email/password (3 role) |
| GET/POST | `/login-wa`, `/login-wa/kirim`, `/login-wa/verifikasi` | guest + throttle | Login OTP WhatsApp |
| GET | `/usaha/dashboard` | auth + role pemilik | Dashboard pemilik |
| GET/POST | `/usaha/profil` | auth + role pemilik | Profil + selfie KTP |
| GET/POST | `/usaha/pasang-loker` | auth + role pemilik | Form & simpan loker |
| POST | `/usaha/loker/{job}/close\|renew` · DELETE | auth + role pemilik | Tutup / perpanjang / hapus |
| GET | `/pelamar/dashboard` | auth + role pelamar | Loker tersimpan |
| GET/POST | `/pelamar/profil` | auth + role pelamar | Profil pelamar + CV (PDF) |
| POST/DELETE | `/pelamar/simpan/{job}` | auth + role pelamar | Simpan / hapus loker |
| GET | `/admin/login` · `/admin/dashboard` | guest / auth + super_admin | Login & moderasi |
| POST | `/admin/loker/{job}/approve\|reject\|toggle-premium` | auth + super_admin | Keputusan admin |
| GET | `/dokumen/{path}` | auth | Dokumen privat (pemilik atau admin) |

---

## 6. Menjalankan lokal

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed
php artisan storage:link
npm run build            # WAJIB: CSS/JS disajikan lewat @vite
php artisan serve
```

Buka `http://127.0.0.1:8000`. Untuk mode pengembangan aset: `npm run dev` (Vite dev server).

### Kredensial demo (hasil seeder)

| Peran | Email | Password | WhatsApp |
|---|---|---|---|
| Super Admin | `admin@loker.com` | `password123` (lokal) · **demo publik sudah dirotasi** → `~/.hermes/.secrets/loker-umkm-admin.txt` | `6281100000001` |
| Pemilik Usaha | `umkm@loker.com` | `password123` | `6281234567890` |
| Pelamar | `pelamar@loker.com` | `password123` | `6285555555555` |

Seeder juga membuat 11 kategori, 7 loker contoh (2 premium, 1 pending), dan 2 loker tersimpan untuk akun pelamar.

### Login WhatsApp (opsional)

Isi `FONNTE_TOKEN` (dan `FONNTE_URL` bila self-host) di `.env`. Tanpa token:
- di `local`/`testing`: kode OTP ditulis ke `storage/logs/laravel.log` dan ditampilkan sebagai "Kode demo" di halaman,
- di produksi: OTP **tidak** dikirim dan kode **tidak** dicatat (hanya warning).

---

## 7. Penjadwalan & email

- **Auto-kadaluarsa:** `php artisan loker:check-expired` (harian, `withoutOverlapping`).
  Produksi: `* * * * * cd /path/app && php artisan schedule:run >> /dev/null 2>&1`
- **Email status loker:** `App\Mail\JobStatusMail`. Kegagalan SMTP dicatat ke log, tidak membuat 500.
- Default `.env.example` memakai `MAIL_MAILER=log`.

---

## 8. Testing

```bash
php artisan test
```

**44 test / 117 assertion** mencakup: loker pending & kedaluwarsa tidak bocor, urutan premium, counter klik, otorisasi role (3 role), aturan persetujuan premium, validasi selfie+KTP, bukti bayar, pendaftaran pelamar, simpan/hapus loker, OTP WhatsApp (kirim/verifikasi/kedaluwarsa), dan akses dokumen privat (pemilik/admin/tamu/path traversal).

---

## 9. Gap & rekomendasi lanjutan

1. **Payment gateway** (Midtrans/Xendit) — belum ada; verifikasi transfer masih manual. Butuh akun merchant + kunci API.
2. **Upgrade framework** — Laravel 10 sudah EOL dan 5 advisory terbuka (lihat `docs/PERBAIKAN-20261028.md`). Mitigasi sudah dipasang; upgrade ke Laravel 12 adalah pekerjaan terpisah.
3. **Fonnte** — kode OTP siap, tinggal isi token & verifikasi pengiriman nyata.
4. **Retensi dokumen** — belum ada kebijakan hapus otomatis untuk selfie+KTP/bukti bayar.
5. **Seeder produksi** — jalankan `php artisan db:seed --class=ProductionSeeder` dengan `ADMIN_EMAIL`/`ADMIN_PASSWORD`; jangan pakai kredensial demo.

---

## 10. Repositori (dua remote)

| Remote | Repo | Fungsi |
|---|---|---|
| `origin` | `diluviumm/loker-umkm` (**private**) | backup utama, sumber kebenaran |
| `public` | `diluviumm/loker-umkm-public` (**public**) | mirror publik untuk dibagikan |
| `upstream` | `2411102441058-blip/pkl` | sumber asli (read-only) |

Setiap perubahan **wajib** dikirim ke keduanya:

```bash
./bin/ship.sh          # git push origin main && git push public main
```

---

## 11. Deployment (demo publik)

| Komponen | Detail |
|---|---|
| URL | `https://loker.naygars.site` (wildcard `*.naygars.site` → Cloudflare Tunnel) |
| App server | `systemd --user` unit **`loker-umkm.service`** → `php artisan serve 127.0.0.1:8123` (4 worker CLI) |
| Scheduler | **`loker-umkm-scheduler.timer`** tiap menit → `php artisan schedule:run` (auto-kadaluarsa harian) |
| Tunnel | entri `loker.naygars.site → http://localhost:8123` di `~/.cloudflared/config.yml` (sebelum wildcard) |
| Env produksi | `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://…`, `SESSION_SECURE_COOKIE=true`, `ALLOW_DEMO_SEED=true` |
| Cache | `php artisan optimize` (config + route). **Setiap ubah kode → `php artisan optimize` + restart service** |
| Proxy | `TrustProxies = *` + `URL::forceScheme('https')` agar aset tidak mixed-content |
| Header | HSTS, nosniff, frame-options, referrer-policy, permissions-policy, tanpa `X-Powered-By` |

Catatan: `php artisan serve` cocok untuk demo/PKL. Untuk produksi nyata, ganti ke php-fpm + nginx/Caddy dan matikan `ALLOW_DEMO_SEED`.
