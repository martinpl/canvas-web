<?php

namespace App;

// TODO: Add credits
// TODO: Cleanup
class Image
{
    protected $width;

    public $palette;

    public function __construct(protected \GdImage $image)
    {
        $this->width = 800 * 3;
        $this->dither = 0.9;
        $this->palette = [
            'black' => [0, 0, 0],
            'white' => [255, 255, 255],
            'yellow' => [255, 255, 0],
            'red' => [255, 0, 0],
            'blue' => [0, 0, 255],
            'green' => [0, 255, 0],
        ];
    }

    public function __invoke()
    {
        $resource = $this->resize($this->image, $this->width);
        // TODO: Bump saturation?
        // imagefilter($resource, IMG_FILTER_BRIGHTNESS, $this->brightness);
        // imagefilter($resource, IMG_FILTER_CONTRAST, $this->contrast);
        $image = (new IndexedColorConverter)->convertToIndexedColor(
            $resource,
            $this->palette,
            $this->dither
        );
        [$width, $height] = [imagesx($image), imagesy($image)];
        $rawData = '';

        for ($h = 0; $h < $height; $h++) {
            for ($w = 0; $w < $width; $w++) {
                [$r, $g, $b] = $this->indexToRgb(imagecolorat($image, $w, $h));
                $index = $this->closestPaletteIndex($r, $g, $b, array_values($this->palette));

                // Apply shift (avoid index 4)
                if ($index >= 4) {
                    $index += 1;
                }

                $rawData .= chr($index);
            }
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: inline; filename="output.raw"');
        header('Content-Length: '.strlen($rawData));

        echo $rawData;
    }

    private function indexToRgb(int $index): array
    {
        $red = ($index >> 16) & 0xFF;
        $green = ($index >> 8) & 0xFF;
        $blue = $index & 0xFF;

        return [$red, $green, $blue];
    }

    protected function resize(\GdImage $source, int $width)
    {
        $image_x = imagesx($source);
        $image_y = imagesy($source);
        $height = floor($image_y * ($width / $image_x));
        // $width, $height
        $width = 800;
        $height = 480;
        $image = imagecreatetruecolor($width, $height);
        imagecopyresampled($image, $source, 0, 0, 0, 0, $width, $height,
            $image_x, $image_y);

        return $image;
    }

    public function closestPaletteIndex($r, $g, $b, $palette)
    {
        $bestIndex = 0;
        $bestDist = PHP_INT_MAX;

        foreach ($palette as $index => [$pr, $pg, $pb]) {
            $dist = ($r - $pr) ** 2 + ($g - $pg) ** 2 + ($b - $pb) ** 2;
            if ($dist < $bestDist) {
                $bestDist = $dist;
                $bestIndex = $index;
            }
        }

        return $bestIndex;
    }
}
