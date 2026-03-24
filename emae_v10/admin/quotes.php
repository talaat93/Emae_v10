<?php
$adminSection = 'quotes';
require __DIR__ . '/partials/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    db_execute('UPDATE quotes SET status = ? WHERE id = ?', [
        trim((string) ($_POST['status'] ?? 'nouveau')),
        (int) ($_POST['id'] ?? 0)
    ]);
    flash('success', 'Statut du devis mis à jour.');
    redirect_to('admin/quotes.php');
}

$quotes = all_quotes();
?>
<div class="admin-page-toolbar">
  <div><div class="admin-breadcrumb">Devis</div><h1 class="admin-page-title">Demandes de devis</h1><p class="admin-page-subtitle">Toutes les demandes envoyées depuis le site.</p></div>
</div>
<section class="admin-panel">
  <div class="admin-panel__head"><h2>Liste</h2></div>
  <div class="admin-panel__body">
    <div class="admin-table-wrap">
      <table class="admin-table">
        <tr><th>Date</th><th>Nom</th><th>Téléphone</th><th>Email</th><th>Service</th><th>Ville</th><th>Message</th><th>Statut</th></tr>
        <?php foreach ($quotes as $quote): ?>
          <tr>
            <td><?= e($quote['created_at']) ?></td>
            <td><?= e($quote['full_name']) ?></td>
            <td><?= e($quote['phone']) ?></td>
            <td><?= e($quote['email']) ?></td>
            <td><?= e($quote['service_type']) ?></td>
            <td><?= e($quote['city']) ?></td>
            <td><?= e($quote['message']) ?></td>
            <td>
              <form method="post">
                <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                <input type="hidden" name="id" value="<?= e((string) $quote['id']) ?>">
                <select name="status" onchange="this.form.submit()">
                  <?php foreach (['nouveau','en cours','traité'] as $status): ?>
                    <option value="<?= e($status) ?>" <?= $quote['status']===$status?'selected':'' ?>><?= e($status) ?></option>
                  <?php endforeach; ?>
                </select>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
