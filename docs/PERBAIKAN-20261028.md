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
- `hermes verify --json` (recipe Laravel di `.hermes/environment.json`): **ok=true** — bootstrap `composer install`, build `npm ci && npm run build`, test `php artisan test` (22 passed), start `php artisan serve` + readiness `/` HTTP 200.
- Build aset Vite kini benar-benar jalan (`npm run build` → `public/build`), walau tampilan masih memakai Tailwind CDN.

## F. Polish setelah audit visual (browser + kontras)

| # | Temuan | Perbaikan | File |
|---|---|---|---|
| F1 | Tanggal tampil bahasa Inggris ("3 days ago", "26 Oct 2026") | `APP_LOCALE=id` + `Carbon::setLocale()` + `translatedFormat()` | `config/app.php`, `AppServiceProvider`, `jobs/show`, `admin/dashboard`, `emails` |
| F2 | Teks putih pada gradient pink-500->rose-500 hanya ~3.9:1 (gagal WCAG AA) | Gradient digelapkan ke pink-600->rose-600 (sekitar 5.0-5.6:1) | `layouts`, `home`, `auth/*`, `business/*`, `admin/*` |
| F3 | Teks sekunder `slate-400` (2.56:1) dan pink-500 kecil (3.53:1) | Dinaikkan ke `slate-500/600` dan `pink-600` | `layouts`, `home`, `jobs/show`, `auth/*` |
| F4 | Login admin: teks opacity 0.2-0.45 dan blob memicu scroll horizontal 80px | Opacity dinaikkan + `overflow-x: hidden` + `background-color` fallback | `admin/login.blade.php` |
| F5 | Chip kategori terpotong di desktop (scroll horizontal tanpa petunjuk) | Wrap otomatis di layar >= lg, ukuran chip diringkas | `home.blade.php` |
| F6 | Tombol ikon (mata/gembok/perpanjang/hapus) target sentuh ~30px | Padding diperbesar + `aria-label` | `business/dashboard.blade.php` |
| F7 | Tombol nonaktif hanya dibedakan warna | Ikon kunci saat nonaktif + tooltip alasan + catatan status pembayaran | `admin/dashboard.blade.php` |
| F8 | Tombol "Logout Admin" duplikat dengan menu "Keluar" dan jargon "FASE 2" | Dihapus / diganti label ramah pengguna | `admin/dashboard.blade.php` |
| F9 | Data demo semuanya tampil "baru saja" | Seeder memberi umur 1-7 hari pada 7 loker contoh | `database/seeders/DatabaseSeeder.php` |

**Bukti verifikasi visual:** 14 kombinasi halaman x viewport (1440 & 390) -> `overflowX = 0`, tanpa console error, tanpa gambar rusak, tanpa respons >= 400. Audit kontras otomatis: 0 pelanggaran pada latar solid (sisa temuan hanya elemen bergradien yang tidak dapat dihitung skrip).
---

# Sesi 2 — eksekusi gap (28 Oktober 2026, sore)

## G. Gap yang dieksekusi

| # | Gap (dari daftar "belum dikerjakan") | Hasil |
|---|---|---|
| G1 | Role pelamar (role ke-3) | `users.role` jadi string; `pelamar` aktif. Registrasi memilih role, redirect ke dashboard pelamar. Middleware `role:pelamar`. |
| G2 | Dashboard pelamar | `/pelamar/dashboard` + tabel `saved_jobs`; simpan/hapus loker dari halaman detail atau dashboard. |
| G3 | Login WhatsApp (Fonnte) | Tabel `otp_codes`, `WhatsAppOtpService`, halaman `/login-wa` (2 langkah), throttle 3/menit kirim & 10/menit verifikasi, OTP di-hash, 5 menit, maks 5 percobaan. |
| G4 | Dokumen privat | Selfie+KTP, SIM, dan bukti bayar disimpan di disk `local` (`storage/app/private/...`); disajikan `/dokumen/{path}` hanya untuk pemilik atau super admin. |
| G5 | Tailwind produksi (bukan CDN) | `tailwind.config.js`, `postcss.config.js`, `resources/css/app.css`, Alpine di-bundle lewat Vite; layout & 3 halaman auth memakai `@vite`. |
| G6 | Seeder produksi | `ProductionSeeder` (kategori + admin dari ENV). `DatabaseSeeder` menolak jalan di produksi. `CategorySeeder` dipakai bersama. |
| G7 | Pagination dashboard usaha | `paginate(10)` + statistik via query terpisah. |
| G8 | View bawaan Laravel | `welcome.blade.php` dipindah ke `legacy/` (tidak dirender, tidak dihapus). |

