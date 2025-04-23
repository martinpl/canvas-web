<?php

namespace Canvas;

// TODO: Add credits
// TODO: Cleanup
class IndexedColorConverter
{
    /**
     * Convert an image resource to indexed color mode.
     * The original image resource will not be changed, a new image resource will be created.
     *
     * @param  ImageResource  $im  The image resource
     * @param  array  $palette  The color palette
     * @param  float  $dither  The Floyd–Steinberg dither amount, value is between 0 and 1 and default value is 0.75
     * @return ImageResource The image resource in indexed colr mode.
     */
    public function convertToIndexedColor($im, $palette, $dither = 0.75)
    {
        $newPalette = [];
        foreach ($palette as $paletteColor) {
            $newPalette[] = [
                'rgb' => $paletteColor,
            ];
        }

        [$width, $height] = [imagesx($im), imagesy($im)];
        $newImage = $this->floydSteinbergDither($im, $width, $height, $newPalette, $dither);

        return $newImage;
    }

    /**
     * Apply Floyd–Steinberg dithering algorithm to an image.
     *
     * http://en.wikipedia.org/wiki/Floyd%E2%80%93Steinberg_dithering
     *
     * @param  ImageResource  $im  The image resource
     * @param  int  $width  The width of an image
     * @param  int  $height  The height of an image
     * @param  array  $palette  The color palette
     * @param  float  $amount  The dither amount(value is between 0 and 1)
     * @return array The pixels after applying Floyd–Steinberg dithering
     */
    private function floydSteinbergDither($im, $width, $height, &$palette, $amount)
    {
        $newImage = imagecreatetruecolor($width, $height);

        for ($i = 0; $i < $height; $i++) {
            if ($i === 0) {
                $currentRowColorStorage = [];
            } else {
                $currentRowColorStorage = $nextRowColorStorage;
            }

            $nextRowColorStorage = [];

            for ($j = 0; $j < $width; $j++) {
                if ($i === 0 && $j === 0) {
                    $color = $this->getRGBColorAt($im, $j, $i);
                } else {
                    $color = $currentRowColorStorage[$j];
                }
                $closestColor = $this->getClosestColor(['rgb' => $color], $palette, 'rgb');
                $closestColor = $closestColor['rgb'];

                if ($j < $width - 1) {
                    if ($i === 0) {
                        $currentRowColorStorage[$j + 1] = $this->getRGBColorAt($im, $j + 1, $i);
                    }
                }
                if ($i < $height - 1) {
                    if ($j === 0) {
                        $nextRowColorStorage[$j] = $this->getRGBColorAt($im, $j, $i + 1);
                    }
                    if ($j < $width - 1) {
                        $nextRowColorStorage[$j + 1] = $this->getRGBColorAt($im, $j + 1, $i + 1);
                    }
                }

                foreach ($closestColor as $key => $channel) {
                    $quantError = $color[$key] - $closestColor[$key];
                    if ($j < $width - 1) {
                        $currentRowColorStorage[$j + 1][$key] += $quantError * 7 / 16 * $amount;
                    }
                    if ($i < $height - 1) {
                        if ($j > 0) {
                            $nextRowColorStorage[$j - 1][$key] += $quantError * 3 / 16 * $amount;
                        }
                        $nextRowColorStorage[$j][$key] += $quantError * 5 / 16 * $amount;
                        if ($j < $width - 1) {
                            $nextRowColorStorage[$j + 1][$key] += $quantError * 1 / 16 * $amount;
                        }
                    }
                }

                $newColor = imagecolorallocate($newImage, $closestColor[0], $closestColor[1], $closestColor[2]);
                imagesetpixel($newImage, $j, $i, $newColor);
            }
        }

        return $newImage;
    }

    /**
     * Get the closest available color from a color palette.
     *
     * @param  array  $pixel  The pixel that contains the color to be calculated
     * @param  array  $palette  The palette that contains all the available colors
     * @param  string  $mode  The calculation mode, the value is 'rgb' or 'lab', 'rgb' is default value.
     * @return array The closest color from the palette
     */
    private function getClosestColor($pixel, &$palette, $mode = 'rgb')
    {

        foreach ($palette as $color) {
            $distance = $this->calculateEuclideanDistanceSquare($pixel[$mode], $color[$mode]);
            if (isset($closestColor)) {
                if ($distance < $closestDistance) {
                    $closestColor = $color;
                    $closestDistance = $distance;
                } elseif ($distance === $closestDistance) {
                    // nothing need to do
                }
            } else {
                $closestColor = $color;
                $closestDistance = $distance;
            }
        }

        return $closestColor;
    }

    /**
     * Calculate the square of the euclidean distance of two colors.
     *
     * @param  array  $p  The first color
     * @param  array  $q  The second color
     * @return float The square of the euclidean distance of first color and second color
     */
    private function calculateEuclideanDistanceSquare($p, $q)
    {
        return pow(($q[0] - $p[0]), 2) + pow(($q[1] - $p[1]), 2) + pow(($q[2] - $p[2]), 2);
    }

    /**
     * Calculate the RGB color of a pixel.
     *
     * @param  ImageResource  $im  The image resource
     * @param  int  $x  The x-coordinate of the pixel
     * @param  int  $y  The y-coordinate of the pixel
     * @return array An array with red, green and blue values of the pixel
     */
    private function getRGBColorAt($im, $x, $y)
    {
        $index = imagecolorat($im, $x, $y);

        return [($index >> 16) & 0xFF, ($index >> 8) & 0xFF, $index & 0xFF];
    }
}
