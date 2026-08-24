<?php
/** Form widgets for the admin, driven entirely by the schema. */
declare(strict_types=1);

require_once __DIR__ . '/media.php';
require_once __DIR__ . '/content.php';
require_once APP_ROOT . '/templates/partials/icons.php';

function field_id(string $name): string {
    return 'f_' . preg_replace('/[^a-z0-9]+/i', '_', $name);
}

/** One scalar field: text, textarea, image or icon. */
function render_field(string $name, array $def, $value): string {
    $id    = field_id($name);
    $type  = $def['type'] ?? 'text';
    $label = $def['label'] ?? $name;
    $help  = $def['help'] ?? '';

    $out  = '<div class="fld fld-' . e($type) . '">';
    $out .= '<label for="' . e($id) . '">' . e($label) . '</label>';
    if ($help !== '') $out .= '<p class="fld-help">' . e($help) . '</p>';

    switch ($type) {
        case 'textarea':
            $rows = max(3, min(16, substr_count((string)$value, "\n") + 3));
            $out .= '<textarea id="' . e($id) . '" name="' . e($name) . '" rows="' . $rows . '">' . e((string)$value) . '</textarea>';
            $out .= '<p class="fld-note">Blank line starts a new paragraph. <code>**bold**</code> and <code>[link text](https://…)</code> work. Nothing else is allowed through.</p>';
            break;

        case 'image':
            $out .= render_image_picker($name, is_array($value) ? $value : [], $def);
            break;

        case 'icon':
            $out .= '<select id="' . e($id) . '" name="' . e($name) . '">';
            foreach (icon_names() as $ic) {
                $sel = ((string)$value === $ic) ? ' selected' : '';
                $out .= '<option value="' . e($ic) . '"' . $sel . '>' . e(ucfirst($ic)) . '</option>';
            }
            $out .= '</select>';
            break;

        default:
            $out .= '<input type="text" id="' . e($id) . '" name="' . e($name) . '" value="' . e((string)$value) . '">';
    }

    $out .= '</div>';
    return $out;
}

/**
 * Choose an already-uploaded image and give it alt text.
 *
 * Stored filenames are randomised, so they tell an editor nothing. The picker
 * therefore shows the name the file was uploaded under, plus a thumbnail grid
 * — otherwise choosing between "20260824-48d13acc2c30.png" and
 * "20260824-42c3353a92f3.png" is guesswork.
 */
function render_image_picker(string $name, array $value, array $def): string {
    $file  = (string)($value['file'] ?? '');
    $alt   = (string)($value['alt'] ?? '');
    $ratio = $def['ratio'] ?? '3 / 2';
    $all   = media_all();

    $out  = '<div class="imgpick" data-imgpick>';

    $out .= '<div class="imgpick-top">';
    $out .= '<div class="imgpick-preview">';
    if ($file !== '') {
        $out .= '<img src="../' . e(media_thumb($file)) . '" alt="">';
    } else {
        $out .= '<span class="imgpick-empty">' . icon('picture') . 'No image</span>';
    }
    $out .= '</div><div class="imgpick-controls">';

    $out .= '<select name="' . e($name) . '[file]" data-imgpick-file>';
    $out .= '<option value="">— No image —</option>';
    foreach ($all as $m) {
        $sel   = $m['file'] === $file ? ' selected' : '';
        $label = ($m['original'] ?? '') !== '' ? $m['original'] : basename($m['file']);
        $when  = substr((string)($m['uploaded'] ?? ''), 0, 10);
        $out  .= '<option value="' . e($m['file']) . '"' . $sel . '>'
               . e($label) . ' · ' . (int)$m['w'] . '×' . (int)$m['h']
               . ($when !== '' ? ' · ' . e($when) : '') . '</option>';
    }
    $out .= '</select>';

    $out .= '<input type="text" name="' . e($name) . '[alt]" value="' . e($alt) . '" placeholder="Describe the image for someone who cannot see it">';
    $out .= '<p class="fld-note">Suggested ' . e(str_replace(' ', '', str_replace('/', ':', $ratio))) . ' · ' . e(ratio_px($ratio)) . 'px. '
          . '<a href="index.php?r=media" target="_blank" rel="noopener">Upload a new image</a>.</p>';
    if (!empty($def['help'])) $out .= '<p class="fld-warn">' . e($def['help']) . '</p>';
    $out .= '</div></div>';

    if ($all) {
        $out .= '<div class="imgpick-thumbs" data-imgpick-thumbs>';
        foreach ($all as $m) {
            $on = $m['file'] === $file ? ' is-on' : '';
            $label = ($m['original'] ?? '') !== '' ? $m['original'] : basename($m['file']);
            $out .= '<button type="button" class="imgthumb' . $on . '" data-file="' . e($m['file']) . '" title="' . e($label) . '">'
                  . '<img src="../' . e(media_thumb($m['file'])) . '" alt="" loading="lazy">'
                  . '<span>' . e($label) . '</span></button>';
        }
        $out .= '</div>';
    }

    $out .= '</div>';
    return $out;
}

/** Smallest stored copy of a file, for admin thumbnails. */
function media_thumb(string $file): string {
    $m = media_find($file);
    if (!$m) return $file;
    $v = $m['variants'] ?? [];
    if (!$v) return $file;
    ksort($v);
    return (string)reset($v);
}

