<?php
session_start();

require_once __DIR__ . '/../app/lib/auth.php';
require_once __DIR__ . '/../app/lib/url.php';
require_once __DIR__ . '/../app/controllers/DestinasiController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$path = rtrim($path, '/');
if ($path === '') { $path = '/'; }
$path = app_route($path);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$auth = new AuthController();
$dest = new DestinasiController();

$isLoginRoute = $path === '/login';

if (!current_user() && !$isLoginRoute) {
  header('Location: ' . app_url('/login'));
  exit;
}

if ($isLoginRoute && $method === 'GET') {
  $auth->form();
  exit;
}

if ($isLoginRoute && $method === 'POST') {
  $auth->login($_POST);
  exit;
}

if ($path === '/logout' && $method === 'POST') {
  $auth->logout();
  exit;
}

if ($path === '/destinasi/create' && $method === 'GET') {
  $dest->create();
  exit;
}

if ($path === '/destinasi' && $method === 'POST') {
  $dest->store($_POST);
  exit;
}

if ($path === '/' || $path === '/destinasi') {
  $dest->index();
  exit;
}

if (preg_match('#^/destinasi/(\\d+)$#', $path, $m)) {
  $dest->show($m[1]);
  exit;
}

http_response_code(404);
echo 'Not Found';
