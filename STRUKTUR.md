# Struktur Proyek Pariwisata

## Ringkasan
- `index.php`  
  Bootstrap di root yang meneruskan seluruh request ke `public/index.php` agar proyek tetap berjalan ketika document root diarahkan ke folder utama.
- `public/index.php`  
  Front controller yang membaca `REQUEST_URI`, menormalkan trailing slash, mengatur routing dasar (`/destinasi`, `/destinasi/{id}`), memanggil `DestinasiController`, dan mengirim 404 jika rute tidak cocok.
- `app/controllers/DestinasiController.php`  
  Controller utama; `index()` mengambil daftar destinasi dan meneruskannya ke view, `show($id)` mengambil detail destinasi beserta atraksi lalu merender view detail.
- `app/lib/db.php`  
  Helper koneksi database; memuat konfigurasi dari `config/db.php`, membuat instance PDO tunggal, serta menyetel mode fetch dan error handling.
- `app/lib/view.php`  
  Helper rendering; menemukan file view, mengekstrak data ke variabel lokal, men-buffer output view, kemudian membungkusnya dengan `views/layout.php`.
- `app/views/layout.php`  
  Template kerangka HTML yang memuat `<head>`, navigasi ke `/destinasi`, dan slot `$content` untuk isi halaman.
- `app/views/destinasi/index.php`  
  View daftar yang menampilkan seluruh destinasi dengan tautan menuju detail masing-masing.
- `app/views/destinasi/show.php`  
  View detail yang memuat kota, alamat, serta daftar atraksi yang terhubung dengan destinasi.
- `config/db.php`  
  Menyimpan konfigurasi koneksi MySQL (DSN, user, password).
- `schema.sql`  
  Skrip SQL untuk membuat tabel `destinasi`, `atraksi`, `kuliner`, `destinasi_kuliner`, sekaligus data seed awal.
- `README.md`  
  Petunjuk singkat menyiapkan database, konfigurasi kredensial, cara menjalankan server built-in PHP, dan ringkasan alur request.
- `.htaccess`  
  Aturan rewrite Apache yang mengarahkan request non-file/non-direktori ke `index.php` sehingga URL cantik (`/destinasi/1`, dll.) tetap diproses oleh front controller.
- `TODO.txt`  
  Daftar tugas lanjutan, saat ini berfokus pada rute dan tampilan daftar kuliner per destinasi.
