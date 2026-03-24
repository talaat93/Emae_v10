<?php
$adminSection = 'pages';
require __DIR__ . '/partials/header.php';

$id = (int) ($_GET['id'] ?? 0);
$page = db_fetch('SELECT * FROM pages WHERE id = ?', [$id]);
if (!$page) {
    flash('error', 'Page introuvable.');
    redirect_to('admin/pages.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $heroImage = upload_image_field('hero_image_file', 'backgrounds') ?: ($page['hero_image'] ?? '');

    db_execute('UPDATE pages SET title=?, slug=?, page_type=?, status=?, excerpt=?, hero_title=?, hero_text=?, hero_image=?, content_html=?, meta_title=?, meta_description=?, sort_order=? WHERE id=?', [
        trim((string) ($_POST['title'] ?? '')),
        trim((string) ($_POST['slug'] ?? '')),
        trim((string) ($_POST['page_type'] ?? 'page')),
        trim((string) ($_POST['status'] ?? 'published')),
        trim((string) ($_POST['excerpt'] ?? '')),
        trim((string) ($_POST['hero_title'] ?? '')),
        trim((string) ($_POST['hero_text'] ?? '')),
        $heroImage,
        (string) ($_POST['content_html'] ?? ''),
        trim((string) ($_POST['meta_title'] ?? '')),
        trim((string) ($_POST['meta_description'] ?? '')),
        (int) ($_POST['sort_order'] ?? 0),
        $id,
    ]);

    flash('success', 'Page enregistrée.');
    redirect_to('admin/page_edit.php?id=' . $id);
}
?>
<div class="admin-page-toolbar">
  <div><div class="admin-breadcrumb">Pages</div><h1 class="admin-page-title">Modifier : <?= e($page['title']) ?></h1><p class="admin-page-subtitle">Tu peux modifier le contenu, le SEO et l’image hero.</p></div>
  <div class="admin-toolbar-actions"><a class="admin-btn admin-btn--secondary" href="<?= e(route_url($page['slug'])) ?>" target="_blank">Voir la page</a></div>
</div>
<form method="post" enctype="multipart/form-data" class="admin-stack">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<section class="admin-panel">
  <div class="admin-panel__head"><h2>Informations</h2></div>
  <div class="admin-panel__body">
    <div class="admin-form-grid admin-form-grid--2">
      <label class="admin-field"><span>Titre</span><input type="text" name="title" value="<?= e($page['title']) ?>"></label>
      <label class="admin-field"><span>Slug</span><input type="text" name="slug" value="<?= e($page['slug']) ?>"></label>
    </div>
    <div class="admin-form-grid admin-form-grid--3">
      <label class="admin-field"><span>Type</span><select name="page_type">
        <?php foreach (['page','landing','service'] as $type): ?><option value="<?= e($type) ?>" <?= $page['page_type']===$type?'selected':'' ?>><?= e($type) ?></option><?php endforeach; ?>
      </select></label>
      <label class="admin-field"><span>Statut</span><select name="status">
        <?php foreach (['published','draft'] as $status): ?><option value="<?= e($status) ?>" <?= $page['status']===$status?'selected':'' ?>><?= e($status) ?></option><?php endforeach; ?>
      </select></label>
      <label class="admin-field"><span>Ordre</span><input type="text" name="sort_order" value="<?= e((string) $page['sort_order']) ?>"></label>
    </div>
    <label class="admin-field"><span>Extrait</span><textarea name="excerpt" rows="3"><?= e((string) $page['excerpt']) ?></textarea></label>
  </div>
</section>

<section class="admin-panel">
  <div class="admin-panel__head"><h2>Hero de la page</h2></div>
  <div class="admin-panel__body">
    <div class="admin-form-grid admin-form-grid--2">
      <label class="admin-field"><span>Titre hero</span><input type="text" name="hero_title" value="<?= e((string) $page['hero_title']) ?>"></label>
      <label class="admin-field"><span>Texte hero</span><input type="text" name="hero_text" value="<?= e((string) $page['hero_text']) ?>"></label>
    </div>
    <?php if (!empty($page['hero_image'])): ?><img class="preview-thumb" src="<?= e(asset_url($page['hero_image'])) ?>" alt="Hero image"><?php endif; ?>
    <label class="admin-field"><span>Image hero</span><input type="file" name="hero_image_file" accept=".png,.jpg,.jpeg,.webp,.svg"></label>
  </div>
</section>

<section class="admin-panel">
  <div class="admin-panel__head"><h2>Contenu HTML simple</h2><p>Tu peux coller du HTML simple : titres, paragraphes, listes, boutons.</p></div>
  <div class="admin-panel__body">
    <label class="admin-field"><span>Contenu</span><textarea name="content_html" rows="16"><?= e((string) $page['content_html']) ?></textarea></label>
  </div>
</section>

<section class="admin-panel">
  <div class="admin-panel__head"><h2>SEO</h2></div>
  <div class="admin-panel__body">
    <label class="admin-field"><span>Meta title</span><input type="text" name="meta_title" value="<?= e((string) $page['meta_title']) ?>"></label>
    <label class="admin-field"><span>Meta description</span><textarea name="meta_description" rows="4"><?= e((string) $page['meta_description']) ?></textarea></label>
  </div>
</section>

<div class="admin-savebar"><button class="admin-btn admin-btn--primary" type="submit">Enregistrer</button></div>
</form>
<?php require __DIR__ . '/partials/footer.php'; ?>
