<?php
/**
 * Strandfold admin — single front controller.
 *
 * Editing content here rewrites the static .html files at the site root. The
 * public site never executes any of this code.
 */
declare(strict_types=1);

require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/lib/forms.php';
require_once __DIR__ . '/lib/render.php';

session_start_secure();

$route  = (string)($_GET['r'] ?? 'dashboard');
$notice = null;
$error  = null;

/* First run: no accounts exist yet, so the only route is setup. */
if (!users_all() && $route !== 'setup') redirect('index.php?r=setup');

switch ($route) {

    /* ------------------------------------------------------------ setup */
    case 'setup':
        if (users_all()) redirect('index.php?r=login');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            [$ok, $msg] = user_create(
                (string)($_POST['username'] ?? ''),
                (string)($_POST['password'] ?? ''),
                (string)($_POST['name'] ?? '')
            );
            if ($ok) redirect('index.php?r=login&new=1');
            $error = $msg;
        }
        view_setup($error);
        break;

    /* ------------------------------------------------------------ login */
    case 'login':
        if (current_user()) redirect('index.php?r=dashboard');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            [$ok, $msg] = login((string)($_POST['username'] ?? ''), (string)($_POST['password'] ?? ''));
            if ($ok) redirect('index.php?r=dashboard');
            $error = $msg;
        }
        view_login($error, isset($_GET['timeout']), isset($_GET['new']));
        break;

    case 'logout':
        logout();
        redirect('index.php?r=login');

    /* -------------------------------------------------------- dashboard */
    case 'dashboard':
        require_login();
        view_dashboard();
        break;

    /* ------------------------------------------------------------- page */
    case 'page':
        $user = require_login();
        $key  = (string)($_GET['p'] ?? '');
        $schema = schema_pages()[$key] ?? null;
        if (!$schema) { http_response_code(404); exit('No such page.'); }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_check();
            $data = collect_post($schema);
            $data['_title'] = trim((string)($_POST['_title'] ?? $schema['title']));
            $data['_desc']  = trim((string)($_POST['_desc'] ?? $schema['desc']));
            if (!content_save($key, $data)) {
                $error = 'Could not write content/' . $key . '.json — check that the content folder is writable (755).';
            } else {
                [$written, $errs] = render_all();
                $error  = $errs ? implode(' ', $errs) : null;
                $notice = $errs ? null : 'Saved, and ' . count($written) . ' pages rebuilt.';
            }
        }
        view_page_form($key, $schema, $notice, $error);
        break;

    /* ------------------------------------------------------------- site */
    case 'site':
        require_login();
        $schema = schema_site();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_check();
            $data = [];
            foreach ($schema['fields'] as $k => $def) $data[$k] = normalise_value($_POST[$k] ?? '', $def);
            if (($data['decoy_url'] ?? '') !== '' && !str_starts_with($data['decoy_url'], 'https://')) {
                $error = 'The Quick Exit destination must start with https:// — an insecure decoy page can be tampered with in transit.';
            } elseif (!content_save('site', $data)) {
                $error = 'Could not write content/site.json — check folder permissions.';
            } else {
                [$written, $errs] = render_all();
                $error  = $errs ? implode(' ', $errs) : null;
                $notice = $errs ? null : 'Saved, and ' . count($written) . ' pages rebuilt.';
            }
        }
        view_site_form($schema, $notice, $error);
        break;

    /* ------------------------------------------------------------ media */
    case 'media':
        require_login();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_check();
            if (($_POST['do'] ?? '') === 'delete') {
                [$ok, $msg] = media_delete((string)($_POST['file'] ?? ''));
                if ($ok) { render_all(); $notice = $msg . ' Pages rebuilt.'; } else { $error = $msg; }
            } else {
                [$ok, $msg] = media_store($_FILES['image'] ?? []);
                $ok ? $notice = $msg : $error = $msg;
            }
        }
        view_media($notice, $error);
        break;

    /* ------------------------------------------------------------ users */
    case 'users':
        $user = require_login();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_check();
            $do = (string)($_POST['do'] ?? '');
            if ($do === 'add') {
                [$ok, $msg] = user_create((string)($_POST['username'] ?? ''), (string)($_POST['password'] ?? ''), (string)($_POST['name'] ?? ''));
            } elseif ($do === 'password') {
                [$ok, $msg] = user_set_password((string)($_POST['username'] ?? ''), (string)($_POST['password'] ?? ''));
            } elseif ($do === 'delete') {
                $target = (string)($_POST['username'] ?? '');
                if (strcasecmp($target, $user['username']) === 0) {
                    [$ok, $msg] = [false, 'You cannot delete the account you are signed in with.'];
                } else {
                    [$ok, $msg] = user_delete($target);
                }
            } else {
                [$ok, $msg] = [false, 'Unknown action.'];
            }
            $ok ? $notice = $msg : $error = $msg;
        }
        view_users($notice, $error);
        break;

    /* ----------------------------------------------------------- republish */
    case 'republish':
        require_login();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_check();
            [$written, $errs] = render_all();
            $_SESSION['flash'] = $errs ? implode(' ', $errs) : count($written) . ' pages rebuilt from the current content.';
        }
        redirect('index.php?r=dashboard');

    default:
        http_response_code(404);
        exit('Not found.');
}

