<?php
require_once __DIR__ . '/../includes/bootstrap.php';

if (admin_logged_in()) {
    redirect_to('admin/index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!rate_limit_passed('admin_login', 4)) {
        flash('error', 'Patiente quelques secondes avant de réessayer.');
        redirect_to('admin/login.php');
    }

    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if (attempt_login($email, $password)) {
        flash('success', 'Connexion réussie.');
        redirect_to('admin/index.php');
    }

    flash('error', 'Email ou mot de passe incorrect.');
    redirect_to('admin/login.php');
}
?><!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion admin | EMAE V8</title>
  <link rel="stylesheet" href="<?= e(asset_url('assets/css/admin.css')) ?>">
</head>
<body class="admin-body">
<div class="login-wrap">
  <div class="login-card">
    <h1>Connexion admin</h1>
    <p>Connecte-toi pour gérer le site.</p>
    <?php if ($msg = flash('success')): ?><div class="flash flash--success"><?= e($msg) ?></div><?php endif; ?>
    <?php if ($msg = flash('error')): ?><div class="flash flash--error"><?= e($msg) ?></div><?php endif; ?>
    <form method="post" class="admin-stack">
      <label class="admin-field">
        <span>Email</span>
        <input type="email" name="email" value="admin@emae.fr" required>
      </label>
      <label class="admin-field">
        <span>Mot de passe</span>
        <input type="password" name="password" value="adminem@e" required>
      </label>
      <button class="admin-btn admin-btn--primary" type="submit">Se connecter</button>
    </form>
  </div>
</div>
</body>
</html>
