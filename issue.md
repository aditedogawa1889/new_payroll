# Issue / Plan: Activity Logging & Salary Settings Enhancements

## 1. Fitur Log Activity (Audit Trail)
### Deskripsi
Merekam semua aktivitas user ke dalam database untuk memantau akses, perubahan data, dan lokasi pengguna saat melakukan request ke sistem.

### Task List
- [ ] **Database Migration:** Buat table `log_activity` dengan struktur:
  - `id` (INT, Primary Key, Auto Increment)
  - `method` (VARCHAR 100) - *Menyimpan HTTP Method (GET, POST, PUT, DELETE, dll)*
  - `uri` (TEXT) - *URL endpoint yang diakses*
  - `controller` (TEXT) - *Nama controller yang memproses request*
  - `func` (VARCHAR 500) - *Nama function/method di dalam controller*
  - `params` (TEXT) - *Data parameter yang dikirim (Query string, Form data, atau JSON body)*
  - `created_by` (VARCHAR 500) - *Username / ID User yang sedang login*
  - `created_at` (DATETIME) - *Timestamp saat aktivitas terjadi*
  - `ip` (TEXT) - *IP Address public pengguna*
  - `ip_local` (TEXT) - *IP Address lokal pengguna*
  - `latitude` (TEXT) - *Garis lintang lokasi pengguna*
  - `longitude` (TEXT) - *Garis bujur lokasi pengguna*
- [ ] **Global Middleware/Hook:** Buat middleware yang akan menangkap semua request HTTP yang masuk, mengumpulkan data-data di atas, dan melakukan insert ke dalam table `log_activity`.

---

## 2. Fitur Mandatory Geolocation Tracking
### Deskripsi
Aplikasi mensyaratkan pengguna untuk mengaktifkan akses lokasi di browser. Jika lokasi tidak aktif atau tidak diizinkan, pengguna akan diblokir dari menggunakan aplikasi.

### Task List
- [ ] **Frontend Location Request:** Buat script JavaScript global yang berjalan saat aplikasi dimuat pertama kali (di halaman login atau master layout).
- [ ] Gunakan API `navigator.geolocation.getCurrentPosition()` untuk meminta izin akses lokasi.
- [ ] **Success Handling (Diizinkan):** 
  - Simpan nilai `latitude` dan `longitude` ke dalam session/cookie/local storage.
  - Sisipkan data koordinat ini ke dalam setiap HTTP Request (misal melalui HTTP Header) agar bisa dicatat oleh Middleware backend ke table `log_activity`.
- [ ] **Error/Denied Handling (Ditolak):**
  - Tampilkan halaman *Full Screen Blocker* atau *Modal Overlay* yang tidak bisa ditutup, dengan pesan "Akses Lokasi Wajib Diaktifkan Untuk Mengakses Aplikasi".
  - Sembunyikan konten utama aplikasi dan cegah navigasi user sampai izin lokasi diberikan.

---

## 3. Modifikasi Modul Salary Components
### Deskripsi
Menambahkan pengaturan jenis perhitungan (Component Parameter) pada master data komponen gaji.

### Task List
- [ ] **Database Migration:** Tambahkan kolom baru `component_parameter` pada table `salary_components` (misal menggunakan tipe ENUM atau VARCHAR).
- [ ] **Update Form UI:** Pada form *Create/Edit* di Modul Salary Components, tambahkan input Dropdown "Parameter Komponen" dengan 3 pilihan:
  1. **General** (Nominal langsung)
  2. **Persentase** (Berdasarkan persentase dari komponen tertentu)
  3. **Custom** (Formula hitungan khusus)
- [ ] Update proses *save* dan *update* ke database untuk menyimpan kolom `component_parameter` ini.

---

## 4. Modifikasi Modul Salary Setting
### Deskripsi
Menyesuaikan form setting gaji karyawan berdasarkan jenis `component_parameter` dari master komponen.

### Task List
- [ ] **Dynamic Form Interface:** Pada halaman Salary Setting, buat UI yang reaktif menyesuaikan form input untuk masing-masing komponen gaji berdasarkan tipe parameternya:
  - **Untuk Komponen [General]:**
    - [ ] Tampilkan input *Number* / *Currency* biasa untuk mengisi nominal tetap.
  - **Untuk Komponen [Persentase]:**
    - [ ] Tampilkan input *Number* untuk nilai persentase (range 1 - 100).
    - [ ] Tampilkan input *Multi-Select* (misal menggunakan Select2/Checkbox) berisi daftar komponen gaji lainnya. User harus bisa memilih komponen mana saja yang akan dijadikan dasar hitungan.
    - *Rumus hitung nantinya: `Total Nominal Komponen Terpilih * (Persentase / 100)`*
  - **Untuk Komponen [Custom]:**
    - [ ] Sediakan UI "Formula Builder" atau text area khusus untuk menulis rumus.
    - [ ] Sediakan *Variable Picker* (daftar pilihan komponen gaji lain) yang bisa dimasukkan ke dalam text area formula.
    - [ ] Sediakan panduan/validasi agar user bisa membuat kombinasi operasi matematika (misal: `([Basic Salary] * 10%) + 500000`).
- [ ] **Database Schema for Salary Setting:** Pastikan struktur table yang menyimpan setting gaji karyawan (misal table `employee_salary_settings`) dapat mengakomodir:
  - Nilai (nominal/persentase).
  - Referensi ID komponen yang dijadikan dasar hitungan (untuk tipe Persentase).
  - String formula (untuk tipe Custom).
- [ ] **Payroll Calculation Engine:** Update logic penghitungan payroll (Generate Payroll) untuk mengeksekusi perhitungan sesuai rule di atas:
  - Evaluasi *Custom Formula* dengan map nilai asli dari masing-masing komponen variabel.

---

## Catatan Teknis (Notes) Penting
- **Dependency Graph (Urutan Eksekusi Payroll):** Karena ada komponen yang bergantung pada komponen lain (Persentase & Custom), sistem payroll harus melakukan *sorting* urutan kalkulasi. Pastikan komponen dasar (seperti Basic Salary / tipe General) dihitung terlebih dahulu sebelum mengeksekusi komponen tipe Persentase/Custom yang bergantung padanya.
- **Security pada Formula Custom:** Jika memproses string formula secara dinamis dari user, JANGAN menggunakan fungsi `eval()` mentah di backend/frontend untuk mencegah celah keamanan (Code Injection). Gunakan library *expression parser/evaluator* khusus (contoh: package `mathjs` untuk JS, atau math evaluator expression parser untuk PHP/Laravel/CI).
- **IP Local Tracker:** IP Local (misal 192.168.x.x) kadang sulit didapatkan dari level backend PHP/Node jika melewati router/NAT. Biasanya ini memerlukan trik WebRTC dari javascript frontend, atau menggunakan `X-Forwarded-For` jika di dalam jaringan proxy.
