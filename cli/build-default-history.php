<?php
/**
 * Rebuild cli/default-history.json: every default each field has ever shipped
 * with, read from the git history of admin/lib/schema.php. upgrade-content.php
 * uses it to tell untouched saved values from real edits.
 *
 *   php cli/build-default-history.php [output.json]
 *
 * Without an argument it writes cli/default-history.json in this checkout.
 *
 * Runs on every deploy, writing into the deployed copy rather than the git
 * checkout (a modified tracked file there would block the next pull), so a default changed in
 * any commit is recognised as "old" from then on. If git is unavailable the
 * committed copy of the JSON is left as it is.
 */
declare(strict_types=1);

if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

$root = dirname(__DIR__);
$tmp  = sys_get_temp_dir() . '/sf-history-' . getmypid();
@mkdir($tmp, 0700, true);

exec('cd ' . escapeshellarg($root) . ' && git log --format=%H -- admin/lib/schema.php 2>/dev/null', $commits, $rc);
if ($rc !== 0 || !$commits) {
    fwrite(STDERR, "build-default-history: git history unavailable, keeping committed copy\n");
    exit(0);
}

$out = [];
foreach ($commits as $i => $c) {
    $src = shell_exec('cd ' . escapeshellarg($root) . ' && git show ' . escapeshellarg($c . ':admin/lib/schema.php') . ' 2>/dev/null');
    if (!is_string($src) || $src === '') continue;
    $fn   = 'hist' . $i;
    $code = str_replace(['function schema_site(', 'function schema_pages('], ["function {$fn}_site(", "function {$fn}_pages("], $src);
    $code = preg_replace('/^\s*declare\(strict_types=1\);/m', '', $code);
    $file = "$tmp/$fn.php";
    file_put_contents($file, $code);
    require $file;

    foreach (($fn . '_site')()['fields'] as $k => $d) $out['site'][$k][] = $d['default'] ?? '';
    foreach (($fn . '_pages')() as $page => $p) {
        foreach ($p['fields'] ?? [] as $k => $d) {
            if (($d['type'] ?? '') !== 'image') $out[$page][$k][] = $d['default'] ?? '';
        }
        foreach ($p['repeaters'] ?? [] as $k => $d) $out[$page][$k][] = $d['default'] ?? [];
        $out[$page]['_title'][] = $p['title'] ?? '';
        $out[$page]['_desc'][]  = $p['desc'] ?? '';
    }
    @unlink($file);
}
@rmdir($tmp);

foreach ($out as $pg => $keys) {
    foreach ($keys as $k => $v) $out[$pg][$k] = array_values(array_unique($v, SORT_REGULAR));
}
$json = json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";
$dest = $argv[1] ?? (__DIR__ . '/default-history.json');
if (file_put_contents($dest, $json) === false) {
    fwrite(STDERR, "build-default-history: could not write $dest\n");
    exit(0);
}
echo 'default history rebuilt from ' . count($commits) . " commits\n";
