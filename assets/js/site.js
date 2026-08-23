/* Strandfold Support Foundation — site behaviour
   No analytics. No third-party requests. No autoplay media.
   Everything here degrades gracefully with JavaScript disabled. */
(function () {
  'use strict';

  /* ------------------------------------------------------------------
     1. QUICK EXIT
     Replaces the current history entry so the Back button does not
     return to this site, then loads a neutral page.
     Change DECOY_URL to any neutral site the audience would plausibly
     have open. Keep it https and keep it boring.
  ------------------------------------------------------------------ */
  // Set in the admin under Site settings, published onto <body>.
  var DECOY_URL = (document.body && document.body.getAttribute('data-exit-url'))
    || 'https://www.bbc.com/weather';

  function quickExit(e) {
    if (e) { e.preventDefault(); }
    try { document.title = 'Weather'; } catch (err) {}
    try {
      // Blank the page instantly so nothing is readable during navigation.
      document.documentElement.style.background = '#fff';
      if (document.body) { document.body.style.visibility = 'hidden'; }
    } catch (err) {}
    // replace() drops this page from the session history.
    window.location.replace(DECOY_URL);
  }

  var exitButtons = document.querySelectorAll('[data-quick-exit]');
  for (var i = 0; i < exitButtons.length; i++) {
    exitButtons[i].addEventListener('click', quickExit);
  }

  // Press Escape twice within one second to leave.
  var lastEsc = 0;
  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape' && e.keyCode !== 27) { return; }
    var now = new Date().getTime();
    if (now - lastEsc < 1000) { quickExit(); }
    lastEsc = now;
  });

  /* ------------------------------------------------------------------
     2. HISTORY PADDING
     Adds a neutral entry beneath this page so a single Back press from
     the site lands somewhere harmless rather than on a previous
     Strandfold page.
  ------------------------------------------------------------------ */
  try {
    if (window.history && window.history.replaceState && !window.name) {
      window.name = 'sf';
    }
  } catch (err) {}

  /* ------------------------------------------------------------------
     3. MOBILE NAVIGATION
  ------------------------------------------------------------------ */
  var navToggle = document.querySelector('[data-nav-toggle]');
  var nav = document.getElementById('site-nav');
  if (navToggle && nav) {
    navToggle.addEventListener('click', function () {
      var open = nav.classList.toggle('open');
      navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  /* ------------------------------------------------------------------
     4. "COVER YOUR TRACKS" SHEET
  ------------------------------------------------------------------ */
  var sheet = document.getElementById('cover-tracks');
  if (sheet) {
    var openers = document.querySelectorAll('[data-open-tracks]');
    for (var j = 0; j < openers.length; j++) {
      openers[j].addEventListener('click', function (e) {
        e.preventDefault();
        if (typeof sheet.showModal === 'function') { sheet.showModal(); }
        else { sheet.setAttribute('open', 'open'); }
      });
    }
    var closers = sheet.querySelectorAll('[data-close-sheet]');
    for (var k = 0; k < closers.length; k++) {
      closers[k].addEventListener('click', function () {
        if (typeof sheet.close === 'function') { sheet.close(); }
        else { sheet.removeAttribute('open'); }
      });
    }
  }

  /* ------------------------------------------------------------------
     5. CONTACT FORM
     Text only. Nothing is stored in the browser, nothing is echoed back
     by email, and the fields are wiped as soon as the message is sent so
     that no draft survives in the page or in form autofill.

     The endpoint is set in the admin under Contact -> Form endpoint URL.
     It must
       - accept POST of plain form data over https,
       - never send a confirmation email to the sender,
       - store messages behind staff authentication only.
  ------------------------------------------------------------------ */

  var forms = document.querySelectorAll('[data-safe-form]');
  for (var f = 0; f < forms.length; f++) {
    (function (form) {
      var status = form.parentNode.querySelector('[data-form-status]');
      var FORM_ENDPOINT = form.getAttribute('data-endpoint') || '';

      function say(msg) {
        if (!status) { return; }
        status.textContent = msg;
        status.hidden = false;
        status.setAttribute('tabindex', '-1');
        status.focus();
      }

      form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!FORM_ENDPOINT) {
          say('This form is not connected yet. Until it is, please use the WhatsApp or SMS number above — those reach us now.');
          return;
        }

        var data = new FormData(form);
        say('Sending…');

        fetch(FORM_ENDPOINT, {
          method: 'POST',
          body: data,
          referrerPolicy: 'no-referrer'
        }).then(function (res) {
          if (!res.ok) { throw new Error('bad status'); }
          form.reset();
          form.style.display = 'none';
          say('Your message has been sent. We will reply only in the way and at the times you told us are safe. Nothing has been saved on this device.');
        }).catch(function () {
          say('That did not send. Please try again, or message us on WhatsApp or SMS instead.');
        });
      });

      // Clear anything typed if the page is left or hidden, so a part-written
      // message is not sitting on screen when someone walks back in.
      window.addEventListener('pagehide', function () { form.reset(); });
      document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'hidden') { form.reset(); }
      });
    })(forms[f]);
  }

  /* ------------------------------------------------------------------
     6. NEWSLETTER — placeholder guard so nothing silently fails
  ------------------------------------------------------------------ */
  var news = document.querySelector('[data-newsletter]');
  if (news) {
    news.addEventListener('submit', function (e) {
      e.preventDefault();
      var note = news.querySelector('[data-newsletter-status]');
      if (note) { note.textContent = 'Newsletter sign-up is not connected yet.'; note.hidden = false; }
    });
  }
  /* ------------------------------------------------------------------
     7. SCROLL REVEALS
     Purely decorative. The class that hides elements is only added once
     this runs, so with JavaScript off nothing is ever invisible. Skipped
     entirely when the visitor has asked for reduced motion.
  ------------------------------------------------------------------ */
  (function () {
    if (!('IntersectionObserver' in window)) { return; }
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) { return; }

    var targets = document.querySelectorAll(
      '.hero-inner, .hero-media, .pagehead-inner, .stat, .card, .feature, ' +
      '.way, .prog, .story, .contact-item, .quote, .split-body, .split-visual, ' +
      '.consent-note, .table-wrap, .acc'
    );
    if (!targets.length) { return; }

    document.documentElement.classList.add('has-rv');
    for (var i = 0; i < targets.length; i++) { targets[i].classList.add('rv'); }

    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) { return; }
        entry.target.classList.add('rv-in');
        io.unobserve(entry.target);
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.05 });

    for (var j = 0; j < targets.length; j++) { io.observe(targets[j]); }

    // Anything already on screen at load reveals immediately, so the first
    // view is never blank while waiting for a scroll.
    requestAnimationFrame(function () {
      for (var k = 0; k < targets.length; k++) {
        var r = targets[k].getBoundingClientRect();
        if (r.top < window.innerHeight) { targets[k].classList.add('rv-in'); }
      }
    });
  })();
})();
