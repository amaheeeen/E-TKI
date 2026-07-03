# E-TKI Local Development Credentials

Gunakan kredensial berikut untuk melakukan proses masuk (Sign In) ke dalam sistem E-TKI selama masa pengembangan lokal. Seluruh akun ini sudah mendapatkan persetujuan (*approved*) dan memiliki peran (*roles*) yang berbeda untuk menguji fitur *Role-Based Access Control* (RBAC).

> [!IMPORTANT]
> Sandi default untuk seluruh akun di bawah ini adalah: **`password`**

## 1. Super Admin
Memiliki akses penuh ke seluruh sistem, termasuk menghapus data secara permanen dan melakukan verifikasi akun baru di halaman Pengaturan Sistem.
- **Email:** `farhan@admin.com`
- **Sandi:** `password`
- **Nama:** Farhan

## 2. Operational Admin
Memiliki akses untuk manajemen harian seperti memperbarui status visa, mengubah status pelacakan dokumen, namun tidak dapat menghapus data TKI.
- **Email:** `ibu@admin.com`
- **Sandi:** `password`
- **Nama:** Ibu

- **Email:** `tsurayya@admin.com`
- **Sandi:** `password`
- **Nama:** Tsurayya

## 3. Sponsor (Eksternal)
Akun representatif dari sponsor untuk simulasi pembatasan data berdasarkan penyewa (*multi-tenant*). Hanya dapat melihat dan mengelola data TKI yang direkrut oleh agensi mereka sendiri.
- **Email:** `alpha@sponsor.com`
- **Sandi:** `password`
- **Nama:** Sponsor Alpha

---

> [!TIP]
> **Pendaftaran Baru (Sponsor)**
> Jika Anda melakukan pendaftaran akun baru melalui halaman Register (`/register`), peran akun akan otomatis diatur menjadi **Sponsor** dan akun akan berstatus **`pending`** (ditangguhkan). 
> Anda harus masuk (*login*) menggunakan akun **Super Admin** (`farhan@admin.com`) dan membuka menu **Pengaturan Sistem** untuk menyetujui (*Approve*) akun tersebut sebelum mereka dapat masuk.
