<?php
$adminSection = 'appearance';
require __DIR__ . '/partials/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    foreach ([
        'color_primary','color_primary_hover','color_secondary','color_secondary_2','color_site_bg','color_text','color_text_muted','color_surface','color_border',
        'font_heading','font_body'
    ] as $field) {
        set_setting($field, trim((string) ($_POST[$field] ?? '')));
    }
    flash('success', 'Apparence enregistrée.');
    redirect_to('admin/appearance.php');
}
?>
<div class="admin-page-toolbar">
  <div><div class="admin-breadcrumb">Apparence</div><h1 class="admin-page-title">Couleurs & typographie</h1><p class="admin-page-subtitle">Palette simple à comprendre pour modifier tout le site.</p></div>
</div>
<form method="post" class="admin-stack">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<section class="admin-panel">
  <div class="admin-panel__head"><h2>Couleurs</h2><p>Ces couleurs pilotent l’interface publique.</p></div>
  <div class="admin-panel__body">
    <div class="admin-form-grid admin-form-grid--3">
      <?php
      $fields = [
        'color_primary' => 'Couleur principale',
        'color_primary_hover' => 'Couleur principale hover',
        'color_secondary' => 'Bleu foncé',
        'color_secondary_2' => 'Bleu header',
        'color_site_bg' => 'Fond général',
        'color_text' => 'Texte principal',
        'color_text_muted' => 'Texte secondaire',
        'color_surface' => 'Fond cartes',
        'color_border' => 'Couleur bordure',
      ];
      foreach ($fields as $key => $label): ?>
        <label class="admin-field"><span><?= e($label) ?></span><input type="color" name="<?= e($key) ?>" value="<?= e(setting($key, '#000000')) ?>"></label>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<section class="admin-panel">
  <div class="admin-panel__head"><h2>Polices</h2><p>Entrez le nom d’une police Google Fonts.</p></div>
  <div class="admin-panel__body">
    <div class="admin-form-grid admin-form-grid--2">
      <label class="admin-field"><span>Police titres</span><input type="text" name="font_heading" value="<?= e(setting('font_heading', 'Montserrat')) ?>"></label>
      <label class="admin-field"><span>Police texte</span><input type="text" name="font_body" value="<?= e(setting('font_body', 'Inter')) ?>"></label>
    </div>
  </div>
</section>
<div class="admin-savebar"><button class="admin-btn admin-btn--primary" type="submit">Enregistrer</button></div>
</form>
<?php require __DIR__ . '/partials/footer.php'; ?>
