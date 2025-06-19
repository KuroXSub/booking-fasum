<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Booking Fasum - Dokumentasi</title>
</head>
<body>
  <h1>Website Booking Fasilitas Umum (Booking Fasum)</h1>
  <p>Website ini adalah platform digital yang dirancang untuk memudahkan masyarakat dalam proses pemesanan atau peminjaman fasilitas umum yang tersedia. Sistem ini memungkinkan pengguna untuk melihat ketersediaan jadwal secara real-time dan melakukan booking secara mandiri.</p>
  <p>Proyek ini dibangun menggunakan <strong>Laravel 12</strong> dan panel admin modern <strong>Filament v3</strong>.</p>

  <hr/>

  <h2>✨ Fitur Utama</h2>

  <h3>📌 Manajemen Booking (Sisi Pengguna)</h3>
  <ul>
    <li><strong>Pemesanan Interaktif</strong>: Form pemesanan canggih dengan pengecekan ketersediaan jadwal secara real-time tanpa perlu me-refresh halaman.</li>
    <li><strong>Riwayat Pemesanan</strong>: Pengguna dapat melihat seluruh riwayat pemesanan yang pernah mereka buat beserta statusnya (Menunggu, Disetujui, Ditolak).</li>
    <li><strong>Manajemen Mandiri</strong>: Pengguna dapat mengubah detail atau membatalkan pemesanan mereka sendiri selama statusnya masih <em>"Menunggu Persetujuan"</em>.</li>
  </ul>

  <h3>🛠️ Panel Admin Modern (Filament)</h3>
  <ul>
    <li><strong>Dashboard Overview</strong>: Widget informatif yang menampilkan statistik total pemesanan, jumlah yang disetujui, dan yang masih menunggu.</li>
    <li><strong>Manajemen Terpusat</strong>: Seluruh data master seperti fasilitas, pengguna, kategori, dan jadwal khusus dikelola dalam satu antarmuka yang intuitif.</li>
    <li><strong>Sistem Notifikasi</strong>: Admin menerima notifikasi untuk setiap pemesanan baru yang masuk.</li>
  </ul>

  <h3>🔐 Autentikasi dan Pengaturan (Livewire)</h3>
  <ul>
    <li><strong>Desain Antarmuka Baru</strong>: Halaman Login, Register, dan Lupa Password telah didesain ulang menggunakan komponen Livewire agar lebih responsif dan modern.</li>
    <li><strong>Social Login</strong>: Kemudahan login untuk pengguna melalui akun Google mereka, didukung oleh Laravel Socialite.</li>
    <li><strong>Pengaturan Akun Lengkap</strong>: Pengguna dapat mengelola profil, mengubah password, dan memilih tampilan tema (terang/gelap) melalui halaman pengaturan.</li>
  </ul>

  <hr/>

  <h2>🧾 Dashboard Admin Panel</h2>

  <h3>📊 Overview Widget</h3>
  <ul>
    <li><strong>Informasi Umum</strong>: Menampilkan jumlah total fasilitas yang terdaftar dan total pengguna.</li>
    <li><strong>Status Pemesanan</strong>: Ringkasan jumlah pemesanan dengan status <em>Menunggu</em>, <em>Disetujui</em>, dan <em>Ditolak</em>.</li>
    <li><strong>Aktivitas Terbaru</strong>: Daftar pemesanan terbaru yang masuk untuk pemantauan cepat.</li>
  </ul>

  <h3>🗃️ Manajemen Data</h3>
  <ul>
    <li><strong>Kelola Booking</strong>: Admin dapat melihat semua pemesanan, melakukan persetujuan, penolakan, atau mengubah detail pemesanan dari semua pengguna.</li>
    <li><strong>Kelola Fasilitas</strong>: CRUD untuk data fasilitas, termasuk mengatur hari dan jam operasional, serta menonaktifkan fasilitas untuk sementara.</li>
    <li><strong>Kelola Tanggal Khusus</strong>: Admin dapat menetapkan hari libur atau jam operasional khusus untuk fasilitas tertentu (misalnya untuk hari raya atau perbaikan).</li>
  </ul>

  <hr/>

  <h2>🧑‍💻 Teknologi yang Digunakan</h2>
  <ul>
    <li><strong>Framework:</strong> Laravel 12</li>
    <li><strong>Admin Panel:</strong> Filament v3</li>
    <li><strong>Database:</strong> MySQL</li>
    <li><strong>Frontend:</strong> Tailwind CSS, Alpine.js</li>
    <li><strong>UI Interaktif:</strong> Livewire</li>
    <li><strong>Autentikasi:</strong> Laravel Auth + Laravel Socialite (Google)</li>
    <li><strong>Development Tool:</strong> Vite</li>
  </ul>

  <hr/>

  <h2>⚙️ Petunjuk Instalasi</h2>

  <h3>🔧 Konfigurasi Awal</h3>

  <h4>Clone Repository</h4>
  <pre><code>git clone https://github.com/KuroXSub/booking-fasum.git
cd booking-fasum
</code></pre>

  <h4>Install Dependencies</h4>
  <pre><code>composer install
npm install
</code></pre>

  <h4>Setup Environment</h4>
  <pre><code>cp .env.example .env
php artisan key:generate
</code></pre>

  <h4>Konfigurasi Database di File .env</h4>
  <pre><code>DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_booking_fasum
DB_USERNAME=root
DB_PASSWORD=
</code></pre>

  <hr/>

  <h3>🔑 Konfigurasi Google Login (Opsional)</h3>
  <p>Tambahkan Client ID dan Client Secret Anda di file <code>.env</code>:</p>
  <pre><code>GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
</code></pre>

  <hr/>

  <h2>🚀 Menjalankan Aplikasi</h2>

  <h4>Migrasi Database</h4>
  <pre><code>php artisan migrate --seed
</code></pre>

  <h4>Kompilasi Assets</h4>
  <pre><code>npm run build
</code></pre>
  <p>Atau untuk development:</p>
  <pre><code>npm run dev
</code></pre>

  <h4>Jalankan Server Lokal</h4>
  <pre><code>php artisan serve
</code></pre>

  <hr/>

  <h2>🔐 Akses Admin Panel</h2>

  <h4>Buat User Admin</h4>
  <pre><code>php artisan make:filament-user
</code></pre>
  <p>Ikuti instruksi untuk membuat akun admin pertama Anda.</p>

  <h4>Akses Admin Panel</h4>
  <p>Buka <a href="http://127.0.0.1:8000/admin">http://127.0.0.1:8000/admin</a> di browser Anda.</p>

  <hr/>

  <h2>📘 Panduan Penggunaan</h2>

  <h3>👤 Untuk Admin</h3>
  <ul>
    <li>Pantau semua aktivitas pemesanan dari halaman dashboard.</li>
    <li>Kelola status pemesanan (setujui/tolak) melalui menu <strong>"Bookings"</strong>.</li>
    <li>Kelola data master seperti fasilitas, pengguna, dan kategori melalui menu navigasi yang tersedia.</li>
  </ul>

  <h3>🙋 Untuk Pengguna</h3>
  <ul>
    <li>Login menggunakan email atau akun Google.</li>
    <li>Buat pemesanan baru melalui form interaktif yang akan menampilkan ketersediaan jadwal secara langsung.</li>
    <li>Lihat status dan riwayat pemesanan Anda di halaman <strong>"My Bookings"</strong>.</li>
    <li>Ubah atau batalkan pemesanan jika diperlukan.</li>
  </ul>
</body>
</html>
