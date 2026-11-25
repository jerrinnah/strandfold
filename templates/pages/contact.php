<?php declare(strict_types=1);
$wa = preg_replace('/\D+/', '', $s['whatsapp_number']);
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

<section class="section-lg">
  <div class="wrap">
    <div class="grid g-2" style="gap:3rem;align-items:start">

      <div>
        <span class="eyebrow">Write to us</span>
        <h2 class="display-caps"><?= e($c['form_headline']) ?></h2>

        <div class="safety-note" style="margin:1.25rem 0 1.75rem">
          <?= icon('warning') ?>
          <div><?= rich($c['form_warning']) ?></div>
        </div>

        <form class="form" data-safe-form autocomplete="off" novalidate<?= trim($c['form_endpoint']) !== '' ? ' data-endpoint="' . e(trim($c['form_endpoint'])) . '"' : '' ?>>
          <div class="field">
            <label for="f-name">Name <span class="label-opt">(optional)</span></label>
            <span class="hint">A first name, an initial, or nothing at all.</span>
            <input id="f-name" name="name" type="text" autocomplete="off" spellcheck="false">
          </div>
          <div class="field">
            <label for="f-method">Preferred contact method</label>
            <select id="f-method" name="method">
              <option value="">Choose one</option>
              <option>WhatsApp only</option>
              <option>SMS only</option>
              <option>Phone call</option>
              <option>Email</option>
              <option>Do not contact me — I will contact you again</option>
            </select>
          </div>
          <div class="field">
            <label for="f-contact">Where to reach you <span class="label-opt">(optional)</span></label>
            <span class="hint">Leave blank if you would rather contact us again yourself.</span>
            <input id="f-contact" name="contact" type="text" autocomplete="off" spellcheck="false">
          </div>
          <div class="field">
            <label for="f-times">Safe times to contact you</label>
            <span class="hint">For example: weekday mornings, or only between 9am and 11am.</span>
            <input id="f-times" name="safe_times" type="text" autocomplete="off">
          </div>
          <div class="field">
            <label for="f-message">Your message</label>
            <span class="hint">Share as much or as little as you want. You do not need proof and you do not need a plan.</span>
            <textarea id="f-message" name="message" required></textarea>
          </div>
          <div class="btn-row">
            <button type="submit" class="btn btn-primary">Send message</button>
            <button type="reset" class="btn btn-ghost">Clear this form</button>
          </div>
        </form>

        <p class="form-status" data-form-status hidden></p>
        <p class="small muted mt-2"><?= inline($c['form_note']) ?></p>
      </div>

      <div class="stack">
        <div class="callout">
          <h4>Helpline</h4>
          <p><a class="val-lg" href="tel:<?= e($s['helpline_tel']) ?>"><?= e($s['helpline']) ?></a><br>Available <?= e($s['helpline_hours']) ?></p>
          <p class="mb-0"><strong>WhatsApp:</strong> <a href="https://wa.me/<?= e($wa) ?>" rel="noreferrer noopener"><?= e($s['whatsapp']) ?></a><br>
          <strong>SMS:</strong> <a href="sms:<?= e($s['sms_tel']) ?>"><?= e($s['sms']) ?></a></p>
        </div>
        <div class="callout">
          <h4>Office</h4>
          <p class="mb-0"><?= inline($s['address']) ?><br><?= e($s['office_hours']) ?></p>
        </div>
        <div class="callout">
          <h4>General enquiries</h4>
          <p class="mb-0"><a href="tel:<?= e($s['phone_tel']) ?>"><?= e($s['phone']) ?></a><br>
          <a href="mailto:<?= e($s['email']) ?>"><?= e($s['email']) ?></a></p>
        </div>
        <div class="callout" id="media">
          <h4>Media enquiries</h4>
          <p><a href="mailto:<?= e($s['email_media']) ?>"><?= e($s['email_media']) ?></a></p>
          <div class="mb-0"><?= rich($c['media_note']) ?></div>
        </div>
        <div class="callout">
          <h4>Volunteering, partnership and fundraising</h4>
          <p class="mb-0">Use the form, and say which one in your message. <a href="/get-involved">What we need</a>.</p>
        </div>
        <?= danger_note($s) ?>
      </div>

    </div>
  </div>
</section>
