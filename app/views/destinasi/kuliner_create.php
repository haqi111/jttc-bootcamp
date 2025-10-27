<?php
/** @var array|null $old */
/** @var array|null $errors */
/** @var array $destinasi */
$old = $old ?? ['nama_kuliner' => '', 'kategori' => ''];
$errors = $errors ?? [];

$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
if ($basePath === '/' || $basePath === '.') { $basePath = ''; }
$destinasiPath = $basePath === '' ? '/destinasi' : $basePath;
$backUrl = $destinasiPath . '/' . $destinasi['id_destinasi'];
$formAction = $destinasiPath . '/' . $destinasi['id_destinasi'] . '/kuliner';
?>

<div class="row">
  <div class="col-12">
    <h2>Tambah Kuliner untuk <?= htmlspecialchars($destinasi['nama_destinasi']) ?></h2>
    
    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
    
    <form method="post" action="<?= $formAction ?>" class="row g-3">
      <div class="col-md-6">
        <label for="nama_kuliner" class="form-label">Nama Kuliner</label>
        <input type="text" class="form-control" id="nama_kuliner" name="nama_kuliner" value="<?= htmlspecialchars($old['nama_kuliner']) ?>">
        <?php if (isset($errors['nama_kuliner'])): ?>
          <div class="text-danger small"><?= htmlspecialchars($errors['nama_kuliner']) ?></div>
        <?php endif; ?>
      </div>
      
      <div class="col-md-6">
        <label for="kategori" class="form-label">Kategori</label>
        <select class="form-select" id="kategori" name="kategori">
          <option value="">Pilih Kategori</option>
          <option value="Makanan" <?= (isset($old['kategori']) && $old['kategori'] === 'Makanan') ? 'selected' : '' ?>>Makanan</option>
          <option value="Minuman" <?= (isset($old['kategori']) && $old['kategori'] === 'Minuman') ? 'selected' : '' ?>>Minuman</option>
          <option value="Camilan" <?= (isset($old['kategori']) && $old['kategori'] === 'Camilan') ? 'selected' : '' ?>>Camilan</option>
          <option value="Buah" <?= (isset($old['kategori']) && $old['kategori'] === 'Buah') ? 'selected' : '' ?>>Buah</option>
        </select>
      </div>
      
      <div class="col-12">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= $backUrl ?>" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>