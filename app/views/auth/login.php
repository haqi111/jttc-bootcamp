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
          <div class="alert alert-danger py-2">
            <?= htmlspecialchars($error) ?>
          </div>
        <?php endif; ?>
        <form method="post" action="<?= $loginAction ?>" class="d-grid gap-3">
          <div>
            <label class="form-label">Username</label>
            <input type="text"
                   name="username"
                   value="<?= htmlspecialchars($old['username']) ?>"
                   class="form-control"
                   autocomplete="username">
          </div>
          <div>
            <label class="form-label">Password</label>
            <input type="password"
                   name="password"
                   class="form-control"
                   autocomplete="current-password">
          </div>
          <button type="submit" class="btn btn-primary w-100">
            Masuk
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
