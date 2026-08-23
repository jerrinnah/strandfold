<?php
/**
 * Content store. Values live in JSON under content/ — one file per page plus
 * site.json. Anything not yet edited falls back to the schema default, so the
 * site renders correctly from a clean checkout with no content directory.
 */
declare(strict_types=1);

require_once __DIR__ . '/util.php';
require_once __DIR__ . '/schema.php';

function content_dir(): string { return APP_ROOT . '/content'; }

function content_load(string $page): array {
    return read_json(content_dir() . '/' . basename($page) . '.json');
}

function content_save(string $page, array $data): bool {
    return write_json(content_dir() . '/' . basename($page) . '.json', $data);
}

/** Site-wide settings merged over their defaults. */
function site_values(): array {
    $saved = content_load('site');
    $out = [];
    foreach (schema_site()['fields'] as $key => $def) {
        $out[$key] = array_key_exists($key, $saved) && $saved[$key] !== ''
            ? $saved[$key]
            : ($def['default'] ?? '');
    }
    return $out;
}

/**
 * All values for one page: scalar fields merged over defaults, plus repeater
 * rows (saved rows win entirely — an editor who deletes a row means it).
 */
function page_values(string $page): array {
    $schema = schema_pages()[$page] ?? null;
    if (!$schema) return [];
    $saved = content_load($page);
    $out = [];

    foreach ($schema['fields'] ?? [] as $key => $def) {
        $out[$key] = array_key_exists($key, $saved) && $saved[$key] !== ''
            ? $saved[$key]
            : ($def['default'] ?? '');
    }
    // An image cleared by the editor must stay cleared, not spring back.
    foreach ($schema['fields'] ?? [] as $key => $def) {
        if (($def['type'] ?? '') === 'image' && array_key_exists($key, $saved)) {
            $out[$key] = $saved[$key];
        }
    }

    foreach ($schema['repeaters'] ?? [] as $key => $def) {
        $out[$key] = array_key_exists($key, $saved) && is_array($saved[$key])
            ? array_values($saved[$key])
            : ($def['default'] ?? []);
    }

    $out['_title'] = $saved['_title'] ?? ($schema['title'] ?? '');
    $out['_desc']  = $saved['_desc']  ?? ($schema['desc'] ?? '');
    return $out;
}

/** Split a one-item-per-line textarea into a clean list. */
function lines(?string $s): array {
    $out = [];
    foreach (preg_split('/\r?\n/', (string)$s) as $line) {
        $line = trim($line);
        if ($line !== '') $out[] = $line;
    }
    return $out;
}

/** Inline markup for a single line (no <p> wrapper). */
function inline(?string $s): string {
    $html = rich($s);
    return preg_replace('#^<p>(.*)</p>$#s', '$1', trim($html)) ?? '';
}

/**
 * A story may only be published when a consent record exists. This is the
 * technical half of "no survivor story without written, revocable consent" —
 * the form refuses to publish without it and the renderer skips it anyway.
 */
function story_publishable(array $row): bool {
    return trim((string)($row['consent'] ?? '')) !== '';
}