/* ====================================================================== */
/* Views                                                                  */
/* ====================================================================== */

function admin_head(string $title, bool $chrome = true): void {
    $u = current_user();
    ?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<meta name="referrer" content="no-referrer">
<title><?= e($title) ?> — Strandfold admin</title>
<link rel="icon" href="../assets/img/favicon.svg" type="image/svg+xml">
<link rel="stylesheet" href="assets/admin.css">
</head>
<body class="<?= $chrome ? '' : 'plain' ?>">
<?php if ($chrome && $u): ?>
<header class="abar">
  <div class="abar-in">
    <a class="abrand" href="index.php?r=dashboard">Strandfold <span>admin</span></a>
    <nav class="anav">
      <a href="index.php?r=dashboard">Pages</a>
      <a href="index.php?r=site">Site settings</a>
      <a href="index.php?r=media">Images</a>
      <a href="index.php?r=users">Accounts</a>
      <a href="../index.html" target="_blank" rel="noopener">View site ↗</a>
    </nav>
    <span class="awho"><?= e($u['name']) ?> · <a href="index.php?r=logout">Sign out</a></span>
  </div>
</header>
<?php endif; ?>
<main class="awrap">
<?php
}

function admin_foot(): void {
    echo '</main><script src="assets/admin.js"></script></body></html>';
}

function flash(?string $notice, ?string $error): void {
    if (!empty($_SESSION['flash'])) { $notice = $notice ?: $_SESSION['flash']; unset($_SESSION['flash']); }
    if ($error)  echo '<p class="msg msg-err">' . e($error) . '</p>';
    if ($notice) echo '<p class="msg msg-ok">' . e($notice) . '</p>';
}

function view_setup(?string $error): void {
    admin_head('Set up', false);
    ?>
    <div class="card-auth">
      <h1>Create the first account</h1>
      <p>Nobody can reach the admin until this account exists. There is no password reset by email on this site — an email trail is a liability here — so store this password in a password manager now.</p>
      <?php flash(null, $error); ?>
      <form method="post" autocomplete="off">
        <div class="fld"><label for="name">Your name</label><input id="name" name="name" type="text" required></div>
        <div class="fld"><label for="username">Username</label><input id="username" name="username" type="text" required autocapitalize="none"></div>
        <div class="fld">
          <label for="password">Password</label>
          <p class="fld-help">At least 12 characters. Four unrelated words beat a short password with symbols.</p>
          <input id="password" name="password" type="password" required minlength="12">
        </div>
        <button class="btn-primary" type="submit">Create account</button>
      </form>
    </div>
    <?php
    admin_foot();
}

function view_login(?string $error, bool $timeout, bool $isNew): void {
    admin_head('Sign in', false);
    ?>
    <div class="card-auth">
      <h1>Strandfold admin</h1>
      <?php if ($isNew) echo '<p class="msg msg-ok">Account created. Sign in below.</p>'; ?>
      <?php if ($timeout) echo '<p class="msg msg-warn">You were signed out after two hours of inactivity.</p>'; ?>
      <?php flash(null, $error); ?>
      <form method="post" autocomplete="off">
        <div class="fld"><label for="username">Username</label><input id="username" name="username" type="text" required autocapitalize="none"></div>
        <div class="fld"><label for="password">Password</label><input id="password" name="password" type="password" required></div>
        <button class="btn-primary" type="submit">Sign in</button>
      </form>
      <p class="fld-note">Five wrong attempts locks this address out for fifteen minutes.</p>
    </div>
    <?php
    admin_foot();
}

