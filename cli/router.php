<?php
/**
 * Router for local preview only:
 *
 *   php -S 127.0.0.1:8799 cli/router.php
 *
 * PHP's built-in server ignores .htaccess, so this reproduces the clean-URL
 * rewrite well enough to test with. Apache does the real thing in production.
 */
declare(strict_types=1);

$root = __DIR__ . '/..';
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$path = rtrim($path, '/');

if ($path === '') {
    require $root . '/index.html';
    return true;
}
$file = $root . $path;
if (is_file($file)) return false;              // real asset — let the server handle it
if (is_file($file . '.html')) {                // clean URL -> the page behind it
    require $file . '.html';
    return true;
}
if (is_dir($file) && is_file($file . '/index.php')) return false;

http_response_code(404);
require $root . '/index.html';
return true;
