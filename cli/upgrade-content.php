<?php
/**
 * Let improved defaults reach content that was saved before they existed.
 *
 *   php cli/upgrade-content.php          apply
 *   php cli/upgrade-content.php --dry    report only
 *
 * Saving a page in the admin stores every field, including ones nobody
 * touched. Those untouched fields then hold whatever the default was on the
 * day of the save, and later improvements to the defaults never show.
 *
 * This compares each saved value with every default that field has ever
 * shipped with (cli/default-history.json, built from git history). A value
 * that matches an old default was never really edited, so it is dropped and
 * the current default shows through. Anything staff actually wrote matches no
 * old default and is left exactly as it is. Uploaded images on repeater rows
 * are carried across to the matching new row.
 *
 * Safe to run on every deploy: once a value has been upgraded it no longer
 * matches an old default, so a second run changes nothing.
 */
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once __DIR__ . '/../admin/lib/render.php';

$dry     = in_array('--dry', $argv, true);
$history = json_decode((string)@file_get_contents(__DIR__ . '/default-history.json'), true);
if (!is_array($history)) {
    fwrite(STDERR, "upgrade-content: default-history.json missing or unreadable, nothing done\n");
    exit(0);
}

/** Comparable form of a value: trimmed strings, rows without images or blanks. */
function norm($v) {
    if (is_string($v)) return trim(str_replace("\r\n", "\n", $v));
    if (!is_array($v)) return $v;
    $out = [];
    foreach ($v as $k => $x) {
        if ($k === 'image') continue;
        $x = norm($x);
        if ($x === '' || $x === []) continue;
        $out[$k] = $x;
    }
    $isList = $v === [] || array_keys($v) === range(0, count($v) - 1);
    if ($isList) $out = array_values($out); else ksort($out);
    return $out;
}

/** The field that identifies a repeater row, for carrying images across. */
function row_key(array $row): string {
    foreach (['title', 'headline', 'name', 'q', 'anchor'] as $k) {
        if (!empty($row[$k])) return mb_strtolower(trim((string)$row[$k]));
    }
    return '';
}

/** Current defaults for one content file, keyed like the saved JSON. */
function current_defaults(string $page): array {
    if ($page === 'site') {
        return array_map(fn($d) => $d['default'] ?? '', schema_site()['fields']);
    }
    $s = schema_pages()[$page] ?? null;
    if (!$s) return [];
    $out = [];
    foreach ($s['fields'] ?? [] as $k => $d) {
        if (($d['type'] ?? '') !== 'image') $out[$k] = $d['default'] ?? '';
    }
    foreach ($s['repeaters'] ?? [] as $k => $d) $out[$k] = $d['default'] ?? [];
    $out['_title'] = $s['title'] ?? '';
    $out['_desc']  = $s['desc'] ?? '';
    return $out;
}

$changed = 0;
foreach (array_merge(['site'], array_keys(schema_pages())) as $page) {
    $saved = content_load($page);
    if (!$saved) continue;
    $now  = current_defaults($page);
    $was  = $history[$page] ?? [];
    $edit = false;

    foreach ($saved as $key => $value) {
        if (!array_key_exists($key, $now) || empty($was[$key])) continue;
        $n = norm($value);
        if ($n === norm($now[$key])) continue;                   // already current

        $isOld = false;
        foreach ($was[$key] as $old) {
            if ($n === norm($old)) { $isOld = true; break; }
        }
        if (!$isOld) continue;                                   // staff wrote this: keep

        if (is_array($value) && is_array($now[$key])) {
            // Keep any image someone uploaded on a row that still exists.
            $images = [];
            foreach ($value as $row) {
                if (is_array($row) && !empty($row['image']) && ($rk = row_key($row)) !== '') {
                    $images[$rk] = $row['image'];
                }
            }
            $rows = $now[$key];
            $kept = 0;
            foreach ($rows as $i => $row) {
                $rk = is_array($row) ? row_key($row) : '';
                if ($rk !== '' && isset($images[$rk])) { $rows[$i]['image'] = $images[$rk]; $kept++; }
            }
            if ($kept > 0) $saved[$key] = $rows; else unset($saved[$key]);
            echo "upgraded  $page.$key" . ($kept ? " (kept $kept uploaded image" . ($kept > 1 ? 's' : '') . ")" : '') . "\n";
        } else {
            unset($saved[$key]);
            echo "upgraded  $page.$key\n";
        }
        $edit = true;
        $changed++;
    }

    if ($edit && !$dry && !content_save($page, $saved)) {
        fwrite(STDERR, "upgrade-content: could not write content/$page.json\n");
    }
}

echo $changed ? ($dry ? "$changed fields would be upgraded (dry run)\n" : "$changed fields upgraded\n") : "content already current\n";
exit(0);
