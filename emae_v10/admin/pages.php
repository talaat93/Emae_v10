<?php
$adminSection = 'pages';
require __DIR__ . '/partials/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
    verify_csrf();
    $title = trim((string) ($_POST['title'] ?? ''));
    $slug = trim((string) ($_POST['slug'] ?? ''));
    if ($title === '' || $slug === '') {
        flash('error', 'Titre et slug obligatoires.');
    } else {
        db_execute('INSERT INTO pages (title, slug, page_type, excerpt, content_html, status) VALUES (?, ?, ?, ?, ?, ?)', [
            $title,
            $slug,
            trim((string) ($_POST['page_type'] ?? 'page')),
            '',
            '<p>Nouveau contenu à éditer.</p>',
            'published'
        ]);
        flash('success', 'Page créée.');
    }
    redirect_to('admin/pages.php');
}

$pages = all_pages();
?>
<div class="admin-page-toolbar">
  <div><div class="admin-breadcrumb">Contenus</div><h1 class="admin-page-title">Pages & landing pages</h1><p class="admin-page-subtitle">Crée et modifie les pages du site.</p></div>
</div>

<section class="admin-panel" style="margin-bottom:1.25rem;">
  <div class="admin-panel__head"><h2>Créer une page</h2><p>Ajoute rapidement une nouvelle page.</p></div>
  <div class="admin-panel__body">
    <form method="post" class="admin-form-grid admin-form-grid--3">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="action" value="create">
      <label class="admin-field"><span>Titre</span><input type="text" name="title"></label>
      <label class="admin-field"><span>Slug</span><input type="text" name="slug" placeholder="nouvelle-page"></label>
      <label class="admin-field"><span>Type</span>
        <select name="page_type">
          <option value="page">Page</option>
          <option value="landing">Landing page</option>
          <option value="service">Service</option>
        </select>
      </label>
      <div><button class="admin-btn admin-btn--primary" type="submit">Créer</button></div>
    </form>
  </div>
</section>

<section class="admin-panel">
  <div class="admin-panel__head"><h2>Liste des pages</h2><p>Clique sur modifier pour éditer le contenu.</p></div>
  <div class="admin-panel__body">
    <div class="admin-table-wrap">
      <table class="admin-table">
        <tr><th>Titre</th><th>Slug</th><th>Type</th><th>Statut</th><th>Action</th></tr>
        <?php foreach ($pages as $page): ?>
          <tr>
            <td><?= e($page['title']) ?></td>
            <td><?= e($page['slug']) ?></td>
            <td><?= e($page['page_type']) ?></td>
            <td><?= e($page['status']) ?></td>
            <td>
              <a class="admin-btn admin-btn--secondary" href="<?= e(url_for('admin/page_edit.php?id=' . $page['id'])) ?>">Modifier</a>
              <a class="admin-btn admin-btn--secondary" href="<?= e(route_url($page['slug'])) ?>" target="_blank">Voir</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
