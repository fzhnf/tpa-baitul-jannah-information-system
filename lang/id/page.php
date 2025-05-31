<?php

return [
    'general_settings' => [
        'title' => 'Pengaturan Umum',
        'heading' => 'Pengaturan Umum',
        'subheading' => 'Kelola pengaturan situs umum di sini.',
        'navigationLabel' => 'Umum',
        'sections' => [
            'site' => [
                'title' => 'Lokasi',
                'description' => 'Kelola Pengaturan Dasar.',
            ],
            'theme' => [
                'title' => 'Tema',
                'description' => 'Ubah tema default.',
            ],
        ],
        'fields' => [
            'brand_name' => 'Nama merek',
            'site_active' => 'Status situs',
            'brand_logoHeight' => 'Tinggi logo merek',
            'brand_logo' => 'Logo Merek',
            'site_favicon' => 'Situs Favicon',
            'primary' => 'Utama',
            'secondary' => 'Sekunder',
            'gray' => 'Abu-abu',
            'success' => 'Kesuksesan',
            'danger' => 'Bahaya',
            'info' => 'Info',
            'warning' => 'Peringatan',
        ],
    ],
    'mail_settings' => [
        'title' => 'Pengaturan surat',
        'heading' => 'Pengaturan surat',
        'subheading' => 'Kelola Konfigurasi Surat.',
        'navigationLabel' => 'Surat',
        'sections' => [
            'config' => [
                'title' => 'Konfigurasi',
                'description' => 'keterangan',
            ],
            'sender' => [
                'title' => 'Dari (pengirim)',
                'description' => 'keterangan',
            ],
            'mail_to' => [
                'title' => 'Surat ke',
                'description' => 'keterangan',
            ],
        ],
        'fields' => [
            'placeholder' => [
                'receiver_email' => 'Email Penerima ..',
            ],
            'driver' => 'Pengemudi',
            'host' => 'Tuan rumah',
            'port' => 'Pelabuhan',
            'encryption' => 'Enkripsi',
            'timeout' => 'Batas waktu',
            'username' => 'Nama belakang',
            'password' => 'Kata sandi',
            'email' => 'E-mail',
            'name' => 'Nama',
            'mail_to' => 'Surat ke',
        ],
        'actions' => [
            'send_test_mail' => 'Kirim surat tes',
        ],
    ]
    ];
