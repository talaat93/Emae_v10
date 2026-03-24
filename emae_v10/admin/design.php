<?php
$adminSection = 'design';
require __DIR__ . '/partials/header.php';
?>
<div class="admin-page-toolbar">
  <div>
    <div class="admin-breadcrumb">Hub design</div>
    <h1 class="admin-page-title">Modifier le site sans se perdre</h1>
    <p class="admin-page-subtitle">Chaque carte t’emmène directement au bon endroit.</p>
  </div>
</div>

<div class="admin-helper-links" style="margin-bottom:1rem;"><a href="<?= e(url_for('admin/gallery.php')) ?>">Ouvrir la galerie médias</a></div>

<div class="admin-card-grid">
  <div class="admin-mini-card">
    <h3>1. Identité</h3>
    <p>Logo, téléphone, email, zones, horaires.</p>
    <a class="admin-btn admin-btn--primary" href="<?= e(url_for('admin/site_identity.php')) ?>">Ouvrir</a>
  </div>
  <div class="admin-mini-card">
    <h3>2. Couleurs</h3>
    <p>Palette orange, bleu, fond, texte, polices.</p>
    <a class="admin-btn admin-btn--primary" href="<?= e(url_for('admin/appearance.php')) ?>">Ouvrir</a>
  </div>
  <div class="admin-mini-card">
    <h3>3. Header</h3>
    <p>Topbar, menu, CTA, libellés de navigation.</p>
    <a class="admin-btn admin-btn--primary" href="<?= e(url_for('admin/header_menu.php')) ?>">Ouvrir</a>
  </div>
  <div class="admin-mini-card">
    <h3>4. Bloc hero</h3>
    <p>Grand texte de gauche + bloc devis à droite.</p>
    <a class="admin-btn admin-btn--primary" href="<?= e(url_for('admin/home_hero.php')) ?>">Ouvrir</a>
  </div>
  <div class="admin-mini-card">
    <h3>5. Cartes services</h3>
    <p>Les 4 cartes blanches sous le hero.</p>
    <a class="admin-btn admin-btn--primary" href="<?= e(url_for('admin/home_services.php')) ?>">Ouvrir</a>
  </div>
  <div class="admin-mini-card">
    <h3>6. Pages</h3>
    <p>Landing pages locales, services, contact, FAQ, etc.</p>
    <a class="admin-btn admin-btn--primary" href="<?= e(url_for('admin/pages.php')) ?>">Ouvrir</a>
  </div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
