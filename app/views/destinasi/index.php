<?php
/** @var array $items */
/** @var array $kotaList */
/** @var string $kotaFilter */

$items = $items ?? [];
$kotaList = $kotaList ?? [];
$kotaFilter = $kotaFilter ?? '';

$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
echo $scriptName;
$basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
if ($basePath === '/' || $basePath === '.') { $basePath = ''; }
$destinasiPath = $basePath === '' ? '/destinasi' : $basePath;
?>

<div class="row mb-4">
  <div class="col-md-6">
    <form method="get" action="<?= $destinasiPath ?>" class="row row-cols-lg-auto g-3 align-items-center">
      <div class="col-12">
        <label class="visually-hidden" for="kota">Filter berdasarkan Kota</label>
        <select name="kota" id="kota" class="form-select" onchange="this.form.submit()">
          <option value="">Semua Kota</option>
          <?php foreach ($kotaList as $kota): ?>
            <option value="<?= htmlspecialchars($kota) ?>" <?= $kotaFilter === $kota ? 'selected' : '' ?>>
              <?= htmlspecialchars($kota) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php if ($kotaFilter !== ''): ?>
        <div class="col-12">
          <a href="<?= $destinasiPath ?>" class="btn btn-outline-secondary">Hapus Filter</a>
        </div>
      <?php endif; ?>
    </form>
  </div>
</div>

<h3>Daftar Destinasi</h3>
<?php if ($items): ?>
  <div class="row row-cols-1 row-cols-md-2 g-4">
    <?php foreach ($items as $item): ?>
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
    <p class="mb-0"><i>Belum ada destinasi wisata.</i></p>
  </div>
<?php endif; ?>
