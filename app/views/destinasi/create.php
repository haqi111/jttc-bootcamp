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

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errors as $error): ?>
        <li><?= htmlspecialchars($error) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post" action="<?= $postAction ?>" class="row g-3" enctype="multipart/form-data">
  <div class="col-md-6">
    <label for="nama_destinasi" class="form-label">Nama Destinasi</label>
    <input type="text" class="form-control" id="nama_destinasi" name="nama_destinasi" value="<?= htmlspecialchars($old['nama_destinasi']) ?>">
    <?php if (isset($errors['nama_destinasi'])): ?>
      <div class="text-danger small"><?= htmlspecialchars($errors['nama_destinasi']) ?></div>
    <?php endif; ?>
  </div>
  
  <div class="col-md-6">
    <label for="kota" class="form-label">Kota</label>
    <input type="text" class="form-control" id="kota" name="kota" value="<?= htmlspecialchars($old['kota']) ?>">
    <?php if (isset($errors['kota'])): ?>
      <div class="text-danger small"><?= htmlspecialchars($errors['kota']) ?></div>
    <?php endif; ?>
  </div>
  
  <div class="col-12">
    <label for="alamat" class="form-label">Alamat</label>
    <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= htmlspecialchars($old['alamat']) ?></textarea>
  </div>
  
  <div class="col-12">
    <label for="gambar" class="form-label">Gambar Destinasi</label>
    <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
    <?php if (isset($errors['gambar'])): ?>
      <div class="text-danger small"><?= htmlspecialchars($errors['gambar']) ?></div>
    <?php endif; ?>
    <div class="form-text">Format yang didukung: JPG, PNG, GIF. Maksimal 2MB.</div>
  </div>
  
  <div class="col-12">
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?= $destinasiPath ?>" class="btn btn-secondary">Batal</a>
  </div>
</form>
