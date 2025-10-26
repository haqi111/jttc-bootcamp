# TODO: Tampilkan Kuliner di Detail Destinasi

Tujuan: ketika pengguna membuka detail destinasi (`/destinasi/{id}`), halaman harus menampilkan daftar kuliner yang terkait dengan destinasi tersebut.

## 1. Perbarui Controller
- **File**: `app/controllers/DestinasiController.php`
- **Langkah**:
  1. Setelah mengambil data destinasi dan atraksi, tambahkan query baru untuk mengambil kuliner.
  2. Sertakan hasil query kuliner saat memanggil `render()`.

```php
// Tambahkan di dalam method show(), setelah $dest terbentuk
$stmtKuliner = db()->prepare("
  SELECT nama_kuliner, kategori
  FROM kuliner
  WHERE id_destinasi = ?
  ORDER BY nama_kuliner
");
$stmtKuliner->execute([$dest['id_destinasi']]);
$kuliner = $stmtKuliner->fetchAll();

render('destinasi/show', [
  'dest'     => $dest,
  'atraksi'  => $rows,
  'kuliner'  => $kuliner,
  'title'    => $dest['nama_destinasi'],
]);
```

## 2. Sesuaikan View Detail
- **File**: `app/views/destinasi/show.php`
- **Langkah**:
  1. Pastikan variabel `$kuliner` diterima.
  2. Render daftar kuliner (tampilkan nama dan kategori bila ada).

```php
<?php /** @var array $kuliner */ ?>

<h3>Kuliner</h3>
<ul>
  <?php if ($kuliner): ?>
    <?php foreach ($kuliner as $item): ?>
      <li>
        <?= htmlspecialchars($item['nama_kuliner']) ?>
        <?php if (!empty($item['kategori'])): ?>
          <small>(<?= htmlspecialchars($item['kategori']) ?>)</small>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  <?php else: ?>
    <li><i>Belum ada kuliner terdaftar</i></li>
  <?php endif; ?>
</ul>
```

