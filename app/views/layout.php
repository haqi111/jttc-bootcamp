<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title><?= isset($title) ? htmlspecialchars($title) . ' — ' : '' ?>Pariwisata</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
  <?php
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
    if ($basePath === '/' || $basePath === '.') { $basePath = ''; }
  ?>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand" href="<?= $basePath === '' ? '/' : $basePath ?>">Pariwisata</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="<?= $basePath === '' ? '/' : $basePath ?>">Beranda</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $basePath === '' ? '/destinasi' : $basePath ?>/destinasi">Destinasi</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $basePath === '' ? '/destinasi/create' : $basePath ?>/destinasi/create">Tambah Destinasi</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $basePath === '' ? '/destinasi/search' : $basePath ?>/destinasi/search">Cari Destinasi</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container mt-4">
    <main><?= $content ?></main>
  </div>

  <!-- Footer -->
  <footer class="footer bg-light mt-5">
    <div class="container">
      <div class="text-center py-3">
        <small class="text-muted">&copy; <?= date('Y') ?> Pariwisata Demo</small>
      </div>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
