# Pertemuan 9: Pengelolaan Konten Destinasi Wisata

Tujuan: Mengimplementasikan fitur pengelolaan konten destinasi wisata dengan katalog atraksi, kuliner, dan budaya serta fitur interaktif berbasis input pengguna.

## 1. Penyajian Katalog Wisata (Atraksi, Kuliner, Budaya)

### 1.1. Perbarui Controller untuk Menampilkan Semua Kategori Konten
- **File**: `app/controllers/DestinasiController.php`
- **Langkah**:
  1. Modifikasi method `show($id)` untuk mengambil data atraksi, kuliner, dan informasi tambahan.
  2. Tambahkan query untuk mengambil kuliner terkait destinasi.
  3. Tambahkan struktur data untuk konten budaya (jika ada dalam skema).

```php
public function show($id) {
  // Query untuk destinasi dan atraksi (sudah ada)
  $stmt = db()->prepare("
    SELECT d.id_destinasi, d.nama_destinasi, d.kota, d.alamat,
           a.nama_atraksi
    FROM destinasi d
    LEFT JOIN atraksi a ON a.id_destinasi = d.id_destinasi
    WHERE d.id_destinasi = ?
    ORDER BY a.nama_atraksi
  ");
  $stmt->execute([(int)$id]);
  $rows = $stmt->fetchAll();
  if (!$rows) { http_response_code(404); echo "Destinasi tidak ditemukan"; return; }

  // Query tambahan untuk kuliner
  $stmtKuliner = db()->prepare("
    SELECT nama_kuliner, kategori
    FROM kuliner
    WHERE id_destinasi = ?
    ORDER BY nama_kuliner
  ");
  $stmtKuliner->execute([(int)$id]);
  $kuliner = $stmtKuliner->fetchAll();

  $dest = [
    'id_destinasi'   => $rows[0]['id_destinasi'],
    'nama_destinasi' => $rows[0]['nama_destinasi'],
    'kota'           => $rows[0]['kota'],
    'alamat'         => $rows[0]['alamat'],
  ];
  
  render('destinasi/show', [
    'dest' => $dest, 
    'atraksi' => $rows, 
    'kuliner' => $kuliner,
    'title' => $dest['nama_destinasi']
  ]);
}
```

### 1.2. Perbarui View Detail Destinasi
- **File**: `app/views/destinasi/show.php`
- **Langkah**:
  1. Tambahkan bagian untuk menampilkan kuliner.
  2. Tambahkan bagian untuk menampilkan konten budaya (placeholder).
  3. Format penyajian katalog dengan jelas.

```php
<?php
/** @var array $dest */
/** @var array $atraksi */
/** @var array $kuliner */
?>
<p>
  <b>Kota:</b> <?= htmlspecialchars($dest['kota']) ?><br>
  <b>Alamat:</b> <?= htmlspecialchars($dest['alamat']) ?>
</p>

<h3>Atraksi</h3>
<ul>
  <?php
    $ada = false;
    foreach ($atraksi as $r):
      if ($r['nama_atraksi']) { $ada = true; ?>
        <li><?= htmlspecialchars($r['nama_atraksi']) ?></li>
  <?php } endforeach;
    if (!$ada) echo '<li><i>Belum ada atraksi</i></li>';
  ?>
</ul>

<h3>Kuliner</h3>
<ul>
  <?php
    $ada = false;
    foreach ($kuliner as $item):
      $ada = true;
      $kategori = $item['kategori'] ? ' (' . htmlspecialchars($item['kategori']) . ')' : '';
      ?>
      <li><?= htmlspecialchars($item['nama_kuliner']) ?><?= $kategori ?></li>
  <?php endforeach;
    if (!$ada) echo '<li><i>Belum ada kuliner terdaftar</i></li>';
  ?>
</ul>

<h3>Budaya</h3>
<ul>
  <li><i>Belum ada informasi budaya</i></li>
</ul>

<p><a href="/destinasi">← Kembali</a></p>
```

## 2. Implementasi Formulir Pencarian Wisata

### 2.1. Tambah Rute untuk Formulir Pencarian
- **File**: `public/index.php`
- **Langkah**:
  1. Tambahkan rute untuk menampilkan formulir pencarian.
  2. Tambahkan rute untuk menangani hasil pencarian.

```php
if ($path === '/destinasi/search' && $method === 'GET') {
  $ctrl->searchForm();
  exit;
}

if ($path === '/destinasi/search' && $method === 'POST') {
  $ctrl->search($_POST);
  exit;
}
```

### 2.2. Tambah Method ke Controller
- **File**: `app/controllers/DestinasiController.php`
- **Langkah**:
  1. Tambahkan method `searchForm()` untuk menampilkan formulir pencarian.
  2. Tambahkan method `search(array $data)` untuk memproses pencarian.

