<?php
/**
 * Rebuild every static page from content. Run after a deploy, or any time the
 * JSON in content/ has been edited by hand.
 *
 *   php cli/render.php
 */
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once __DIR__ . '/../admin/lib/render.php';

[$written, $errors] = render_all();

foreach ($written as $f) echo "rendered  $f\n";
foreach ($errors as $e)  fwrite(STDERR, "ERROR     $e\n");

echo "\n" . count($written) . " pages written";
echo $errors ? ", " . count($errors) . " failed\n" : "\n";
exit($errors ? 1 : 0);