function view_dashboard(): void {
    admin_head('Pages');
    flash(null, null);
    ?>
    <h1>Pages</h1>
    <p class="lede-a">Edit a page and it is saved, then every static file is rebuilt straight away. Changes are live the moment you see “Saved”.</p>
    <div class="tiles">
      <?php foreach (schema_pages() as $key => $p):
        $c = content_load($key);
        $edited = $c ? 'Edited' : 'Not yet edited';
      ?>
      <a class="tile" href="index.php?r=page&amp;p=<?= e($key) ?>">
        <span class="tile-name"><?= e($p['label']) ?></span>
        <span class="tile-file"><?= e($p['file']) ?></span>
        <span class="tile-state <?= $c ? 'on' : '' ?>"><?= e($edited) ?></span>
      </a>
      <?php endforeach; ?>
    </div>

    <h2>Everything else</h2>
    <div class="tiles">
      <a class="tile" href="index.php?r=site"><span class="tile-name">Site settings</span><span class="tile-file">Helpline, WhatsApp, address, Quick Exit</span></a>
      <a class="tile" href="index.php?r=media"><span class="tile-name">Images</span><span class="tile-file"><?= count(media_all()) ?> uploaded</span></a>
      <a class="tile" href="index.php?r=users"><span class="tile-name">Accounts</span><span class="tile-file"><?= count(users_all()) ?> user(s)</span></a>
    </div>

    <form method="post" action="index.php?r=republish" class="mt">
      <?= csrf_field() ?>
      <button class="btn-ghost" type="submit">Rebuild all pages</button>
      <span class="fld-note">Use this after editing content JSON by hand, or after a deploy.</span>
    </form>
    <?php
    admin_foot();
}

function view_page_form(string $key, array $schema, ?string $notice, ?string $error): void {
    $c = page_values($key);
    admin_head($schema['label']);
    ?>
    <p class="crumb"><a href="index.php?r=dashboard">Pages</a> / <?= e($schema['label']) ?></p>
    <h1><?= e($schema['label']) ?></h1>
    <p class="lede-a">Writes to <code><?= e($schema['file']) ?></code>. <a href="../<?= e($schema['file']) ?>" target="_blank" rel="noopener">View this page ↗</a></p>
    <?php if (!empty($schema['note'])): ?><p class="msg msg-warn"><?= e($schema['note']) ?></p><?php endif; ?>
    <?php flash($notice, $error); ?>

    <form method="post" class="editor">
      <?= csrf_field() ?>

      <details class="meta-box">
        <summary>Browser tab title and search description</summary>
        <div class="fld"><label for="_title">Browser tab title</label>
          <p class="fld-help">Keep this discreet. It is what shows in the tab, in browser history, and to anyone glancing at the screen.</p>
          <input id="_title" name="_title" type="text" value="<?= e($c['_title']) ?>"></div>
        <div class="fld"><label for="_desc">Search description</label>
          <textarea id="_desc" name="_desc" rows="3"><?= e($c['_desc']) ?></textarea></div>
      </details>

      <?php foreach ($schema['fields'] ?? [] as $name => $def) {
          echo render_field($name, $def, $c[$name] ?? '');
      } ?>

      <?php foreach ($schema['repeaters'] ?? [] as $rk => $rdef) {
          echo render_repeater($rk, $rdef, $c[$rk] ?? []);
      } ?>

      <div class="savebar">
        <button class="btn-primary" type="submit">Save and publish</button>
        <a class="btn-ghost" href="index.php?r=dashboard">Cancel</a>
      </div>
    </form>
    <?php
    admin_foot();
}

function view_site_form(array $schema, ?string $notice, ?string $error): void {
    $v = site_values();
    admin_head('Site settings');
    ?>
    <p class="crumb"><a href="index.php?r=dashboard">Pages</a> / Site settings</p>
    <h1>Site settings</h1>
    <p class="lede-a"><?= e($schema['intro']) ?></p>
    <?php flash($notice, $error); ?>
    <form method="post" class="editor">
      <?= csrf_field() ?>
      <?php foreach ($schema['fields'] as $name => $def) echo render_field($name, $def, $v[$name] ?? ''); ?>
      <div class="savebar">
        <button class="btn-primary" type="submit">Save and publish</button>
        <a class="btn-ghost" href="index.php?r=dashboard">Cancel</a>
      </div>
    </form>
    <?php
    admin_foot();
}

