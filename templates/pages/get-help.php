<?php declare(strict_types=1);
$wa = preg_replace('/\D+/', '', $s['whatsapp_number']);
$methods = [
  ['phone',    'Call',         $s['helpline'], 'tel:' . $s['helpline_tel'], 'When you can speak safely. Available ' . $s['helpline_hours'] . '.'],
  ['whatsapp', 'WhatsApp',     $s['whatsapp'], 'https://wa.me/' . $wa,      'When you cannot speak, or need to message at odd hours.'],
  ['sms',      'Text / SMS',   $s['sms'],      'sms:' . $s['sms_tel'],      'Low data, discreet, works on any phone.'],
  ['mail',     'Email',        $s['email'],    'mailto:' . $s['email'],     'Non-urgent questions. Remember that email can be read by anyone with access to your account.'],
  ['pin',      'Walk in',      $s['address'],  '',                          $s['office_hours'] . '. If it is safer to come in person, you can simply arrive. No appointment needed.'],
  ['chat',     'Message form', 'Write to us privately', '/contact',     'Text only. We reply only in the way and at the times you tell us are safe.'],
];
?>
<section class="pagehead">
  <div class="wrap">
    <div class="pagehead-inner">
      <span class="eyebrow"><?= e($c['hero_eyebrow']) ?></span>
      <h1><?= e($c['hero_headline']) ?></h1>
      <div class="lede"><?= rich($c['hero_lede']) ?></div>
      <?= rich($c['hero_assure']) ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <?= danger_note($s) ?>

    <span class="eyebrow" style="margin-top:2rem">How to reach us</span>
    <h2 class="display-caps">Reach us however is safest.</h2>

    <ul class="contact-list" style="margin-top:1.75rem">
      <?php foreach ($methods as [$ic, $title, $val, $href, $note]): ?>
      <li class="contact-item">
        <?= icon($ic) ?>
        <div>
          <h3><?= e($title) ?></h3>
          <?php if ($href !== ''): ?>
            <a class="val" href="<?= e($href) ?>"<?= str_starts_with($href, 'https://') ? ' rel="noreferrer noopener"' : '' ?>><?= e($val) ?></a>
          <?php else: ?>
            <span class="val"><?= e($val) ?></span>
          <?php endif; ?>
          <p><?= e($note) ?></p>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<section class="section" style="background:var(--white);border-block:1px solid var(--rule)">
  <div class="wrap">
    <span class="eyebrow"><?= e($c['steps_eyebrow']) ?></span>
    <h2 class="display-caps"><?= e($c['steps_headline']) ?></h2>
    <div class="grid g-4" style="margin-top:2.25rem">
      <?php foreach ($c['steps'] as $i => $step): ?>
      <div class="card-flat">
        <div class="card-num"><?= $i + 1 ?></div>
        <h3><?= e($step['title'] ?? '') ?></h3>
        <p class="muted"><?= e($step['body'] ?? '') ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section-lg">
  <div class="wrap wrap-narrow">
    <div class="acc">
      <?php foreach ($c['accordions'] as $i => $a): ?>
      <details id="<?= e($a['anchor'] ?? slugify($a['title'] ?? 'section')) ?>"<?= $i === 0 ? ' open' : '' ?>>
        <summary><?= e($a['title'] ?? '') ?></summary>
        <div class="acc-body"><?= rich($a['body'] ?? '') ?></div>
      </details>
      <?php endforeach; ?>
    </div>

    <div class="safety-note" style="margin-top:2.5rem">
      <?= icon('info') ?>
      <p>Worried someone can see what you are reading? <a href="#" data-open-tracks>Learn how to cover your tracks</a>, or use the red <strong>Quick exit</strong> button at the top of this page to leave immediately.</p>
    </div>
  </div>
</section>

<section class="redband section-lg">
  <div class="wrap wrap-narrow center">
    <h2 class="display-caps"><?= e($c['close_headline']) ?></h2>
    <div class="lede mx-auto" style="margin-bottom:1.75rem"><?= rich($c['close_body']) ?></div>
    <div class="btn-row" style="justify-content:center">
      <a class="btn btn-light" href="tel:<?= e($s['helpline_tel']) ?>">Call <?= e($s['helpline']) ?></a>
      <a class="btn btn-outline-light" href="https://wa.me/<?= e($wa) ?>" rel="noreferrer noopener">Message on WhatsApp</a>
    </div>
  </div>
</section>
