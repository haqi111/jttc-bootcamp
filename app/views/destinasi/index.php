<section class="banner position-relative text-center text-white d-flex align-items-center justify-content-center"
  style="
  background-image: url('https://t3.ftcdn.net/jpg/09/03/91/64/360_F_903916468_L5Oli1cL45LG0I58JTVPbNv23khMK5y9.jpg');
  background-size: cover;
  background-position: center;
  height: 50vh;
  position: relative;
  overflow: hidden;
">
  <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0,0,0,0.55);"></div>
  <h1 class="display-4 fw-bold text-shadow position-relative">Destination</h1>
</section>


<section class="container py-5 mt-4">
  <form action="" method="get">
    <div class="row mb-4 align-items-center">
      <div class="col-md-3 mb-2 mb-md-0">
        <select class="form-select" name="city">
          <option value="">Filter by City</option>
          <option value="Bandung">Bandung</option>
          <option value="Yogyakarta">Yogyakarta</option>
          <option value="Bali">Bali</option>
        </select>
      </div>
      <div class="col-md-6 mb-2 mb-md-0">
        <input type="text" class="form-control" name="search" placeholder="Search destinations...">
      </div>
      <div class="col-md-3">
        <button class="btn btn-primary w-100" type="submit">Search</button>
      </div>
    </div>
  </form>

  <div class="row">
    <?php foreach ($items as $d): ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-0">
          <img src="<?= $basePath ?>/assets/images/destinasi/<?= (int)$d['id_destinasi'] ?>.jpg" class="card-img-top" alt="<?= htmlspecialchars($d['nama_destinasi']) ?>">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($d['nama_destinasi']) ?></h5>
            <p class="card-text text-muted mb-2"><?= htmlspecialchars($d['kota']) ?></p>
            <a href="<?= $basePath ?>/destinasi/<?= (int)$d['id_destinasi'] ?>" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>