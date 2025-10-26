<?php /** @var array $items */ ?>
<ul>
  <?php foreach ($items as $d): ?>
    <li>
      <a href="/destinasi/<?= (int)$d['id_destinasi'] ?>">
        <?= htmlspecialchars($d['nama_destinasi']) ?>
      </a> — <?= htmlspecialchars($d['kota']) ?>
    </li>
  <?php endforeach; ?>
</ul>
