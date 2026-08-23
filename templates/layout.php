<?php
/** The shared page shell: safety bar, masthead, footer, cover-tracks dialog. */
declare(strict_types=1);

require_once __DIR__ . '/partials/icons.php';

function nav_items(): array {
    return [
        ['index.html', 'index', 'Home'],
        ['get-help.html', 'get-help', 'Get Help'],
        ['programmes.html', 'programmes', 'Programmes'],
        ['about.html', 'about', 'About'],
        ['stories.html', 'stories', 'Stories'],
        ['get-involved.html', 'get-involved', 'Get Involved'],
        ['contact.html', 'contact', 'Contact'],
    ];
}

function render_layout(string $key, array $meta, string $body, array $s): string {
    $nav = '';
    foreach (nav_items() as [$href, $k, $label]) {
        $cur = $k === $key ? ' aria-current="page"' : '';
        $nav .= '<li><a href="' . $href . '"' . $cur . '>' . e($label) . '</a></li>' . "\n        ";
    }

    $exit = $s['decoy_url'] !== '' ? $s['decoy_url'] : 'https://www.bbc.com/weather';
    $wa   = preg_replace('/\D+/', '', (string)$s['whatsapp_number']);

    ob_start(); ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="referrer" content="no-referrer">
<title><?= e($meta['title']) ?></title>
<meta name="description" content="<?= e($meta['desc']) ?>">
<meta name="theme-color" content="#1E1B19">
<link rel="icon" href="assets/img/favicon.svg" type="image/svg+xml">
<link rel="preload" href="assets/fonts/playfair-normal-latin.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body data-exit-url="<?= e($exit) ?>">
<a class="skip-link" href="#main">Skip to content</a>

<!-- Persistent safety bar: on every page, above everything -->
<div class="safetybar">
  <div class="wrap">
    <div class="safetybar-info">
      <a href="tel:<?= e($s['helpline_tel']) ?>">Helpline<span class="hide-xs"> <?= e($s['helpline']) ?></span></a>
      <a href="https://wa.me/<?= e($wa) ?>" rel="noreferrer noopener">WhatsApp</a>
      <a href="#" data-open-tracks>Cover your tracks</a>
    </div>
    <button type="button" class="quick-exit" data-quick-exit aria-label="Quick exit — leave this site immediately">
      <?= icon('exit') ?>
      Quick exit
    </button>
  </div>
</div>

<header class="masthead">
  <div class="wrap masthead-inner">
    <a class="brand" href="index.html">
      <?= brand_mark() ?>
      <span>
        <span class="brand-name">Strandfold</span>
        <span class="brand-sub">Support Foundation</span>
      </span>
    </a>
    <button type="button" class="nav-toggle" data-nav-toggle aria-expanded="false" aria-controls="site-nav">
      <?= icon('menu') ?>
      Menu
    </button>
    <nav class="nav" id="site-nav" aria-label="Main">
      <ul>
        <?= $nav ?><li><a class="btn btn-primary btn-sm" href="get-involved.html#give">Donate</a></li>
      </ul>
    </nav>
  </div>
</header>

<main id="main">
<?= $body ?>
</main>

<footer class="footer">
  <div class="wrap">
    <div class="footer-grid">
      <div class="footer-brand">
        <?= brand_mark('mark', 'currentColor') ?>
        <div class="brand-name">Strandfold</div>
        <span class="brand-sub">Support Foundation</span>
        <p style="margin-top:1rem"><?= e($s['tagline']) ?></p>
      </div>
      <div>
        <h4>Get Help</h4>
        <ul>
          <li><a href="get-help.html">Talk to Someone</a></li>
          <li><a href="get-help.html#is-this-abuse">Is This Abuse?</a></li>
          <li><a href="get-help.html#safety-planning">Safety Planning</a></li>
          <li><a href="get-help.html#friends-family">For Friends &amp; Family</a></li>
          <li><a href="get-help.html#shelter">Find a Shelter</a></li>
        </ul>
      </div>
      <div>
        <h4>About</h4>
        <ul>
          <li><a href="about.html">Our Story</a></li>
          <li><a href="programmes.html">Programmes</a></li>
          <li><a href="about.html#team">Team</a></li>
          <li><a href="about.html#governance">Reports &amp; Accounts</a></li>
          <li><a href="about.html#governance">Safeguarding Policy</a></li>
        </ul>
      </div>
      <div>
        <h4>Get Involved</h4>
        <ul>
          <li><a href="get-involved.html#give">Donate</a></li>
          <li><a href="get-involved.html#volunteer">Volunteer</a></li>
          <li><a href="get-involved.html#partner">Partner</a></li>
          <li><a href="get-involved.html#fundraise">Fundraise</a></li>
          <li><a href="get-involved.html#careers">Careers</a></li>
        </ul>
      </div>
      <div>
        <h4>Connect</h4>
        <ul>
          <li><a href="contact.html">Contact us</a></li>
          <li><a href="faq.html">Frequently asked questions</a></li>
          <li><a href="#" rel="noreferrer noopener">[Social links]</a></li>
        </ul>
        <form class="newsletter" data-newsletter>
          <label for="nl-email" class="sr-only">Email address</label>
          <input id="nl-email" type="email" name="email" placeholder="Newsletter email" autocomplete="off">
          <button type="submit">Join</button>
        </form>
        <p class="small" data-newsletter-status hidden style="margin-top:.6rem"></p>
        <p class="small" style="margin-top:.6rem;color:#8A827C">We never name survivors in anything we send.</p>
      </div>
    </div>
    <div class="footer-bottom">
      <p style="margin:0"><?= e($s['org_name']) ?> is a registered non-profit <?= e($s['reg_number']) ?>.</p>
      <ul>
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="#">Terms</a></li>
        <li><a href="about.html#governance">Safeguarding</a></li>
        <li><a href="about.html#governance">Complaints</a></li>
        <li><a href="#" data-open-tracks>Cover your tracks</a></li>
      </ul>
      <p style="margin:1rem 0 0">© <?= date('Y') ?> <?= e($s['org_name']) ?>. All rights reserved.</p>
    </div>
  </div>
</footer>

<dialog class="sheet" id="cover-tracks" aria-labelledby="tracks-title">
  <button type="button" class="sheet-close" data-close-sheet aria-label="Close">×</button>
  <div class="sheet-inner">
    <span class="eyebrow">Your safety</span>
    <h2 id="tracks-title">Covering your tracks</h2>
    <p>A browser keeps a record of the pages you visit. If someone else can use your phone or computer, here is how to reduce what they can see.</p>
    <ol>
      <li><strong>Use private browsing next time.</strong> Chrome: menu → New Incognito tab. Safari: tabs → Private. Firefox: menu → New private window. Pages opened this way are not added to your history.</li>
      <li><strong>Clear your history now.</strong> Chrome: menu → History → Clear browsing data → choose <em>Browsing history</em> and <em>Cookies</em>. Safari on iPhone: Settings → Safari → Clear History and Website Data.</li>
      <li><strong>Delete only some entries if clearing everything would look strange.</strong> In the history list you can remove single pages and leave the rest.</li>
      <li><strong>Check what else is watching.</strong> Location sharing, shared cloud accounts, a linked tablet, or a family plan can show your activity even after you clear history.</li>
      <li><strong>Safest of all:</strong> use a phone or computer he has never had access to — a friend's, a library's, or one at work.</li>
    </ol>
    <p class="small"><strong>Quick exit:</strong> the red button at the top of every page leaves this site straight away and removes this page from the Back button. You can also press the <kbd>Esc</kbd> key twice quickly.</p>
    <p class="small">Clearing history is not a guarantee. If you believe your phone is being monitored, contact us from a different device.</p>
    <div class="btn-row" style="margin-top:1.25rem">
      <button type="button" class="btn btn-ghost btn-sm" data-close-sheet>Close</button>
      <button type="button" class="btn btn-primary btn-sm" data-quick-exit>Leave this site now</button>
    </div>
  </div>
</dialog>

<script src="assets/js/site.js"></script>
</body>
</html>
<?php
    return ob_get_clean();
}

/** The danger line that must sit beside every contact point. */
function danger_note(array $s, string $extra = ''): string {
    return '<div class="safety-note' . ($extra !== '' ? ' ' . $extra : '') . '">'
        . icon('warning')
        . '<p>If you are in immediate danger, stop reading and call emergency services on <strong>'
        . e($s['emergency']) . '</strong>. If you cannot speak safely, send us a message and we will follow your lead on how and when to respond.</p></div>';
}
