<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/render.php';

$route = trim((string) ($_GET['route'] ?? ''), '/');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_type'] ?? '') === 'quote') {
    verify_csrf();

    if (!rate_limit_passed('quote_submit', 8)) {
        flash('error', 'Merci de patienter quelques secondes avant de renvoyer le formulaire.');
        redirect_to('quote');
    }

    if (trim((string) ($_POST['website'] ?? '')) !== '') {
        flash('error', 'Envoi bloqué.');
        redirect_to('quote');
    }

    $fullName = trim((string) ($_POST['full_name'] ?? ''));
    $phone = trim((string) ($_POST['phone'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $city = trim((string) ($_POST['city'] ?? ''));
    $serviceType = trim((string) ($_POST['service_type'] ?? ''));
    $message = trim((string) ($_POST['message'] ?? ''));
    $urgency = trim((string) ($_POST['urgency'] ?? 'Normale'));

    if ($fullName === '' || $phone === '' || $message === '') {
        flash('error', 'Merci de remplir les champs obligatoires.');
        redirect_to('quote');
    }

    db_execute(
        'INSERT INTO quotes (full_name, phone, email, city, service_type, message, urgency, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
        [$fullName, $phone, $email, $city, $serviceType, $message, $urgency, 'nouveau']
    );

    flash('success', quote_form_options()['success_message']);
    redirect_to('quote');
}

$meta = seo_defaults($route);
$activeNav = route_url($route);


if ($route === '' || $route === 'home') {
    $hero = hero_settings();
    $cards = home_cards();
    $reviews = visible_reviews(3);
    $button1Href = $hero['button1_url'];
    if (!preg_match('#^(https?:|tel:|mailto:|/)#i', $button1Href)) { $button1Href = route_url($button1Href); }
    $button2Href = $hero['button2_url'];
    if (!preg_match('#^(https?:|tel:|mailto:|/)#i', $button2Href)) { $button2Href = route_url($button2Href); }

    render_head($meta);
    render_header(route_url(''));
    ?>
    <style>
    .hero-v10-premium {
  position: relative;
  overflow: hidden;
  padding: 56px 0 34px;
  background:
    radial-gradient(circle at 21% 18%, rgba(96, 43, 76, 0.34) 0%, rgba(96, 43, 76, 0.18) 10%, rgba(0,0,0,0) 28%),
    radial-gradient(circle at 84% 18%, rgba(76, 116, 255, 0.28) 0%, rgba(76, 116, 255, 0.14) 12%, rgba(0,0,0,0) 30%),
    linear-gradient(90deg, #031041 0%, #081c5c 38%, #12327a 100%);
}

.hero-v10-premium::before {
  content: "";
  position: absolute;
  inset: 0;
  pointer-events: none;
  background:
    linear-gradient(180deg, rgba(255,255,255,0.02) 0%, rgba(255,255,255,0) 22%),
    linear-gradient(90deg, rgba(2,10,45,0.16) 0%, rgba(2,10,45,0.02) 35%, rgba(255,255,255,0) 100%);
}

.hero-v10-premium::after {
  content: "";
  position: absolute;
  inset: 0;
  pointer-events: none;
  background:
    radial-gradient(circle at 18% 52%, rgba(0, 24, 96, 0.20) 0%, rgba(0, 24, 96, 0.07) 16%, rgba(0,0,0,0) 34%),
    radial-gradient(circle at 72% 65%, rgba(18, 54, 145, 0.10) 0%, rgba(18, 54, 145, 0.04) 18%, rgba(0,0,0,0) 38%);
}
    .hero-v10-premium__grid {display:grid;grid-template-columns:minmax(0,1.08fr) minmax(360px,470px);gap:36px;align-items:center;}
    .hero-v10-premium__content {
  position: relative;
  z-index: 2;
  max-width: 760px;
}
    .hero-v10-premium__eyebrow {margin:0 0 14px;color:<?= e($hero['eyebrow_color']) ?>;font-family:var(--font-heading);font-size:13px;font-weight:800;letter-spacing:.14em;text-transform:uppercase;}
    .hero-v10-premium__title {margin:0;color:<?= e($hero['title_color']) ?>;font-family:var(--font-heading);font-size:clamp(44px,5vw,72px);line-height:1.02;font-weight:800;letter-spacing:-0.03em;}
    .hero-v10-premium__lead {margin:18px 0 0;color:<?= e($hero['lead_color']) ?>;font-family:var(--font-body);font-size:18px;line-height:1.55;max-width:760px;}
    .hero-v10-premium__bullets {list-style:none;padding:0;margin:18px 0 0;display:grid;gap:.7rem;}
    .hero-v10-premium__bullets li {position:relative;padding-left:1.15rem;color:#fff;font-weight:600;}
    .hero-v10-premium__bullets li::before{content:"•";position:absolute;left:0;top:-.05rem;color:#f3c748;font-weight:900;}
    .hero-v10-premium__chips{display:flex;flex-wrap:wrap;gap:10px;margin-top:18px;}
    .hero-v10-premium__chip{display:inline-flex;align-items:center;justify-content:center;min-height:40px;padding:0 16px;border-radius:999px;border:1px solid rgba(255,255,255,.16);background:rgba(255,255,255,.06);color:#fff;font-family:var(--font-body);font-size:14px;font-weight:700;box-shadow:inset 0 1px 0 rgba(255,255,255,.05);}
    .hero-v10-premium__actions{display:flex;flex-wrap:wrap;gap:12px;margin-top:18px;}
    .hero-v10-premium__features{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin-top:18px;}
    .hero-v10-premium__feature{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.10);border-radius:18px;padding:16px 16px 14px;box-shadow:inset 0 1px 0 rgba(255,255,255,.05);}
    .hero-v10-premium__feature-title{margin:0 0 6px;color:#fff;font-family:var(--font-heading);font-size:22px;font-weight:800;line-height:1.08;}
    .hero-v10-premium__feature-text{margin:0;color:#d7e4ff;font-family:var(--font-body);font-size:15px;line-height:1.5;}
    .hero-v10-premium__quote-wrap {
  position: relative;
  z-index: 2;
  display: flex;
  justify-content: flex-end;
}
    .hero-v10-premium__quote-bg{position:relative;padding:14px;background:rgba(15,35,92,.45);border-radius:30px;}
    .hero-v10-premium__quote-card{width:100%;max-width:470px;background:#f8f8fb;border-radius:28px;padding:18px 18px 16px;box-shadow:0 26px 60px rgba(11,22,65,.18);border:1px solid rgba(173,186,215,.35);}
    .hero-v10-premium__quote-eyebrow{margin:0 0 12px;color:#536ea8;font-family:var(--font-heading);font-size:13px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;}
    .hero-v10-premium__quote-title{margin:0 0 14px;color:#e8e8ee;font-family:var(--font-heading);font-size:44px;line-height:.95;font-weight:800;}
    .hero-v10-premium__form{display:grid;gap:14px;}
    .hero-v10-premium__field{display:grid;gap:6px;color:#0f1a44;font-family:var(--font-body);font-weight:700;}
    .hero-v10-premium__form select,.hero-v10-premium__form input{width:100%;min-height:52px;border-radius:16px;border:1px solid #cfd9ee;background:#fbfbfc;color:#16234d;padding:0 16px;outline:none;font-family:var(--font-body);}
    .hero-v10-premium__submit{min-height:54px;border:0;border-radius:16px;background:linear-gradient(180deg,#f3c748 0%,#eeb937 100%);color:#0b1641;font-family:var(--font-body);font-weight:800;font-size:18px;cursor:pointer;box-shadow:0 16px 30px rgba(238,185,55,.24);}
    .hero-v10-premium__meta{margin:0;color:#7283a8;font-size:14px;font-family:var(--font-body);}
    @media (max-width:1200px){.hero-v10-premium__grid{grid-template-columns:1fr;}.hero-v10-premium__features{grid-template-columns:1fr;}}
    </style>

    <section class="hero-v10-premium">
      <div class="container">
        <div class="hero-v10-premium__grid">
          <div class="hero-v10-premium__content">
            <p class="hero-v10-premium__eyebrow"><?= e($hero['eyebrow']) ?></p>
            <h1 class="hero-v10-premium__title"><?= nl2br(e($hero['title'])) ?></h1>
            <p class="hero-v10-premium__lead"><?= nl2br(e($hero['lead'])) ?></p>
            <?php if (!empty($hero['bullets'])): ?><ul class="hero-v10-premium__bullets"><?php foreach ($hero['bullets'] as $bullet): ?><li><?= e($bullet) ?></li><?php endforeach; ?></ul><?php endif; ?>
            <?php if (!empty($hero['chips'])): ?><div class="hero-v10-premium__chips"><?php foreach ($hero['chips'] as $chip): ?><span class="hero-v10-premium__chip"><?= e($chip) ?></span><?php endforeach; ?></div><?php endif; ?>
            <div class="hero-v10-premium__actions"><a class="btn btn--primary" href="<?= e($button1Href) ?>"><?= e($hero['button1_label']) ?></a><a class="btn btn--outline" href="<?= e($button2Href) ?>"><?= e($hero['button2_label']) ?></a></div>
            <div class="hero-v10-premium__features">
              <div class="hero-v10-premium__feature"><h3 class="hero-v10-premium__feature-title"><?= e($hero['feature_1_title']) ?></h3><p class="hero-v10-premium__feature-text"><?= e($hero['feature_1_text']) ?></p></div>
              <div class="hero-v10-premium__feature"><h3 class="hero-v10-premium__feature-title"><?= e($hero['feature_2_title']) ?></h3><p class="hero-v10-premium__feature-text"><?= e($hero['feature_2_text']) ?></p></div>
              <div class="hero-v10-premium__feature"><h3 class="hero-v10-premium__feature-title"><?= e($hero['feature_3_title']) ?></h3><p class="hero-v10-premium__feature-text"><?= e($hero['feature_3_text']) ?></p></div>
            </div>
          </div>
          <div class="hero-v10-premium__quote-wrap">
            <div class="hero-v10-premium__quote-bg">
              <div class="hero-v10-premium__quote-card">
                <p class="hero-v10-premium__quote-eyebrow"><?= e($hero['quote_eyebrow']) ?></p>
                <h2 class="hero-v10-premium__quote-title"><?= e($hero['quote_title']) ?></h2>
                <form class="hero-v10-premium__form" action="<?= e(route_url('quote')) ?>" method="get">
                  <label class="hero-v10-premium__field"><?= e($hero['quote_service_label']) ?><select name="service"><option value="">Choisir un service</option><?php foreach ($cards as $card): ?><option value="<?= e($card['title']) ?>"><?= e($card['title']) ?></option><?php endforeach; ?></select></label>
                  <label class="hero-v10-premium__field"><?= e($hero['quote_city_label']) ?><input type="text" name="city" placeholder="<?= e($hero['quote_city_placeholder']) ?>"></label>
                  <button class="hero-v10-premium__submit" type="submit"><?= e($hero['quote_button_label']) ?></button>
                  <p class="hero-v10-premium__meta"><?= e($hero['quote_meta']) ?></p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="home-services-white">
      <div class="container">
        <div class="section-title">
          <p class="eyebrow">Services</p>
          <h2>Des cartes services premium en format rectangle</h2>
          <p>Chaque carte peut être modifiée depuis l’espace admin.</p>
        </div>
        <div class="home-services-cards">
          <?php foreach ($cards as $card): ?>
            <a class="home-service-card" href="<?= e(route_url($card['link'])) ?>">
              <img src="<?= e(asset_url($card['image'])) ?>" alt="<?= e($card['title']) ?>">
              <div class="home-service-card__overlay"></div>
              <div class="home-service-card__title"><?= e($card['title']) ?></div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section class="section section--soft">
      <div class="container split-panel">
        <div class="card">
          <p class="eyebrow">Avis clients</p>
          <h2>Des témoignages qui rassurent</h2>
          <div class="reviews-grid">
            <?php foreach ($reviews as $review): ?>
              <article class="review-card"><div class="review-card__stars"><?= str_repeat('★', (int) $review['rating']) ?></div><h3><?= e($review['author_name']) ?></h3><p><?= e($review['content']) ?></p></article>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="card form-card">
          <p class="eyebrow">Demande rapide</p>
          <h2>Être rappelé</h2>
          <form action="<?= e(route_url('quote')) ?>" method="post">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="form_type" value="quote">
            <input type="text" name="website" value="" class="hp-field" tabindex="-1" autocomplete="off">
            <div class="form-grid"><label>Nom complet<input type="text" name="full_name" required></label><label>Téléphone<input type="tel" name="phone" required></label></div>
            <div class="form-grid"><label>Email<input type="email" name="email"></label><label>Ville<input type="text" name="city"></label></div>
            <label>Service<select name="service_type"><option value="">Choisir</option><?php foreach ($cards as $card): ?><option value="<?= e($card['title']) ?>"><?= e($card['title']) ?></option><?php endforeach; ?></select></label>
            <label>Votre besoin<textarea name="message" required></textarea></label>
            <label>Urgence<select name="urgency"><option>Normale</option><option>Urgente</option><option>Très urgente</option></select></label>
            <button class="btn btn--primary btn--block" type="submit"><?= e(quote_form_options()['submit_label']) ?></button>
          </form>
        </div>
      </div>
    </section>
    <?php
    render_footer();
    exit;
}


if (in_array($route, ['quote', 'contact'], true)) {
    $page = page_by_slug($route);
    $meta = seo_defaults($route, $page);
    render_head($meta);
    render_header(route_url($route));
    ?>
    <section class="page-hero">
      <div class="container">
        <p class="eyebrow"><?= e(company_name()) ?></p>
        <h1><?= e($page['title'] ?? ($route === 'contact' ? 'Contact' : 'Demande de devis')) ?></h1>
        <p><?= e($page['excerpt'] ?? '') ?></p>
      </div>
    </section>
    <section class="section">
      <div class="container split-panel">
        <div class="card">
          <h2><?= e($page['hero_title'] ?: ($route === 'contact' ? 'Nous contacter' : 'Décrivez votre besoin')) ?></h2>
          <p><?= e($page['hero_text'] ?: company_name() . ' vous répond rapidement.') ?></p>
          <div class="contact-list">
            <div><strong>Téléphone :</strong> <a href="<?= e(company_phone_link()) ?>"><?= e(company_phone()) ?></a></div>
            <div><strong>Email :</strong> <a href="mailto:<?= e(company_email()) ?>"><?= e(company_email()) ?></a></div>
            <div><strong>Zones :</strong> <?= e(company_regions()) ?></div>
            <div><strong>Horaires :</strong> <?= e(company_hours()) ?></div>
          </div>
        </div>
        <div class="card form-card">
          <h2><?= $route === 'contact' ? 'Envoyer un message' : 'Recevoir un devis' ?></h2>
          <form action="<?= e(route_url('quote')) ?>" method="post">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="form_type" value="quote">
            <input type="text" name="website" value="" class="hp-field" tabindex="-1" autocomplete="off">
            <div class="form-grid">
              <label>Nom complet<input type="text" name="full_name" required></label>
              <label>Téléphone<input type="tel" name="phone" required></label>
            </div>
            <div class="form-grid">
              <label>Email<input type="email" name="email"></label>
              <label>Ville<input type="text" name="city" value="<?= e((string) ($_GET['city'] ?? '')) ?>"></label>
            </div>
            <label>Service
              <select name="service_type">
                <option value="">Choisir</option>
                <?php foreach (home_cards() as $card): ?>
                  <?php $selected = ((string) ($_GET['service'] ?? '')) === $card['title'] ? 'selected' : ''; ?>
                  <option value="<?= e($card['title']) ?>" <?= $selected ?>><?= e($card['title']) ?></option>
                <?php endforeach; ?>
              </select>
            </label>
            <label>Votre besoin<textarea name="message" required></textarea></label>
            <label>Urgence
              <select name="urgency">
                <option>Normale</option>
                <option>Urgente</option>
                <option>Très urgente</option>
              </select>
            </label>
            <button class="btn btn--primary btn--block" type="submit"><?= e(quote_form_options()['submit_label']) ?></button>
          </form>
        </div>
      </div>
    </section>
    <?php
    render_footer();
    exit;
}

$page = page_by_slug($route);
if ($page) {
    $meta = seo_defaults($route, $page);
    render_head($meta);
    render_header(route_url($route));
    ?>
    <section class="page-hero">
      <div class="container">
        <p class="eyebrow"><?= e(company_name()) ?></p>
        <h1><?= e($page['hero_title'] ?: $page['title']) ?></h1>
        <p><?= e($page['excerpt'] ?: '') ?></p>
      </div>
    </section>
    <section class="section">
      <div class="container">
        <div class="card rich-content">
          <?= $page['content_html'] ?: '<p>Contenu à venir.</p>' ?>
        </div>
      </div>
    </section>
    <?php
    render_footer();
    exit;
}

http_response_code(404);
render_head([
    'title' => 'Page introuvable | ' . company_name(),
    'description' => 'Cette page est introuvable.',
    'canonical' => route_url($route),
]);
render_header('');
?>
<section class="page-hero">
  <div class="container">
    <p class="eyebrow">Erreur 404</p>
    <h1>Page introuvable</h1>
    <p>La page demandée n’existe pas ou a été déplacée.</p>
    <a class="btn btn--primary" href="<?= e(route_url('')) ?>">Retour à l’accueil</a>
  </div>
</section>
<?php render_footer(); ?>
