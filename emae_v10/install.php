<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';

$defaultDbName = 'emae_v10';
$resetMode = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbHost = trim($_POST['db_host'] ?? 'localhost');
    $dbPort = trim($_POST['db_port'] ?? '3306');
    $dbName = trim($_POST['db_name'] ?? $defaultDbName);
    $dbUser = trim($_POST['db_user'] ?? 'root');
    $dbPass = (string) ($_POST['db_pass'] ?? '');
    $baseUrl = trim($_POST['base_url'] ?? '');

    $adminName = trim($_POST['admin_name'] ?? 'Admin EMAE');
    $adminEmail = trim($_POST['admin_email'] ?? 'admin@emae.fr');
    $adminPassword = (string) ($_POST['admin_password'] ?? 'adminem@e');

    try {
        $pdo = new PDO("mysql:host={$dbHost};port={$dbPort};charset=utf8mb4", $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `{$dbName}`");

        if ($resetMode) {
            $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
            foreach (['quotes', 'reviews', 'pages', 'settings', 'media', 'admins'] as $table) {
                $pdo->exec("DROP TABLE IF EXISTS `{$table}`");
            }
            $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
        }

        $schema = file_get_contents(__DIR__ . '/includes/schema.sql');
        if ($schema === false) {
            throw new RuntimeException('Impossible de lire le schéma SQL.');
        }
        $pdo->exec($schema);

        $passwordHash = password_hash($adminPassword, PASSWORD_DEFAULT);
        $stmtAdmin = $pdo->prepare('INSERT INTO admins (name, email, password_hash) VALUES (?, ?, ?)');
        $stmtAdmin->execute([$adminName, $adminEmail, $passwordHash]);

        $defaults = [
            'company_name' => 'EMAE',
            'company_phone' => '06 67 83 03 76',
            'company_phone_link' => 'tel:+33667830376',
            'company_email' => 'emaeentreprisemultitechniqueavance@gmail.com',
            'company_regions' => 'Île-de-France et Occitanie',
            'company_hours' => '24h/24 - 7j/7',
            'company_address' => 'Île-de-France et Occitanie',
            'company_siret' => '',
            'color_primary' => '#ee7d1a',
            'color_primary_hover' => '#c95f0b',
            'color_secondary' => '#0b1641',
            'color_secondary_2' => '#31447f',
            'color_site_bg' => '#f5f7fc',
            'color_text' => '#1b2440',
            'color_text_muted' => '#64708f',
            'color_surface' => '#ffffff',
            'color_border' => 'rgba(11,22,65,.12)',
            'font_heading' => 'Montserrat',
            'font_body' => 'Inter',
            'site_logo' => 'storage/uploads/logos/logo-emae-default.png',
            'site_logo_width' => '180',
            'site_logo_height' => 'auto',
            'site_logo_position' => 'left',
            'topbar_visible' => '1',
            'topbar_sticky' => '0',
            'header_cta_label' => 'Devis gratuit',
            'header_cta_url' => 'quote',
            'nav_home' => 'Accueil',
            'nav_services' => 'Services',
            'nav_realisations' => 'Réalisations',
            'nav_faq' => 'FAQ',
            'nav_contact' => 'Contact',
            'home_eyebrow' => 'EMAE - INTERVENTION RAPIDE 24H/24 7J/7',
            'home_title' => 'Dépannage et rénovation en électricité, climatisation, PAC, ventilation et chauffage',
            'home_lead' => 'Intervention moyenne en moins de 2h sur l’Île-de-France et l’Occitanie. Appel immédiat, devis rapide, urgence 24h/24 7j/7.',
            'home_bullets' => "Intervention moyenne sous 2h\nDevis gratuit et sans engagement\nZone couverte : Île-de-France et Occitanie",
            'home_button1_label' => 'Appeler maintenant',
            'home_button1_url' => 'tel:+33667830376',
            'home_button2_label' => 'Demander un devis',
            'home_button2_url' => 'quote',
            'home_quote_title' => 'Obtenir un rappel rapide',
            'home_quote_service_label' => 'Service',
            'home_quote_city_label' => 'Ville',
            'home_quote_city_placeholder' => 'Ex : Meaux, Paris, Toulouse',
            'home_quote_button_label' => 'Continuer',
            'home_quote_eyebrow' => 'DEMANDE DE DEVIS GRATUITE',
            'hero_bg_from' => '#06133f',
            'hero_bg_to' => '#18357f',
            'hero_glow_left' => '#4b2340',
            'hero_glow_right' => '#3f5fff',
            'home_title_color' => '#ffffff',
            'home_lead_color' => '#dbe6ff',
            'home_eyebrow_color' => '#7fa3ff',
            'home_chip_1' => 'Mobile first',
            'home_chip_2' => 'Prêt pour Google Ads local',
            'home_chip_3' => 'Conversion rapide',
            'home_chip_4' => 'Appel, devis, contact, espace client',
            'home_chip_5' => 'Image premium',
            'home_chip_6' => 'Corporate, claire et rassurante',
            'home_feature_1_title' => 'Mobile first',
            'home_feature_1_text' => 'Prêt pour Google Ads local',
            'home_feature_2_title' => 'Conversion rapide',
            'home_feature_2_text' => 'Appel, devis, contact, espace client',
            'home_feature_3_title' => 'Image premium',
            'home_feature_3_text' => 'Corporate, claire et rassurante',
            'home_quote_meta' => 'Artisans disponibles • devis gratuit • réponse rapide',
            'home_meta_title' => 'EMAE | Dépannage, climatisation, PAC et électricité',
            'home_meta_description' => 'Dépannage et rénovation en électricité, climatisation, PAC, ventilation et chauffage en Île-de-France et Occitanie.',
            'schema_rating_value' => '4.9',
            'schema_review_count' => '18',
            'form_success_message' => 'Votre demande a bien été envoyée.',
            'form_submit_label' => 'Envoyer ma demande',
            'form_email_to' => 'emaeentreprisemultitechniqueavance@gmail.com',
        ];

        $stmtSetting = $pdo->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)');
        foreach ($defaults as $k => $v) {
            $stmtSetting->execute([$k, $v]);
        }

        $cards = [
            ['title' => 'Électricité', 'image' => 'storage/uploads/services/service-electricite.jpg', 'link' => 'electricien-meaux'],
            ['title' => 'Plomberie', 'image' => 'storage/uploads/services/service-plomberie.jpg', 'link' => 'plombier-meaux'],
            ['title' => 'CVC', 'image' => 'storage/uploads/services/service-cvc.jpg', 'link' => 'climatisation-meaux'],
            ['title' => 'Énergies renouvelables', 'image' => 'storage/uploads/services/service-energies.jpg', 'link' => 'depannage-paris'],
        ];
        $stmtSetting->execute(['home_service_cards', json_encode($cards, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);

        $pages = [
            ['Services', 'services', 'page', 'Nos services multitechniques', 'Services EMAE', 'Découvrez nos services en dépannage, électricité, plomberie, CVC et énergie.', '<p>EMAE intervient pour vos besoins en électricité, climatisation, PAC, ventilation, plomberie et énergie.</p>', 1],
            ['Réalisations', 'realisations', 'page', 'Quelques exemples de réalisations', 'Réalisations EMAE', 'Découvrez nos interventions et chantiers.', '<p>Des interventions rapides, propres et structurées sur toute l’Île-de-France et l’Occitanie.</p>', 2],
            ['FAQ', 'faq', 'page', 'Questions fréquentes', 'FAQ EMAE', 'Retrouvez les réponses aux questions fréquentes.', '<p>Nous répondons rapidement à vos questions techniques et administratives.</p>', 3],
            ['Contact', 'contact', 'page', 'Contactez EMAE', 'Contact EMAE', 'Contactez-nous pour un devis ou une urgence.', '<p>Contactez EMAE pour toute demande de devis, dépannage ou intervention urgente.</p>', 4],
            ['Demande de devis', 'quote', 'page', 'Demandez un devis', 'Demande de devis EMAE', 'Obtenez un devis rapide.', '<p>Décrivez votre besoin et nous vous recontactons rapidement.</p>', 5],
            ['Dépannage Meaux', 'depannage-meaux', 'landing', 'Urgence dépannage à Meaux', 'Dépannage Meaux | EMAE', 'Intervention rapide à Meaux.', '<p>EMAE intervient à Meaux pour les urgences de dépannage multitechnique.</p>', 6],
            ['Électricien Meaux', 'electricien-meaux', 'landing', 'Électricien à Meaux', 'Électricien Meaux | EMAE', 'Dépannage et rénovation électrique à Meaux.', '<p>Dépannage, rénovation et mise en sécurité électrique à Meaux.</p>', 7],
            ['Plombier Meaux', 'plombier-meaux', 'landing', 'Plomberie à Meaux', 'Plombier Meaux | EMAE', 'Intervention plomberie à Meaux.', '<p>Recherche de fuite, dépannage et rénovation plomberie à Meaux.</p>', 8],
            ['Climatisation Meaux', 'climatisation-meaux', 'landing', 'Climatisation à Meaux', 'Climatisation Meaux | EMAE', 'Installation et dépannage climatisation.', '<p>Entretien, dépannage et mise en service de climatisation à Meaux.</p>', 9],
            ['Dépannage Paris', 'depannage-paris', 'landing', 'Urgence dépannage à Paris', 'Dépannage Paris | EMAE', 'Intervention rapide à Paris.', '<p>Intervention rapide à Paris pour vos urgences en électricité, plomberie, climatisation et chauffage.</p>', 10],
        ];
        $stmtPage = $pdo->prepare('INSERT INTO pages (title, slug, page_type, excerpt, meta_title, meta_description, content_html, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        foreach ($pages as $page) {
            $stmtPage->execute($page);
        }

        $reviewStmt = $pdo->prepare('INSERT INTO reviews (author_name, rating, content, is_visible, sort_order) VALUES (?, ?, ?, ?, ?)');
        $reviews = [
            ['Nadia B.', 5, 'Intervention rapide et très propre. Très bon contact du début à la fin.', 1, 1],
            ['Sofiane M.', 5, 'Dépannage électrique efficace, devis clair et travail sérieux.', 1, 2],
            ['Karim L.', 5, 'Très satisfait pour la climatisation et la réactivité.', 1, 3],
        ];
        foreach ($reviews as $review) {
            $reviewStmt->execute($review);
        }

        $configPhp = "<?php\nreturn " . var_export([
            'installed' => true,
            'db' => [
                'host' => $dbHost,
                'port' => $dbPort,
                'name' => $dbName,
                'user' => $dbUser,
                'pass' => $dbPass,
                'charset' => 'utf8mb4',
            ],
            'site' => [
                'base_url' => $baseUrl,
            ],
        ], true) . ";\n";
        file_put_contents(__DIR__ . '/config/config.php', $configPhp);

        $adminLoginUrl = ($baseUrl !== '' ? rtrim($baseUrl, '/') : rtrim((isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/'), '/')) . '/admin/login.php';
        $siteUrl = ($baseUrl !== '' ? rtrim($baseUrl, '/') : rtrim((isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/'), '/')) . '/';

        echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>Installation terminée</title><style>body{font-family:Arial,sans-serif;padding:40px;background:#f5f7fc} .card{max-width:760px;background:#fff;padding:30px;border-radius:18px;margin:auto;box-shadow:0 20px 40px rgba(0,0,0,.08)} .btn{display:inline-block;padding:12px 18px;border-radius:12px;background:#1e4fa8;color:#fff;text-decoration:none;font-weight:700;margin-right:10px}</style></head><body><div class="card"><h1>Installation terminée</h1><p>Le site EMAE V10 est prêt.</p><p><strong>Admin :</strong> ' . e($adminEmail) . '</p><p><strong>Mot de passe :</strong> ' . e($adminPassword) . '</p><p><a class="btn" href="' . e($adminLoginUrl) . '">Ouvrir l’administration</a><a class="btn" href="' . e($siteUrl) . '">Voir le site</a></p><p>Astuce : si tu relances l’installation sur la même base, le script réinitialise les tables pour repartir proprement.</p></div></body></html>';
        exit;
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Installer EMAE V10</title>
  <style>
    body{margin:0;background:#f5f7fc;font-family:Arial,sans-serif;color:#13254c}
    .wrap{max-width:980px;margin:40px auto;padding:0 20px}
    .card{background:#fff;border-radius:20px;padding:28px;box-shadow:0 20px 50px rgba(11,22,65,.08)}
    h1,h2{margin-top:0}
    .grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
    label{display:grid;gap:6px;font-weight:700}
    input{min-height:48px;border:1px solid #dbe4f0;border-radius:12px;padding:0 14px}
    button{margin-top:20px;min-height:52px;border:0;border-radius:14px;padding:0 18px;background:#1e4fa8;color:#fff;font-weight:800;cursor:pointer}
    .error{background:#fff0f0;border:1px solid #f0c8c8;padding:12px 14px;border-radius:12px;color:#8a2d2d;margin-bottom:16px}
    .tip{background:#f5f8ff;border:1px solid #dbe4f0;padding:12px 14px;border-radius:12px;color:#24417a;margin-bottom:16px}
  </style>
</head>
<body>
<div class="wrap">
  <div class="card">
    <h1>Installer EMAE V10</h1>
    <p>Remplis ces champs une seule fois. Si tu relances l’installation sur la même base, le script efface et recrée les tables automatiquement.</p>
    <div class="tip">Conseil XAMPP : laisse <strong>Base URL</strong> vide. Mets un nom de base simple, par exemple <strong>emae_v10</strong>.</div>
    <?php if (!empty($error)): ?><div class="error"><?= e($error) ?></div><?php endif; ?>
    <form method="post">
      <h2>Base de données</h2>
      <div class="grid">
        <label>Host<input type="text" name="db_host" value="localhost"></label>
        <label>Port<input type="text" name="db_port" value="3306"></label>
        <label>Nom base<input type="text" name="db_name" value="<?= e($defaultDbName) ?>"></label>
        <label>Utilisateur<input type="text" name="db_user" value="root"></label>
        <label>Mot de passe<input type="text" name="db_pass" value=""></label>
        <label>Base URL (laisser vide sur XAMPP)<input type="text" name="base_url" value=""></label>
      </div>
      <h2>Compte admin</h2>
      <div class="grid">
        <label>Nom<input type="text" name="admin_name" value="Admin EMAE"></label>
        <label>Email<input type="email" name="admin_email" value="admin@emae.fr"></label>
        <label>Mot de passe<input type="text" name="admin_password" value="adminem@e"></label>
      </div>
      <button type="submit">Installer EMAE V10</button>
    </form>
  </div>
</div>
</body>
</html>
