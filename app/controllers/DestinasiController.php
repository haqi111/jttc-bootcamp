<?php
require_once __DIR__ . '/../lib/db.php';
require_once __DIR__ . '/../lib/view.php';

class DestinasiController {
  public function index() {
    $kotaFilter = $_GET['kota'] ?? '';
    
    // Query untuk daftar destinasi dengan filter
    $sql = "SELECT id_destinasi, nama_destinasi, kota FROM destinasi";
    $params = [];
    
    if ($kotaFilter !== '') {
      $sql .= " WHERE kota = ?";
      $params[] = $kotaFilter;
    }
    
    $sql .= " ORDER BY nama_destinasi";
    
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $items = $stmt->fetchAll();
    
    // Query untuk daftar kota unik
    $kotaStmt = db()->query("SELECT DISTINCT kota FROM destinasi ORDER BY kota");
    $kotaList = $kotaStmt->fetchAll(PDO::FETCH_COLUMN);
    
    render('destinasi/index', [
      'items' => $items,
      'kotaList' => $kotaList,
      'kotaFilter' => $kotaFilter,
      'title' => 'Daftar Destinasi'
    ]);
  }

  public function show($id) {
    // Query untuk destinasi dan atraksi (sudah ada)
    $stmt = db()->prepare("
      SELECT d.id_destinasi, d.nama_destinasi, d.kota, d.alamat, d.gambar,
             a.nama_atraksi
      FROM destinasi d
      LEFT JOIN atraksi a ON a.id_destinasi = d.id_destinasi
      WHERE d.id_destinasi = ?
      ORDER BY a.nama_atraksi
    ");
    $stmt->execute([(int)$id]);
    $rows = $stmt->fetchAll();
    if (!$rows) { http_response_code(404); echo "Destinasi tidak ditemukan"; return; }

    // Query tambahan untuk kuliner
    $stmtKuliner = db()->prepare("
      SELECT id_kuliner, nama_kuliner, kategori
      FROM kuliner
      WHERE id_destinasi = ?
      ORDER BY nama_kuliner
    ");
    $stmtKuliner->execute([(int)$id]);
    $kuliner = $stmtKuliner->fetchAll();

    $dest = [
      'id_destinasi'   => $rows[0]['id_destinasi'],
      'nama_destinasi' => $rows[0]['nama_destinasi'],
      'kota'           => $rows[0]['kota'],
      'alamat'         => $rows[0]['alamat'],
      'gambar'         => $rows[0]['gambar'],
    ];
    
    render('destinasi/show', [
      'dest' => $dest, 
      'atraksi' => $rows, 
      'kuliner' => $kuliner,
      'title' => $dest['nama_destinasi']
    ]);
  }

  public function create() {
    render('destinasi/create', ['title' => 'Tambah Destinasi']);
  }

  public function store(array $data) {
    $nama   = trim($data['nama_destinasi'] ?? '');
    $kota   = trim($data['kota'] ?? '');
    $alamat = trim($data['alamat'] ?? '');

    $errors = [];
    if ($nama === '') { $errors['nama_destinasi'] = 'Nama destinasi wajib diisi'; }
    if ($kota === '') { $errors['kota'] = 'Kota wajib diisi'; }

    // Handle file upload
    $gambar = null;
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
      $file = $_FILES['gambar'];
      $fileName = $file['name'];
      $fileTmpName = $file['tmp_name'];
      $fileSize = $file['size'];
      $fileType = $file['type'];
      
      // Validate file type
      $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
      if (!in_array($fileType, $allowedTypes)) {
        $errors['gambar'] = 'Format gambar tidak didukung. Gunakan JPEG, PNG, atau GIF.';
      }
      
      // Validate file size (max 2MB)
      if ($fileSize > 2 * 1024 * 1024) {
        $errors['gambar'] = 'Ukuran gambar terlalu besar. Maksimal 2MB.';
      }
      
      if (empty($errors)) {
        // Generate unique filename
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = uniqid() . '.' . $fileExtension;
        $uploadPath = __DIR__ . '/../../public/uploads/destinasi/' . $newFileName;
        
        // Move uploaded file
        if (move_uploaded_file($fileTmpName, $uploadPath)) {
          $gambar = 'uploads/destinasi/' . $newFileName;
        } else {
          $errors['gambar'] = 'Gagal mengunggah gambar.';
        }
      }
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
      INSERT INTO destinasi (nama_destinasi, kota, alamat, gambar)
      VALUES (:nama, :kota, :alamat, :gambar)
    ");
    $stmt->execute([
      ':nama'   => $nama,
      ':kota'   => $kota,
      ':alamat' => $alamat === '' ? null : $alamat,
      ':gambar' => $gambar,
    ]);

    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    if ($basePath === '/' || $basePath === '.') { $basePath = ''; }
    $redirectTo = $basePath === '' ? '/destinasi' : $basePath;

    header('Location: ' . $redirectTo);
    exit;
  }
  
  public function searchForm() {
    render('destinasi/search', ['title' => 'Cari Destinasi']);
  }

  public function search(array $data) {
    $keyword = trim($data['keyword'] ?? '');
    $kota = trim($data['kota'] ?? '');
    
    $errors = [];
    if ($keyword === '' && $kota === '') {
      $errors[] = 'Masukkan kata kunci pencarian atau pilih kota';
    }
    
    if ($errors) {
      render('destinasi/search', [
        'title' => 'Cari Destinasi',
        'errors' => $errors,
        'keyword' => $keyword,
        'kota' => $kota
      ]);
      return;
    }
    
    $sql = "SELECT id_destinasi, nama_destinasi, kota FROM destinasi WHERE 1=1";
    $params = [];
    
    if ($keyword !== '') {
      $sql .= " AND (nama_destinasi LIKE ? OR alamat LIKE ?)";
      $params[] = "%$keyword%";
      $params[] = "%$keyword%";
    }
    
    if ($kota !== '') {
      $sql .= " AND kota LIKE ?";
      $params[] = "%$kota%";
    }
    
    $sql .= " ORDER BY nama_destinasi";
    
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll();
    
    render('destinasi/search', [
      'title' => 'Hasil Pencarian',
      'results' => $results,
      'keyword' => $keyword,
      'kota' => $kota
    ]);
  }
  
  public function landing() {
    // Get popular destinations
    $sql = "SELECT id_destinasi, nama_destinasi, kota, alamat FROM destinasi ORDER BY nama_destinasi LIMIT 6";
    $destinations = db()->query($sql)->fetchAll();
    
    render('landing', [
      'title' => 'Beranda',
      'destinations' => $destinations
    ]);
  }
  
  // Methods for culinary management
  public function createKuliner($destinasiId) {
    // Get destination info
    $stmt = db()->prepare("SELECT id_destinasi, nama_destinasi FROM destinasi WHERE id_destinasi = ?");
    $stmt->execute([(int)$destinasiId]);
    $destinasi = $stmt->fetch();
    
    if (!$destinasi) {
      http_response_code(404);
      echo "Destinasi tidak ditemukan";
      return;
    }
    
    render('destinasi/kuliner_create', [
      'title' => 'Tambah Kuliner',
      'destinasi' => $destinasi
    ]);
  }
  
  public function storeKuliner($destinasiId, array $data) {
    // Get destination info
    $stmt = db()->prepare("SELECT id_destinasi, nama_destinasi FROM destinasi WHERE id_destinasi = ?");
    $stmt->execute([(int)$destinasiId]);
    $destinasi = $stmt->fetch();
    
    if (!$destinasi) {
      http_response_code(404);
      echo "Destinasi tidak ditemukan";
      return;
    }
    
    $namaKuliner = trim($data['nama_kuliner'] ?? '');
    $kategori = trim($data['kategori'] ?? '');
    
    $errors = [];
    if ($namaKuliner === '') {
      $errors['nama_kuliner'] = 'Nama kuliner wajib diisi';
    }
    
    if ($errors) {
      render('destinasi/kuliner_create', [
        'title' => 'Tambah Kuliner',
        'errors' => $errors,
        'old' => ['nama_kuliner' => $namaKuliner, 'kategori' => $kategori],
        'destinasi' => $destinasi
      ]);
      return;
    }
    
    // Insert culinary item
    $stmt = db()->prepare("INSERT INTO kuliner (id_destinasi, nama_kuliner, kategori) VALUES (:id_destinasi, :nama_kuliner, :kategori)");
    $stmt->execute([
      ':id_destinasi' => (int)$destinasiId,
      ':nama_kuliner' => $namaKuliner,
      ':kategori' => $kategori === '' ? null : $kategori
    ]);
    
    // Redirect back to destination detail
    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    if ($basePath === '/' || $basePath === '.') { $basePath = ''; }
    $redirectTo = $basePath === '' ? '/destinasi/' . $destinasiId : $basePath . '/destinasi/' . $destinasiId;
    
    header('Location: ' . $redirectTo);
    exit;
  }
  
  public function deleteKuliner($destinasiId, $kulinerId) {
    // Delete culinary item
    $stmt = db()->prepare("DELETE FROM kuliner WHERE id_kuliner = ? AND id_destinasi = ?");
    $stmt->execute([(int)$kulinerId, (int)$destinasiId]);
    
    // Redirect back to destination detail
    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    if ($basePath === '/' || $basePath === '.') { $basePath = ''; }
    $redirectTo = $basePath === '' ? '/destinasi/' . $destinasiId : $basePath . '/destinasi/' . $destinasiId;
    
    header('Location: ' . $redirectTo);
    exit;
  }
  
}
