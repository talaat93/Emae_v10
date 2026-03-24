<?php
require_once __DIR__ . '/includes/bootstrap.php';

$meta = seo_meta();
$services = service_cards();

$heroBgFrom    = site_setting('hero_bg_from', '#04113b');
$heroBgTo      = site_setting('hero_bg_to', '#18357f');
$heroGlowLeft  = site_setting('hero_glow_left', '#4a233f');
$heroGlowRight = site_setting('hero_glow_right', '#335dff');

$homeEyebrow = site_setting('home_eyebrow', 'ENTREPRISE MULTITECHNIQUE AVANCÉE');
$homeTitle   = site_setting('home_title', "Le partenaire\ntechnique de vos\nbâtiments en Île-de-\nFrance et en Occitanie");
$homeLead    = site_setting('home_lead', "EMAE aide à capter des clients pour le dépannage, l’entretien et les besoins techniques en électricité, plomberie, chauffage, climatisation, CVC et pompes à chaleur.");

$eyebrowColor = site_setting('home_eyebrow_color', '#83a6ff');
$titleColor   = site_setting('home_title_color', '#ffffff');
$leadColor    = site_setting('home_lead_color', '#dbe6ff');

$chips = array_values(array_filter([
    site_setting('home_chip_1', 'Électricité'),
    site_setting('home_chip_2', 'Plomberie'),
    site_setting('home_chip_3', 'CVC'),
    site_setting('home_chip_4', 'Climatisation'),
    site_setting('home_chip_5', 'Chauffage'),
    site_setting('home_chip_6', 'PAC'),
], fn($v) => trim((string)$v) !== ''));

$button1Label = site_setting('home_button1_label', 'Demander un devis');
$button1Url   = site_setting('home_button1_url', url_for('quote.php'));
$button2Label = site_setting('home_button2_label', 'Domaines d’intervention');
$button2Url   = site_setting('home_button2_url', url_for('services.php'));

$features = [
    [
        'title' => site_setting('home_feature_1_title', 'Mobile first'),
        'text'  => site_setting('home_feature_1_text', 'Prêt pour Google Ads local'),
    ],
    [
        'title' => site_setting('home_feature_2_title', 'Conversion rapide'),
        'text'  => site_setting('home_feature_2_text', 'Appel, devis, contact, espace client'),
    ],
    [
        'title' => site_setting('home_feature_3_title', 'Image premium'),
        'text'  => site_setting('home_feature_3_text', 'Corporate, claire et rassurante'),
    ],
];

$quoteEyebrow        = site_setting('home_quote_eyebrow', 'DEMANDE DE DEVIS GRATUITE');
$quoteTitle          = site_setting('home_quote_title', 'Obtenir un rappel rapide');
$quoteServiceLabel   = site_setting('home_quote_service_label', 'Service');
$quoteCityLabel      = site_setting('home_quote_city_label', 'Ville');
$quoteCityPlaceholder= site_setting('home_quote_city_placeholder', 'Ex : Meaux, Paris, Toulouse');
$quoteButtonLabel    = site_setting('home_quote_button_label', 'Continuer');
$quoteMeta           = site_setting('home_quote_meta', 'Artisans disponibles • devis gratuit • réponse rapide');

require __DIR__ . '/includes/header.php';
?>

<style>
.hero-v11 {
  position: relative;
  overflow: hidden;
  padding: 58px 0 36px;
  background:
    radial-gradient(circle at 22% 22%, <?= e($heroGlowLeft) ?> 0%, rgba(0,0,0,0) 20%),
    radial-gradient(circle at 85% 20%, <?= e($heroGlowRight) ?> 0%, rgba(0,0,0,0) 24%),
    linear-gradient(90deg, <?= e($heroBgFrom) ?> 0%, <?= e($heroBgTo) ?> 100%);
}

.hero-v11__grid {
  display: grid;
  grid-template-columns: minmax(0, 1.05fr) minmax(360px, 470px);
  gap: 34px;
  align-items: center;
}

.hero-v11__content {
  max-width: 760px;
}

