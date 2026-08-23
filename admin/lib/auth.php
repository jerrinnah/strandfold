<?php
/**
 * Sessions, passwords, CSRF and login throttling.
 *
 * Accounts live in content/users.json with password_hash() digests. There is
 * no password reset by email — on a site like this an email trail is a
 * liability, so a locked-out admin is recovered by another admin or by
 * deleting users.json on the server.
 */
declare(strict_types=1);

require_once __DIR__ . '/util.php';

const LOGIN_MAX_ATTEMPTS = 5;
const LOGIN_LOCKOUT_SECS = 900;   // 15 minutes

function users_file(): string { return APP_ROOT . '/content/users.json'; }

function users_all(): array {
    $u = read_json(users_file());
    return isset($u['users']) && is_array($u['users']) ? $u['users'] : [];
}

function users_save(array $users): bool {
    return write_json(users_file(), ['users' => array_values($users)]);
}

function user_find(string $username): ?array {
    foreach (users_all() as $u) {
        if (strcasecmp((string)$u['username'], $username) === 0) return $u;
    }
    return null;
}

function user_create(string $username, string $password, string $name): array {
    $username = trim($username);
    if (!preg_match('/^[A-Za-z0-9._-]{3,32}$/', $username)) {
        return [false, 'Username must be 3–32 characters: letters, numbers, dot, dash or underscore.'];
    }
    if (strlen($password) < 12) {
        return [false, 'Password must be at least 12 characters. Length matters more than symbols — a short phrase of four words is both stronger and easier to remember.'];
    }
    if (user_find($username)) return [false, 'That username already exists.'];

    $users = users_all();
    $users[] = [
        'username' => $username,
        'name'     => trim($name) !== '' ? trim($name) : $username,
        'hash'     => password_hash($password, PASSWORD_DEFAULT),
        'created'  => gmdate('c'),
    ];
    return [users_save($users), 'Account created.'];
}

function user_set_password(string $username, string $password): array {
    if (strlen($password) < 12) return [false, 'Password must be at least 12 characters.'];
    $users = users_all();
    foreach ($users as &$u) {
        if (strcasecmp((string)$u['username'], $username) === 0) {
            $u['hash'] = password_hash($password, PASSWORD_DEFAULT);
            return [users_save($users), 'Password changed.'];
        }
    }
    return [false, 'No such user.'];
}

function user_delete(string $username): array {
    $users = users_all();
    if (count($users) <= 1) return [false, 'You cannot delete the only account — you would lock everyone out.'];
    $users = array_filter($users, fn($u) => strcasecmp((string)$u['username'], $username) !== 0);
    return [users_save($users), 'Account removed.'];
}

/* ---------------------------------------------------------------- session */

function session_start_secure(): void {
    if (session_status() === PHP_SESSION_ACTIVE) return;
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    session_name('SFADMIN');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'secure'   => $https,
        'samesite' => 'Strict',
    ]);
    session_start();
}

function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

function require_login(): array {
    $u = current_user();
    if (!$u) redirect('index.php?r=login');
    // Idle timeout: an unattended admin session should not stay open.
    if (isset($_SESSION['seen']) && time() - (int)$_SESSION['seen'] > 7200) {
        logout();
        redirect('index.php?r=login&timeout=1');
    }
    $_SESSION['seen'] = time();
    return $u;
}

function login(string $username, string $password): array {
    if (throttle_locked()) {
        return [false, 'Too many failed attempts. Try again in ' . ceil(throttle_remaining() / 60) . ' minutes.'];
    }
    $u = user_find($username);
    // Always run a hash comparison so a wrong username and a wrong password
    // take the same time and cannot be told apart.
    $hash = $u['hash'] ?? '$2y$10$invalidinvalidinvalidinvalidinvalidinvalidinvalidinvalidin';
    if (!password_verify($password, $hash) || !$u) {
        throttle_fail();
        return [false, 'Wrong username or password.'];
    }
    throttle_clear();
    session_regenerate_id(true);
    $_SESSION['user'] = ['username' => $u['username'], 'name' => $u['name']];
    $_SESSION['seen'] = time();
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return [true, ''];
}

function logout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

/* ------------------------------------------------------------------ CSRF */

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}

function csrf_field(): string {
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check(): void {
    $sent = (string)($_POST['_csrf'] ?? '');
    if ($sent === '' || !hash_equals(csrf_token(), $sent)) {
        http_response_code(400);
        exit('Request rejected: the form expired or came from somewhere else. Go back and try again.');
    }
}

/* -------------------------------------------------------------- throttle */

function throttle_file(): string { return APP_ROOT . '/content/.throttle.json'; }

function throttle_key(): string {
    return hash('sha256', (string)($_SERVER['REMOTE_ADDR'] ?? 'cli'));
}

function throttle_fail(): void {
    $all = read_json(throttle_file());
    $k = throttle_key();
    $rec = $all[$k] ?? ['n' => 0, 'first' => time()];
    if (time() - (int)$rec['first'] > LOGIN_LOCKOUT_SECS) $rec = ['n' => 0, 'first' => time()];
    $rec['n'] = (int)$rec['n'] + 1;
    $all[$k] = $rec;
    // Drop stale records so the file cannot grow without bound.
    foreach ($all as $key => $r) {
        if (time() - (int)($r['first'] ?? 0) > LOGIN_LOCKOUT_SECS * 4) unset($all[$key]);
    }
    write_json(throttle_file(), $all);
}

function throttle_clear(): void {
    $all = read_json(throttle_file());
    unset($all[throttle_key()]);
    write_json(throttle_file(), $all);
}

function throttle_locked(): bool {
    $rec = read_json(throttle_file())[throttle_key()] ?? null;
    if (!$rec) return false;
    return (int)$rec['n'] >= LOGIN_MAX_ATTEMPTS && time() - (int)$rec['first'] < LOGIN_LOCKOUT_SECS;
}

function throttle_remaining(): int {
    $rec = read_json(throttle_file())[throttle_key()] ?? null;
    if (!$rec) return 0;
    return max(0, LOGIN_LOCKOUT_SECS - (time() - (int)$rec['first']));
}
