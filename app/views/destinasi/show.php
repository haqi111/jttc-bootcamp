<?php
/** @var array $dest */
/** @var array $atraksi */
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

<p><a href="/destinasi">← Kembali</a></p>
