# Legacy / tidak dipakai

File di folder ini **tidak di-autoload** dan **tidak dirutekan**. Disimpan hanya untuk jejak historis.

| File | Asal | Alasan dipensiunkan |
|---|---|---|
| `JobSubmissionController.php.txt` | `app/Http/Controllers/JobSubmissionController.php` | Form posting loker publik tanpa login. Bertentangan dengan konsep: hanya pemilik usaha terdaftar yang boleh memasang loker. Tidak pernah punya route sejak awal. |
| `public-job-form.blade.php.txt` | `resources/views/jobs/create.blade.php` | View form publik di atas. Memanggil `route('jobs.store')` yang tidak ada, sehingga pasti error bila dirender. |

Form pengganti: `/usaha/pasang-loker` (middleware `auth` + `role:pemilik_usaha`).
