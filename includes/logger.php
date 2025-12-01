<?php
// Minimal file logger for errors

function cf_log(string $message, array $context = []): void {
    $dir = __DIR__ . '/../storage/logs';
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    $file = $dir . '/app.log';
    $timestamp = date('Y-m-d H:i:s');
    $ctx = $context ? ' ' . json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : '';
    @file_put_contents($file, "[$timestamp] $message$ctx\n", FILE_APPEND);
}