/** A repeatable block of rows. */
function render_repeater(string $key, array $def, array $rows): string {
    $label   = $def['label'] ?? $key;
    $consent = !empty($def['consent']);

    $out  = '<section class="rep" data-rep="' . e($key) . '">';
    $out .= '<div class="rep-head"><h3>' . e($label) . '</h3>';
    if (!empty($def['help'])) $out .= '<p class="fld-help">' . e($def['help']) . '</p>';
    $out .= '</div><div class="rep-rows" data-rep-rows>';

    foreach ($rows as $i => $row) {
        $out .= render_repeater_row($key, $def, $row, (int)$i, $consent);
    }

    $out .= '</div>';
    $out .= '<button type="button" class="btn-sm2" data-rep-add>Add ' . e(rtrim(strtolower($label), 's')) . '</button>';
    $out .= '<template data-rep-template>' . render_repeater_row($key, $def, [], 0, $consent, true) . '</template>';
    $out .= '</section>';
    return $out;
}

function render_repeater_row(string $key, array $def, array $row, int $i, bool $consent, bool $tpl = false): string {
    $idx = $tpl ? '__I__' : (string)$i;
    $base = 'rep[' . $key . '][' . $idx . ']';

    $title = '';
    foreach (['title', 'name', 'headline', 'q', 'amount', 'number'] as $k) {
        if (!empty($row[$k])) { $title = (string)$row[$k]; break; }
    }

    $out  = '<div class="rep-row" data-rep-row>';
    $out .= '<div class="rep-row-head">';
    $out .= '<span class="rep-row-title">' . e($title !== '' ? $title : 'New entry') . '</span>';
    $out .= '<button type="button" class="rep-del" data-rep-del title="Remove this entry">Remove</button>';
    $out .= '</div><div class="rep-row-body">';

    foreach ($def['fields'] as $fk => $fdef) {
        $out .= render_field($base . '[' . $fk . ']', $fdef, $row[$fk] ?? ($fdef['type'] === 'image' ? [] : ''));
    }

    if ($consent) {
        $val = (string)($row['consent'] ?? '');
        $ok  = trim($val) !== '';
        $out .= '<div class="fld fld-consent' . ($ok ? '' : ' is-missing') . '">';
        $out .= '<label for="' . e(field_id($base . '[consent]')) . '">Consent record <span class="req">required to publish</span></label>';
        $out .= '<p class="fld-help">Reference the signed consent form: date, reference number, and who holds it. This entry stays off the public site until this is filled in, and clearing it removes the story from the site at the next save.</p>';
        $out .= '<input type="text" id="' . e(field_id($base . '[consent]')) . '" name="' . e($base) . '[consent]" value="' . e($val) . '" placeholder="e.g. Signed 2026-03-04, ref CF-118, held by [name]">';
        $out .= '<p class="' . ($ok ? 'fld-ok' : 'fld-warn') . '">' . ($ok ? 'Consent on file — this entry will publish.' : 'No consent record — this entry will NOT appear on the public site.') . '</p>';
        $out .= '</div>';
    }

    $out .= '</div></div>';
    return $out;
}

/**
 * Rebuild a page's saved values from $_POST.
 * Image dimensions are looked up from the media index rather than trusted
 * from the browser.
 */
function collect_post(array $schema): array {
    $data = [];

    foreach ($schema['fields'] ?? [] as $key => $def) {
        $raw = $_POST[$key] ?? '';
        $data[$key] = normalise_value($raw, $def);
    }

    foreach ($schema['repeaters'] ?? [] as $key => $def) {
        $rows = $_POST['rep'][$key] ?? [];
        $out  = [];
        if (is_array($rows)) {
            foreach ($rows as $idx => $row) {
                if (!is_array($row)) continue;
                // The blank <template> row is inert in a browser, but never
                // trust that — it must not become content.
                if ((string)$idx === '__I__') continue;
                $clean = [];
                foreach ($def['fields'] as $fk => $fdef) {
                    $clean[$fk] = normalise_value($row[$fk] ?? '', $fdef);
                }
                if (!empty($def['consent'])) {
                    $clean['consent'] = trim((string)($row['consent'] ?? ''));
                }
                // Keep a row only if it carries actual content. An icon
                // select always has a value, so it cannot on its own keep an
                // otherwise-blank row alive — otherwise pressing "Add" and
                // saving would publish an empty card.
                $has = false;
                foreach ($def['fields'] as $fk => $fdef) {
                    $type = $fdef['type'] ?? 'text';
                    if ($type === 'icon') continue;
                    $v = $clean[$fk] ?? '';
                    if ($type === 'image') {
                        if (!empty($v['file'])) { $has = true; break; }
                    } elseif (trim((string)$v) !== '') {
                        $has = true; break;
                    }
                }
                if ($has) $out[] = $clean;
            }
        }
        $data[$key] = $out;
    }

    return $data;
}

function normalise_value($raw, array $def) {
    if (($def['type'] ?? 'text') === 'image') {
        $file = is_array($raw) ? trim((string)($raw['file'] ?? '')) : '';
        $alt  = is_array($raw) ? trim((string)($raw['alt'] ?? '')) : '';
        if ($file === '') return ['file' => '', 'alt' => ''];
        $m = media_find($file);
        if (!$m) return ['file' => '', 'alt' => ''];   // points at something deleted
        return [
            'file'   => $file,
            'alt'    => $alt,
            'w'      => (int)$m['w'],
            'h'      => (int)$m['h'],
            'srcset' => media_srcset($m),
        ];
    }
    $s = is_string($raw) ? $raw : '';
    $s = str_replace("\r\n", "\n", $s);
    return trim($s);
}
