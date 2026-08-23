<?php
/**
 * Image slot. Renders the uploaded image when there is one, and a labelled
 * placeholder when there is not — so an unfilled slot reads as "an image
 * belongs here", never as a broken layout.
 */
declare(strict_types=1);

require_once __DIR__ . '/icons.php';

/** "4 / 5" -> suggested pixel size at a 1200px base width. */
function ratio_px(string $ratio): string {
    if (!preg_match('#^\s*(\d+(?:\.\d+)?)\s*/\s*(\d+(?:\.\d+)?)\s*$#', $ratio, $m)) return '1200 × 800';
    $w = 1200;
    $h = (int)round($w * ((float)$m[2] / (float)$m[1]));
    return $w . ' × ' . $h;
}

/**
 * @param mixed $img  '' or ['file'=>…, 'alt'=>…, 'w'=>…, 'h'=>…]
 * @param array $opt  ratio, label, note, class, priority
 */
function image_slot($img, array $opt = []): string {
    $ratio = $opt['ratio'] ?? '3 / 2';
    $class = trim('imgslot ' . ($opt['class'] ?? ''));
    $style = ' style="--ratio:' . e($ratio) . '"';

    $file = is_array($img) ? trim((string)($img['file'] ?? '')) : '';
    if ($file !== '') {
        $alt = is_array($img) ? trim((string)($img['alt'] ?? '')) : '';
        $w   = (int)($img['w'] ?? 0);
        $h   = (int)($img['h'] ?? 0);
        $dim = ($w && $h) ? ' width="' . $w . '" height="' . $h . '"' : '';
        // The hero loads eagerly; everything below the fold defers.
        $load = !empty($opt['priority']) ? ' fetchpriority="high"' : ' loading="lazy" decoding="async"';

        // Serve the smallest copy that fits. Most visitors here are on a phone
        // with limited data, so this matters more than usual.
        $srcset = is_array($img) ? trim((string)($img['srcset'] ?? '')) : '';
        $sizes  = $opt['sizes'] ?? '(min-width: 900px) 45vw, 100vw';
        $rs = $srcset !== ''
            ? ' srcset="' . e($srcset) . '" sizes="' . e($sizes) . '"'
            : '';

        return '<figure class="' . e($class) . '"' . $style . '>'
             . '<img src="' . e($file) . '" alt="' . e($alt) . '"' . $rs . $dim . $load . '>'
             . '</figure>';
    }

    $label = $opt['label'] ?? 'Image';
    $note  = $opt['note'] ?? '';
    return '<div class="' . e($class) . ' is-empty"' . $style . ' role="img" aria-label="' . e($label) . ' — not yet added">'
         . '<div class="imgslot-inner">'
         . icon('picture', 'imgslot-icon')
         . '<p class="imgslot-label">' . e($label) . '</p>'
         . '<p class="imgslot-hint">' . e(str_replace('/', ':', str_replace(' ', '', $ratio))) . ' · ' . e(ratio_px($ratio)) . 'px</p>'
         . ($note !== '' ? '<p class="imgslot-note">' . e($note) . '</p>' : '')
         . '</div></div>';
}
