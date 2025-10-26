# TODO: Tambah Menu Kuliner

Tujuan: menambah menu `Kuliner` di navigasi utama yang menampilkan daftar kuliner (tanpa tautan) di halaman tersendiri.

## 1. Tambah Rute Baru
- **File**: `public/index.php`
- **Langkah**: setelah blok `/destinasi`, tambahkan pengecekan path `/kuliner`.

```php
if ($path === '/kuliner') {
  $ctrl->kuliner();
  exit;
}
```

## 2. Perluas Controller
- **File**: `app/controllers/DestinasiController.php`
- **Langkah**:
  1. Tambahkan method baru `kuliner()`.
  2. Query tabel `kuliner` dan render view khusus.

```php
public function kuliner() {
  $sql = "SELECT nama_kuliner, kategori FROM kuliner ORDER BY nama_kuliner";
  $rows = db()->query($sql)->fetchAll();
  render('kuliner/index', ['items' => $rows, 'title' => 'Daftar Kuliner']);
}
```

## 3. Buat View Kuliner
- **File baru**: `app/views/kuliner/index.php`
- **Isi contoh**:

```php
<?php /** @var array $items */ ?>
<ul>
  <?php if ($items): ?>
    <?php foreach ($items as $k): ?>
      <li>
        <?= htmlspecialchars($k['nama_kuliner']) ?>
        <?php if (!empty($k['kategori'])): ?>
          <small>(<?= htmlspecialchars($k['kategori']) ?>)</small>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  <?php else: ?>
    <li><i>Belum ada data kuliner</i></li>
  <?php endif; ?>
</ul>
```

## 4. Perbarui Navigasi
- **File**: `app/views/layout.php`
- **Langkah**: tambahkan tautan baru di elemen `<nav>`.

```php
<nav>
        <a href="<?= $basePath === '' ? '/destinasi' : $basePath ?>">Destinasi</a>
        <a href="<?= $basePath === '' ? '/kuliner' : $basePath . '/kuliner' ?>">Kuliner</a>
      </nav>
```
