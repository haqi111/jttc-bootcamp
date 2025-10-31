<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title><?= isset($title) ? htmlspecialchars($title) . ' — ' : '' ?>Pariwisata</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 (CDN) -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous"
  >

  <style>
    body{font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial;}
    a{text-decoration:none}
    a:hover{text-decoration:underline}
    header, footer{margin-bottom:16px}
  </style>
</head>
<body class="bg-light">
  <div class="container py-4">
    <header class="mb-3">
      <h1 class="h4 mb-2"><?= htmlspecialchars($title ?? 'Pariwisata') ?></h1>
      <?php
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
        if ($basePath === '/' || $basePath === '.') { $basePath = ''; }
      ?>
      <?php $user = current_user(); ?>
      <nav class="d-flex flex-wrap gap-2 align-items-center">
        <a href="<?= $basePath === '' ? '/destinasi' : $basePath ?>"
          class="btn btn-outline-primary btn-sm">
          Destinasi
        </a>
        <a href="<?= $basePath === '' ? '/destinasi/create' : $basePath . '/create' ?>"
          class="btn btn-primary btn-sm">
          Tambah Destinasi
        </a>
        <div class="ms-auto d-flex align-items-center gap-2">
          <?php if ($user): ?>
            <span class="text-muted small">
              Logged in as <?= htmlspecialchars($user) ?>
            </span>
            <form method="post"
                  action="<?= $basePath === '' ? '/logout' : $basePath . '/logout' ?>"
                  class="m-0">
              <button type="submit" class="btn btn-outline-danger btn-sm">
                Logout
              </button>
            </form>
          <?php else: ?>
            <a href="<?= $basePath === '' ? '/login' : $basePath . '/login' ?>"
               class="btn btn-success btn-sm">
              Login
            </a>
          <?php endif; ?>
        </div>
      </nav>
      <hr>
    </header>

    <main><?= $content ?></main>

    <footer class="mt-4">
      <hr>
      <small class="text-muted">&copy; <?= date('Y') ?> Pariwisata Demo</small>
    </footer>
  </div>

  <!-- Bootstrap 5 Bundle (JS) -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"
  ></script>
</body>
</html>
