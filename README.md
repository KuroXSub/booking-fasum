# Website Booking Fasilitas Umum (Booking Fasum)

Website ini adalah platform digital yang dirancang untuk memudahkan masyarakat dalam proses pemesanan atau peminjaman fasilitas umum yang tersedia. Sistem ini memungkinkan pengguna untuk melihat ketersediaan jadwal secara real-time dan melakukan booking secara mandiri.

Proyek ini dibangun menggunakan **Laravel 12** dan panel admin modern **Filament v3**.

---

## ✨ Fitur Utama

### 📌 Manajemen Booking (Sisi Pengguna)
- **Pemesanan Interaktif**: Form pemesanan canggih dengan pengecekan ketersediaan jadwal secara real-time tanpa perlu me-refresh halaman.
- **Riwayat Pemesanan**: Pengguna dapat melihat seluruh riwayat pemesanan yang pernah mereka buat beserta statusnya (Menunggu, Disetujui, Ditolak).
- **Manajemen Mandiri**: Pengguna dapat mengubah detail atau membatalkan pemesanan mereka sendiri selama statusnya masih *"Menunggu Persetujuan"*.

### 🛠️ Panel Admin Modern (Filament)
- **Dashboard Overview**: Widget informatif yang menampilkan statistik total pemesanan, jumlah yang disetujui, dan yang masih menunggu.
- **Manajemen Terpusat**: Seluruh data master seperti fasilitas, pengguna, kategori, dan jadwal khusus dikelola dalam satu antarmuka yang intuitif.
- **Sistem Notifikasi**: Admin menerima notifikasi untuk setiap pemesanan baru yang masuk.

### 🔐 Autentikasi dan Pengaturan (Livewire)
- **Desain Antarmuka Baru**: Halaman Login, Register, dan Lupa Password telah didesain ulang menggunakan komponen Livewire agar lebih responsif dan modern.
- **Social Login**: Kemudahan login untuk pengguna melalui akun Google mereka, didukung oleh Laravel Socialite.
- **Pengaturan Akun Lengkap**: Pengguna dapat mengelola profil, mengubah password, dan memilih tampilan tema (terang/gelap) melalui halaman pengaturan.

---

## 🧾 Dashboard Admin Panel

### 📊 Overview Widget
- **Informasi Umum**: Menampilkan jumlah total fasilitas yang terdaftar dan total pengguna.
- **Status Pemesanan**: Ringkasan jumlah pemesanan dengan status *Menunggu*, *Disetujui*, dan *Ditolak*.
- **Aktivitas Terbaru**: Daftar pemesanan terbaru yang masuk untuk pemantauan cepat.

### 🗃️ Manajemen Data
- **Kelola Booking**: Admin dapat melihat semua pemesanan, melakukan persetujuan, penolakan, atau mengubah detail pemesanan dari semua pengguna.
- **Kelola Fasilitas**: CRUD untuk data fasilitas, termasuk mengatur hari dan jam operasional, serta menonaktifkan fasilitas untuk sementara.
- **Kelola Tanggal Khusus**: Admin dapat menetapkan hari libur atau jam operasional khusus untuk fasilitas tertentu (misalnya untuk hari raya atau perbaikan).

---

## 🧑‍💻 Teknologi yang Digunakan
- **Framework**: Laravel 12
- **Admin Panel**: Filament v3
- **Database**: MySQL
- **Frontend**: Tailwind CSS, Alpine.js
- **UI Interaktif**: Livewire
- **Autentikasi**: Laravel Auth + Laravel Socialite (Google)
- **Development Tool**: Vite

---

## ⚙️ Petunjuk Instalasi

### 🔧 Konfigurasi Awal

**Clone repository**

```bash
git clone https://github.com/KuroXSub/booking-fasum.git
cd booking-fasum