.hero-v11__eyebrow {
  margin: 0 0 16px;
  color: <?= e($eyebrowColor) ?>;
  font-family: "Montserrat", Arial, sans-serif;
  font-size: 13px;
  font-weight: 800;
  letter-spacing: .14em;
  text-transform: uppercase;
}

.hero-v11__title {
  margin: 0;
  color: <?= e($titleColor) ?>;
  font-family: "Montserrat", Arial, sans-serif;
  font-size: clamp(50px, 5.2vw, 72px);
  line-height: 1.01;
  letter-spacing: -0.04em;
  font-weight: 800;
  max-width: 720px;
}

.hero-v11__lead {
  margin: 18px 0 0;
  color: <?= e($leadColor) ?>;
  font-family: "Inter", Arial, sans-serif;
  font-size: 17px;
  line-height: 1.55;
  max-width: 640px;
}

.hero-v11__chips {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-top: 16px;
}

.hero-v11__chip {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-height: 40px;
  padding: 0 16px;
  border-radius: 999px;
  border: 1px solid rgba(255,255,255,.12);
  background: rgba(255,255,255,.06);
  color: #fff;
  font-family: "Inter", Arial, sans-serif;
  font-size: 14px;
  font-weight: 700;
}

.hero-v11__actions {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
  margin-top: 18px;
}

.hero-v11__features {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 14px;
  margin-top: 18px;
  max-width: 920px;
}

.hero-v11__feature {
  border-radius: 18px;
  padding: 16px 16px 14px;
  background: rgba(255,255,255,.06);
  border: 1px solid rgba(255,255,255,.10);
  box-shadow: inset 0 1px 0 rgba(255,255,255,.05);
}

.hero-v11__feature-title {
  margin: 0 0 6px;
  color: #fff;
  font-family: "Montserrat", Arial, sans-serif;
  font-size: 19px;
  font-weight: 800;
  line-height: 1.08;
}

.hero-v11__feature-text {
  margin: 0;
  color: #d8e4ff;
  font-family: "Inter", Arial, sans-serif;
  font-size: 15px;
  line-height: 1.5;
}

.hero-v11__quote-wrap {
  display: flex;
  justify-content: flex-end;
}

.hero-v11__quote-frame {
  position: relative;
  padding: 14px;
  border-radius: 30px;
  background: rgba(15, 35, 92, .42);
}

.hero-v11__quote-card {
  width: 100%;
  max-width: 470px;
  background: #f7f7fa;
  border-radius: 28px;
  padding: 18px 18px 16px;
  border: 1px solid rgba(176, 188, 219, .34);
  box-shadow: 0 26px 60px rgba(11, 22, 65, .16);
}

.hero-v11__quote-eyebrow {
  margin: 0 0 12px;
  color: #526ea8;
  font-family: "Montserrat", Arial, sans-serif;
  font-size: 13px;
  font-weight: 800;
  letter-spacing: .12em;
  text-transform: uppercase;
}

.hero-v11__quote-title {
  margin: 0 0 14px;
  color: #ececf2;
  font-family: "Montserrat", Arial, sans-serif;
  font-size: 26px;
  line-height: 1.04;
  font-weight: 800;
}

.hero-v11__form {
  display: grid;
  gap: 14px;
}

.hero-v11__field {
  display: grid;
  gap: 6px;
  color: #0f1a44;
  font-family: "Inter", Arial, sans-serif;
  font-weight: 700;
}

.hero-v11__form select,
.hero-v11__form input {
  width: 100%;
  min-height: 52px;
  border-radius: 16px;
  border: 1px solid #cfd9ee;
  background: #fbfbfc;
  color: #16234d;
  padding: 0 16px;
  outline: none;
  font-family: "Inter", Arial, sans-serif;
  font-size: 15px;
}

