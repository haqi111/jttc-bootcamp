<?php
function app_base_path(): string {
  static $base = null;
  if ($base !== null) {
    return $base;
  }

  $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
  $base = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
  if ($base === '/' || $base === '.') {
    $base = '';
  }

  return $base;
}

function app_url(string $path = '/'): string {
  $path = '/' . ltrim($path, '/');
  $base = app_base_path();
  if ($base === '' || $base === '/') {
    return $path;
  }
  return $base . $path;
}

function app_route(string $requestPath): string {
  $requestPath = $requestPath === '' ? '/' : $requestPath;
  $base = app_base_path();
  if ($base !== '' && strpos($requestPath, $base) === 0) {
    $requestPath = substr($requestPath, strlen($base));
    if ($requestPath === '') {
      $requestPath = '/';
    }
  }

  return $requestPath;
}
