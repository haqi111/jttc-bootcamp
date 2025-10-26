<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title><?= isset($title) ? htmlspecialchars($title) . ' — ' : '' ?>Pariwisata</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial; margin: 24px;}
    a{color:#0a58ca; text-decoration:none} a:hover{text-decoration:underline}
    header, footer{margin-bottom:16px}
    .container{max-width:900px; margin:0 auto}
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1><?= htmlspecialchars($title ?? 'Pariwisata') ?></h1>
      <?php
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
        if ($basePath === '/' || $basePath === '.') { $basePath = ''; }
      ?>
      <nav>
        <a href="<?= $basePath === '' ? '/destinasi' : $basePath ?>">Destinasi</a>
      </nav>
      <hr>
    </header>

    <main><?= $content ?></main>

    <footer><hr><small>&copy; <?= date('Y') ?> Pariwisata Demo</small></footer>
  </div>
</body>
</html>
