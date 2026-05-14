# MKAS - Web Management Keuangan

MKAS adalah aplikasi manajemen keuangan berbasis web yang dirancang dengan pendekatan **mobile-first**. Aplikasi ini memungkinkan pengguna untuk mencatat transaksi masuk, keluar, dan iuran bulanan (deposit) dengan sistem persetujuan (approval) oleh administrator.

## 🚀 Fitur Utama

### 👤 Peran Pengguna
*   **Admin:** Mengelola kategori, rekening pembayaran, menyetujui/menolak transaksi, dan melihat statistik global.
*   **User:** Mencatat transaksi, melakukan iuran bulanan, dan memantau saldo serta riwayat keuangan pribadi.

### 💰 Manajemen Keuangan
*   **Kas Masuk & Keluar:** Pencatatan transaksi dengan kewajiban upload bukti foto/struk.
*   **Deposit (Iuran):** Sistem iuran bulanan untuk anggota dengan informasi rekening tujuan.
*   **Sistem Approval:** Semua transaksi (Masuk/Keluar/Iuran) harus disetujui oleh Admin sebelum memengaruhi saldo.
*   **Dashboard Interaktif:** Ringkasan saldo utama, total kas masuk, dan total kas keluar.
*   **Riwayat Transaksi:** Filter berdasarkan bulan, kategori, dan status untuk memudahkan pelacakan.

### 🛠️ Fitur Admin
*   **Manajemen Kategori:** Menambah dan mengedit kategori transaksi.
*   **Manajemen Rekening:** Mengatur informasi rekening bank untuk pembayaran iuran.
*   **Prioritas Pembayaran:** Mengelola rencana atau prioritas pembayaran pengeluaran.
*   **Persetujuan Massal:** Halaman khusus untuk memproses transaksi yang berstatus *Pending*.

### 📱 Pengalaman Pengguna (UX)
*   **Mobile-First Design:** Dioptimalkan untuk penggunaan di smartphone.
*   **Floating Action Button (FAB):** Akses cepat untuk mencatat transaksi baru melalui *bottom sheet*.
*   **Navigasi Responsif:** Navigasi bawah (bottom navigation) untuk memudahkan akses di perangkat mobile.

## 🛠️ Teknologi yang Digunakan

*   **Framework:** [Laravel 12](https://laravel.com)
*   **Frontend:** [Tailwind CSS](https://tailwindcss.com), [Alpine.js](https://alpinejs.dev)
*   **Authentikasi:** [Laravel Breeze](https://laravel.com/docs/12.x/starter-kits#laravel-breeze)
*   **Build Tool:** [Vite](https://vitejs.dev)
*   **Database:** MySQL
*   **Containerization:** Docker & Docker Compose

## 📋 Prasyarat

Sebelum memulai, pastikan Anda telah menginstal:
*   PHP >= 8.2
*   Composer
*   Node.js & NPM
*   Docker & Docker Compose (Opsional, untuk setup cepat)

## ⚙️ Instalasi

### Menggunakan Docker (Rekomendasi)

1.  Clone repository:
    ```bash
    git clone https://github.com/username/mkas-laravel.git
    cd mkas-laravel
    ```
2.  Salin file environment:
    ```bash
    cp .env.example .env
    ```
3.  Jalankan container:
    ```bash
    docker-compose up -d
    ```
4.  Instal dependensi dan jalankan migrasi di dalam container:
    ```bash
    docker-compose exec app composer install
    docker-compose exec app php artisan key:generate
    docker-compose exec app php artisan migrate --seed
    docker-compose exec app npm install
    docker-compose exec app npm run build
    ```

### Instalasi Lokal

1.  Clone repository dan masuk ke direktori:
    ```bash
    git clone https://github.com/username/mkas-laravel.git
    cd mkas-laravel
    ```
2.  Instal dependensi PHP:
    ```bash
    composer install
    ```
3.  Salin `.env.example` ke `.env` dan sesuaikan konfigurasi database:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
4.  Jalankan migrasi dan seeder:
    ```bash
    php artisan migrate --seed
    ```
5.  Instal dependensi frontend dan build:
    ```bash
    npm install
    npm run build
    ```
6.  Jalankan server:
    ```bash
    php artisan serve
    ```

## 🔐 Akun Default (Seeder)

Jika Anda menjalankan migrasi dengan `--seed`, gunakan akun berikut untuk mencoba:

*   **Admin:** `admin@example.com` / `password`
*   **User:** `user@example.com` / `password`

## 📸 Preview Struktur Database

*   `users`: Menyimpan data pengguna, peran (admin/user), dan foto profil.
*   `transactions`: Mencatat semua transaksi IN/OUT dengan status approval.
*   `deposits`: Khusus untuk pencatatan iuran bulanan pengguna.
*   `categories`: Kategori transaksi (Listrik, Makanan, Air, dll).
*   `payment_accounts`: Informasi rekening bank tujuan iuran.
*   `payment_plans`: Rencana prioritas pembayaran (Fitur Admin).

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).
