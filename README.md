# EMS - Sistem Informasi Cuti Pegawai

**EMS** (Employee Management System) adalah aplikasi berbasis web yang dirancang untuk memodernisasi dan mengelola data pegawai serta proses pengajuan cuti secara efisien dan terstruktur. Project ini dibangun menggunakan **Laravel 12** dan **MySQL** sebagai bagian dari portofolio *fullstack web development*.

---

## 🚀 Fitur Utama

### 👤 Role Pegawai
* **Dashboard Pegawai:** 
  * Menampilkan informasi ringkas sisa jatah cuti yang dimiliki.
  * Tombol akses cepat *Ajukan Cuti Baru*.
  * Tabel riwayat 5 Pengajuan Cuti Terakhir lengkap dengan tanggal, alasan, dan status approval (*Pending*, *Disetujui*, *Ditolak*).
* **Pengajuan Cuti Online:**
  * **Formulir Cuti Baru:** Mengisi tanggal mulai, tanggal selesai, dan alasan permohonan cuti secara mandiri.
  * Informasi indikator sisa jatah cuti aktif di dalam formulir.
  * **Tabel Riwayat Cuti:** Melihat seluruh histori permohonan cuti beserta durasi hari dan statusnya.
  * **Notifikasi Email:** Menerima email notifikasi otomatis berisi rincian data dan perubah status permohonan cuti (*Pending*, *Disetujui*, atau *Ditolak*).
* **Pengaturan Profil Akun:** 
  * Melihat data diri (nama lengkap, email, divisi, dan sisa jatah cuti).
  * Fitur memperbarui informasi profil dan **Ganti Password**.

---

### 👨‍💼 Role Admin (Manajemen Sistem)
* **Dashboard Monitoring:** Menampilkan statistik *real-time* seperti total karyawan, jumlah pengajuan yang perlu persetujuan, total divisi aktif, dan ringkasan aktivitas pengajuan cuti terbaru seluruh karyawan.
* **Manajemen Data Pengguna (CRUD):** 
  * Mengelola **Daftar Pegawai** (melihat email, divisi, sisa jatah cuti, serta opsi edit/hapus).
  * Mengelola **Daftar Administrator Sistem** beserta hak aksesnya.
  * Fitur untuk menambah data pegawai baru dan admin baru.
* **Manajemen Cuti (Approval System):**
  * Memproses permohonan cuti pegawai dengan aksi **Setujui** atau **Tolak**.
  * Menampilkan riwayat pengajuan lengkap dengan tanggal mulai/selesai, alasan, jumlah hari, sisa jatah cuti, dan status pengajuan.
  * **Email Notification System:** Mengirimkan email notifikasi otomatis ke pegawai saat permohonan cuti selesai diproses.
* **Laporan Rekap Cuti Pegawai:**
  * Filter riwayat cuti berdasarkan rentang tanggal (*Tanggal Mulai* & *Tanggal Selesai*) dan *Divisi*.
  * Fitur **Cetak PDF / Print** untuk pembuatan laporan rekapitulasi data pengajuan cuti seluruh karyawan.
* **Pengaturan Profil Akun Admin:** Mengubah informasi pribadi dan pembaruan password akun admin.

---

## 🛠️ Teknologi yang Digunakan

* **Framework Back-End:** [Laravel 12](https://laravel.com/) (PHP)
* **Database:** MySQL
* **Front-End:** Blade Templating, HTML5, CSS3, JavaScript
* **Authentication:** Multi-role Access Control (Admin & Pegawai)
* **Email Engine:** Laravel Mail (Custom HTML Template & Local Log Driver)

---

## ⚙️ Panduan Instalasi (Local Development)

Ikuti langkah-langkah berikut untuk menjalankan project ini di komputer lokal:

1. **Clone Repositori**
   ```bash
   git clone https://github.com/bellarisma/sistem-informasi-cuti-pegawai.git
   cd sistem-informasi-cuti-pegawai

```

2. **Install Dependensi PHP**
```bash
composer install

```

3. **Konfigurasi Environment (.env)**
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env

```

Atur koneksi database pada file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ems_system
DB_USERNAME=root
DB_PASSWORD=

```

4. **Generate Application Key**
```bash
php artisan key:generate

```

5. **Jalankan Migrasi Database & Seeder**
```bash
php artisan migrate --seed

```

6. **Jalankan Server Lokal**
```bash
php artisan serve

```

Buka browser dan akses `[http://127.0.0.1:8000](http://127.0.0.1:8000)`.

---

## 👤 Penulis

* **Bella Risma** - *Fullstack Developer* - [GitHub Profile](https://www.google.com/search?q=https://github.com/bellarisma)

```