## H. Temuan keamanan dependency

`composer audit` awal: **14 advisory / 5 paket**. Setelah `composer update` untuk commonmark, flysystem, symfony/http-foundation, symfony/yaml → **5 advisory / 1 paket**, semuanya `laravel/framework` (Laravel 10 sudah EOL):

| Severity | Advisory | Mitigasi di aplikasi |
|---|---|---|
| critical | TokenGuard auth bypass (CVE-2026-114509) | Route API berbasis token (`/api/user`) dihapus — tidak ada permukaan TokenGuard |
| high | CRLF injection pada rule `email` | Middleware `SanitizeInput` membuang `\r` dan NUL dari semua input |
| medium | Temporary signed URL path confusion | Aplikasi tidak memakai signed URL |
| low | XSS di halaman debug | `APP_DEBUG=false` untuk produksi didokumentasikan |
| – | duplikat advisory CRLF | sama dengan di atas |

**Belum ditambal (butuh upgrade framework):** Laravel 10 → 12. Ini pekerjaan tersendiri, bukan tambal sulam.

## I. Bug baru yang ditemukan saat implementasi

| # | Temuan | Perbaikan |
|---|---|---|
| I1 | Kode OTP ikut ditulis ke `laravel.log` di **semua** environment bila `FONNTE_TOKEN` kosong | Log kode hanya di `local`/`testing`; produksi hanya warning tanpa kode |
| I2 | `DocumentController` bisa menyajikan file apa pun di disk lokal (mis. `config/app.php`) untuk admin | Whitelist prefix `private/`, `dokumen/`, `pembayaran/` |
| I3 | Kelas Tailwind dinamis (`bg-{{ $color }}-100`) tidak akan ter-generate saat build | Diganti kelas statis (`bg`/`fg` per item) |
| I4 | Hapus loker tersimpan tanpa konfirmasi | Ditambah `confirm()` |
| I5 | Kartu benefit di halaman daftar hanya relevan untuk pemilik usaha | Diganti netral: Gratis / Simpan Loker / Lamar via WA |
| I6 | Helper text abu-abu muda di login-wa & daftar | Dinaikkan kontrasnya |
| I7 | OTP menumpuk tanpa pembersihan | Hapus kode kedaluwarsa > 1 hari saat membuat kode baru |

## J. Sisa yang belum dikerjakan (jujur)

| # | Item | Kenapa belum | Yang dibutuhkan |
|---|---|---|---|
| J1 | Payment gateway (Midtrans/Xendit) | Butuh akun merchant + kunci API | Kredensial dari Mael |
| J2 | Upgrade Laravel 10 → 12 | Perubahan mayor (bootstrap, middleware, dependency); risiko regresi tinggi bila dicampur sesi ini | Sesi khusus + regression test |
| J3 | Fonnte produksi | Kode siap, token belum ada | `FONNTE_TOKEN` + nomor device |
| J4 | Profil pelamar (CV, pengalaman, foto) | Di luar revisi; pelamar saat ini hanya simpan loker | Keputusan produk |
| J5 | Notifikasi WA otomatis saat status loker berubah | Bisa memakai Fonnte yang sama | Token Fonnte |
| J6 | Retensi dokumen (auto-hapus selfie/KTP/bukti) | Butuh kebijakan pemilik data | Keputusan kebijakan |

## K. Verifikasi sesi 2

- `php artisan test` → **38 passed / 99 assertions**.
- `php -l` seluruh `app/config/routes/tests/database` bersih; `php artisan view:cache` sukses.
- `npm run build` → CSS 52.45 kB + JS 115.42 kB (Alpine bundled), manifest dipakai `@vite`.
- Audit browser 17 kombinasi halaman × viewport: **0 overflow, 0 console error, 0 gambar rusak, 0 respons ≥ 400**.
- E2E Playwright: pilih role pelamar → daftar → simpan loker → hapus → login OTP WhatsApp → dashboard pemilik; dokumen tanpa login → redirect. Semua lulus.
- `composer audit`: 5 advisory (semua laravel/framework, sudah dimitigasi).
---

# Sesi 3 — deployment publik + audit produksi (29 Oktober 2026)

## L. Deployment

