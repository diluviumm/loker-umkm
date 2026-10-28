<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Retensi dokumen sensitif (UU PDP: simpan selama diperlukan)
    |--------------------------------------------------------------------------
    |
    | Dokumen yang dihapus otomatis hanya yang sudah tidak diperlukan lagi:
    | bukti pembayaran loker yang sudah final, identitas pemilik usaha yang
    | sudah lolos moderasi, dan CV pelamar yang tidak diperbarui.
    |
    */

    'retention' => [
        'days'    => (int) env('DOCUMENT_RETENTION_DAYS', 365),
        'enforce' => (bool) env('DOCUMENT_RETENTION_ENFORCE', false),
    ],

];
