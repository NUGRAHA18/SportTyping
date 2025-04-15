# 🏆 SportTyping
## Di Mana Mengetik Menjadi Olahraga Kompetitif
![SportTyping Banner](https://via.placeholder.com/800x200)
**SportTyping** adalah platform kompetisi mengetik dinamis yang mengubah keterampilan mengetik sehari-hari menjadi olahraga kompetitif yang menarik. Baik Anda menggunakan smartphone atau laptop, platform kami menawarkan kompetisi khusus perangkat, papan peringkat komprehensif, peringkat liga, dan pelajaran mengetik profesional untuk membantu Anda menguasai teknik mengetik 10 jari.

## ✨ Fitur
- **Kompetisi Khusus Perangkat**: Arena terpisah untuk pengguna mobile dan PC untuk memastikan kompetisi yang adil
- **Sistem Liga**: Berkembang melalui peringkat seiring peningkatan keterampilan mengetik Anda
- **Kompetisi Real-time**: Tantang pengguna lain dalam balapan mengetik yang menarik
- **Papan Peringkat Komprehensif**: Lacak performa Anda dibandingkan dengan kompetitor global
- **Pelajaran Mengetik Profesional**: Pelajari teknik mengetik 10 jari dengan kursus terstruktur
- **Profil Pengguna**: Lacak kemajuan dan peningkatan Anda dari waktu ke waktu
- **Tes Mengetik Kustom**: Berlatih dengan berbagai kategori teks dan tingkat kesulitan

## 🚀 Tech Stack
- **Backend**: PHP dengan Framework Laravel
- **Frontend**: HTML, CSS, JavaScript
- **Database**: MariaDB
- **Autentikasi**: Sistem autentikasi bawaan Laravel

## 👥 Anggota Tim Kelompok 2
| Nama | NIM | Peran |
|------|------------|------|
| Sirly Shaira Najiha | 23106050003 | Resources & Idea Management |
| Agung Nugraha | 23106050011 | Front End, UI-UX |
| Radipta Basri Wijaya | 23106050035 | Full Stack Developer |
| Muhammad Adam | 23106050041 | Tester |

## 📋 Instalasi & Pengaturan
### Prasyarat
- PHP >= 8.1
- Composer
- MariaDB

### Pengaturan Pengembangan Lokal
```bash
# Clone repositori
git clone https://github.com/NUGRAHA18/SportTyping.git
cd SportTyping
# Instal dependensi PHP
composer install
# Salin file environment
cp .env.example .env
# Generate kunci aplikasi
php artisan key:generate
# Atur kredensial database di file .env
# Kemudian migrasi dan seed database
php artisan migrate --seed
# Kompilasi aset frontend
npm run dev
# Mulai server pengembangan
php artisan serve
```

## 📱 Tangkapan Layar
<div style="display: flex; justify-content: space-between;">
    <img src="https://via.placeholder.com/250x450" alt="Tampilan Mobile" width="250px">
    <img src="https://via.placeholder.com/500x300" alt="Balapan Desktop" width="500px">
    <img src="https://via.placeholder.com/500x300" alt="Papan Peringkat" width="500px">
</div>

## 🎯 Peta Jalan
- [x] Fungsi dasar kompetisi mengetik
- [x] Autentikasi dan profil pengguna
- [x] Ruang kompetisi khusus perangkat
- [ ] Implementasi sistem liga
- [ ] Modul pelajaran mengetik 10 jari
- [ ] Statistik dan analitik lanjutan
- [ ] Pengembangan aplikasi mobile
- [ ] Integrasi dengan game mengetik populer
- [ ] Turnamen internasional

**SportTyping** - Mengubah mengetik menjadi olahraga kompetitif yang menarik sejak 2025.
