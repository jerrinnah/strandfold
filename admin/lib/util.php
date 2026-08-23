<?php
/* Shared helpers. No framework, no dependencies. */
declare(strict_types=1);

const APP_ROOT = __DIR__ . '/../..';

/** HTML-escape. Every value that reaches a template goes through this. */
function e(?string $s): string {
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Turn editor plain text into HTML: blank lines become paragraphs, single
 * newlines become <br>. Deliberately not a rich-text editor — staff should not
 * be able to paste a tracking pixel or an iframe into a domestic-abuse site.
 * **bold** and [text](url) are the only markup, and links are scheme-checked.
 */
function rich(?string $s): string {
    $s = trim((string)$s);
    if ($s === '') return '';
    $out = [];
    foreach (preg_split('/\n\s*\n/', $s) as $para) {
        $p = e(trim($para));
        $p = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $p);
        $p = preg_replace_callback(
            '/\[([^\]]+)\]\(([^)\s]+)\)/',
            function ($m) {
                $href = $m[2];
                $ok = preg_match('#^(https://|mailto:|tel:|sms:|/|[a-z0-9_-]+\.html|\#)#i', $href);
                if (!$ok) return $m[1];
                $ext = str_starts_with(strtolower($href), 'https://');
                return '<a href="' . $href . '"' . ($ext ? ' rel="noreferrer noopener"' : '') . '>' . $m[1] . '</a>';
            },
            $p
        );
        $p = nl2br($p, false);
        $out[] = '<p>' . $p . '</p>';
    }
    return implode("\n", $out);
}

/** Read a JSON file into an array. Returns [] when absent or malformed. */
function read_json(string $path): array {
    if (!is_file($path)) return [];
    $raw = file_get_contents($path);
    if ($raw === false || trim($raw) === '') return [];
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

/** Write JSON atomically, so a crash mid-save cannot truncate content. */
function write_json(string $path, array $data): bool {
    $dir = dirname($path);
    if (!is_dir($dir)) @mkdir($dir, 0755, true);
    $tmp = $dir . '/.' . basename($path) . '.' . bin2hex(random_bytes(4)) . '.tmp';
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    if ($json === false) return false;
    if (file_put_contents($tmp, $json . "\n", LOCK_EX) === false) return false;
    return rename($tmp, $path);
}

/** Dotted path lookup: dget($a, 'hero.headline'). */
function dget(array $a, string $key, $default = null) {
    foreach (explode('.', $key) as $part) {
        if (!is_array($a) || !array_key_exists($part, $a)) return $default;
        $a = $a[$part];
    }
    return $a;
}

function slugify(string $s): string {
    $s = strtolower(trim($s));
    $s = preg_replace('/[^a-z0-9]+/', '-', $s);
    return trim((string)$s, '-') ?: 'item';
}

function redirect(string $to): never {
    header('Location: ' . $to);
    exit;
}
