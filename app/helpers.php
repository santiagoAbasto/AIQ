<?php

if (! function_exists('media_url')) {
    function media_url(?string $path): string
    {
        if (! $path) {
            return '';
        }

        $path = str_replace('\\', '/', trim($path));

        if (preg_match('/^(https?:)?\/\//', $path) || str_starts_with($path, 'data:')) {
            return $path;
        }

        $path = ltrim($path, '/');
        $storagePath = str_starts_with($path, 'public/') ? substr($path, 7) : $path;

        if (file_exists(public_path($path))) {
            return asset($path);
        }

        return asset('storage/'.ltrim($storagePath, '/'));
    }
}
