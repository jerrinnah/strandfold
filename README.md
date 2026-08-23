# Strandfold Support Foundation — website

Static HTML/CSS/JS. No framework, no build dependencies, no tracking, no third-party
scripts. Total weight per page is roughly 25–35 kB plus one webfont — deliberate, because
most visitors will arrive on a phone with limited data.

```
index.html  get-help.html  programmes.html  about.html
stories.html  get-involved.html  faq.html  contact.html
assets/css/style.css     one stylesheet
assets/js/site.js        quick exit, cover-your-tracks, nav, contact form
assets/img/*.svg         woven textures + favicon (no photographs)
src/*.html               page bodies — EDIT THESE
tools/build.js           wraps each src file in the shared header/footer
```

## Editing

Header, footer, safety bar and the cover-your-tracks dialog live in `tools/build.js`.
Page content lives in `src/`. After any change:

```bash
node tools/build.js      # regenerates the eight .html files at the root
```

Preview locally:

```bash
python3 -m http.server 8788     # then open http://localhost:8788
```

Deploying is a file copy — any static host works (Netlify, Vercel, Cloudflare Pages,
GitHub Pages, or plain shared hosting). Serve over HTTPS.

If the build script is ever lost, the generated `.html` files are complete and
hand-editable on their own.

---

## Safety features — what is built and what still needs wiring

| Requirement | Status | Where |
|---|---|---|
| Quick Exit, fixed, every page | **Built.** Uses `location.replace()` so the page is dropped from Back history. Also fires on double-`Esc`. | `assets/js/site.js`, `tools/build.js` |
| Cover-your-tracks notice | **Built.** Dialog linked from the safety bar, the footer, Get Help and the FAQ. | dialog in `tools/build.js` |
| No autoplay audio or video | **Built.** There is no audio or video anywhere on the site. | — |
| Discreet page titles | **Built.** Get Help is "Resources — Strandfold", FAQ is "Questions — Strandfold", Contact is "Contact — Strandfold". | `src/*.html` META blocks |
| No survivor faces | **Built.** There is not a single photograph on the site. All imagery is abstract woven-thread SVG. | `assets/img/` |
| WhatsApp / SMS contact | **Built.** `wa.me` and `sms:` links on the safety bar, Get Help, Contact and the homepage. | — |
| Text-only form, no email trail | **Built** front-end; **needs a backend.** No autocomplete, no confirmation email, fields wipe on tab-hide and page-hide. | `FORM_ENDPOINT` in `assets/js/site.js` |
| Mobile-first, low data | **Built.** Single stylesheet, one deferred webfont, SVG only. | — |
| Danger line near every contact point | **Built.** | homepage hero, Get Help, Contact, FAQ |

### Two things to decide before launch

1. **Quick-exit destination.** `DECOY_URL` at the top of `assets/js/site.js` is
   `https://www.bbc.com/weather`. Change it to whatever is most plausibly "already open"
   for a Nigerian user — a local news site or a search results page.
2. **Contact form endpoint.** Set `FORM_ENDPOINT` in `assets/js/site.js`. Until it is set,
   the form tells the visitor to use WhatsApp or SMS instead rather than failing silently.
   The endpoint **must not** send a confirmation email to the sender — that is the whole
   point of the text-only form. Store submissions behind staff authentication only.

Do not add Google Analytics, Meta Pixel, chat widgets, or embedded video. Each one is a
third party that can see who visited a domestic-abuse site, and several of them are
readable from a shared household account.

---

## What you must supply before launch

In priority order. Everything below appears on the site in `[square brackets]` —
search the `src/` folder and `tools/build.js` for `[` to find every one.

1. **Helpline numbers and operating hours.** Nothing else matters if this is wrong.
   Appears in: safety bar (`tools/build.js`), homepage hero, Get Help, Contact, footer.
   Also update the `tel:`, `sms:` and `wa.me` hrefs — they currently point at
   `+000000000000` placeholders.
2. **Emergency services number** for the "immediate danger" line.
3. **Registration / regulator details** — required before you can take donations.
   `about.html#governance` and the footer bottom bar.
4. **Verified statistics with sources and dates.** The homepage stats band is bracketed on
   purpose. Do not publish an estimate. If a figure cannot be cited, delete that column.
5. **Vetted shelter partner list** — held internally, never published. The site says so.
6. **Real unit costs** for the donation table on `get-involved.html`.
7. **Team names, credentials, and photographs** (staff only, with consent).
8. **Founding story specifics** — year, founder, founding moment (`about.html`).
9. **Relevant legislation** for `programmes.html#standing-ground` (e.g. VAPP Act, state
   family law).
10. **Safeguarding, confidentiality and complaints policies** — these should exist as
    documents before the site goes live, not after. Link them from `about.html#governance`.
11. **Consented survivor stories** — with signed, revocable consent forms on file. The
    three cards on `stories.html` are templates, not content.
12. **Donation processor** — the Give Once / Give Monthly buttons currently point at `#`.
13. **Live domain** in `sitemap.xml` and `robots.txt`.

## Accessibility and browser support

Works with JavaScript disabled: navigation, accordions (`<details>`), and all content
remain usable. Quick Exit and the cover-tracks dialog need JavaScript, which is why the
dialog content is also duplicated in plain language in the FAQ.

Tested layout breakpoints: 360, 500, 720, 1000, 1280+.
