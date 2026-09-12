# SaDesain.id

Website jasa desain grafis berbasis web untuk media promosi, pemesanan, pengelolaan pesanan, tracking, dan pengiriman hasil desain kepada pelanggan.

## Fitur
- Informasi layanan dan portfolio
- Form pemesanan pelanggan
- Detail pesanan maksimal 50 kata
- Upload gambar referensi (opsional)
- Kode tracking pesanan
- Dashboard administrator
- Pemilihan designer
- Penentuan harga oleh admin
- Pengelolaan status dan catatan proses
- Upload hasil desain oleh admin
- Download hasil desain oleh pelanggan setelah status **Selesai**
- Konsultasi melalui WhatsApp
- Validasi upload dan proteksi endpoint admin

## Teknologi
- PHP 8.x
- MySQL/MariaDB
- HTML5
- CSS3
- JavaScript
- Apache/XAMPP

## Instalasi Lokal (XAMPP)
1. Salin folder `SaDesain.id` ke `C:/xampp/htdocs/`.
2. Jalankan Apache dan MySQL dari XAMPP.
3. Buat database bernama `sadesain` di phpMyAdmin.
4. Import `database.sql`.
5. Periksa konfigurasi database pada `config.php`.
6. Buka `http://localhost/SaDesain.id/`.
7. Untuk dashboard, buka `http://localhost/SaDesain.id/admin/login.php`.

> Kredensial demo yang terdapat pada dokumentasi proyek hanya untuk pengujian lokal. Segera ganti password administrator sebelum penggunaan nyata.

## Alur Bisnis
Pelanggan mengisi pesanan → mengirim detail dan referensi opsional → admin menerima → admin melihat referensi → menentukan designer dan harga → designer mengerjakan → admin mengunggah hasil → status menjadi Selesai → pelanggan melakukan tracking → pelanggan mengunduh hasil desain.

## Struktur Folder
```text
SaDesain.id/
├── index.php
├── config.php
├── database.sql
├── api/
├── admin/
├── assets/
└── storage/
    ├── order_results/
    └── reference_images/
```

## Catatan GitHub
Folder `storage/order_results/` dan `storage/reference_images/` sengaja tidak menyimpan file pelanggan di repository. File runtime tetap berada di server lokal/hosting.

Jangan commit password, API key, data pelanggan, hasil desain pelanggan, atau file rahasia lainnya.
