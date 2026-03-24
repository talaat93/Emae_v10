<?php
$adminSection = 'header_menu';
require __DIR__ . '/partials/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    foreach ([
        'nav_home','nav_services','nav_realisations','nav_faq','nav_contact','header_cta_label','header_cta_url'
    ] as $field) {
        set_setting($field, trim((string) ($_POST[$field] ?? '')));
    }
    foreach (['topbar_visible','topbar_sticky'] as $field) {
        set_setting($field, isset($_POST[$field]) ? '1' : '0');
    }
    flash('success', 'Header & menu enregistrés.');
    redirect_to('admin/header_menu.php');
}
?>
<div class="admin-page-toolbar">
  <div><div class="admin-breadcrumb">Header</div><h1 class="admin-page-title">Header & menu</h1><p class="admin-page-subtitle">Topbar, navigation et bouton CTA.</p></div>
</div>
<form method="post" class="admin-stack">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<section class="admin-panel">
  <div class="admin-panel__head"><h2>Topbar</h2><p>Téléphone, email, zones et horaires s’affichent automatiquement depuis Identité.</p></div>
  <div class="admin-panel__body">
    <div class="admin-form-grid admin-form-grid--2">
      <label class="admin-field admin-field--check"><span>Afficher la topbar</span><div class="check-row"><input type="checkbox" name="topbar_visible" value="1" <?= setting_bool('topbar_visible', true)?'checked':'' ?>><span>Visible</span></div></label>
      <label class="admin-field admin-field--check"><span>Topbar sticky</span><div class="check-row"><input type="checkbox" name="topbar_sticky" value="1" <?= setting_bool('topbar_sticky', false)?'checked':'' ?>><span>Sticky</span></div></label>
    </div>
  </div>
</section>
<section class="admin-panel">
  <div class="admin-panel__head"><h2>Menu</h2><p>Libellés visibles dans le header.</p></div>
  <div class="admin-panel__body">
    <div class="admin-form-grid admin-form-grid--2">
      <label class="admin-field"><span>Accueil</span><input type="text" name="nav_home" value="<?= e(setting('nav_home','Accueil')) ?>"></label>
      <label class="admin-field"><span>Services</span><input type="text" name="nav_services" value="<?= e(setting('nav_services','Services')) ?>"></label>
    </div>
    <div class="admin-form-grid admin-form-grid--2">
      <label class="admin-field"><span>Réalisations</span><input type="text" name="nav_realisations" value="<?= e(setting('nav_realisations','Réalisations')) ?>"></label>
      <label class="admin-field"><span>FAQ</span><input type="text" name="nav_faq" value="<?= e(setting('nav_faq','FAQ')) ?>"></label>
    </div>
    <div class="admin-form-grid admin-form-grid--2">
      <label class="admin-field"><span>Contact</span><input type="text" name="nav_contact" value="<?= e(setting('nav_contact','Contact')) ?>"></label>
      <label class="admin-field"><span>Texte bouton CTA</span><input type="text" name="header_cta_label" value="<?= e(setting('header_cta_label','Devis gratuit')) ?>"></label>
    </div>
    <label class="admin-field"><span>Lien bouton CTA</span><input type="text" name="header_cta_url" value="<?= e(setting('header_cta_url','quote')) ?>"></label>
  </div>
</section>
<div class="admin-savebar"><button class="admin-btn admin-btn--primary" type="submit">Enregistrer</button></div>
</form>
<?php require __DIR__ . '/partials/footer.php'; ?>
