[![PHP Version](https://img.shields.io/badge/php-8.3%2B-blue)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/laravel-12-red.svg)](https://laravel.com/)

# 🏆 SportTyping

**Di Mana Mengetik Menjadi Olahraga Kompetitif**  
SportTyping mengubah keterampilan mengetik sehari-hari menjadi olahraga kompetitif yang seru, dengan arena khusus perangkat, papan peringkat global, sistem liga, dan pelajaran mengetik profesional.

---

## Demo & Screenshots

> _Coming Soon_

![Homepage](https://via.placeholder.com/800x400)  
![Kompetisi Real-time](https://via.placeholder.com/800x400)  
![Papan Peringkat](https://via.placeholder.com/800x400)

---

## ✨ Fitur Utama

-   **Kompetisi Khusus Perangkat** – Arena terpisah untuk mobile dan PC agar fairplay.
-   **Sistem Liga & Badge** – Naik peringkat dan kumpulkan badge untuk kecepatan, akurasi, konsistensi.
-   **Kompetisi Real-time** – Balapan mengetik melawan pemain lain atau bot.
-   **Papan Peringkat Global** – Lacak peringkatmu di seluruh dunia.
-   **Pelajaran Mengetik 10 Jari** – Kursus terstruktur untuk pemula hingga mahir.
-   **Profil & Statistik** – Pantau progres, histori lomba, dan statistik lanjutan.
-   **Tes Mengetik Kustom** – Latih diri dengan berbagai kategori teks & level kesulitan.
-   **Mode Tamu** – Akses latihan & kompetisi tanpa membuat akun.
-   **Bot Kompetitor** – Berlomba melawan AI dengan level kesulitan yang bisa diatur.

---

## 🚀 Tech Stack

| Layer        | Teknologi                              |
| ------------ | -------------------------------------- |
| **Backend**  | PHP 8.3+, Laravel 12                   |
| **Frontend** | HTML5, CSS3, JavaScript (ES6+)         |
| **Database** | MariaDB                                |
| **Realtime** | Pusher / Laravel Echo                  |
| **Auth**     | Laravel Sanctum / Built-in Auth System |
| **DevOps**   | GitHub Actions / GitHub Pages          |

---

## 👥 Anggota Tim

| Nama                 | NIM         | Peran                       |
| -------------------- | ----------- | --------------------------- |
| Sirly Shaira Najiha  | 23106050003 | Resources & Idea Management |
| Agung Nugraha        | 23106050011 | Front End & UI/UX           |
| Radipta Basri Wijaya | 23106050035 | Full Stack Developer        |
| Muhammad Adam        | 23106050041 | Tester                      |

> **Tugas Singkat**
>
> -   **Sirly**: Menyiapkan konten typing tests, ikon badge/league.
> -   **Agung**: Merancang & mengimplementasi UI responsif, animasi, dan real-time race.
> -   **Radipta**: Membangun logika bisnis, API, database, dan integrasi penuh.
> -   **Adam**: Pengujian manual/otomatis, dokumentasi bug & validasi fitur.

---

## 📥 Instalasi & Setup

### Setup Lokal

```bash
# 1. Clone repo
git clone https://github.com/NUGRAHA18/SportTyping.git
cd SportTyping

# 2. Install dependencies
composer install
npm install    # jika pakai asset build

# 3. Salin dan konfigurasi .env
cp .env.example .env
php artisan key:generate

# 4. Atur database pada .env (sesuaikan)
# DB_CONNECTION=mariadb
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=sport_typing_db
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Migrasi & seed data
php artisan migrate
php artisan db:seed

# 6. Jalankan server
php artisan serve
```

## 🎯 Peta Jalan & Progress

-   ✅ Struktur database untuk semua fitur
-   ✅ Pembuatan model dan relasi
-   ✅ Sistem autentikasi pengguna
-   ✅ Mode tamu untuk akses tanpa login
-   ✅ Ruang kompetisi khusus perangkat
-   ✅ Implementasi sistem liga & badge
-   ✅ Sistem bot kompetitor
-   ❌ Konten pelajaran mengetik 10 jari
-   ❌ Aset visual (badge, liga, keyboard guides)
-   ❌ UI/UX responsif untuk mobile dan desktop
-   ❌ Implementasi real-time race tracking
-   ❌ Statistik dan analitik lanjutan
-   ❌ Pengujian menyeluruh
-   ❌ Deployment production

**SportTyping** - Mengubah mengetik menjadi olahraga kompetitif yang menarik sejak 2025.
