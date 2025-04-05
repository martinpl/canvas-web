<?php

namespace App\Http\Controllers;

class PrivateFileController
{
    public function __invoke($path)
    {
        $path = storage_path("app/private/{$path}");
        if (! file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }
}
