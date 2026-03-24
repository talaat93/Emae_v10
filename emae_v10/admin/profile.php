<?php
$adminSection = 'profile';
require __DIR__ . '/partials/header.php';
$admin = current_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    db_execute('UPDATE admins SET name = ?, email = ? WHERE id = ?', [$name, $email, (int) $admin['id']]);

    $newPassword = trim((string) ($_POST['new_password'] ?? ''));
    if ($newPassword !== '') {
        db_execute('UPDATE admins SET password_hash = ? WHERE id = ?', [password_hash($newPassword, PASSWORD_DEFAULT), (int) $admin['id']]);
    }

    flash('success', 'Profil admin mis à jour.');
    redirect_to('admin/profile.php');
}
?>
<div class="admin-page-toolbar">
  <div><div class="admin-breadcrumb">Compte</div><h1 class="admin-page-title">Profil admin</h1><p class="admin-page-subtitle">Change ton nom, ton email et ton mot de passe.</p></div>
</div>
<form method="post" class="admin-stack">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<section class="admin-panel">
  <div class="admin-panel__head"><h2>Profil</h2></div>
  <div class="admin-panel__body">
    <div class="admin-form-grid admin-form-grid--2">
      <label class="admin-field"><span>Nom</span><input type="text" name="name" value="<?= e($admin['name']) ?>"></label>
      <label class="admin-field"><span>Email</span><input type="email" name="email" value="<?= e($admin['email']) ?>"></label>
    </div>
    <label class="admin-field"><span>Nouveau mot de passe (laisser vide pour ne pas changer)</span><input type="password" name="new_password" value=""></label>
  </div>
</section>
<div class="admin-savebar"><button class="admin-btn admin-btn--primary" type="submit">Enregistrer</button></div>
</form>
<?php require __DIR__ . '/partials/footer.php'; ?>
