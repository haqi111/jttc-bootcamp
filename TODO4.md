# TODO: Sesi Login Sederhana

Tujuan: menyediakan autentikasi dasar untuk studi. Password tetap disimpan polos sesuai permintaan, tapi setiap langkah sudah menyiapkan struktur yang mudah diganti ke hash di kemudian hari. Semua contoh kode sudah disesuaikan dengan implementasi terbaru (support base path / sub-folder).

## 1. Tambah Tabel `users` (Database)
- **File**: `schema.sql`
- **Penjelasan**: aplikasinya butuh sumber data pengguna. Kita buat tabel `users` berisi username unik dan password plain text, plus seed akun demo supaya gampang diuji.

```sql

DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL 
);


INSERT INTO users (username, password) VALUES
('admin', 'admin123');
```

## 2. Helper URL untuk Base Path
- **File baru**: `app/lib/url.php`
- **Penjelasan**: proyek ini bisa dijalankan via subfolder (`http://localhost/destinasi/...`). Helper ini menghitung base path lalu memberikan utilitas `app_url()` & `app_route()` agar routing dan redirect tetap benar.

```php
<?php
function app_base_path(): string {
  static $base = null;
  if ($base !== null) return $base;

  $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
  $base = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
  if ($base === '/' || $base === '.') $base = '';

  return $base;
}

function app_url(string $path = '/'): string {
  $path = '/' . ltrim($path, '/');
  $base = app_base_path();
  return ($base === '' || $base === '/') ? $path : $base . $path;
}

function app_route(string $requestPath): string {
  $requestPath = $requestPath === '' ? '/' : $requestPath;
  $base = app_base_path();
  if ($base !== '' && strpos($requestPath, $base) === 0) {
    $requestPath = substr($requestPath, strlen($base)) ?: '/';
  }
  return $requestPath;
}
```

## 3. Helper Auth (Session)
- **File**: `app/lib/auth.php`
- **Penjelasan**: kumpulan fungsi untuk membaca/menulis session dan menjaga agar redirect login juga memanfaatkan base path helper.

```php
<?php
require_once __DIR__ . '/url.php';

function current_user(): ?string {
  return $_SESSION['user'] ?? null;
}

function login_user(string $username): void {
  $_SESSION['user'] = $username;
  session_regenerate_id(true);
}

function logout_user(): void {
  unset($_SESSION['user']);
  session_regenerate_id(true);
}

function require_login(): void {
  if (!current_user()) {
    header('Location: ' . app_url('/login'));
    exit;
  }
}
```

## 4. Controller Login
- **File**: `app/controllers/AuthController.php`
- **Penjelasan**: menangani tampilan form, validasi sederhana, pencocokan password plain text, serta redirect memakai `app_url()` supaya aman terhadap subfolder.

```php
<?php
require_once __DIR__ . '/../lib/db.php';
require_once __DIR__ . '/../lib/view.php';
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/url.php';

class AuthController {
  public function form(array $ctx = []): void {
    render('auth/login', [
      'title' => 'Login',
      'old'   => $ctx['old'] ?? ['username' => ''],
      'error' => $ctx['error'] ?? null,
    ]);
  }

  public function login(array $data): void {
    $username = trim($data['username'] ?? '');
    $password = trim($data['password'] ?? '');

    if ($username === '' || $password === '') {
      $this->form(['error' => 'Username dan password wajib diisi', 'old' => ['username' => $username]]);
      return;
    }

    $stmt = db()->prepare('SELECT username, password FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (!$user || $user['password'] !== $password) {
      $this->form(['error' => 'Kredensial tidak valid', 'old' => ['username' => $username]]);
      return;
    }

    login_user($user['username']);
    header('Location: ' . app_url('/destinasi'));
    exit;
  }

  public function logout(): void {
    logout_user();
    header('Location: ' . app_url('/login'));
    exit;
  }
}
```

## 5. Front Controller
- **File**: `public/index.php`
- **Penjelasan**: inisialisasi session, baca URL, normalisasi base path dengan `app_route()`, wajibkan login sebelum masuk ke rute destinasi, dan arahkan semua redirect melalui `app_url()`.

