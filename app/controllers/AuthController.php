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
      $this->form([
        'error' => 'Username dan password wajib diisi',
        'old'   => ['username' => $username],
      ]);
      return;
    }

    $stmt = db()->prepare('SELECT username, password FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (!$user || $user['password'] !== $password) {
      $this->form([
        'error' => 'Kredensial tidak valid',
        'old'   => ['username' => $username],
      ]);
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
