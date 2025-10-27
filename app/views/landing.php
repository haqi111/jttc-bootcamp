<?php
/** @var array $destinations */
$destinations = $destinations ?? [];
?>

<!-- Hero Section -->
<div class="jumbotron bg-primary text-white rounded-3 p-5 mb-4">
  <div class="container py-5">
    <h1 class="display-4 fw-bold">Selamat Datang di Pariwisata</h1>
    <p class="col-md-8 fs-4">Temukan destinasi wisata terbaik di Indonesia dengan pengalaman yang tak terlupakan.</p>
    <a class="btn btn-light btn-lg" href="/destinasi" role="button">Jelajahi Destinasi</a>
  </div>
</div>

<!-- Features Section -->
<div class="container mb-5">
  <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
    <div class="col d-flex align-items-start">
      <div class="icon-square text-body-emphasis bg-body-secondary d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0 me-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-map feature-icon" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M15.817.113A.5.5 0 0 1 16 .5v14a.5.5 0 0 1-.402.49l-5 1a.502.502 0 0 1-.196 0L5.5 15.01l-4.902.98A.5.5 0 0 1 0 15.5v-14a.5.5 0 0 1 .402-.49l5-1a.5.5 0 0 1 .196 0L10.5.99l4.902-.98a.5.5 0 0 1 .415.103zM10 1.91l-4-.8v12.98l4 .8V1.91zm1 12.98 4-.8V1.11l-4 .8v12.98zm-6-.8V1.11l-4 .8v12.98l4-.8z"/>
        </svg>
      </div>
      <div>
        <h3 class="fs-4 text-body-emphasis">Destinasi Lengkap</h3>
        <p>Jelajahi berbagai destinasi wisata dari seluruh Indonesia dalam satu platform.</p>
      </div>
    </div>
    <div class="col d-flex align-items-start">
      <div class="icon-square text-body-emphasis bg-body-secondary d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0 me-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-search feature-icon" viewBox="0 0 16 16">
          <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
        </svg>
      </div>
      <div>
        <h3 class="fs-4 text-body-emphasis">Pencarian Mudah</h3>
        <p>Temukan destinasi impian Anda dengan fitur pencarian yang cepat dan akurat.</p>
      </div>
    </div>
    <div class="col d-flex align-items-start">
      <div class="icon-square text-body-emphasis bg-body-secondary d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0 me-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-heart feature-icon" viewBox="0 0 16 16">
          <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
        </svg>
      </div>
      <div>
        <h3 class="fs-4 text-body-emphasis">Rekomendasi Terbaik</h3>
        <p>Dapatkan rekomendasi destinasi terbaik berdasarkan minat dan preferensi Anda.</p>
      </div>
    </div>
  </div>
</div>

<!-- Popular Destinations -->
<div class="container mb-5">
  <h2 class="mb-4">Destinasi Populer</h2>
  <?php if (!empty($destinations)): ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php foreach (array_slice($destinations, 0, 3) as $destination): ?>
        <div class="col">
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($destination['nama_destinasi']) ?></h5>
              <p class="card-text">
                <strong>Kota:</strong> <?= htmlspecialchars($destination['kota']) ?><br>
                <?php if (!empty($destination['alamat'])): ?>
                  <strong>Alamat:</strong> <?= htmlspecialchars($destination['alamat']) ?>
                <?php endif; ?>
              </p>
              <a href="/destinasi/<?= $destination['id_destinasi'] ?>" class="btn btn-primary">Lihat Detail</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-info">
      <p>Belum ada destinasi wisata yang tersedia.</p>
    </div>
  <?php endif; ?>
</div>

<!-- Call to Action -->
<div class="container mb-5">
  <div class="p-5 text-center bg-light rounded-3">
    <h2>Tambah Destinasi Baru</h2>
    <p class="col-lg-8 mx-auto fs-5 text-muted">
      Punya destinasi wisata yang ingin Anda bagikan? Tambahkan ke platform kami.
    </p>
    <div class="d-inline-flex gap-2 mb-5">
      <a class="btn btn-primary btn-lg px-4 gap-3" href="/destinasi/create">Tambah Destinasi</a>
    </div>
  </div>
</div>