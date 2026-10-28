# Catatan Perbaikan — 28 Oktober 2026

Audit menyeluruh + perbaikan pada proyek `loker-umkm` (asal: `2411102441058-blip/pkl` @ `b864841`).
Setiap butir menyebut file dan alasan.

## A. Keamanan & kebocoran data

| # | Masalah | Perbaikan | File |
|---|---|---|---|
| A1 | `AdminDashboardController@login` menangkap `RuntimeException` lalu **menimpa password user** dengan input login (backdoor: siapa pun bisa mengambil alih akun bila hash korup) | Blok try/catch dihapus; login murni `Auth::attempt` | `app/Http/Controllers/AdminDashboardController.php` |
| A2 | Route `/reset-admin-password` membuat ulang admin `admin@loker.com` / `admin123` hanya bermodal `APP_ENV=local` | Route dihapus total | `routes/web.php` |
| A3 | Loker `pending`/`ditolak` dapat dibuka publik lewat `/loker/{slug}` (terbukti HTTP 200 sebelum perbaikan) | Query detail dibatasi `status = aktif` + belum kedaluwarsa | `app/Http/Controllers/JobDetailController.php` |
| A4 | Endpoint `track-click` menaikkan counter untuk loker apa pun, tanpa batas | Hanya loker `aktif` (422 bila tidak), ditambah `throttle:30,1` | `JobTrackController.php`, `routes/web.php` |
| A5 | Login & register tanpa rate limit (brute force) | `throttle:5,1` pada POST login, register, dan admin login | `routes/web.php` |
| A6 | Parameter `is_premium` dari request bisa memaksa premium walau tidak ada pembayaran | `approve()` menentukan premium dari `hasPaidPremium()`; kombinasi salah ditolak | `AdminDashboardController.php` |
| A7 | `JobPostController` menerima `is_premium` tanpa bukti bayar | `required_if:is_premium,1` + `premium_requested` terpisah dari `is_premium` | `JobPostController.php` |

## B. Bug fungsional

| # | Masalah | Perbaikan | File |
|---|---|---|---|
| B1 | Email approve/reject memakai `Mail::send()` tanpa proteksi → 500 saat SMTP belum disetel | Dibungkus try/catch + `Log::warning` | `AdminDashboardController.php` |
| B2 | Validasi unggah memakai `image` **dan** `mimes:...pdf` (kontradiktif: PDF selalu ditolak) | `image|mimes:jpg,jpeg,png,webp` + pesan spesifik | `Business/ProfileController.php` |
| B3 | `renew`/`close` menerima status apa pun (loker aktif bisa "diperpanjang") | Guard status: renew hanya `kadaluarsa/ditolak`, close hanya `aktif` | `Business/JobPostController.php` |
| B4 | Perbandingan `user_id !== auth()->id()` rawan beda tipe | Cast `(int)` pada kedua sisi | `Business/JobPostController.php` |
| B5 | `Category::jobListings()` mengembalikan assignment (`return $table = ...`) | Dikembalikan langsung | `app/Models/Category.php` |
| B6 | Hitungan loker per kategori mengabaikan `expires_at` (angka beda dengan daftar) | `withCount` memakai status aktif + expiry | `HomeController.php` |
| B7 | Pencarian LIKE tidak meng-escape `%`/`_` | `addcslashes(...)` | `HomeController.php` |
| B8 | Nomor WA berawalan `8xxx` menghasilkan tautan `wa.me/8xxx` (invalid) | Normalisasi ke `62…` | `app/Models/JobListing.php` |
| B9 | `RouteServiceProvider::HOME = '/home'` menunjuk route yang tidak ada | Diubah ke `'/'` | `RouteServiceProvider.php` |
| B10 | Login form admin mengarahkan pemilik usaha ke dashboard admin (403) | Redirect sesuai role | `AdminDashboardController.php` |
| B11 | Filter admin tidak punya opsi "Semua" | Tab **Semua** ditambahkan | `admin/dashboard.blade.php` |
| B12 | `foto_ktp` lama tetap dipakai di modal admin padahal revisi minta selfie+KTP | Modal menampilkan `foto_selfie_ktp` | `admin/dashboard.blade.php` |
| B13 | `JobDetailController` menghitung `$relatedJobs` tetapi view tidak pernah memakainya | Section "Loker serupa" ditambahkan | `jobs/show.blade.php` |

## C. Tampilan & HTML

| # | Masalah | Perbaikan | File |
|---|---|---|---|
| C1 | Dua tag penutup `</footer>` (HTML tidak valid) | Duplikat dihapus | `layouts/app.blade.php` |
| C2 | `py-4.5` / `px-4.5` bukan kelas Tailwind (padding tidak pernah diterapkan) | Diganti kelas valid | `jobs/show`, `jobs/create` (legacy) |
| C3 | Ukuran layout & tombol terlalu besar (revisi klien) | Header 112→64/80px, hero 8xl→5xl, container 1700→1280px, kartu & tombol diperkecil, konsisten di semua halaman publik | `layouts`, `home`, `jobs/show`, `auth/*`, `business/*`, `admin/*` |
| C4 | `public/favicon.ico` berukuran 0 byte | Ikon dibuat ulang (16/32/48/64) | `public/favicon.ico` |
| C5 | `og:image` menunjuk file yang tidak ada | `public/images/og-default.png` (1200×630) dibuat | `jobs/show.blade.php` |
| C6 | JS bookmark/share memakai `addslashes()` di dalam string JS → rusak bila ada petik/newline | Diganti `data-*` + `dataset` | `jobs/show.blade.php` |
| C7 | Tombol WA di beranda tidak melacak klik (hanya halaman detail) | Script delegated global `[data-track-job]` di layout | `layouts/app.blade.php` |
| C8 | CSS email `max-w-xl;` bukan properti CSS yang valid | `max-width: 600px` | `emails/job-status.blade.php` |
| C9 | Label form tidak terhubung (`for`/`id`) pada halaman utama | Ditambahkan + `sr-only` label | `home.blade.php` |
| C10 | Flash `error` tidak pernah ditampilkan di layout | Blok flash error ditambahkan | `layouts/app.blade.php` |

## D. Kode mati / struktur

| # | Masalah | Perbaikan |
|---|---|---|
| D1 | `JobSubmissionController` + `views/jobs/create.blade.php` tidak punya route dan memanggil `route('jobs.store')` yang tidak ada | Dipindah ke `legacy/` (tidak di-autoload) + penjelasan di `legacy/README.md` |
| D2 | `.env.example` masih default Laravel | Diisi default proyek (SQLite, nama app, mail log) |
| D3 | `.gitignore` tidak mengabaikan database SQLite & folder upload | Ditambahkan |

## E. Test & verifikasi

- `php artisan test` → **20 passed, 62 assertions**.
- Semua file PHP lolos `php -l`; semua Blade lolos `php artisan view:cache`.
- Smoke test HTTP: `/` 200, detail aktif 200, **detail pending 404**, `/admin/reset-admin-password` 404, aset 200.
- Verifikasi visual: screenshot desktop 1440px & mobile 390px untuk beranda, detail, login, register, dashboard usaha, form loker, profil, admin.
