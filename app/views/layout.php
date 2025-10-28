<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title><?= isset($title) ? htmlspecialchars($title) . ' — ' : '' ?>Pariwisata</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="<?= $basePath === '' ? 'assets/css/style.css' : $basePath . '/assets/css/style.css' ?>">
</head>

<body>
  <header>
    <?php
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
    if ($basePath === '/' || $basePath === '.') {
      $basePath = '';
    }
    ?>
    <nav class="navbar navbar-expand-lg bg-body-transparent fixed-top">
      <div class="container">
        <a class="navbar-brand" href="#">
          <img src="<?= $basePath === '' ? 'assets/images/logo.png' : $basePath . '/assets/images/logo.png' ?>" alt="Logo" width="80" height="80" class="d-inline-block align-text-top">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link active text-white fs-5 text-shadow" aria-current="page" href="<?= $basePath === '' ? '/' : $basePath . '/' ?>">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white fs-5" href="<?= $basePath === '' ? '/destinasi' : $basePath . '/destinasi' ?>">Destinasi</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white fs-5" href="<?= $basePath === '' ? '/destinasi/create' : $basePath . '/destinasi/create' ?>">Tambah Destinasi</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <main><?= $content ?></main>

  <footer class="text-center mt-4 mb-4">
    <hr><small>&copy; <?= date('Y') ?> Pariwisata Demo</small>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>