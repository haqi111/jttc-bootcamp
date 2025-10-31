<?php
/** @var array $dest */
/** @var array $atraksi */

function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
$kota   = e($dest['kota']   ?? '-');
$alamat = e($dest['alamat'] ?? '-');
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <a href="/destinasi" class="btn btn-outline-secondary btn-sm">&larr; Kembali</a>
</div>

<div class="card shadow-sm">
  <div class="card-header bg-white">
    <strong>Detail Destinasi</strong>
  </div>
  <div class="card-body">
    <dl class="row mb-0">
      <dt class="col-sm-3">Kota</dt>
      <dd class="col-sm-9"><?= $kota ?></dd>

      <dt class="col-sm-3">Alamat</dt>
      <dd class="col-sm-9"><?= $alamat ?></dd>
    </dl>
  </div>

  <div class="card-body border-top">
    <h6 class="mb-3">Atraksi</h6>
    <ul class="list-group list-group-flush">
      <?php
        $ada = false;
        if (!empty($atraksi) && is_array($atraksi)) {
          foreach ($atraksi as $r) {
            if (!empty($r['nama_atraksi'])) {
              $ada = true;
              echo '<li class="list-group-item">'.e($r['nama_atraksi']).'</li>';
            }
          }
        }
        if (!$ada) {
          echo '<li class="list-group-item text-muted fst-italic">Belum ada atraksi</li>';
        }
      ?>
    </ul>
  </div>

  <div class="card-footer bg-white text-end">
    <a href="/destinasi" class="btn btn-primary">
      Lihat Daftar Destinasi
    </a>
  </div>
</div>
