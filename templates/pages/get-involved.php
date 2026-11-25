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

    <?php
      $once    = trim($c['give_once_url']);
      $monthly = trim($c['give_monthly_url']);
      $once    = $once === '#' ? '' : $once;
      $monthly = $monthly === '#' ? '' : $monthly;
    ?>
    <?php if ($once !== '' || $monthly !== ''): ?>
    <div class="btn-row" style="margin-top:1.75rem">
      <?php if ($once !== ''): ?><a class="btn btn-primary" href="<?= e($once) ?>">Give once</a><?php endif; ?>
      <?php if ($monthly !== ''): ?><a class="btn btn-ghost" href="<?= e($monthly) ?>">Give monthly</a><?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="grid g-2" style="gap:1.5rem;margin-top:2.5rem;align-items:start">
      <?php if (trim($c['bank_account_number']) !== ''): ?>
      <div class="callout" id="bank-transfer">
        <h3>Give by bank transfer</h3>
        <p class="mb-0">
          <strong>Account name:</strong> <?= e($c['bank_account_name']) ?><br>
          <strong>Bank:</strong> <?= e($c['bank_name']) ?><br>
          <strong>Account number:</strong> <span class="val-lg"><?= e($c['bank_account_number']) ?></span>
        </p>
      </div>
      <?php endif; ?>
      <div class="callout">
        <h3>In-kind donations and enquiries</h3>
        <?= rich($c['inkind_note']) ?>
        <p class="mb-0">
          <strong>Phone:</strong> <a href="tel:<?= e($s['phone_tel']) ?>"><?= e($s['phone']) ?></a><br>
          <strong>Email:</strong> <a href="mailto:<?= e($s['email']) ?>"><?= e($s['email']) ?></a>
        </p>
      </div>
    </div>

    <div class="callout" style="margin-top:2.5rem">
      <h3>Transparency</h3>
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
          <a class="btn btn-primary" href="/contact">Apply to volunteer</a>
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
          <a class="btn btn-primary" href="/contact">Start a conversation</a>
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
        <a class="btn btn-light" href="/contact">Request a fundraising pack</a>
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
