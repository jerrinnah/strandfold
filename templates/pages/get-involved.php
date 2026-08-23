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

<section id="give" class="section-lg">
  <div class="wrap">
    <span class="eyebrow">Give</span>
    <h2 class="display-caps"><?= e($c['give_headline']) ?></h2>
    <div class="lede"><?= rich($c['give_lede']) ?></div>

    <div class="table-wrap mt-2">
      <table>
        <caption class="sr-only">Donation amounts and what each one funds</caption>
        <thead><tr><th scope="col">Amount</th><th scope="col">What it does</th></tr></thead>
        <tbody>
          <?php foreach ($c['amounts'] as $a): ?>
          <tr><td class="amount"><?= e($a['amount'] ?? '') ?></td><td><?= e($a['does'] ?? '') ?></td></tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="btn-row" style="margin-top:1.75rem">
      <a class="btn btn-primary" href="<?= e($c['give_once_url'] ?: '#') ?>">Give once</a>
      <a class="btn btn-ghost" href="<?= e($c['give_monthly_url'] ?: '#') ?>">Give monthly</a>
    </div>

    <div class="callout" style="margin-top:2.5rem">
      <h4>Transparency</h4>
      <?= rich($c['transparency']) ?>
    </div>
  </div>
</section>

<section id="volunteer" class="section-lg" style="background:var(--white);border-block:1px solid var(--rule)">
  <div class="wrap">
    <div class="grid g-2" style="gap:3rem;align-items:start">
      <div>
        <span class="eyebrow">Volunteer</span>
        <h2 class="display-caps"><?= e($c['vol_headline']) ?></h2>
        <?= rich($c['vol_body']) ?>
        <div class="btn-row" style="margin-top:1.5rem">
          <a class="btn btn-primary" href="contact.html">Apply to volunteer</a>
        </div>
      </div>
      <div>
        <p><strong>We need</strong></p>
        <ul class="checklist">
          <?php foreach (lines($c['vol_list']) as $li): ?>
          <li><?= inline($li) ?></li>
          <?php endforeach; ?>
        </ul>
        <p class="small muted mb-0"><?= inline($c['vol_note']) ?></p>
      </div>
    </div>
  </div>
</section>

<section id="partner" class="section-lg">
  <div class="wrap">
    <div class="grid g-2" style="gap:3rem;align-items:start">
      <div>
        <span class="eyebrow">Partner</span>
        <h2 class="display-caps"><?= e($c['partner_headline']) ?></h2>
        <?= rich($c['partner_body']) ?>
        <div class="btn-row" style="margin-top:1.5rem">
          <a class="btn btn-primary" href="contact.html">Start a conversation</a>
        </div>
      </div>
      <div>
        <p><strong>Partnership options</strong></p>
        <ul class="checklist">
          <?php foreach (lines($c['partner_list']) as $li): ?>
          <li><?= inline($li) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
</section>

<section id="fundraise" class="redband">
  <div class="split">
    <div class="split-visual">
      <?= image_slot($c['fund_image'] ?? '', ['ratio' => '4 / 3', 'label' => 'Fundraising image', 'note' => 'A market stall, a donation box, a run. No survivor imagery.', 'class' => 'imgslot-fill']) ?>
    </div>
    <div class="split-body">
      <span class="eyebrow">Fundraise</span>
      <h2 class="display-caps"><?= e($c['fund_headline']) ?></h2>
      <?= rich($c['fund_body']) ?>
      <div class="btn-row" style="margin-top:1.75rem">
        <a class="btn btn-light" href="contact.html">Request a fundraising pack</a>
      </div>
    </div>
  </div>
</section>

<section id="careers" class="section-lg">
  <div class="wrap wrap-narrow">
    <span class="eyebrow">Careers</span>
    <h2 class="display-caps"><?= e($c['careers_headline']) ?></h2>
    <?= rich($c['careers_body']) ?>
  </div>
</section>
