<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 */

// Redirect to the public directory
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '');

// If the file exists in public, serve it directly
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

// Otherwise, redirect to the public directory
header('Location: public/');
exit; 