| Item | Hasil |
|---|---|
| Domain | **https://loker.naygars.site** (wildcard `*.naygars.site` → Cloudflare Tunnel, sudah ada) |
| Ingress tunnel | entri `loker.naygars.site → http://localhost:8123` **sebelum** wildcard (urutan ingress menentukan) |
| App service | `systemd --user loker-umkm.service` (auto-restart, 4 worker CLI) |
| Scheduler | `loker-umkm-scheduler.timer` tiap menit → `php artisan schedule:run` |
| Env | produksi: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://…`, `SESSION_SECURE_COOKIE=true`, `ALLOW_DEMO_SEED=true` |
| HTTPS | `TrustProxies=*`, `URL::forceScheme('https')`, HTTP→HTTPS 301 di edge |
| Header | HSTS, nosniff, X-Frame-Options, Referrer-Policy, Permissions-Policy, tanpa `X-Powered-By` |
| Aset | `@vite` + `npm run build`; CSS/JS publik 200, tanpa mixed-content |

## M. Bug/temuan baru saat deployment (dan perbaikannya)

| # | Temuan | Perbaikan |
|---|---|---|
| M1 | Cloudflare menyuntik `static.cloudflareinsights.com` ke HTML → 1 console error di semua halaman | `Cache-Control: no-transform` untuk HTML → beacon hilang (diverifikasi 0 kemunculan) |
| M2 | Header `X-Powered-By: PHP/8.5.12` membocorkan versi PHP | `header_remove('X-Powered-By')` di `public/index.php` + middleware |
| M3 | Halaman error masih tampilan bawaan Laravel (Inggris, tidak konsisten) | 5 halaman error branded: 403/404/419/429/500 |
| M4 | Pesan OTP di produksi menyebut "kode dicatat di log" padahal produksi tidak mencatat kode | Pesan dibedakan: lokal = kode demo, produksi tanpa token = "layanan OTP belum aktif" |
| M5 | `phpunit` bisa memakai cache config/route produksi → test berisiko menyentuh DB produksi | `APP_CONFIG_CACHE` & `APP_ROUTES_CACHE` dipisah di `phpunit.xml` |
| M6 | Seeder demo tidak bisa dipakai di produksi | guard `ALLOW_DEMO_SEED` + `ProductionSeeder` (admin dari ENV) |
| M7 | Throttle login 5/menit terlalu ketat untuk IP bersama (NAT) | login 10/menit; admin login tetap 5/menit; OTP kirim 3/menit |
| M8 | Icon `fa-compass` pada 404 tampak kosong di screenshot | diganti `fa-map-location-dot`; setelah diverifikasi, ikon tampil (yang lama sebenarnya *screenshot artifact* — font belum termuat) |
| M9 | Data uji E2E menumpuk di DB produksi | dibersihkan (4 user + 4 file privat dihapus) |

## N. Fitur yang dieksekusi dari daftar "belum"

| # | Item | Hasil |
|---|---|---|
| N1 | Profil pelamar (J4) | Tabel `seeker_profiles` (headline, kota, keahlian, CV PDF), halaman `/pelamar/profil`, CV privat + otorisasi, tautan di dashboard |
| N2 | Notifikasi WhatsApp status loker (J5) | `WhatsAppGateway` dipakai OTP + notifikasi approve/reject; tanpa token → log lokal, produksi hanya warning |
| N3 | Retensi dokumen (J6) | **Belum** — butuh kebijakan pemilik data (lihat daftar sisa) |
| N4 | Payment gateway (J1) | **Belum** — butuh akun merchant |
| N5 | Upgrade Laravel (J2) | **Belum** — pekerjaan terpisah (Laravel 10 EOL, 5 advisory) |
| N6 | Token Fonnte (J3) | **Belum** — kode siap, tinggal isi `FONNTE_TOKEN` |

## O. Verifikasi sesi 3

- `php artisan test` → **44 passed / 117 assertions**.
- Audit browser **18 kombinasi** halaman × viewport di **domain publik**: 0 overflow, 0 console error, 0 respons ≥ 400.
- Audit kontras: 0 pelanggaran pada latar solid (sisa hanya elemen bergradien).
- E2E publik: daftar pelamar → simpan/hapus loker → profil + CV (HTTP 200) → OTP (pesan graceful tanpa token) → dokumen tanpa login redirect.
- HTTP: `/` 200, detail aktif 200, **pending 404**, `/login-wa` 200, `/admin/login` 200, HTTP→HTTPS 301.
- `composer audit`: tetap 5 advisory (laravel/framework, sudah dimitigasi).
