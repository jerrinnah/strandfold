<?php declare(strict_types=1); ?>
<section class="pagehead">
  <div class="wrap">
    <div class="pagehead-inner">
      <span class="eyebrow"><?= e($c['hero_eyebrow']) ?></span>
      <h1><?= e($c['hero_headline']) ?></h1>
      <div class="lede"><?= rich($c['hero_lede']) ?></div>
      <?php if (trim($c['reviewed']) !== ''): ?>
      <p class="small muted">Last reviewed by our Board of Trustees: <?= e($c['reviewed']) ?></p>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="section-lg">
  <div class="wrap wrap-narrow">
    <div class="acc">
      <?php foreach ($c['policies'] as $i => $p): ?>
      <details id="<?= e($p['anchor'] ?? '') ?>"<?= $i === 0 ? ' open' : '' ?>>
        <summary><?= e($p['title'] ?? '') ?></summary>
        <div class="acc-body"><?= rich($p['body'] ?? '') ?></div>
      </details>
      <?php endforeach; ?>
    </div>
    <?= danger_note($s, 'mt-2') ?>
  </div>
</section>
