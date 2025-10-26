<?php
function render($view, array $data = []) {
  $viewFile = __DIR__ . '/../views/' . $view . '.php';
  if (!file_exists($viewFile)) {
    http_response_code(500);
    echo "View not found: $view";
    return;
  }
  extract($data, EXTR_SKIP);
  ob_start();
  require $viewFile;
  $content = ob_get_clean();
  require __DIR__ . '/../views/layout.php';
}
