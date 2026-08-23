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
    <div class="grid g-2" style="gap:3rem;align-items:start">
      <div>
        <span class="eyebrow">Our story</span>
        <h2 class="display-caps"><?= e($c['story_headline']) ?></h2>
        <?= image_slot($c['story_image'] ?? '', ['ratio' => '3 / 2', 'label' => 'Story image', 'note' => 'A room, a doorway, a desk. Not a person who came to you for help.']) ?>
      </div>
      <div>
        <?= rich($c['story_body']) ?>
        <blockquote class="pullquote"><?= e($c['story_quote']) ?></blockquote>
        <?= rich($c['story_body2']) ?>
      </div>
    </div>
  </div>
</section>

<section class="redband">
  <div class="split">
    <div class="split-body">
      <span class="eyebrow">The name</span>
      <h2 class="display-caps"><?= e($c['band_headline']) ?></h2>
      <?= rich($c['band_body']) ?>
    </div>
    <div class="split-visual">
      <?= image_slot($c['band_image'] ?? '', ['ratio' => '4 / 3', 'label' => 'Band image', 'note' => 'Abstract or environmental.', 'class' => 'imgslot-fill']) ?>
    </div>
  </div>
</section>

<section class="section-lg">
  <div class="wrap">
    <span class="eyebrow">Our approach</span>
    <h2 class="display-caps"><?= e($c['approach_headline']) ?></h2>
    <div class="grid g-2" style="margin-top:2.25rem;gap:2rem">
      <?php foreach ($c['approach'] as $a): ?>
      <div class="card">
        <h3><?= e($a['title'] ?? '') ?></h3>
        <?= rich($a['body'] ?? '') ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section-lg" style="background:var(--white);border-block:1px solid var(--rule)">
  <div class="wrap">
    <span class="eyebrow">Core values</span>
    <h2 class="display-caps"><?= e($c['values_headline']) ?></h2>
    <div class="grid g-2" style="margin-top:2.25rem;gap:2rem 3rem">
      <?php foreach ($c['values'] as $v): ?>
      <div class="feature">
        <?= icon($v['icon'] ?? 'check') ?>
        <div>
          <h3><?= e($v['title'] ?? '') ?></h3>
          <p><?= e($v['body'] ?? '') ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section-lg">
  <div class="wrap">
    <div class="grid g-2" style="gap:3rem;align-items:start">
      <div>
        <span class="eyebrow">What we don’t do</span>
        <h2 class="display-caps"><?= e($c['dont_headline']) ?></h2>
      </div>
      <ul class="checklist">
        <?php foreach (lines($c['dont_list']) as $li): ?>
        <li><?= inline($li) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>

<section id="team" class="section-lg" style="background:var(--paper-2);border-top:1px solid var(--rule)">
  <div class="wrap">
    <span class="eyebrow">Our team</span>
    <h2 class="display-caps"><?= e($c['team_headline']) ?></h2>
    <div class="lede"><?= rich($c['team_lede']) ?></div>
    <div class="grid g-3" style="margin-top:2.25rem">
      <?php foreach ($c['team'] as $t): ?>
      <div class="card card-media">
        <?= image_slot($t['image'] ?? '', ['ratio' => '1 / 1', 'label' => 'Staff photograph', 'note' => 'Staff only, with their consent.', 'class' => 'imgslot-card']) ?>
        <div class="card-body">
          <h3><?= e($t['name'] ?? '') ?></h3>
          <p class="role"><?= e($t['role'] ?? '') ?></p>
          <p class="mb-0"><?= e($t['bio'] ?? '') ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <p class="small muted mt-2">Staff photographs are optional and are published only with each person’s consent. No photograph anywhere on this site depicts a woman we have supported unless she has specifically asked to be photographed.</p>
  </div>
</section>

<section id="governance" class="section-lg">
  <div class="wrap">
    <div class="grid g-2" style="gap:3rem;align-items:start">
      <div>
        <span class="eyebrow">Governance &amp; accountability</span>
        <h2 class="display-caps"><?= e($c['gov_headline']) ?></h2>
        <?= rich($c['gov_body']) ?>
      </div>
      <ul class="checklist">
        <?php foreach (lines($c['gov_list']) as $li): ?>
        <li><?= inline($li) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="callout" style="margin-top:2.5rem">
      <h4>Our line on donor access</h4>
      <p class="mb-0">We do not accept donations conditional on access to survivors’ identities or stories. No donor, partner or journalist is introduced to a woman we support without her prior, written, revocable consent.</p>
    </div>
  </div>
</section>
