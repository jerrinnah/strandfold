<?php
/**
 * Image slot. Renders the uploaded image when there is one, and a labelled
 * placeholder when there is not — so an unfilled slot reads as "an image
 * belongs here", never as a broken layout.
 */
declare(strict_types=1);

require_once __DIR__ . '/icons.php';

/**
 * Clamp an uploaded image's own aspect ratio into something a page layout can
 * survive. Without this a panorama would flatten the hero to a strip, and a
 * very tall portrait would push everything below it off the screen.
 */
const RATIO_MIN = 0.62;   // narrower than about 5:8 is cropped back
const RATIO_MAX = 1.85;   // wider than about 16:9 is cropped back

function natural_ratio(int $w, int $h): ?string {
    if ($w < 1 || $h < 1) return null;
    $r = max(RATIO_MIN, min(RATIO_MAX, $w / $h));
    return round($r, 4) . ' / 1';
}

/** "4 / 5" -> suggested pixel size at a 1200px base width. */
function ratio_px(string $ratio): string {
    if (!preg_match('#^\s*(\d+(?:\.\d+)?)\s*/\s*(\d+(?:\.\d+)?)\s*$#', $ratio, $m)) return '1200 × 800';
    $w = 1200;
    $h = (int)round($w * ((float)$m[2] / (float)$m[1]));
    return $w . ' × ' . $h;
}

/**
 * @param mixed $img  '' or ['file'=>…, 'alt'=>…, 'w'=>…, 'h'=>…, 'srcset'=>…]
 * @param array $opt  ratio, label, note, class, priority, sizes, adapt
 */
function image_slot($img, array $opt = []): string {
    $ratio    = $opt['ratio'] ?? '3 / 2';
    $nominal  = $ratio;
    $class    = trim('imgslot ' . ($opt['class'] ?? ''));

    $file = is_array($img) ? trim((string)($img['file'] ?? '')) : '';

    if ($file !== '') {
        $alt = is_array($img) ? trim((string)($img['alt'] ?? '')) : '';
        $w   = (int)($img['w'] ?? 0);
        $h   = (int)($img['h'] ?? 0);

        // Where the layout allows it, the slot takes the shape of the image
        // that was actually uploaded instead of cropping it to a fixed ratio.
        // Card grids and full-height panels keep their nominal ratio, because
        // uneven heights there would break the row.
        if (!empty($opt['adapt']) && ($nat = natural_ratio($w, $h)) !== null) {
            $ratio  = $nat;
            $class .= ' imgslot-adapt';
        }

        $dim  = ($w && $h) ? ' width="' . $w . '" height="' . $h . '"' : '';
        // The hero loads eagerly; everything below the fold defers.
        $load = !empty($opt['priority']) ? ' fetchpriority="high"' : ' loading="lazy" decoding="async"';

        // Serve the smallest copy that fits. Most visitors here are on a phone
        // with limited data, so this matters more than usual.
        $srcset = is_array($img) ? trim((string)($img['srcset'] ?? '')) : '';
        $sizes  = $opt['sizes'] ?? '(min-width: 900px) 45vw, 100vw';
        $rs = $srcset !== '' ? ' srcset="' . e($srcset) . '" sizes="' . e($sizes) . '"' : '';

        return '<figure class="' . e($class) . '" style="--ratio:' . e($ratio) . '">'
             . '<img src="' . e($file) . '" alt="' . e($alt) . '"' . $rs . $dim . $load . '>'
             . '</figure>';
    }

    // An empty slot keeps the nominal ratio, so the placeholder still shows the
    // shape and size the design is asking for.
    $label = $opt['label'] ?? 'Image';
    $note  = $opt['note'] ?? '';
    return '<div class="' . e($class) . ' is-empty" style="--ratio:' . e($nominal) . '" role="img" aria-label="' . e($label) . ' — not yet added">'
         . '<div class="imgslot-inner">'
         . icon('picture', 'imgslot-icon')
         . '<p class="imgslot-label">' . e($label) . '</p>'
         . '<p class="imgslot-hint">' . e(str_replace('/', ':', str_replace(' ', '', $nominal))) . ' · ' . e(ratio_px($nominal)) . 'px</p>'
         . ($note !== '' ? '<p class="imgslot-note">' . e($note) . '</p>' : '')
         . '</div></div>';
}
