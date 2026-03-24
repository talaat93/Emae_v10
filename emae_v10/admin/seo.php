<?php
$adminSection = 'seo';
require __DIR__ . '/partials/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    foreach ([
        'home_meta_title','home_meta_description','schema_rating_value','schema_review_count','google_analytics_id'
    ] as $field) {
        set_setting($field, trim((string) ($_POST[$field] ?? '')));
    }
    flash('success', 'SEO enregistré.');
    redirect_to('admin/seo.php');
}
?>
<div class="admin-page-toolbar">
  <div><div class="admin-breadcrumb">SEO</div><h1 class="admin-page-title">SEO & Analytics</h1><p class="admin-page-subtitle">Méta title, description, note moyenne et Google Analytics.</p></div>
</div>
<form method="post" class="admin-stack">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<section class="admin-panel">
  <div class="admin-panel__head"><h2>Accueil</h2></div>
  <div class="admin-panel__body">
    <label class="admin-field"><span>Meta title</span><input type="text" name="home_meta_title" value="<?= e(setting('home_meta_title', '')) ?>"></label>
    <label class="admin-field"><span>Meta description</span><textarea name="home_meta_description" rows="4"><?= e(setting('home_meta_description', '')) ?></textarea></label>
  </div>
</section>
<section class="admin-panel">
  <div class="admin-panel__head"><h2>Schema.org</h2></div>
  <div class="admin-panel__body">
    <div class="admin-form-grid admin-form-grid--2">
      <label class="admin-field"><span>Note moyenne</span><input type="text" name="schema_rating_value" value="<?= e(setting('schema_rating_value', '4.9')) ?>"></label>
      <label class="admin-field"><span>Nombre d’avis</span><input type="text" name="schema_review_count" value="<?= e(setting('schema_review_count', '18')) ?>"></label>
    </div>
  </div>
</section>
<section class="admin-panel">
  <div class="admin-panel__head"><h2>Analytics</h2></div>
  <div class="admin-panel__body">
    <label class="admin-field"><span>Google Analytics ID</span><input type="text" name="google_analytics_id" value="<?= e(setting('google_analytics_id', '')) ?>"></label>
    <div class="admin-helper-links">
      <a href="<?= e(url_for('sitemap.xml')) ?>" target="_blank">Voir le sitemap XML</a>
    </div>
  </div>
</section>
<div class="admin-savebar"><button class="admin-btn admin-btn--primary" type="submit">Enregistrer</button></div>
</form>
<?php require __DIR__ . '/partials/footer.php'; ?>
