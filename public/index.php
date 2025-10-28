<?php
require_once __DIR__ . '/../app/controllers/DestinasiController.php';
require_once __DIR__ . '/../app/controllers/HomeController.php';

$path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$path   = rtrim($path, '/');
if ($path === '') {
  $path = '/';
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$ctrl = new DestinasiController();
$homeCtrl = new HomeController();

if ($path === '/home' || $path === '/' && $method === 'GET') {
  $homeCtrl->index();
  exit;
}

if ($path === '/destinasi/create' && $method === 'GET') {
  $ctrl->create();
  exit;
}

if ($path === '/destinasi' && $method === 'POST') {
  $ctrl->store($_POST);
  exit;
}

if ($path === '/destinasi') {
  $ctrl->index();
  exit;
}

if (preg_match('#^/destinasi/(\d+)$#', $path, $m)) {
  $ctrl->show($m[1]);
  exit;
}

http_response_code(404);
echo "Not Found";
