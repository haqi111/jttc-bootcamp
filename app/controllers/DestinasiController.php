<?php
require_once __DIR__ . '/../lib/db.php';
require_once __DIR__ . '/../lib/view.php';

class DestinasiController
{
  public function index()
  {

    // Get Filter Data from Query Params (if any)
    $filter = [];
    if (isset($_GET['city'])) {
      $filter['city'] = $_GET['city'];
    }

    if (isset($_GET['search'])) {
      $filter['search'] = $_GET['search'];
    }

    // query 
    $sql = "SELECT id_destinasi, nama_destinasi, kota FROM destinasi";
    $conditions = [];
    $params = [];
    if (isset($filter['city']) && $filter['city'] !== '') {
      $conditions[] = "kota = :city";
      $params[':city'] = $filter['city'];
    }
    if (isset($filter['search']) && $filter['search'] !== '') {
      $conditions[] = "nama_destinasi LIKE :search";
      $params[':search'] = '%' . $filter['search'] . '%';
    }
    if ($conditions) {
      $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY nama_destinasi";
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    render('destinasi/index', ['items' => $rows, 'title' => 'Daftar Destinasi']);
  }

  public function show($id)
  {
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
    if (!$rows) {
      http_response_code(404);
      echo "Destinasi tidak ditemukan";
      return;
    }

    $dest = [
      'id_destinasi'   => $rows[0]['id_destinasi'],
      'nama_destinasi' => $rows[0]['nama_destinasi'],
      'kota'           => $rows[0]['kota'],
      'alamat'         => $rows[0]['alamat'],
    ];
    render('destinasi/show', ['dest' => $dest, 'atraksi' => $rows, 'title' => $dest['nama_destinasi']]);
  }

  public function create()
  {
    render('destinasi/create', ['title' => 'Tambah Destinasi']);
  }

  public function store(array $data)
  {
    $nama   = trim($data['nama_destinasi'] ?? '');
    $kota   = trim($data['kota'] ?? '');
    $alamat = trim($data['alamat'] ?? '');

    $errors = [];
    if ($nama === '') {
      $errors['nama_destinasi'] = 'Nama destinasi wajib diisi';
    }
    if ($kota === '') {
      $errors['kota'] = 'Kota wajib diisi';
    }

    if ($errors) {
      render('destinasi/create', [
        'title'   => 'Tambah Destinasi',
        'errors'  => $errors,
        'old'     => ['nama_destinasi' => $nama, 'kota' => $kota, 'alamat' => $alamat],
      ]);
      return;
    }

    $stmt = db()->prepare("
      INSERT INTO destinasi (nama_destinasi, kota, alamat)
      VALUES (:nama, :kota, :alamat)
    ");
    $stmt->execute([
      ':nama'   => $nama,
      ':kota'   => $kota,
      ':alamat' => $alamat === '' ? null : $alamat,
    ]);

    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    if ($basePath === '/' || $basePath === '.') {
      $basePath = '';
    }
    $redirectTo = $basePath === '' ? '/destinasi' : $basePath;

    header('Location: ' . $redirectTo);
    exit;
  }
}
