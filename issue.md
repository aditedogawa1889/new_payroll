# Plan: UI Redesign (AdminLTE v3 Style) & Routing Adjustment

## Tujuan Utama
1. **Redesign UI**: Mengubah tampilan aplikasi agar mengikuti gaya dan struktur dari AdminLTE v3 (seperti sidebar kiri, top navbar, konten utama di tengah).
2. **Redirect Root URL**: Mengubah URL default (`http://localhost/new_payroll/public/`) agar langsung mengarah ke halaman form login, bukan ke halaman welcome default Laravel.
3. **Perapihan Tampilan**: Mengatur ulang elemen-elemen UI agar tidak menumpuk (cluttered), memperbaiki spacing (margin/padding), dan menggunakan skema warna yang lebih menarik dan modern.

## Langkah-langkah Implementasi

### 1. Penyesuaian Routing (Redirect ke Login)
*   **File Target:** `routes/web.php`
*   **Aksi:** Mengubah route `/` yang saat ini mengembalikan view `welcome` menjadi sebuah redirect otomatis ke route `/login`. Ini memastikan pengunjung pertama kali langsung diminta untuk autentikasi.

### 2. Standarisasi Skema Warna & Tipografi (Tailwind CSS 4)
*   **File Target:** `resources/css/app.css`
*   **Aksi:** 
    *   Mendefinisikan *custom theme* (menggunakan variabel CSS Tailwind 4) yang terinspirasi dari skema warna AdminLTE v3 (misal: warna sidebar *dark*, navbar putih/cerah, *primary blue* untuk tombol dan aksen).
    *   Menerapkan *font family* standar (seperti *Source Sans Pro* atau *Inter*) agar tipografi terlihat lebih rapi dan profesional.

### 3. Restrukturisasi Layout Utama (AdminLTE Structure)
*   **File Target:** `resources/views/layouts/admin.blade.php`, `sidebar.blade.php`, `navbar.blade.php`
*   **Aksi:**
    *   Mengatur ulang struktur HTML agar membentuk susunan klasik AdminLTE: **Sidebar** di sisi kiri (fixed/collapsible), **Navbar** di atas, dan **Main Content** di bagian sisa layar.
    *   Memastikan desain responsif: sidebar dapat disembunyikan (*collapse*) pada layar *mobile* atau tablet agar konten tidak menumpuk.

### 4. Perapihan Komponen & Halaman (De-cluttering)
*   **File Target:** Halaman Dashboard (`dashboard.blade.php`) dan Profil (`profile/edit.blade.php`).
*   **Aksi:**
    *   Menerapkan sistem *Card* (kotak dengan shadow tipis dan border-radius) untuk membungkus konten (tabel, form, informasi).
    *   Memperbaiki *padding* dan *margin* antar elemen (form input, tombol, teks) sehingga memiliki ruang napas (*white space*) yang cukup dan tidak terlihat sesak.

### 5. Redesign Halaman Login (Guest Layout)
*   **File Target:** `resources/views/layouts/guest.blade.php` & `resources/views/auth/login.blade.php`
*   **Aksi:**
    *   Membuat tampilan login terpusat (berada di tengah layar).
    *   Menggunakan *card* bergaya AdminLTE dengan logo aplikasi di atasnya.
    *   Menambahkan warna latar belakang (background color) yang lembut atau gradasi agar form login terlihat lebih elegan dan premium.
