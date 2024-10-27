<?php

spl_autoload_register(function ($path) {
    $filePath = str_replace(['\\', '_'], DIRECTORY_SEPARATOR, $path) . '.php';
    $baseDir = __DIR__ . '\\src\\';
    $fullPath = $baseDir . $filePath;

    if (file_exists($fullPath)) {
        require_once $fullPath;
    }
});

?>