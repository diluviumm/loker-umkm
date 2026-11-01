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

    'premium' => [
        'price'     => (int) env('PREMIUM_PRICE', 50000),
        'bank_info' => env('PREMIUM_BANK_INFO'),
    ],

    'retention' => [
        'days'    => (int) env('DOCUMENT_RETENTION_DAYS', 365),
        'enforce' => (bool) env('DOCUMENT_RETENTION_ENFORCE', false),
    ],

];
