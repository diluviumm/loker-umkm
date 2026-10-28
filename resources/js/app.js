import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Analitik: hitung klik tombol WhatsApp (delegated, dipakai semua halaman)
document.addEventListener('click', function (event) {
    const trigger = event.target.closest('[data-track-job]');
    if (!trigger) return;

    const jobId = trigger.getAttribute('data-track-job');
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!jobId || !token) return;

    fetch(`/loker/${jobId}/track-click`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        keepalive: true,
    }).catch(() => {});
});
