# 🚀 Panduan Deployment Lingga POS di Hostinger (VPS + aaPanel)

Karena sistem Anda terdiri dari Frontend (React PWA), Backend (FastAPI Python), dan Database (MySQL), menggunakan **VPS Hostinger dengan OS Ubuntu + aaPanel** adalah cara yang paling tepat dan tidak memusingkan. 

*aaPanel* adalah panel kontrol visual (seperti cPanel) yang membuat Anda bisa mengurus server hanya dengan klik-klik tanpa harus hafal kode Linux.

Berikut adalah 5 langkah utamanya:

---

## Tahap 1: Pembelian & Setup Awal VPS
1. Buka website **Hostinger Indonesia** dan masuk ke menu **VPS Hosting**.
2. Pilih paket **KVM 2** (Sangat disarankan karena memiliki RAM 8GB yang cukup lega untuk menjalankan *database* dan proses *build* React. KVM 1 dengan RAM 4GB juga bisa, tapi lebih berisiko *lag* jika kasir sudah banyak).
3. Lakukan pembayaran (bisa via QRIS/BCA/dll).
4. Setelah aktif, masuk ke panel Hostinger, lalu klik **Setup VPS**.
5. Di bagian pilihan Sistem Operasi (OS), **JANGAN** pilih Ubuntu biasa kosong. Carilah menu **OS with Control Panel** dan pilih **Ubuntu 22.04 with aaPanel**.
6. Pilih Lokasi Server: **Singapura** atau **Jakarta** (pilih yang terdekat dengan target pasar Anda).
7. Selesaikan setup dan tunggu 5-10 menit. Hostinger akan memberikan Anda **IP Address**, **URL aaPanel**, **Username**, dan **Password**.

---

## Tahap 2: Pengaturan aaPanel (Lingkungan Server)
1. Buka browser dan akses **URL aaPanel** yang diberikan Hostinger.
2. Login menggunakan *Username* dan *Password* Anda.
3. Saat pertama kali masuk, aaPanel akan menawarkan instalasi aplikasi secara otomatis (*One-Click Install*). Pilih opsi **LNMP (Linux, Nginx, MySQL, PHP)**.
   - Pastikan Anda mencentang: **Nginx** dan **MySQL** (versi 8.0 atau 5.7).
4. Klik *Install* dan biarkan aaPanel mengunduh dan mengatur semuanya secara otomatis (bisa memakan waktu 15-30 menit, Anda bisa tinggal ngopi dulu ☕).

---

## Tahap 3: Membuat Database MySQL
1. Di menu sebelah kiri aaPanel, klik **Databases**.
2. Klik tombol hijau **Add Database**.
3. Isi kolom:
   - **DB Name:** `lingga_pos_db`
   - **Username:** `lingga_user`
   - **Password:** (Buat password yang kuat, misal: `Lingg4!POS2026`)
4. Klik **Submit**. Database MySQL Anda kini sudah siap! 
5. *(Catat Username dan Password ini, kita akan memasukkannya ke file `.env` di tahap selanjutnya)*.

---

## Tahap 4: Mengunggah Backend (FastAPI)
1. Di aaPanel, pergi ke menu **App Store** (kiri bawah). Cari dan install **Python Manager** (atau PM2).
2. Pergi ke menu **Files**. Masuk ke direktori `/www/wwwroot/`.
3. Buat folder baru, misal `api_lingga`.
4. Unggah seluruh isi folder `backend/` dari laptop Anda (kecuali folder `__pycache__` dan `.venv`) ke dalam folder `api_lingga` di aaPanel.
5. Edit file `.env` yang ada di aaPanel, ubah URL databasenya menjadi:
   `DATABASE_URL="mysql+pymysql://lingga_user:Lingg4!POS2026@127.0.0.1/lingga_pos_db"`
6. Gunakan **Python Manager** di aaPanel untuk menambahkan proyek baru. Arahkan ke folder `api_lingga`, pilih file `main.py`, dan install module dari `requirements.txt`. aaPanel akan otomatis menjalankan server FastAPI Anda secara non-stop 24/7.

---

## Tahap 5: Mengunggah Frontend (Tampilan Kasir)
1. Buka Terminal di laptop/komputer Anda saat ini, masuk ke folder `frontend`.
2. Buka file `src/api/axios.js` dan ubah `baseURL` dari `http://localhost:8000` menjadi alamat IP VPS Anda atau domain API Anda (misal: `https://api.domainanda.com`).
3. Jalankan perintah `npm run build` di terminal laptop Anda.
4. Akan muncul folder baru bernama `dist`. 
5. Kembali ke aaPanel, buka menu **Website** -> **Add Site**.
6. Masukkan nama domain kasir Anda (misal: `app.domainanda.com` atau cukup IP VPS Anda jika belum punya domain).
7. Buka menu **Files**, masuk ke folder website yang baru dibuat (biasanya `/www/wwwroot/app.domainanda.com`).
8. Unggah **semua isi folder `dist`** dari laptop Anda ke dalam folder ini.
9. Di menu konfigurasi website aaPanel, atur URL Rewrite (untuk React SPA) dengan menambahkan kode: `try_files $uri $uri/ /index.html;`
10. Di menu **Website**, klik tombol **SSL**, pilih **Let's Encrypt**, dan klik *Apply* agar web Anda memiliki gembok aman (HTTPS) secara gratis!

🎉 **Selesai!** Lingga POS kini sudah online dan bisa diakses karyawan dari mana saja.

---

> [!TIP]
> Jika Anda memutuskan untuk menyewa VPS Hostinger, beritahu saya. Saya dapat membuatkan **Script Otomatis** yang akan mempercepat Tahap 4 dan Tahap 5, sehingga Anda tidak perlu banyak menekan tombol di aaPanel.
