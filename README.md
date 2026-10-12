# biotaglintasan

**Lintasan — Jejak Hijau Keluargaku**
*Kolaborasi Program: The Community Space • Yayasan Lintasan × BioTag (Yota Adiwidya Center)*

Platform edukasi dan aksi lingkungan digital berbasis keluarga, sekolah, dan komunitas dengan arsitektur SPA modern (Laravel 12 + Inertia.js v2 + Vue 3).

---

## Fitur Utama

- **Keluarga & Anak (Family Portal)**:
  - Pencatatan emisi karbon rumah tangga sederhana (listrik, LPG, bahan bakar kendaraan, limbah sampah).
  - Rekomendasi aksi hijau mingguan ramah keluarga.
  - Portofolio pohon adopsi keluarga dengan galeri foto pertumbuhan berkala.
- **Peta Pohon Nyata (Real Geospatial Map)**:
  - Peta interaktif Leaflet.js dengan layer OpenStreetMap & citra satelit asli (Esri World Imagery).
  - Koordinat publik tergeneralisasi untuk mematuhi prinsip *Privacy by Design* lingkungan anak.
  - Filter pencarian pohon, kode QR, dan status adopsi.
- **Petugas Lapangan & Pengawas (Officer Field App)**:
  - Pemindaian QR tag pohon di lapangan.
  - Pelaporan kondisi fisik pohon, input diameter & tinggi, serta unggah foto observasi terbaru.
- **Mitra Sekolah (School Management)**:
  - Pemantauan keterlibatan siswa, adopsi pohon sekolah, dan integrasi kurikulum hijau.
- **Platform BioTag & Yayasan Lintasan**:
  - Monitoring agregasi serapan CO₂, metrik verifikasi, dan audit log pemantauan.

---

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.4)
- **Frontend**: Inertia.js v2, Vue 3, Tailwind CSS
- **Maps Engine**: Leaflet.js (OpenStreetMap & Esri World Imagery)
- **Database**: SQLite / MySQL / PostgreSQL
- **Testing**: PHPUnit (33 Feature & Unit Tests, 83 Assertions)
- **Code Quality**: Laravel Pint

---

## Menjalankan Proyek Secara Lokal

1. Clone repositori:
   ```bash
   git clone https://github.com/Trifebri3/biotaglintasan.git
   cd biotaglintasan
   ```

2. Instal dependensi PHP & Node:
   ```bash
   composer install
   npm install
   ```

3. Konfigurasi Environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Jalankan Migrasi & Database Seeder:
   ```bash
   php artisan migrate:fresh --seed
   ```

5. Jalankan Server Pengembangan:
   ```bash
   npm run dev
   php artisan serve
   ```

---

## Lisensi & Hak Cipta

© 2026 Yayasan Lintasan — Program The Community Space & BioTag Yota Adiwidya Center.
