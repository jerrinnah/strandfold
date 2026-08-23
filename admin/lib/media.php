<?php
/**
 * Uploads.
 *
 * Every image is re-encoded through GD before it is stored. That is not for
 * compression — it is to destroy the metadata. A photo taken on a phone can
 * carry GPS coordinates, a device serial and a timestamp in its EXIF block.
 * Publishing that on a domestic-abuse site could disclose a shelter location
 * or a survivor's whereabouts. Re-encoding drops all of it.
 */
declare(strict_types=1);

require_once __DIR__ . '/util.php';

const MAX_UPLOAD_BYTES = 8388608;   // 8 MB
const MAX_IMAGE_WIDTH  = 2000;      // plenty for a 1200px layout on a 2x screen

function uploads_dir(): string { return APP_ROOT . '/uploads'; }
function media_index_file(): string { return uploads_dir() . '/index.json'; }

function media_all(): array {
    $idx = read_json(media_index_file());
    $items = $idx['items'] ?? [];
    usort($items, fn($a, $b) => strcmp((string)$b['uploaded'], (string)$a['uploaded']));
    return $items;
}

function media_find(string $file): ?array {
    foreach (media_all() as $m) if ($m['file'] === $file) return $m;
    return null;
}

function media_index_save(array $items): bool {
    return write_json(media_index_file(), ['items' => array_values($items)]);
}

/**
 * Validate, strip and store one uploaded file.
 * @return array [ok(bool), message(string), item(array|null)]
 */
function media_store(array $file): array {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return [false, 'No file was chosen.', null];
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [false, 'Upload failed (error ' . (int)$file['error'] . '). The file may be larger than the server allows.', null];
    }
    if (!is_uploaded_file($file['tmp_name'])) {
        return [false, 'That was not an uploaded file.', null];
    }
    if ($file['size'] > MAX_UPLOAD_BYTES) {
        return [false, 'That image is ' . round($file['size'] / 1048576, 1) . ' MB. The limit is 8 MB — please resize it first.', null];
    }
    if (!is_dir(uploads_dir()) && !@mkdir(uploads_dir(), 0755, true)) {
        return [false, 'The uploads folder does not exist and could not be created.', null];
    }
    if (!is_writable(uploads_dir())) {
        return [false, 'The uploads folder is not writable. Set it to 755 in cPanel File Manager.', null];
    }

    // Trust the bytes, never the filename or the browser-supplied type.
    $info = @getimagesize($file['tmp_name']);
    if (!$info) return [false, 'That file is not an image we can read.', null];

    [$w, $h, $type] = $info;
    $allowed = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp'];
    if (!isset($allowed[$type])) {
        return [false, 'Only JPEG, PNG and WebP images are accepted.', null];
    }
    if ($w < 200 || $h < 200) {
        return [false, 'That image is only ' . $w . '×' . $h . '. It will look blurred — please use something at least 1200px wide.', null];
    }
    if (!function_exists('imagecreatefromjpeg')) {
        return [false, 'PHP\'s GD image extension is not enabled on this server, so metadata cannot be stripped. Enable GD in cPanel before uploading photographs.', null];
    }

    $src = match ($type) {
        IMAGETYPE_JPEG => @imagecreatefromjpeg($file['tmp_name']),
        IMAGETYPE_PNG  => @imagecreatefrompng($file['tmp_name']),
        IMAGETYPE_WEBP => @imagecreatefromwebp($file['tmp_name']),
    };
    if (!$src) return [false, 'That image could not be decoded. It may be corrupt.', null];

    // Downscale anything unreasonably large — this site is built for slow
    // connections and a 6000px phone photo would undo that.
    if ($w > MAX_IMAGE_WIDTH) {
        $nh = (int)round($h * (MAX_IMAGE_WIDTH / $w));
        $dst = imagecreatetruecolor(MAX_IMAGE_WIDTH, $nh);
        if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_WEBP) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }
        imagecopyresampled($dst, $src, 0, 0, 0, 0, MAX_IMAGE_WIDTH, $nh, $w, $h);
        imagedestroy($src);
        $src = $dst;
        $w = MAX_IMAGE_WIDTH;
        $h = $nh;
    }

    $ext  = $allowed[$type];
    $name = gmdate('Ymd') . '-' . bin2hex(random_bytes(6)) . '.' . $ext;
    $path = uploads_dir() . '/' . $name;

    // Writing a fresh file from decoded pixels is what discards EXIF/GPS.
    $ok = match ($type) {
        IMAGETYPE_JPEG => imagejpeg($src, $path, 82),
        IMAGETYPE_PNG  => imagepng($src, $path, 6),
        IMAGETYPE_WEBP => imagewebp($src, $path, 82),
    };
    imagedestroy($src);

    if (!$ok || !is_file($path)) return [false, 'The image could not be saved to the uploads folder.', null];
    @chmod($path, 0644);

    $item = [
        'file'     => 'uploads/' . $name,
        'w'        => $w,
        'h'        => $h,
        'bytes'    => filesize($path) ?: 0,
        'uploaded' => gmdate('c'),
        'original' => substr(preg_replace('/[^\w. -]/', '', (string)$file['name']), 0, 80),
        'variants' => media_variants($path, $name, $ext, $type, $w, $h),
    ];
    $items = media_all();
    array_unshift($items, $item);
    media_index_save($items);

    return [true, 'Uploaded, and its location metadata has been stripped. It is not on the site yet — open the page you want it on, choose it in the image field, and press Save and publish.', $item];
}

