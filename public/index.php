<?php
require_once __DIR__ . '/../app/controllers/DestinasiController.php';

$path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$path   = rtrim($path, '/');
if ($path === '') { $path = '/'; }

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$ctrl = new DestinasiController();

if ($path === '/destinasi/create' && $method === 'GET') {
  $ctrl->create();
  exit;
}

if ($path === '/destinasi' && $method === 'POST') {
  $ctrl->store($_POST);
  exit;
}

if ($path === '/destinasi/search' && $method === 'GET') {
  $ctrl->searchForm();
  exit;
}

if ($path === '/destinasi/search' && $method === 'POST') {
  $ctrl->search($_POST);
  exit;
}

// Routes for culinary management
if (preg_match('#^/destinasi/(\d+)/kuliner/create$#', $path, $m) && $method === 'GET') {
  $ctrl->createKuliner($m[1]);
  exit;
}

if (preg_match('#^/destinasi/(\d+)/kuliner$#', $path, $m) && $method === 'POST') {
  $ctrl->storeKuliner($m[1], $_POST);
  exit;
}

if (preg_match('#^/destinasi/(\d+)/kuliner/(\d+)/delete$#', $path, $m) && $method === 'POST') {
  $ctrl->deleteKuliner($m[1], $m[2]);
  exit;
}

if ($path === '/') {
  $ctrl->landing();
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