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
  <div class="wrap">
    <?php foreach ($c['programmes'] as $p): ?>
    <article class="prog" id="<?= e($p['anchor'] ?? slugify($p['title'] ?? 'programme')) ?>">
      <div class="prog-head">
        <?= icon($p['icon'] ?? 'shield') ?>
        <div>
          <h2 class="display-caps"><?= e($p['title'] ?? '') ?></h2>
          <p class="prog-for"><?= e($p['for'] ?? '') ?></p>
        </div>
      </div>
      <div class="prog-grid">
        <div>
          <p><strong>What it includes</strong></p>
          <ul class="checklist">
            <?php foreach (lines($p['includes'] ?? '') as $li): ?>
            <li><?= inline($li) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="stack">
          <?= image_slot($p['image'] ?? '', ['ratio' => '3 / 2', 'label' => ($p['title'] ?? '') . ' image', 'note' => 'A workshop, a classroom, a courthouse step. No faces without consent.', 'adapt' => true]) ?>
          <?php if (trim((string)($p['note_h'] ?? '')) !== '' || trim((string)($p['note_b'] ?? '')) !== ''): ?>
          <div class="callout">
            <?php if (trim((string)($p['note_h'] ?? '')) !== ''): ?><h4><?= e($p['note_h']) ?></h4><?php endif; ?>
            <?= rich($p['note_b'] ?? '') ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </article>
    <?php endforeach; ?>
  </div>
</section>

<section class="redband section-lg">
  <div class="wrap wrap-narrow center">
    <h2 class="display-caps"><?= e($c['close_headline']) ?></h2>
    <div class="lede mx-auto" style="margin-bottom:1.75rem"><?= rich($c['close_body']) ?></div>
    <div class="btn-row" style="justify-content:center">
      <a class="btn btn-light" href="/get-involved#give">Fund a programme</a>
      <a class="btn btn-outline-light" href="/get-help">Access a programme</a>
    </div>
  </div>
</section>
