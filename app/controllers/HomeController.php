<?php
require_once __DIR__ . '/../lib/db.php';
require_once __DIR__ . '/../lib/view.php';

class HomeController
{
  public function index()
  {
    $sql = "SELECT id_destinasi, nama_destinasi, kota FROM destinasi ORDER BY nama_destinasi";
    $rows = db()->query($sql)->fetchAll();
    render('home', ['items' => $rows]);
  }
}
