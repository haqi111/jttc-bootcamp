<?php
/** @var array $dest */
/** @var array $atraksi */
/** @var array $kuliner */
?>
<div class="row">
  <?php if (!empty($dest['gambar'])): ?>
    <div class="col-md-6 mb-4">
      <div class="card">
        <img src="/<?= htmlspecialchars($dest['gambar']) ?>" class="card-img-top" alt="<?= htmlspecialchars($dest['nama_destinasi']) ?>" style="max-height: 300px; object-fit: cover;">
      </div>
    </div>
    <div class="col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title">Informasi Destinasi</h5>
          <p class="card-text">
            <strong>Kota:</strong> <?= htmlspecialchars($dest['kota']) ?><br>
            <strong>Alamat:</strong> <?= htmlspecialchars($dest['alamat']) ?>
          </p>
        </div>
      </div>
    </div>
  <?php else: ?>
    <div class="col-12 mb-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Informasi Destinasi</h5>
          <p class="card-text">
            <strong>Kota:</strong> <?= htmlspecialchars($dest['kota']) ?><br>
            <strong>Alamat:</strong> <?= htmlspecialchars($dest['alamat']) ?>
          </p>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<div class="row">
  <div class="col-md-4">
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title">Atraksi</h5>
        <ul class="list-group list-group-flush">
          <?php
            $ada = false;
            foreach ($atraksi as $r):
              if ($r['nama_atraksi']) { $ada = true; ?>
                <li class="list-group-item"><?= htmlspecialchars($r['nama_atraksi']) ?></li>
          <?php } endforeach;
            if (!$ada) echo '<li class="list-group-item"><i>Belum ada atraksi</i></li>';
          ?>
        </ul>
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Kuliner</h5>
          <a href="/destinasi/<?= $dest['id_destinasi'] ?>/kuliner/create" class="btn btn-sm btn-primary">Tambah</a>
        </div>
        <ul class="list-group list-group-flush mt-3">
          <?php
            $ada = false;
            foreach ($kuliner as $item):
              $ada = true;
              $kategori = $item['kategori'] ? ' <span class="badge bg-secondary">' . htmlspecialchars($item['kategori']) . '</span>' : '';
              ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <?= htmlspecialchars($item['nama_kuliner']) ?><?= $kategori ?>
                </div>
                <form method="post" action="/destinasi/<?= $dest['id_destinasi'] ?>/kuliner/<?= $item['id_kuliner'] ?>/delete" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kuliner ini?')">
                  <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
              </li>
          <?php endforeach;
            if (!$ada) echo '<li class="list-group-item"><i>Belum ada kuliner terdaftar</i></li>';
          ?>
        </ul>
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title">Budaya</h5>
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><i>Belum ada informasi budaya</i></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<a href="/destinasi" class="btn btn-secondary">← Kembali</a>
