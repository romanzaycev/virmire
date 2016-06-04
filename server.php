<?php

/**
 * Public directory path
 */
define('BUILT_IN_SERVER_PUBLIC_HTML', __DIR__ . '/public');

$requestUri = urldecode(
    parse_url(
        $_SERVER['REQUEST_URI'],
        PHP_URL_PATH
    )
);

if ($requestUri !== '/' && file_exists(BUILT_IN_SERVER_PUBLIC_HTML . '/' . $requestUri)) {
    /**
     * Pass request
     */
    return false;
}

/**
 * Run application
 */
require_once BUILT_IN_SERVER_PUBLIC_HTML . '/index.php';
