# Planning Pengembangan Sistem Payroll

## 1. Gambaran Umum Proyek
Tujuan dari proyek ini adalah membangun Sistem Payroll (Penggajian) yang handal untuk mengelola data karyawan, kehadiran, perhitungan gaji, hingga pencetakan slip gaji.

## 2. Technology Stack
*   **Backend Framework:** Laravel 12 (atau versi terbaru).
*   **Frontend Styling:** Tailwind CSS 4 (menggunakan Blade templating).
*   **Database:** Microsoft SQL Server.

## 3. Persiapan dan Konfigurasi Awal (Setup)
*   **Inisialisasi Proyek:** Install Laravel 12 menggunakan Composer.
*   **Konfigurasi Database:** Atur koneksi SQL Server pada file `.env` (pastikan ekstensi PHP `sqlsrv` dan `pdo_sqlsrv` sudah terinstal dan aktif).
*   **Integrasi UI:** Install dan konfigurasi Tailwind CSS 4 beserta integrasinya dengan Vite untuk asset bundling.
*   **Autentikasi:** Set up sistem login menggunakan starter kit bawaan Laravel (seperti Laravel Breeze atau Fortify) untuk memisahkan hak akses antara Admin/HR dan Karyawan.

## 4. Desain Database (High Level)
Buat file *migrations* untuk entitas-entitas utama berikut:
*   **Users/Employees:** Menyimpan data pribadi karyawan (Nama, NIK, Email, Jabatan, Status, Tanggal Bergabung, Gaji Pokok).
*   **Departments/Positions:** Menyimpan master data departemen dan jabatan.
*   **Attendances (Kehadiran):** Menyimpan log absensi harian karyawan (Tanggal, Jam Masuk, Jam Keluar, Status Kehadiran).
*   **Payroll/Payslips (Slip Gaji):** Menyimpan riwayat penggajian tiap periode (Karyawan ID, Periode Gaji, Total Tunjangan, Total Potongan, Gaji Bersih).

## 5. Fitur Utama yang Harus Dikembangkan
Lakukan pengembangan secara bertahap untuk fitur-fitur berikut:
1.  **Modul Manajemen Karyawan (CRUD):**
    *   Fitur untuk menambah, mengubah, melihat, dan menghapus data karyawan.
2.  **Modul Kehadiran:**
    *   Sistem untuk merekap kehadiran (bisa manual input oleh HR atau import data).
3.  **Modul Perhitungan Gaji (Payroll Engine):**
    *   Logika untuk menghitung gaji kotor (Gaji Pokok + Tunjangan/Lembur).
    *   Logika untuk menghitung potongan (Pajak, BPJS, Keterlambatan/Absen).
    *   Perhitungan Gaji Bersih (Net Salary).
4.  **Laporan dan Cetak Slip Gaji:**
    *   Halaman rekapitulasi gaji bulanan.
    *   Fitur untuk mendownload Slip Gaji (PDF).

## 6. Langkah-Langkah Pengerjaan (Development Flow)
Untuk para Junior Programmer, ikuti urutan pengerjaan berikut:
1.  **Tahap 1:** Lakukan setup environment, Laravel, Tailwind 4, dan koneksi SQL Server.
2.  **Tahap 2:** Buat *Migrations*, *Models*, dan *Relationships* antar tabel. Buat juga *Seeders* untuk data dummy HR/Admin.
3.  **Tahap 3:** Buat *Routes* dan *Controllers* untuk masing-masing modul.
4.  **Tahap 4:** Rancang *Views* menggunakan Blade dan styling Tailwind CSS 4. Pastikan UI/UX cukup bersih dan responsif.
5.  **Tahap 5:** Implementasikan logika perhitungan gaji di Controller atau Service/Action class terpisah agar rapi.
6.  **Tahap 6:** Lakukan testing manual pada tiap alur (login, tambah karyawan, hitung gaji).

> **Catatan Tambahan untuk Tim Developer:**
> Fokuslah terlebih dahulu pada alur data yang benar (M-V-C) dan jangan terjebak terlalu lama pada detail UI sebelum fungsi utamanya berjalan. Pastikan query ke SQL Server teroptimasi dengan baik.
