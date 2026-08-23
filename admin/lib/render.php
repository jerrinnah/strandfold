<?php
/**
 * Renders content + templates into flat .html files at the site root.
 *
 * The public site is therefore plain static HTML: fast on a slow connection,
 * and still standing if PHP or the admin ever breaks. Nothing a visitor loads
 * touches this code.
 */
declare(strict_types=1);

require_once __DIR__ . '/content.php';
require_once APP_ROOT . '/templates/layout.php';
require_once APP_ROOT . '/templates/partials/icons.php';
require_once APP_ROOT . '/templates/partials/image.php';

/** Render one page and return its HTML. */
function render_page(string $key): string {
    $schema = schema_pages()[$key] ?? null;
    if (!$schema) throw new RuntimeException("Unknown page: $key");

    $c = page_values($key);   // used by the template
    $s = site_values();       // used by the template

    $tpl = APP_ROOT . '/templates/pages/' . $key . '.php';
    if (!is_file($tpl)) throw new RuntimeException("Missing template: $key");

    ob_start();
    include $tpl;
    $body = ob_get_clean();

    return render_layout($key, ['title' => $c['_title'], 'desc' => $c['_desc']], $body, $s);
}

/**
 * Write every page. Returns [written, errors].
 * Writes atomically so a half-written file is never served.
 */
function render_all(): array {
    $written = [];
    $errors  = [];
    foreach (array_keys(schema_pages()) as $key) {
        $file = schema_pages()[$key]['file'];
        try {
            $html = render_page($key);
            $path = APP_ROOT . '/' . $file;
            $tmp  = APP_ROOT . '/.' . $file . '.tmp';
            if (file_put_contents($tmp, $html, LOCK_EX) === false || !rename($tmp, $path)) {
                $errors[] = "Could not write $file — check directory permissions.";
                @unlink($tmp);
                continue;
            }
            $written[] = $file;
        } catch (Throwable $ex) {
            $errors[] = $file . ': ' . $ex->getMessage();
        }
    }
    render_sitemap();
    return [$written, $errors];
}

/** Keep sitemap.xml in step with whatever pages exist. */
function render_sitemap(): void {
    $base = rtrim((string)(site_values()['site_url'] ?? ''), '/');
    if ($base === '') $base = 'https://example.org';
    $out  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $out .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    foreach (schema_pages() as $key => $p) {
        $loc = $base . '/' . ($key === 'index' ? '' : $p['file']);
        $pri = $key === 'index' || $key === 'get-help' ? '1.0' : '0.7';
        $out .= "  <url><loc>" . e($loc) . "</loc><priority>$pri</priority></url>\n";
    }
    $out .= "</urlset>\n";
    @file_put_contents(APP_ROOT . '/sitemap.xml', $out);
}