function view_media(?string $notice, ?string $error): void {
    admin_head('Images');
    ?>
    <p class="crumb"><a href="index.php?r=dashboard">Pages</a> / Images</p>
    <h1>Images</h1>
    <p class="lede-a">Every upload is re-encoded, which strips EXIF data — including the GPS coordinates a phone camera records. Nothing that could disclose a location survives the upload.</p>
    <p class="msg msg-warn">No photograph of a woman you support may be published without her written, revocable consent. If in doubt, do not upload it.</p>
    <?php flash($notice, $error); ?>

    <form method="post" enctype="multipart/form-data" class="upload">
      <?= csrf_field() ?>
      <label for="image">Choose an image</label>
      <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" required>
      <button class="btn-primary" type="submit">Upload</button>
      <p class="fld-note">JPEG, PNG or WebP. Up to 8 MB. Anything wider than 2000px is scaled down to keep the site light on mobile data.</p>
    </form>

    <?php $all = media_all(); ?>
    <h2><?= count($all) ?> image<?= count($all) === 1 ? '' : 's' ?></h2>
    <?php if (!$all): ?>
      <p class="empty">Nothing uploaded yet. Every image slot on the site is showing a labelled placeholder until you add one.</p>
    <?php else: ?>
    <div class="mgrid">
      <?php foreach ($all as $m): $used = media_usage($m['file']); ?>
      <figure class="mitem">
        <img src="../<?= e($m['file']) ?>" alt="" loading="lazy">
        <figcaption>
          <code><?= e(basename($m['file'])) ?></code>
          <span><?= (int)$m['w'] ?>×<?= (int)$m['h'] ?> · <?= e(human_bytes((int)$m['bytes'])) ?></span>
          <span class="<?= $used ? 'used' : 'unused' ?>"><?= $used ? 'Used on ' . e(implode(', ', $used)) : 'Not used anywhere' ?></span>
          <form method="post" onsubmit="return confirm('Delete this image? Any page still using it will lose it.');">
            <?= csrf_field() ?>
            <input type="hidden" name="do" value="delete">
            <input type="hidden" name="file" value="<?= e($m['file']) ?>">
            <button class="btn-del" type="submit">Delete</button>
          </form>
        </figcaption>
      </figure>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php
    admin_foot();
}

function view_users(?string $notice, ?string $error): void {
    $me = current_user();
    admin_head('Accounts');
    ?>
    <p class="crumb"><a href="index.php?r=dashboard">Pages</a> / Accounts</p>
    <h1>Accounts</h1>
    <p class="lede-a">Everyone who edits this site needs their own account, so that a departing staff member can be removed without changing a shared password.</p>
    <?php flash($notice, $error); ?>

    <table class="utable">
      <thead><tr><th>Name</th><th>Username</th><th>Added</th><th></th></tr></thead>
      <tbody>
      <?php foreach (users_all() as $u): ?>
        <tr>
          <td><?= e($u['name']) ?><?= strcasecmp($u['username'], $me['username']) === 0 ? ' <span class="you">you</span>' : '' ?></td>
          <td><code><?= e($u['username']) ?></code></td>
          <td><?= e(substr((string)$u['created'], 0, 10)) ?></td>
          <td>
            <?php if (strcasecmp($u['username'], $me['username']) !== 0): ?>
            <form method="post" onsubmit="return confirm('Remove this account?');">
              <?= csrf_field() ?>
              <input type="hidden" name="do" value="delete">
              <input type="hidden" name="username" value="<?= e($u['username']) ?>">
              <button class="btn-del" type="submit">Remove</button>
            </form>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>

    <div class="cols">
      <form method="post" class="editor">
        <?= csrf_field() ?>
        <input type="hidden" name="do" value="add">
        <h2>Add someone</h2>
        <div class="fld"><label for="n2">Name</label><input id="n2" name="name" type="text" required></div>
        <div class="fld"><label for="u2">Username</label><input id="u2" name="username" type="text" required autocapitalize="none"></div>
        <div class="fld"><label for="p2">Password</label><p class="fld-help">At least 12 characters.</p><input id="p2" name="password" type="password" required minlength="12"></div>
        <button class="btn-primary" type="submit">Add account</button>
      </form>

      <form method="post" class="editor">
        <?= csrf_field() ?>
        <input type="hidden" name="do" value="password">
        <input type="hidden" name="username" value="<?= e($me['username']) ?>">
        <h2>Change my password</h2>
        <div class="fld"><label for="p3">New password</label><p class="fld-help">At least 12 characters.</p><input id="p3" name="password" type="password" required minlength="12"></div>
        <button class="btn-primary" type="submit">Change password</button>
      </form>
    </div>
    <?php
    admin_foot();
}
