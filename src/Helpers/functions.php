<?php

if (!function_exists('remove_trailing_slashes')) {
    function remove_trailing_slashes(?string $path = ''): string
    {
        $path = ltrim($path, '/');
        return rtrim($path, '/');
    }
}

if (!function_exists('isTesting')) {
    function isTesting(): bool
    {
        return config('app.env') === 'testing';
    }
}

if (!function_exists('isImage')) {
    function isImage(string $extension): bool
    {
        return in_array($extension, config('tablar-kit.mimetypes.images'));
    }
}
