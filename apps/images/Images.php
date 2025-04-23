<?php

namespace Apps\Images;

use Illuminate\Support\Facades\Storage;

class Images extends \App\App
{
    public function __invoke()
    {
        $image = $this->metadata()['list'][array_rand($this->metadata()['list'])];

        if (str($image)->isUrl()) {
            $im = imagecreatefromstring(file_get_contents($image));
        } else {
            $path = Storage::path($image);
            if (preg_match('/png/i', mime_content_type($path))) {
                $im = imagecreatefrompng($path);
            } else {
                $im = imagecreatefromjpeg($path);
            }
        }

        return $im;
    }
}