```php
public function searchForm() {
  render('destinasi/search', ['title' => 'Cari Destinasi']);
}

public function search(array $data) {
  $keyword = trim($data['keyword'] ?? '');
  $kota = trim($data['kota'] ?? '');
  
  $sql = "SELECT id_destinasi, nama_destinasi, kota FROM destinasi WHERE 1=1";
  $params = [];
  
  if ($keyword !== '') {
    $sql .= " AND (nama_destinasi LIKE ? OR alamat LIKE ?)";
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
  }
  
  if ($kota !== '') {
    $sql .= " AND kota LIKE ?";
    $params[] = "%$kota%";
  }
  
  $sql .= " ORDER BY nama_destinasi";
  
  $stmt = db()->prepare($sql);
  $stmt->execute($params);
  $results = $stmt->fetchAll();
  
  render('destinasi/search', [
    'title' => 'Hasil Pencarian',
    'results' => $results,
    'keyword' => $keyword,
    'kota' => $kota
  ]);
}
```

### 2.3. Buat View Formulir Pencarian
- **File baru**: `app/views/destinasi/search.php`
- **Isi**:
  1. Formulir dengan input untuk kata kunci dan kota.
  2. Tampilkan hasil pencarian jika ada.

```php
<?php
/** @var string|null $keyword */
/** @var string|null $kota */
/** @var array|null $results */
$keyword = $keyword ?? '';
$kota = $kota ?? '';

$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
if ($basePath === '/' || $basePath === '.') { $basePath = ''; }
$destinasiPath = $basePath === '' ? '/destinasi' : $basePath;
$searchAction = rtrim($destinasiPath, '/') . '/search';
?>

<h3>Formulir Pencarian</h3>
<form method="post" action="<?= $searchAction ?>">
  <label>
    Kata Kunci<br>
    <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="Nama destinasi atau alamat">
  </label>
  <br><br>
  
  <label>
    Kota<br>
    <input type="text" name="kota" value="<?= htmlspecialchars($kota) ?>" placeholder="Nama kota">
  </label>
  <br><br>
  
  <button type="submit">Cari</button>
  <a href="<?= $destinasiPath ?>">Batal</a>
</form>

<?php if (isset($results)): ?>
  <h3>Hasil Pencarian</h3>
  <?php if (count($results) > 0): ?>
    <ul>
      <?php foreach ($results as $item): ?>
        <li>
          <a href="<?= $destinasiPath ?>/<?= $item['id_destinasi'] ?>">
            <?= htmlspecialchars($item['nama_destinasi']) ?>
          </a>
          (<?= htmlspecialchars($item['kota']) ?>)
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p><i>Tidak ditemukan destinasi yang sesuai dengan kriteria pencarian.</i></p>
  <?php endif; ?>
<?php endif; ?>
```

## 3. Penambahan Fitur Filter Lokasi

### 3.1. Perbarui Halaman Index dengan Filter
- **File**: `app/controllers/DestinasiController.php`
- **Langkah**:
  1. Modifikasi method `index()` untuk menerima parameter filter.
  2. Tambahkan query untuk mengambil daftar kota unik.

```php
public function index() {
  $kotaFilter = $_GET['kota'] ?? '';
  
  // Query untuk daftar destinasi dengan filter
  $sql = "SELECT id_destinasi, nama_destinasi, kota FROM destinasi";
  $params = [];
  
  if ($kotaFilter !== '') {
    $sql .= " WHERE kota = ?";
    $params[] = $kotaFilter;
  }
  
  $sql .= " ORDER BY nama_destinasi";
  
  $stmt = db()->prepare($sql);
  $stmt->execute($params);
  $items = $stmt->fetchAll();
  
  // Query untuk daftar kota unik
  $kotaStmt = db()->query("SELECT DISTINCT kota FROM destinasi ORDER BY kota");
  $kotaList = $kotaStmt->fetchAll(PDO::FETCH_COLUMN);
  
  render('destinasi/index', [
    'items' => $items,
    'kotaList' => $kotaList,
    'kotaFilter' => $kotaFilter,
    'title' => 'Daftar Destinasi'
  ]);
}
```

### 3.2. Perbarui View Index dengan Filter
- **File**: `app/views/destinasi/index.php`
- **Langkah**:
  1. Tambahkan form filter berdasarkan kota.
  2. Tampilkan daftar kota untuk filter.

```php
<?php
/** @var array $items */
/** @var array $kotaList */
/** @var string $kotaFilter */

$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
if ($basePath === '/' || $basePath === '.') { $basePath = ''; }
$destinasiPath = $basePath === '' ? '/destinasi' : $basePath;
?>

<form method="get" action="<?= $destinasiPath ?>">
  <label>
    Filter berdasarkan Kota:
    <select name="kota" onchange="this.form.submit()">
      <option value="">Semua Kota</option>
      <?php foreach ($kotaList as $kota): ?>
        <option value="<?= htmlspecialchars($kota) ?>" <?= $kotaFilter === $kota ? 'selected' : '' ?>>
          <?= htmlspecialchars($kota) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </label>
  <?php if ($kotaFilter !== ''): ?>
    <a href="<?= $destinasiPath ?>">Hapus Filter</a>
  <?php endif; ?>
</form>

<h3>Daftar Destinasi</h3>
<?php if ($items): ?>
  <ul>
    <?php foreach ($items as $item): ?>
      <li>
        <a href="<?= $destinasiPath ?>/<?= $item['id_destinasi'] ?>">
          <?= htmlspecialchars($item['nama_destinasi']) ?>
        </a>
        (<?= htmlspecialchars($item['kota']) ?>)
      </li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p><i>Belum ada destinasi wisata.</i></p>
<?php endif; ?>
```

