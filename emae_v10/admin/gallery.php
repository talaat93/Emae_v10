
<?php
$adminSection = 'gallery';
require __DIR__ . '/partials/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    if (($_POST['action'] ?? '') === 'upload') {
        $uploaded = upload_image_field('gallery_file', 'gallery');
        if ($uploaded) {
            flash('success', 'Image ajoutée à la galerie.');
        } else {
            flash('error', 'Ajout impossible. Vérifie le format (jpg, png, webp, svg).');
        }
    }
    if (($_POST['action'] ?? '') === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $row = db_fetch('SELECT * FROM media WHERE id = ?', [$id]);
        if ($row) {
            $full = __DIR__ . '/../' . $row['file_path'];
            if (is_file($full)) { @unlink($full); }
            db_execute('DELETE FROM media WHERE id = ?', [$id]);
            flash('success', 'Image supprimée.');
        }
    }
    redirect_to('admin/gallery.php');
}

$images = db_fetch_all("SELECT * FROM media WHERE category = 'gallery' ORDER BY created_at DESC");
?>
<div class="admin-page-toolbar">
  <div><div class="admin-breadcrumb">Médias</div><h1 class="admin-page-title">Galerie & uploads</h1><p class="admin-page-subtitle">Ajoute des images réutilisables pour les pages et démonstrations.</p></div>
</div>
<form method="post" enctype="multipart/form-data" class="admin-stack">
  <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
  <input type="hidden" name="action" value="upload">
  <section class="admin-panel">
    <div class="admin-panel__head"><h2>Ajouter une image</h2><p>Les images seront stockées dans storage/uploads/gallery.</p></div>
    <div class="admin-panel__body">
      <label class="admin-field"><span>Fichier image</span><input type="file" name="gallery_file" accept=".jpg,.jpeg,.png,.webp,.svg" required></label>
      <div class="admin-savebar"><button class="admin-btn admin-btn--primary" type="submit">Envoyer</button></div>
    </div>
  </section>
</form>
<section class="admin-panel">
  <div class="admin-panel__head"><h2>Images disponibles</h2></div>
  <div class="admin-panel__body">
    <div class="admin-card-grid">
      <?php foreach ($images as $img): ?>
        <div class="admin-mini-card">
          <img class="preview-thumb" src="<?= e(asset_url($img['file_path'])) ?>" alt="<?= e($img['alt_text'] ?: 'Image galerie') ?>">
          <p style="margin-top:.8rem;word-break:break-all;"><?= e($img['file_path']) ?></p>
          <form method="post" onsubmit="return confirm('Supprimer cette image ?');">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= e((string)$img['id']) ?>">
            <button class="admin-btn admin-btn--secondary" type="submit">Supprimer</button>
          </form>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
