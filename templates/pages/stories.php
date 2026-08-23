<?php declare(strict_types=1);
/* A story renders only when a consent record exists. This is enforced here as
   well as in the admin, so a story can never reach the public site by accident. */
$publishable = array_values(array_filter($c['stories'], 'story_publishable'));
$withheld = count($c['stories']) - count($publishable);
?>
<section class="pagehead">
  <div class="wrap">
    <div class="pagehead-inner">
      <span class="eyebrow"><?= e($c['hero_eyebrow']) ?></span>
      <h1><?= e($c['hero_headline']) ?></h1>
      <div class="lede"><?= rich($c['hero_lede']) ?></div>
    </div>
  </div>
</section>

<section class="section tight" style="padding-top:2.5rem">
  <div class="wrap">
    <div class="consent-note">
      <?= rich($c['consent_note']) ?>
      <p class="mb-0"><a class="linkarrow" href="contact.html">Withdraw consent for a story</a></p>
    </div>
  </div>
</section>

<?php if ($publishable): ?>
<section class="section-lg tight">
  <div class="wrap">
    <div class="grid g-3">
      <?php foreach ($publishable as $st): ?>
      <article class="story">
        <?= image_slot($st['image'] ?? '', ['ratio' => '3 / 2', 'label' => 'Story image', 'note' => 'Never her face unless she asked to be photographed.', 'class' => 'imgslot-card']) ?>
        <svg class="strandline" viewBox="0 0 46 6" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M0 3c8 0 8-2 15-2s7 4 15 4 8-2 16-2"/></svg>
        <?php $tags = array_filter(array_map('trim', explode(',', (string)($st['tags'] ?? '')))); ?>
        <?php if ($tags): ?>
        <ul class="tags"><?php foreach ($tags as $t): ?><li><?= e($t) ?></li><?php endforeach; ?></ul>
        <?php endif; ?>
        <h3>&ldquo;<?= e($st['headline'] ?? '') ?>&rdquo;</h3>
        <?= rich($st['body'] ?? '') ?>
        <?php if (trim((string)($st['quote'] ?? '')) !== ''): ?>
        <p class="story-quote">&ldquo;<?= e($st['quote']) ?>&rdquo;</p>
        <?php endif; ?>
        <p class="story-meta">Name and details changed · Shared with written consent</p>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php else: ?>
<section class="section-lg tight">
  <div class="wrap wrap-narrow">
    <div class="callout">
      <h4>No stories published yet</h4>
      <p class="mb-0">Stories appear here once a signed consent record has been entered against each one. Until then this page shows the consent policy and nothing else — which is the correct state for a site that has not yet collected consent forms.</p>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($withheld > 0): ?>
<!-- <?= $withheld ?> story/stories are saved but withheld from publication: no consent record. -->
<?php endif; ?>

<section class="section-lg" style="background:var(--white);border-block:1px solid var(--rule)">
  <div class="wrap">
    <div class="grid g-2" style="gap:3rem;align-items:start">
      <div>
        <span class="eyebrow">How we write these</span>
        <h2 class="display-caps"><?= e($c['rules_headline']) ?></h2>
        <?= rich($c['rules_body']) ?>
      </div>
      <div>
        <p><strong>Never included</strong></p>
        <ul class="checklist">
          <?php foreach (lines($c['rules_list']) as $li): ?>
          <li><?= inline($li) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
</section>

<section class="section-lg">
  <div class="wrap wrap-narrow center">
    <h2 class="display-caps">Journalists and researchers</h2>
    <p class="lede mx-auto">We do not connect journalists directly to survivors under any circumstances. We are glad to talk about the work, the data and the policy gaps.</p>
    <div class="btn-row" style="justify-content:center">
      <a class="btn btn-ghost" href="contact.html#media">Media enquiries</a>
    </div>
  </div>
</section>