/** Remove a file and its index entry. Returns [ok, message]. */
function media_delete(string $file): array {
    if (!str_starts_with($file, 'uploads/') || str_contains($file, '..')) {
        return [false, 'That is not a file in the uploads folder.'];
    }
    $item = media_find($file);
    $path = APP_ROOT . '/' . $file;
    if (is_file($path) && !@unlink($path)) {
        return [false, 'The file could not be deleted — check folder permissions.'];
    }
    foreach (($item['variants'] ?? []) as $v) {
        if (str_starts_with((string)$v, 'uploads/')) @unlink(APP_ROOT . '/' . $v);
    }
    media_index_save(array_filter(media_all(), fn($m) => $m['file'] !== $file));
    return [true, 'Image deleted.'];
}

/** Which content fields still point at this file. Prevents surprise gaps. */
function media_usage(string $file): array {
    require_once __DIR__ . '/content.php';
    $used = [];
    foreach (array_keys(schema_pages()) as $key) {
        $json = json_encode(content_load($key));
        if ($json !== false && str_contains($json, '"' . $file . '"')) {
            $used[] = schema_pages()[$key]['label'];
        }
    }
    return $used;
}

function human_bytes(int $b): string {
    if ($b >= 1048576) return round($b / 1048576, 1) . ' MB';
    if ($b >= 1024) return round($b / 1024) . ' kB';
    return $b . ' B';
}

/**
 * Write smaller copies so a phone does not download a 2000px file.
 * Returns [width => path]. Failure is non-fatal: without variants the full
 * image is still served, just less efficiently.
 */
function media_variants(string $path, string $name, string $ext, int $type, int $w, int $h): array {
    $out = [];
    foreach ([640, 1024, 1600] as $tw) {
        if ($tw >= $w) continue;
        $src = match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
            IMAGETYPE_PNG  => @imagecreatefrompng($path),
            IMAGETYPE_WEBP => @imagecreatefromwebp($path),
            default        => null,
        };
        if (!$src) continue;

        $th  = (int)round($h * ($tw / $w));
        $dst = imagecreatetruecolor($tw, $th);
        if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_WEBP) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
        imagedestroy($src);

        $vn = preg_replace('/\.' . preg_quote($ext, '/') . '$/', '', $name) . '-' . $tw . '.' . $ext;
        $vp = uploads_dir() . '/' . $vn;
        $ok = match ($type) {
            IMAGETYPE_JPEG => imagejpeg($dst, $vp, 80),
            IMAGETYPE_PNG  => imagepng($dst, $vp, 6),
            IMAGETYPE_WEBP => imagewebp($dst, $vp, 80),
            default        => false,
        };
        imagedestroy($dst);
        if ($ok) { @chmod($vp, 0644); $out[$tw] = 'uploads/' . $vn; }
    }
    return $out;
}

/** Build a srcset string for an item, widest last. */
function media_srcset(array $item): string {
    $parts = [];
    foreach (($item['variants'] ?? []) as $wd => $f) $parts[] = $f . ' ' . (int)$wd . 'w';
    $parts[] = $item['file'] . ' ' . (int)$item['w'] . 'w';
    return count($parts) > 1 ? implode(', ', $parts) : '';
}
