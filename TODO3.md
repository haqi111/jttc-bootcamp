# TODO: Form Input Destinasi Baru

Tujuan: menyediakan halaman formulir untuk menambah data destinasi wisata baru dan menyimpan hasilnya ke database.

## 1. Tambah Rute Baru
- **File**: `public/index.php`
- **Langkah**:
  1. Setelah inisialisasi `$ctrl`, tambahkan blok rute untuk `/destinasi/create` (GET) dan `/destinasi` dengan metode POST.
  2. Gunakan pola yang mirip dengan rute yang sudah ada.

```php
if ($path === '/destinasi/create' && $method === 'GET') {
  $ctrl->create();
  exit;
}

if ($path === '/destinasi' && $method === 'POST') {
  $ctrl->store($_POST);
  exit;
}
```

## 2. Perluas DestinasiController
- **File**: `app/controllers/DestinasiController.php`
- **Langkah**:
  1. Tambahkan method `create()` untuk merender formulir (tanpa logika tambahan).
  2. Tambahkan method `store(array $data)` untuk validasi sederhana dan penyimpanan ke tabel `destinasi`.
  3. Terapkan sanitasi dasar dan redirect setelah sukses.

```php
public function create() {
    render('destinasi/create', ['title' => 'Tambah Destinasi']);
  }

public function store(array $data) {
    $nama   = trim($data['nama_destinasi'] ?? '');
    $kota   = trim($data['kota'] ?? '');
    $alamat = trim($data['alamat'] ?? '');

    $errors = [];
    if ($nama === '') { $errors['nama_destinasi'] = 'Nama destinasi wajib diisi'; }
    if ($kota === '') { $errors['kota'] = 'Kota wajib diisi'; }

    if ($errors) {
      render('destinasi/create', [
        'title'   => 'Tambah Destinasi',
        'errors'  => $errors,
        'old'     => ['nama_destinasi' => $nama, 'kota' => $kota, 'alamat' => $alamat],
      ]);
      return;
    }

    $stmt = db()->prepare("
      INSERT INTO destinasi (nama_destinasi, kota, alamat)
      VALUES (:nama, :kota, :alamat)
    ");
    $stmt->execute([
      ':nama'   => $nama,
      ':kota'   => $kota,
      ':alamat' => $alamat === '' ? null : $alamat,
    ]);

    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    if ($basePath === '/' || $basePath === '.') { $basePath = ''; }
    $redirectTo = $basePath === '' ? '/destinasi' : $basePath;

    header('Location: ' . $redirectTo);
    exit;
}
```

## 3. Tambah View Formulir
- **File baru**: `app/views/destinasi/create.php`
- **Isi contoh**: buat formulir sederhana dengan pengisian ulang (`old`) dan pesan error.

```php
<?php
/** @var array|null $old */
/** @var array|null $errors */
$old = $old ?? ['nama_destinasi' => '', 'kota' => '', 'alamat' => ''];
$errors = $errors ?? [];

$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
if ($basePath === '/' || $basePath === '.') { $basePath = ''; }
$destinasiPath = $basePath === '' ? '/destinasi' : $basePath;
$postAction = rtrim($destinasiPath, '/') . '/';
?>

<form method="post" action="<?= $postAction ?>">
  <label>
    Nama Destinasi<br>
    <input type="text" name="nama_destinasi" value="<?= htmlspecialchars($old['nama_destinasi']) ?>">
    <?php if (isset($errors['nama_destinasi'])): ?>
      <small style="color:#b02a37"><?= htmlspecialchars($errors['nama_destinasi']) ?></small>
    <?php endif; ?>
  </label>
  <br><br>

  <label>
    Kota<br>
    <input type="text" name="kota" value="<?= htmlspecialchars($old['kota']) ?>">
    <?php if (isset($errors['kota'])): ?>
      <small style="color:#b02a37"><?= htmlspecialchars($errors['kota']) ?></small>
    <?php endif; ?>
  </label>
  <br><br>

  <label>
    Alamat<br>
    <textarea name="alamat" rows="3"><?= htmlspecialchars($old['alamat']) ?></textarea>
  </label>
  <br><br>

  <button type="submit">Simpan</button>
  <a href="<?= $destinasiPath ?>">Batal</a>
</form>

```

## 4. Perbarui Navigasi
- **File**: `app/views/layout.php`
- **Langkah**:
  1. Tambahkan tautan ke halaman formulir.
  2. Pastikan URL berpindah sesuai dengan `$basePath`.

```php
<nav>
  <a href="<?= $basePath === '' ? '/destinasi' : $basePath ?>">Destinasi</a>
  <a href="<?= $basePath === '' ? '/destinasi/create' : $basePath . '/create' ?>">Tambah Destinasi</a>
</nav>
```

## 5. Validasi Manual
- Uji coba dengan menjalankan `php -S localhost:8000 -t public`.
- Akses `/destinasi/create`, kirim formulir tanpa data untuk memicu error, lalu isi dengan benar.
- Periksa daftar `/destinasi` memastikan entri baru muncul.