.hero-v11__submit {
  min-height: 54px;
  border: 0;
  border-radius: 16px;
  background: linear-gradient(180deg, #f3c748 0%, #eeb937 100%);
  color: #0b1641;
  font-family: "Inter", Arial, sans-serif;
  font-weight: 800;
  font-size: 18px;
  cursor: pointer;
  box-shadow: 0 16px 30px rgba(238,185,55,.22);
}

.hero-v11__meta {
  margin: 0;
  color: #7082aa;
  font-size: 14px;
  font-family: "Inter", Arial, sans-serif;
}

.services-strip-white {
  background: #fff;
  padding: 26px 0 30px;
}

.services-strip-white__grid {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 18px;
}

.service-tile {
  position: relative;
  display: block;
  min-height: 330px;
  border-radius: 22px;
  overflow: hidden;
  text-decoration: none;
  background: #10224d;
  box-shadow: 0 16px 34px rgba(11,22,65,.16), 0 6px 14px rgba(11,22,65,.08);
}

.service-tile img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.service-tile__overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, rgba(9,18,53,.08) 0%, rgba(8,20,61,.76) 100%);
}

.service-tile__title {
  position: absolute;
  left: 16px;
  right: 16px;
  bottom: 16px;
  color: #fff;
  text-align: center;
  font-family: "Montserrat", Arial, sans-serif;
  font-size: 18px;
  line-height: 1.08;
  font-weight: 800;
  text-shadow: 0 2px 12px rgba(0,0,0,.35);
}

@media (max-width: 1200px) {
  .hero-v11__grid {
    grid-template-columns: 1fr;
  }

  .hero-v11__features,
  .services-strip-white__grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 760px) {
  .hero-v11__features,
  .services-strip-white__grid {
    grid-template-columns: 1fr;
  }
}
</style>

<section class="hero-v11">
  <div class="container">
    <div class="hero-v11__grid">
      <div class="hero-v11__content">
        <p class="hero-v11__eyebrow"><?= e($homeEyebrow) ?></p>
        <h1 class="hero-v11__title"><?= nl2br(e($homeTitle)) ?></h1>
        <p class="hero-v11__lead"><?= nl2br(e($homeLead)) ?></p>

        <?php if ($chips): ?>
          <div class="hero-v11__chips">
            <?php foreach ($chips as $chip): ?>
              <span class="hero-v11__chip"><?= e($chip) ?></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <div class="hero-v11__actions">
          <a class="btn btn--primary" href="<?= e($button1Url) ?>"><?= e($button1Label) ?></a>
          <a class="btn btn--outline" href="<?= e($button2Url) ?>"><?= e($button2Label) ?></a>
        </div>

        <div class="hero-v11__features">
          <?php foreach ($features as $feature): ?>
            <div class="hero-v11__feature">
              <h3 class="hero-v11__feature-title"><?= e($feature['title']) ?></h3>
              <p class="hero-v11__feature-text"><?= e($feature['text']) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="hero-v11__quote-wrap">
        <div class="hero-v11__quote-frame">
          <div class="hero-v11__quote-card">
            <p class="hero-v11__quote-eyebrow"><?= e($quoteEyebrow) ?></p>
            <h2 class="hero-v11__quote-title"><?= e($quoteTitle) ?></h2>

            <form class="hero-v11__form" action="<?= e(url_for('quote.php')) ?>" method="get">
              <label class="hero-v11__field">
                <?= e($quoteServiceLabel) ?>
                <select name="service">
                  <option value="">Choisir un service</option>
                  <?php foreach ($services as $service): ?>
                    <option value="<?= e($service['title']) ?>"><?= e($service['title']) ?></option>
                  <?php endforeach; ?>
                </select>
              </label>

              <label class="hero-v11__field">
                <?= e($quoteCityLabel) ?>
                <input type="text" name="city" placeholder="<?= e($quoteCityPlaceholder) ?>">
              </label>

              <button class="hero-v11__submit" type="submit"><?= e($quoteButtonLabel) ?></button>
              <p class="hero-v11__meta"><?= e($quoteMeta) ?></p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="services-strip-white">
  <div class="container">
    <div class="services-strip-white__grid">
      <?php foreach (array_slice($services, 0, 4) as $service): ?>
        <a class="service-tile" href="<?= e(url_for($service['slug'])) ?>">
          <img src="<?= e(asset_url($service['hero_image'] ?: 'assets/img/gallery-2.jpeg')) ?>" alt="<?= e($service['title']) ?>">
          <div class="service-tile__overlay"></div>
          <div class="service-tile__title"><?= e($service['title']) ?></div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>