## 4. Validasi dan Umpan Balik Input Pengguna

### 4.1. Perbarui Formulir Pencarian dengan Validasi
- **File**: `app/controllers/DestinasiController.php`
- **Langkah**:
  1. Tambahkan validasi sederhana untuk input pencarian.
  2. Tampilkan pesan error jika diperlukan.

```php
public function search(array $data) {
  $keyword = trim($data['keyword'] ?? '');
  $kota = trim($data['kota'] ?? '');
  
  $errors = [];
  if ($keyword === '' && $kota === '') {
    $errors[] = 'Masukkan kata kunci pencarian atau pilih kota';
  }
  
  if ($errors) {
    render('destinasi/search', [
      'title' => 'Cari Destinasi',
      'errors' => $errors,
      'keyword' => $keyword,
      'kota' => $kota
    ]);
    return;
  }
  
  $sql = "SELECT id_destinasi, nama_destinasi, kota FROM destinasi WHERE 1=1";
  $params = [];
  
  if ($keyword !== '') {
    $sql .= " AND (nama_destinasi LIKE ? OR alamat LIKE ?)";
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
  }
  
  if ($kota !== '') {
    $sql .= " AND kota LIKE ?";
    $params[] = "%$kota%";
  }
  
  $sql .= " ORDER BY nama_destinasi";
  
  $stmt = db()->prepare($sql);
  $stmt->execute($params);
  $results = $stmt->fetchAll();
  
  render('destinasi/search', [
    'title' => 'Hasil Pencarian',
    'results' => $results,
    'keyword' => $keyword,
    'kota' => $kota
  ]);
}
```

### 4.2. Perbarui View Formulir Pencarian dengan Pesan Error
- **File**: `app/views/destinasi/search.php`
- **Langkah**:
  1. Tambahkan penampilan pesan error.
  2. Tambahkan styling sederhana untuk pesan error.

```php
<?php
/** @var string|null $keyword */
/** @var string|null $kota */
/** @var array|null $results */
/** @var array|null $errors */
$keyword = $keyword ?? '';
$kota = $kota ?? '';
$errors = $errors ?? [];

$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
if ($basePath === '/' || $basePath === '.') { $basePath = ''; }
$destinasiPath = $basePath === '' ? '/destinasi' : $basePath;
$searchAction = rtrim($destinasiPath, '/') . '/search';
?>

<?php if (!empty($errors)): ?>
  <div style="color: #b02a37; margin-bottom: 16px;">
    <?php foreach ($errors as $error): ?>
      <p><?= htmlspecialchars($error) ?></p>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<h3>Formulir Pencarian</h3>
<form method="post" action="<?= $searchAction ?>">
  <label>
    Kata Kunci<br>
    <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="Nama destinasi atau alamat">
  </label>
  <br><br>
  
  <label>
    Kota<br>
    <input type="text" name="kota" value="<?= htmlspecialchars($kota) ?>" placeholder="Nama kota">
  </label>
  <br><br>
  
  <button type="submit">Cari</button>
  <a href="<?= $destinasiPath ?>">Batal</a>
</form>

<?php if (isset($results)): ?>
  <h3>Hasil Pencarian</h3>
  <?php if (count($results) > 0): ?>
    <ul>
      <?php foreach ($results as $item): ?>
        <li>
          <a href="<?= $destinasiPath ?>/<?= $item['id_destinasi'] ?>">
            <?= htmlspecialchars($item['nama_destinasi']) ?>
          </a>
          (<?= htmlspecialchars($item['kota']) ?>)
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p><i>Tidak ditemukan destinasi yang sesuai dengan kriteria pencarian.</i></p>
  <?php endif; ?>
<?php endif; ?>
```

## 5. Perbarui Navigasi

### 5.1. Tambahkan Link ke Fitur Baru
- **File**: `app/views/layout.php`
- **Langkah**:
  1. Tambahkan link ke halaman pencarian.
  2. Pastikan semua link mengikuti struktur path yang benar.

```php
<nav>
  <a href="<?= $basePath === '' ? '/destinasi' : $basePath ?>">Destinasi</a>
  <a href="<?= $basePath === '' ? '/destinasi/create' : $basePath . '/create' ?>">Tambah Destinasi</a>
  <a href="<?= $basePath === '' ? '/destinasi/search' : $basePath . '/search' ?>">Cari Destinasi</a>
</nav>
```

## 6. Validasi Manual

- Jalankan server dengan `php -S localhost:8000 -t public`
- Akses `/destinasi` untuk melihat daftar destinasi dengan filter
- Akses `/destinasi/search` untuk menggunakan formulir pencarian
- Uji filter berdasarkan kota
- Uji validasi input dengan mengirim formulir kosong
- Periksa detail destinasi untuk memastikan semua kategori konten ditampilkan