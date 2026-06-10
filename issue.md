# Plan Modul Employee Loans

## 1. Database Schema

### Tabel `employee_loans`
Akan dibuat migration untuk tabel `employee_loans` dengan struktur berikut:
- `loan_id`: int (Primary Key, Auto Increment)
- `employee_id`: int (Foreign Key ke tabel `employee`)
- `loan_amount`: numeric(18,0)
- `loan_date`: date
- `loan_description`: text
- `loan_status`: int (1 = Aktif, 2 = Lunas)
- `loan_created_at`: datetime
- `loan_created_by`: varchar(200) (Nama user pembuat)
- `loan_updated_at`: datetime
- `loan_updated_by`: varchar(200) (Nama user pengubah)

### Tabel `employee_loans_schedule`
Akan dibuat migration untuk tabel `employee_loans_schedule` dengan struktur berikut:
- `loan_schedule_id`: int (Primary Key, Auto Increment)
- `loan_id`: int (Foreign Key ke tabel `employee_loans`)
- `month_number`: int (Angka bulan 1-12)
- `year_number`: int (Angka tahun 4 digit)
- `amount`: numeric(18,0)
- `remaining_amount`: numeric(18,0)
- `paid_amount`: numeric(18,0) default 0
- `payment_date`: datetime
- `payment_status`: int (1 = Aktif, 2 = Lunas)
- `created_at`: datetime
- `created_by`: varchar(200) (Nama user pembuat)
- `updated_at`: datetime
- `updated_by`: varchar(200) (Nama user pengubah)

---

## 2. Modul Employee Loans (CRUD & UI)

- **Relasi (One-to-Many):** 1 Employee dapat memiliki lebih dari 1 Pinjaman (Loans).
- **List Pinjaman:** Halaman index untuk menampilkan daftar semua pinjaman karyawan.
- **Form Input Pinjaman:** 
  - Form untuk memilih karyawan (atau dari profil karyawan).
  - Field input untuk `loan_amount` (Jumlah Pinjaman), `loan_date`, dan `loan_description`.
  - Proses submit akan menyimpan data pinjaman baru ke tabel `employee_loans`.

---

## 3. Detail Pinjaman & Upload Schedule

- **Halaman Detail Pinjaman:** Menampilkan informasi detail dari 1 pinjaman yang sudah diinput.
- **Upload Schedule Cicilan:**
  - **Download Template:** Menyediakan tombol/link untuk mendownload Template (Excel/CSV) format jadwal cicilan pinjaman.
  - **Form Upload:** Menyediakan form untuk mengupload file jadwal cicilan yang sudah diisi, lalu membaca datanya dan menyimpannya ke tabel `employee_loans_schedule` terkait dengan `loan_id` tersebut.

---

## 4. Integrasi dengan Menu Salary Setting

- Setelah pinjaman dibuat dan jadwal cicilan (schedule) sudah ter-upload, sistem akan mengintegrasikan tagihan pinjaman ini ke menu **Salary Setting**.
- Saat melakukan kalkulasi atau setting gaji bulanan (berdasarkan `month_number` dan `year_number`), sistem akan mengecek apakah karyawan memiliki cicilan aktif di `employee_loans_schedule`.
- Jika ada, nominal `amount` akan otomatis dimasukkan sebagai potongan (deduction) pada gaji karyawan tersebut.
- Update `paid_amount`, `payment_date`, dan `payment_status` jika gaji telah diproses atau dibayarkan.

---

## 5. Langkah-langkah Eksekusi (Action Plan)

1. Buat file **Migration** untuk tabel `employee_loans` dan `employee_loans_schedule`.
2. Buat **Model** `EmployeeLoan` dan `EmployeeLoanSchedule` beserta relasinya ke model `Employee`.
3. Buat **Controller** (`EmployeeLoanController`) untuk menangani proses List, Create (Input), dan Show (Detail).
4. Buat **Views** menggunakan Blade (Index, Create, Show).
5. Buat fitur **Export Template** dan **Import** file cicilan untuk mengisi data ke tabel `employee_loans_schedule`.
6. Lakukan modifikasi pada fungsi **Salary Setting** untuk menambahkan logika pengecekan pinjaman karyawan saat perhitungan gaji.
