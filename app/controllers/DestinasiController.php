<?php
require_once __DIR__ . '/../lib/db.php';
require_once __DIR__ . '/../lib/view.php';

class DestinasiController {
  public function index() {
    $sql = "SELECT id_destinasi, nama_destinasi, kota FROM destinasi ORDER BY nama_destinasi";
    $rows = db()->query($sql)->fetchAll();
    render('destinasi/index', ['items' => $rows, 'title' => 'Daftar Destinasi']);
  }

  public function show($id) {
    $stmt = db()->prepare("
      SELECT d.id_destinasi, d.nama_destinasi, d.kota, d.alamat,
             a.nama_atraksi
      FROM destinasi d
      LEFT JOIN atraksi a ON a.id_destinasi = d.id_destinasi
      WHERE d.id_destinasi = ?
      ORDER BY a.nama_atraksi
    ");
    $stmt->execute([(int)$id]);
    $rows = $stmt->fetchAll();
    if (!$rows) { http_response_code(404); echo "Destinasi tidak ditemukan"; return; }

    $dest = [
      'id_destinasi'   => $rows[0]['id_destinasi'],
      'nama_destinasi' => $rows[0]['nama_destinasi'],
      'kota'           => $rows[0]['kota'],
      'alamat'         => $rows[0]['alamat'],
    ];
    render('destinasi/show', ['dest' => $dest, 'atraksi' => $rows, 'title' => $dest['nama_destinasi']]);
  }
  
}
