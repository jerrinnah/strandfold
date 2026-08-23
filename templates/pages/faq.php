<?php declare(strict_types=1); ?>
<section class="pagehead">
  <div class="wrap">
    <div class="pagehead-inner">
      <span class="eyebrow"><?= e($c['hero_eyebrow']) ?></span>
      <h1><?= e($c['hero_headline']) ?></h1>
      <div class="lede"><?= rich($c['hero_lede']) ?></div>
    </div>
  </div>
</section>

<section class="section-lg">
  <div class="wrap wrap-narrow">
    <div class="acc">
      <?php foreach ($c['faqs'] as $i => $f): ?>
      <details<?= $i === 0 ? ' open' : '' ?>>
        <summary><?= e($f['q'] ?? '') ?></summary>
        <div class="acc-body"><?= rich($f['a'] ?? '') ?></div>
      </details>
      <?php endforeach; ?>
    </div>
    <?= danger_note($s, 'mt-2') ?>
  </div>
</section>
