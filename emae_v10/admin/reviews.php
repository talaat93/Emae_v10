<?php
$adminSection = 'reviews';
require __DIR__ . '/partials/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    if (($_POST['action'] ?? '') === 'create') {
        db_execute('INSERT INTO reviews (author_name, rating, content, is_visible, sort_order) VALUES (?, ?, ?, ?, ?)', [
            trim((string) ($_POST['author_name'] ?? '')),
            (int) ($_POST['rating'] ?? 5),
            trim((string) ($_POST['content'] ?? '')),
            isset($_POST['is_visible']) ? 1 : 0,
            (int) ($_POST['sort_order'] ?? 0),
        ]);
        flash('success', 'Avis ajouté.');
    }
    if (($_POST['action'] ?? '') === 'delete') {
        db_execute('DELETE FROM reviews WHERE id = ?', [(int) ($_POST['id'] ?? 0)]);
        flash('success', 'Avis supprimé.');
    }
    redirect_to('admin/reviews.php');
}

$reviews = db_fetch_all('SELECT * FROM reviews ORDER BY sort_order ASC, id DESC');
?>
<div class="admin-page-toolbar">
  <div><div class="admin-breadcrumb">Avis</div><h1 class="admin-page-title">Avis clients</h1><p class="admin-page-subtitle">Ajoute ou supprime des avis visibles sur le site.</p></div>
</div>
<section class="admin-panel" style="margin-bottom:1.25rem;">
  <div class="admin-panel__head"><h2>Ajouter un avis</h2></div>
  <div class="admin-panel__body">
    <form method="post" class="admin-stack">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
      <input type="hidden" name="action" value="create">
      <div class="admin-form-grid admin-form-grid--3">
        <label class="admin-field"><span>Auteur</span><input type="text" name="author_name"></label>
        <label class="admin-field"><span>Note</span><input type="text" name="rating" value="5"></label>
        <label class="admin-field"><span>Ordre</span><input type="text" name="sort_order" value="0"></label>
      </div>
      <label class="admin-field"><span>Contenu</span><textarea name="content" rows="4"></textarea></label>
      <label class="admin-field admin-field--check"><span>Visible</span><div class="check-row"><input type="checkbox" name="is_visible" value="1" checked><span>Afficher cet avis</span></div></label>
      <div class="admin-savebar"><button class="admin-btn admin-btn--primary" type="submit">Ajouter</button></div>
    </form>
  </div>
</section>
<section class="admin-panel">
  <div class="admin-panel__head"><h2>Liste des avis</h2></div>
  <div class="admin-panel__body">
    <div class="admin-table-wrap">
      <table class="admin-table">
        <tr><th>Auteur</th><th>Note</th><th>Visible</th><th>Contenu</th><th>Action</th></tr>
        <?php foreach ($reviews as $review): ?>
          <tr>
            <td><?= e($review['author_name']) ?></td>
            <td><?= e((string) $review['rating']) ?></td>
            <td><?= $review['is_visible'] ? 'Oui' : 'Non' ?></td>
            <td><?= e($review['content']) ?></td>
            <td>
              <form method="post" onsubmit="return confirm('Supprimer cet avis ?');">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= e((string) $review['id']) ?>">
                <button class="admin-btn admin-btn--secondary" type="submit">Supprimer</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
