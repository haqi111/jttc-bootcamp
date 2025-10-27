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
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errors as $error): ?>
        <li><?= htmlspecialchars($error) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<div class="card mb-4">
  <div class="card-header">
    <h3 class="card-title mb-0">Formulir Pencarian</h3>
  </div>
  <div class="card-body">
    <form method="post" action="<?= $searchAction ?>" class="row g-3">
      <div class="col-md-6">
        <label for="keyword" class="form-label">Kata Kunci</label>
        <input type="text" class="form-control" id="keyword" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="Nama destinasi atau alamat">
      </div>
      
      <div class="col-md-6">
        <label for="kota" class="form-label">Kota</label>
        <input type="text" class="form-control" id="kota" name="kota" value="<?= htmlspecialchars($kota) ?>" placeholder="Nama kota">
      </div>
      
      <div class="col-12">
        <button type="submit" class="btn btn-primary">Cari</button>
        <a href="<?= $destinasiPath ?>" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>

<?php if (isset($results)): ?>
  <div class="card">
    <div class="card-header">
      <h3 class="card-title mb-0">Hasil Pencarian</h3>
    </div>
    <div class="card-body">
      <?php if (count($results) > 0): ?>
        <div class="row row-cols-1 row-cols-md-2 g-4">
          <?php foreach ($results as $item): ?>
            <div class="col">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">
                    <a href="<?= $destinasiPath ?>/<?= $item['id_destinasi'] ?>" class="text-decoration-none">
                      <?= htmlspecialchars($item['nama_destinasi']) ?>
                    </a>
                  </h5>
                  <p class="card-text">
                    <span class="badge bg-secondary"><?= htmlspecialchars($item['kota']) ?></span>
                  </p>
                  <a href="<?= $destinasiPath ?>/<?= $item['id_destinasi'] ?>" class="btn btn-primary">Lihat Detail</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="alert alert-info">
          <p class="mb-0"><i>Tidak ditemukan destinasi yang sesuai dengan kriteria pencarian.</i></p>
        </div>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>