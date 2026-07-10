# 📘 Panduan Operasional Sistem E-TKI

Dokumen ini adalah acuan resmi untuk menyalakan, mengakses, dan mengoperasikan sistem E-TKI setiap hari di lingkungan kantor. Ikuti langkah-langkah di bawah ini secara berurutan.

---

## 🛠️ Langkah 1: Menyalakan Peladen (Server Pusat)
PC Server adalah pusat data. Langkah ini wajib dilakukan pertama kali sebelum staf lain mulai bekerja.

1. Nyalakan PC Utama (Server).
2. Buka aplikasi **Laragon** melalui pintasan di Desktop atau arahkan ke direktori `D:\laragon\laragon.exe`.
3. Pada jendela Laragon, klik tombol **Start All**. Pastikan status indicator untuk **Apache** (atau Nginx) dan **MySQL** telah berubah menjadi hijau/aktif.

---

## 🚀 Langkah 2: Mengaktifkan Jaringan Jarak Jauh (Jalur Wi-Fi)
Agar aplikasi bisa dibuka melalui ponsel (HP) staf tanpa koneksi internet, Anda harus menyalakan jalur peladen internal.

1. Di dalam aplikasi Laragon, klik tombol **Terminal**.
2. Pastikan posisi terminal berada di folder project (`D:\laragon\www\E-TKI`). Jika tidak, ketik:
   ```bash
   cd D:\laragon\www\E-TKI
   ```

3. Jalankan perintah peluncur peladen dengan mengetik komando berikut, lalu tekan **Enter**:
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```

*Catatan: Parameter `--host=0.0.0.0` wajib disertakan agar komputer dan ponsel lain di dalam jaringan Wi-Fi yang sama dapat mendeteksi peladen ini.*

---

## 📱 Langkah 3: Mengakses Aplikasi (PC & HP Klien)

### A. Akses Melalui PC Utama (Server)

1. Buka peramban web (Google Chrome atau Microsoft Edge).
2. Ketik alamat berikut di bilah pencarian: `http://localhost:8000` atau `http://127.0.0.1:8000`.

### B. Akses Melalui HP Staf/Klien (PWA)

1. Sambungkan ponsel staf ke jaringan **Wi-Fi Kantor yang sama** dengan PC Server.
2. Cari tahu alamat IP PC Server hari ini. (Buka CMD di PC, ketik `ipconfig`, lihat angka di baris `IPv4 Address`, contoh: `192.168.1.15`).
3. Buka peramban web di ponsel (Chrome untuk Android / Safari untuk iPhone).
4. Ketik alamat IP peladen diikuti port 8000. Contoh: `http://192.168.1.15:8000`.
5. **Instal Aplikasi:** Saat halaman terbuka, klik menu pengaturan peramban ponsel, lalu pilih **Tambahkan ke Layar Utama (Add to Home Screen)** atau **Instal Aplikasi**. Sistem E-TKI akan terpasang sebagai aplikasi mandiri (PWA) di ponsel tanpa perlu mengunduh dari Playstore.

---

## 💻 Langkah 4: Panduan Fitur Utama Sistem

1. **Dashboard Utama (Analitik & Peringatan):**
* Menampilkan grafik interaktif *Chart.js* yang membandingkan jumlah pendaftar baru dan TKI yang berangkat secara *real-time* berbasis data asli.
* Perhatikan widget **Peringatan Dokumen**. Sistem otomatis mendeteksi dan menampilkan nama TKI yang masa berlaku paspor atau visanya tersisa kurang dari 30 hari untuk tindakan proaktif.

2. **Halaman Data TKI (CRUD, Sorting, & Bulk Delete):**
* **Display Data:** Menampilkan seluruh isi kolom form secara lengkap. Gunakan fitur geser horizontal (*horizontal scroll*) jika layar gawai Anda terbatas.
* **Paginasi:** Atur jumlah tampilan data per halaman (pilihan: 1, 10, 100, 1000 data) melalui tombol menu di atas tabel.
* **Pengurutan (Sorting):** Klik pada judul kolom tabel (Tanggal Daftar, Nama, Tanggal Lahir, Tempat Lahir, Negara Tujuan, Nama Sponsor) untuk mengurutkan data secara naik (Ascending) atau turun (Descending).
* **Hapus Massal (Bulk Delete):** Centang kotak master di kepala tabel untuk memilih semua data, atau centang beberapa kotak di baris data tertentu, lalu klik tombol merah **Hapus Terpilih** yang muncul secara dinamis.

3. **Formulir & Impor Excel:**
* Kolom **Tanggal Daftar** akan terisi otomatis dengan tanggal hari ini saat input manual, namun tetap dapat diubah secara kalender.
* Saat melakukan impor data masal via fail Excel, pastikan file memuat kolom data bernama `tgl_daftar` agar sistem dapat merekam tanggal pendaftaran historis secara akurat.

---

## 💾 Langkah 5: Protokol Pencadangan Data (Backup)

### A. Pencadangan Otomatis (Autopilot)

Sistem telah dikonfigurasi menggunakan *Windows Task Scheduler* untuk mengeksekusi berkas otomatis (`auto-backup.bat`) setiap hari pukul **16:30 sore**.

* **Aturan Penting:** Jangan mematikan PC Server sebelum pukul **16:35 sore** untuk memastikan proses kompresi arsip dan pengunggahan dokumen ke brankas Google Drive selesai sempurna.

### B. Pencadangan Manual (Darurat)

Jika Anda ingin mengamankan data seketika sebelum melakukan perbaikan komputer atau di luar jam kerja:

1. Masuk ke halaman **System Settings** di dalam aplikasi.
2. Cari area Manajemen Penyimpanan, lalu klik tombol **Eksekusi Backup Sekarang**.
3. Tombol akan mengunci secara otomatis dan menampilkan teks *"Memproses Arsip..."* untuk mencegah klik ganda.
4. Tunggu hingga muncul kotak pemberitahuan (*Toast Notification*) kaca transparan di sudut bawah layar yang mengonfirmasi keberhasilan. Fail ZIP cadangan Anda dapat langsung diperiksa di folder utama Google Drive Anda.
