# Strandfold Support Foundation — website + CMS

A static public site with a small self-hosted PHP admin behind it. No database,
no framework, no third-party services.

**The public site is plain static HTML.** Editing in the admin rewrites the
`.html` files at the site root. Visitors never execute any PHP, so the site
stays fast on limited mobile data and keeps serving even if the admin breaks.

```
index.html … policies.html  generated pages — do not hand-edit
assets/                     css, js, self-hosted font, svg
uploads/                    images added through the admin        (server-owned)
content/                    JSON content + admin accounts         (server-owned)
admin/                      the CMS   → yoursite.org/admin/
  lib/schema.php            what is editable and what it defaults to
templates/                  page templates the renderer uses
cli/render.php              rebuild every page from content
```

## Using the admin

Go to `/admin/`. The first visit asks you to create an account; after that the
setup route refuses to run again.

| Screen | What it does |
|---|---|
| **Pages** | Every page. Edit, press *Save and publish*, and the static files are rewritten immediately. |
| **Site settings** | Helpline, WhatsApp, SMS, email, address, registration number, emergency number, Quick Exit destination. These appear on all nine pages at once. |
| **Images** | Upload, review and delete. Shows which pages use each image. |
| **Accounts** | One login per person, so a departing staff member can be removed without changing a shared password. |

Text fields take blank lines for paragraphs, `**bold**`, and
`[link text](https://…)`. That is the whole set, on purpose — a rich-text
editor would let anyone paste in a tracking pixel or an iframe, and on this
site a third-party request is a safety problem, not a performance one.

### Adding a field

Add it to `admin/lib/schema.php` and it appears in the admin automatically.
Then reference it in the matching file under `templates/pages/`.

## Safety features

| Requirement | Status |
|---|---|
| Quick Exit, fixed, every page | **Built.** `location.replace()` drops the page from Back history; double-`Esc` also fires it; screen blanks before navigating. Destination is editable in Site settings. |
| Cover-your-tracks notice | **Built.** Dialog linked from the safety bar, footer, Get Help and the FAQ. |
| No autoplay audio or video | **Built.** There is none anywhere, and the CMS cannot add any. |
| Discreet page titles | **Built,** and editable per page. Get Help is "Resources — Strandfold". |
| No survivor faces without consent | **Built.** Every story carries a consent record; the renderer skips any story whose record is empty, so a story cannot reach the public site by accident. Clearing the record unpublishes it at the next save. |
| WhatsApp / SMS contact | **Built,** driven by Site settings. |
| Text-only form, no email trail | **Built** front-end. Set the endpoint under Contact → *Form endpoint URL*. Until it is set the form tells visitors to use WhatsApp or SMS rather than failing silently. |
| Mobile-first, low data | **Built.** One stylesheet, one self-hosted font, no third-party requests. |

Three further protections the brief did not ask for but this kind of site needs:

- **Uploads are re-encoded through GD**, which destroys EXIF metadata. A phone
  photo can carry GPS coordinates; publishing one could disclose a shelter
  location. Verified: an image with embedded GPS tags comes out the other side
  with the EXIF block gone.
- **A Content-Security-Policy of `default-src 'self'`** in `.htaccess`. A future
  edit cannot quietly add analytics or a chat widget that would reveal who is
  reading a domestic-abuse site.
- **`uploads/` cannot execute PHP**, and `content/` is unreachable over HTTP —
  it holds the password hashes.

## Deploying

cPanel Git Version Control runs `.cpanel.yml` on *Deploy HEAD Commit*. It copies
the code, then rebuilds the pages **from the server's own content**.

`content/` and `uploads/` are never touched by a deploy, so a deploy can never
revert what staff have edited. That also means **they are not in git** — back
those two directories up from cPanel, not from GitHub. They hold every edit and
every image, and nothing else has a copy.

Generated `.html` files are also not in git; the deploy renders them.

Saving a page in the admin stores every field, including ones nobody touched,
which would otherwise freeze old defaults in place. Each deploy therefore runs
`cli/upgrade-content.php`: any saved value that exactly matches a default the
schema once had (recorded in `cli/default-history.json`, rebuilt from git on
deploy) is dropped so the current default shows. Real edits match no old
default and are never changed. Preview with `php cli/upgrade-content.php --dry`.

Local preview — note the router, which reproduces the clean-URL rewrite that
Apache does in production:

```bash
php -S 127.0.0.1:8799 cli/router.php   # http://127.0.0.1:8799/ and /admin/
php cli/render.php                     # rebuild after editing content JSON by hand
```

URLs are extensionless: `/get-help`, not `/get-help.html`. The `.html` files
still exist on disk — `.htaccess` maps the clean URL onto them and 301s any
old `.html` address across, so existing links and bookmarks keep working.
This relies on `mod_rewrite`, which cPanel has on by default.

Requires PHP 8 with the GD extension. Both are standard on cPanel; if GD is off,
uploads are refused with an explanation rather than silently storing metadata.

## What you must still supply

Everything below shows on the site in `[square brackets]`. Most are now editable
in the admin rather than in code.

1. **Helpline numbers and hours** — Site settings. Nothing else matters if this
   is wrong. Set both the displayed number and the dialled one.
2. **Emergency services number** — Site settings.
3. **Registration and regulator details** — Site settings; required before
   taking donations.
4. **Verified statistics with sources** — Homepage → Statistics. Delete a row
   rather than estimating.
5. **Real unit costs** — Get Involved → Donation table.
6. **Founding story specifics** — About.
7. **Team names and credentials** — About → Team members.
8. **Relevant legislation** — no longer needed while legal support is not offered.
9. **Policies** — public summaries are on `/policies` (Pages → Policies).
   Still needed there: the Executive Director's email, the Board Chair's
   address for whistleblowing reports, and the date the board last reviewed
   them. The intake form, consent form, referral sheet and case-management SOP
   are internal documents — never publish them on the site.
10. **Online donation links** — Get Involved. Bank transfer details are already
    there; the Give once / Give monthly buttons stay hidden until a link is set.
11. **Consented survivor stories** — Stories. Each needs a signed, revocable
    consent form on file before it will publish.
12. **Vetted shelter partner list** — held internally, never published. Do not
    put it in this repo.
13. **Live domain** — set to `https://strandoldsf.com` in Site settings
    and `robots.txt`. Change both if the site goes live elsewhere.

## Accessibility

Works with JavaScript disabled: navigation, accordions and all content remain
usable. Quick Exit and the cover-tracks dialog need JavaScript, which is why the
dialog's advice is repeated in plain text in the FAQ. The admin needs JavaScript
only to add and remove repeated entries.

Layout tested at 360, 500, 720, 1000 and 1280px.
