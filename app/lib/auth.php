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
