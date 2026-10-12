<?php declare(strict_types=1); ?>
<!-- ================= HERO ================= -->
<section class="hero">
  <div class="wrap">
    <div class="hero-grid">
      <div class="hero-inner">
        <span class="eyebrow"><?= e($c['hero_eyebrow']) ?></span>
        <h1><?= e($c['hero_headline']) ?></h1>
        <div class="lede"><?= rich($c['hero_lede']) ?></div>
        <div class="btn-row">
          <a class="btn btn-primary" href="/get-help"><?= e($c['hero_cta1']) ?></a>
          <a class="btn btn-ghost" href="/get-involved#give"><?= e($c['hero_cta2']) ?></a>
        </div>
        <div class="hero-contact">
          <p class="hero-stat-label">Confidential helpline · <?= e($s['helpline_hours']) ?></p>
          <p class="hero-stat">
            <a href="tel:<?= e($s['helpline_tel']) ?>"><?= e($s['helpline']) ?></a>
            <span class="hero-stat-alt">or WhatsApp <a href="https://wa.me/<?= e(preg_replace('/\D+/', '', $s['whatsapp_number'])) ?>" rel="noreferrer noopener"><?= e($s['whatsapp']) ?></a></span>
          </p>
          <p class="small mb-0">If you are in immediate danger, stop reading and call emergency services. If you cannot speak safely, send us a message and we will follow your lead on how and when to respond.</p>
        </div>
      </div>
      <div class="hero-media">
        <?= image_slot($c['hero_image'] ?? '', ['ratio' => '4 / 5', 'label' => 'Hero image', 'note' => 'Hands, textiles, a doorway, light through a window. Never a survivor’s face.', 'priority' => true, 'adapt' => true]) ?>
      </div>
    </div>
  </div>
</section>

<!-- ================= THE REALITY ================= -->
<section class="stats section">
  <div class="wrap">
    <span class="eyebrow eyebrow-muted"><?= e($c['stats_eyebrow']) ?></span>
    <div class="grid g-4">
      <?php foreach ($c['stats'] as $row): ?>
      <div class="stat">
        <span class="stat-num"><?= e($row['number'] ?? '') ?></span>
        <p class="stat-label"><?= e($row['label'] ?? '') ?></p>
      </div>
      <?php endforeach; ?>
    </div>
    <p class="source-note"><?= inline($c['stats_source']) ?></p>
  </div>
</section>

<!-- ================= WHAT WE ACTUALLY DO ================= -->
<section class="section-lg">
  <div class="wrap">
    <div class="grid g-2" style="align-items:start;gap:2.5rem">
      <div>
        <span class="eyebrow"><?= e($c['do_eyebrow']) ?></span>
        <h2 class="display-caps"><?= e($c['do_headline']) ?></h2>
      </div>
      <div>
        <?= rich($c['do_body']) ?>
        <a class="linkarrow" href="/programmes">See how each programme works</a>
      </div>
    </div>

    <div class="grid g-<?= max(2, min(4, count($c['programmes']))) ?>" style="margin-top:3rem">
      <?php foreach ($c['programmes'] as $p): ?>
      <article class="card card-media">
        <?= image_slot($p['image'] ?? '', ['ratio' => '1 / 1', 'label' => ($p['title'] ?? 'Programme') . ' image', 'class' => 'imgslot-card']) ?>
        <div class="card-body">
          <?= icon($p['icon'] ?? 'shield', 'card-icon') ?>
          <h3><?= e($p['title'] ?? '') ?></h3>
          <p><?= e($p['body'] ?? '') ?></p>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ================= SURVIVOR VOICE ================= -->
<section class="quoteband section-lg">
  <div class="wrap">
    <div class="quote">
      <svg class="quote-mark" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7.5 5C5 5 3 7.2 3 10c0 2.6 1.9 4.6 4.3 4.6.4 0 .8 0 1.1-.1-.5 2-2 3.4-4 4l.8 1.5c3.6-1 6.3-4.3 6.3-8.8C11.5 7.2 9.9 5 7.5 5Zm10 0C15 5 13 7.2 13 10c0 2.6 1.9 4.6 4.3 4.6.4 0 .8 0 1.1-.1-.5 2-2 3.4-4 4l.8 1.5c3.6-1 6.3-4.3 6.3-8.8C21.5 7.2 19.9 5 17.5 5Z"/></svg>
      <blockquote>&ldquo;<?= e($c['quote_text']) ?>&rdquo;</blockquote>
      <cite><?= e($c['quote_cite']) ?></cite>
      <div class="btn-row" style="justify-content:center;margin-top:1.75rem">
        <a class="linkarrow" href="/stories">Read more stories</a>
      </div>
    </div>
  </div>
</section>

<!-- ================= RED BAND ================= -->
<section class="redband">
  <div class="split">
    <div class="split-visual">
      <?= image_slot($c['band_image'] ?? '', ['ratio' => '4 / 3', 'label' => 'Band image', 'note' => 'Abstract or environmental. No identifiable faces.', 'class' => 'imgslot-fill']) ?>
    </div>
    <div class="split-body">
      <span class="eyebrow"><?= e($c['band_eyebrow']) ?></span>
      <h2 class="display-caps"><?= e($c['band_headline']) ?></h2>
      <?= rich($c['band_body']) ?>
      <div class="btn-row" style="margin-top:1.75rem">
        <a class="btn btn-light" href="/about">Read our story</a>
      </div>
    </div>
  </div>
</section>

<!-- ================= THREE WAYS ================= -->
<section class="section-lg">
  <div class="wrap">
    <span class="eyebrow">Stand with us</span>
    <h2 class="display-caps">Three ways to stand with us.</h2>
    <div class="grid g-3" style="margin-top:2.25rem">
      <?php foreach ($c['ways'] as $w): ?>
      <div class="way">
        <h3><?= e($w['title'] ?? '') ?></h3>
        <p><?= e($w['body'] ?? '') ?></p>
        <a class="linkarrow" href="<?= e($w['link'] ?? '#') ?>"><?= e($w['cta'] ?? '') ?></a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ================= CLOSING BAND ================= -->
<section class="section-lg" style="background:var(--paper-2);border-top:1px solid var(--rule)">
  <div class="wrap wrap-narrow center">
    <h2 class="display-caps"><?= e($c['close_headline']) ?></h2>
    <div class="lede mx-auto" style="margin-bottom:1.75rem"><?= rich($c['close_body']) ?></div>
    <div class="btn-row" style="justify-content:center">
      <a class="btn btn-primary" href="/get-help#friends-family">How to help someone you love</a>
    </div>
  </div>
</section>
