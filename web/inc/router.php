<?php
$url  = parse_url($_SERVER['REQUEST_URI']);
$file = pathinfo($url['path']);

// Forbid direct access to router file
if ($url['path'] == '/inc/router.php') {
    return false;
}

// Real files and folders don't get pre-processed
if (file_exists($_SERVER['DOCUMENT_ROOT'] . $url['path'])
    && $url['path'] != '/') {
    return false;
}

// Don't process non-PHP files, even if they don't exist on the server
if ((isset($file['extension']) && $file['extension'] != 'php')) {
    return false;
}

// Check if we process this url or not
if ($url['path'] != '/') {
    // Normalize path before comparing the string to list of valid paths
    $url['path'] = explode('/', $url['path']);
    $url['path'] = array_filter($url['path']);
    $url['path'] = implode('/', $url['path']);
}

// Include all valid urls here
require_once __DIR__ . '/urls.php';

if (!array_key_exists($url['path'], $urls)) {
    return false;
}

// alway redirect to an url ending with slashes
$temp_url = parse_url($_SERVER['REQUEST_URI']);
if ( substr($temp_url['path'], -1) != '/') {
    unset($temp_url);
    header('Location:/' . $url['path'] . '/');
    exit;
}

// We can now initialize the application and dispatch urls
require_once __DIR__ . '/init.php';
