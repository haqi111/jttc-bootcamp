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