```php
<?php
session_start();

require_once __DIR__ . '/../app/lib/auth.php';
require_once __DIR__ . '/../app/lib/url.php';
require_once __DIR__ . '/../app/controllers/DestinasiController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$path = rtrim($path, '/');
if ($path === '') $path = '/';
$path = app_route($path);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$auth = new AuthController();
$dest = new DestinasiController();

$isLoginRoute = $path === '/login';

if (!current_user() && !$isLoginRoute) {
  header('Location: ' . app_url('/login'));
  exit;
}

if ($isLoginRoute && $method === 'GET') { $auth->form(); exit; }
if ($isLoginRoute && $method === 'POST') { $auth->login($_POST); exit; }
if ($path === '/logout' && $method === 'POST') { $auth->logout(); exit; }
if ($path === '/destinasi/create' && $method === 'GET') { $dest->create(); exit; }
if ($path === '/destinasi' && $method === 'POST') { $dest->store($_POST); exit; }
if ($path === '/' || $path === '/destinasi') { $dest->index(); exit; }
if (preg_match('#^/destinasi/(\\d+)$#', $path, $m)) { $dest->show($m[1]); exit; }

http_response_code(404);
echo 'Not Found';
```

## 6. Controller Destinasi (Redirect)
- **File**: `app/controllers/DestinasiController.php`
- **Penjelasan**: setelah menyimpan data baru, redirect memakai `app_url()` agar kembali ke daftar destinasi dengan path yang benar. (Proteksi login bisa ditambahkan via `require_login()` jika diinginkan di tiap method.)

```php
<?php
require_once __DIR__ . '/../lib/db.php';
require_once __DIR__ . '/../lib/view.php';
require_once __DIR__ . '/../lib/url.php';

class DestinasiController {
  // ... method index/show/create tidak berubah

  public function store(array $data) {
    // validasi & insert seperti sebelumnya

    header('Location: ' . app_url('/destinasi'));
    exit;
  }
}
```

## 7. View Login
- **File**: `app/views/auth/login.php`
- **Penjelasan**: form menggunakan `app_url('/login')` untuk action sehingga submit tetap berada di dalam base path. Tampilan memakai Bootstrap card dengan feedback error.

```php
<?php
/** @var array $old */
/** @var string|null $error */
require_once __DIR__ . '/../../lib/url.php';
$loginAction = app_url('/login');
?>
<div class="row justify-content-center">
  <div class="col-md-4">
    <div class="card shadow-sm">
      <div class="card-header bg-white">
        <strong>Login</strong>
      </div>
      <div class="card-body">
        <?php if ($error): ?>
          <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post" action="<?= $loginAction ?>" class="d-grid gap-3">
          <div>
            <label class="form-label">Username</label>
            <input type="text" name="username" value="<?= htmlspecialchars($old['username']) ?>" class="form-control" autocomplete="username">
          </div>
          <div>
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" autocomplete="current-password">
          </div>
          <button type="submit" class="btn btn-primary w-100">Masuk</button>
        </form>
      </div>
    </div>
  </div>
</div>
```

## 8. Layout Header
- **File**: `app/views/layout.php`
- **Penjelasan**: tautan Login/Logout harus menghormati base path. Pembacaan `$basePath` sudah ada di layout, kita tinggal memanfaatkannya dan menampilkan nama user aktif.

```php
<?php $user = current_user(); ?>
<nav class="d-flex flex-wrap gap-2 align-items-center">
  <a href="<?= $basePath === '' ? '/destinasi' : $basePath ?>"
     class="btn btn-outline-primary btn-sm">Destinasi</a>
  <a href="<?= $basePath === '' ? '/destinasi/create' : $basePath . '/create' ?>"
     class="btn btn-primary btn-sm">Tambah Destinasi</a>
  <div class="ms-auto d-flex align-items-center gap-2">
    <?php if ($user): ?>
      <span class="text-muted small">Logged in as <?= htmlspecialchars($user) ?></span>
      <form method="post"
            action="<?= $basePath === '' ? '/logout' : $basePath . '/logout' ?>"
            class="m-0">
        <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
      </form>
    <?php else: ?>
      <a href="<?= $basePath === '' ? '/login' : $basePath . '/login' ?>"
         class="btn btn-success btn-sm">Login</a>
    <?php endif; ?>
  </div>
</nav>
```

## 9. Uji Coba Manual
- **Langkah**:
  1. Jalankan `php -S localhost:8000 -t public` (atau siapkan virtual host yang menunjuk ke `public/`).
  2. Buka `/login` melalui base path yang sesuai (`http://localhost:8000/login` atau `http://localhost/destinasi/login` bila lewat subfolder).
  3. Coba kredensial salah untuk melihat pesan error.
  4. Masuk memakai `admin / admin123`, pastikan diarahkan ke daftar destinasi.
  5. Tekan tombol Logout dan cek bahwa session terhapus lalu kembali ke halaman login.

> Catatan: login ini untuk latihan. Untuk sistem nyata, ganti penyimpanan password dengan hash (`password_hash`/`password_verify`) dan lengkapi dengan perlindungan CSRF.
