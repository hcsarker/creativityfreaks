<?php
// Lightweight .env loader
// Loads KEY=VALUE pairs from a .env file into putenv/$_ENV/$_SERVER

if (!function_exists('cf_load_env')) {
    function cf_load_env(string $dir): void {
        $envPath = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '.env';
        if (!is_readable($envPath)) {
            return;
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) continue;
            $parts = explode('=', $line, 2);
            if (count($parts) !== 2) continue;
            [$name, $value] = $parts;
            $name = trim($name);
            $value = trim($value);
            if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
                $value = substr($value, 1, -1);
            }
            putenv("{$name}={$value}");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Auto-load from project root
$projectRoot = dirname(__DIR__);
cf_load_env($projectRoot);
