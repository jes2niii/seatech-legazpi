<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GeneratePwaIconsCommand extends Command
{
    protected $signature = 'pwa:generate-icons {--force : Overwrite existing icon files}';

    protected $description = 'Generate PWA icon PNGs (192, 512, maskable-512) from the source logo.';

    public function handle(): int
    {
        $source = public_path('images/logo.webp');

        if (! file_exists($source)) {
            $this->error("Source logo not found at {$source}");

            return self::FAILURE;
        }

        if (! function_exists('imagecreatefromwebp') || ! function_exists('imagepng')) {
            $this->error('PHP GD extension with WebP support is required.');

            return self::FAILURE;
        }

        $sourceImg = @imagecreatefromwebp($source);
        if (! $sourceImg) {
            $this->error("Failed to decode {$source} as WebP.");

            return self::FAILURE;
        }

        $sourceW = imagesx($sourceImg);
        $sourceH = imagesy($sourceImg);
        $this->info("Source: {$sourceW}x{$sourceH}");

        $targets = [
            ['size' => 192, 'path' => public_path('images/icon-192.png'), 'pad' => 0],
            ['size' => 512, 'path' => public_path('images/icon-512.png'), 'pad' => 0],
            ['size' => 512, 'path' => public_path('images/icon-maskable-512.png'), 'pad' => 0.25],
        ];

        foreach ($targets as $t) {
            if (file_exists($t['path']) && ! $this->option('force')) {
                $this->warn('Skipping (exists): '.basename($t['path']));

                continue;
            }

            $canvasSize = $t['size'];
            $safeSize = (int) round($canvasSize * (1 - 2 * $t['pad']));
            $offset = (int) round(($canvasSize - $safeSize) / 2);

            $canvas = imagecreatetruecolor($canvasSize, $canvasSize);
            // Make canvas fully transparent (maskable icons need transparency).
            $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
            imagefill($canvas, 0, 0, $transparent);
            imagesavealpha($canvas, true);

            $resized = $this->resizeContain($sourceImg, $sourceW, $sourceH, $safeSize);
            if ($resized === null) {
                $this->error("Failed to resize source for {$t['path']}.");
                imagedestroy($canvas);

                continue;
            }

            imagecopy($canvas, $resized, $offset, $offset, 0, 0, $safeSize, $safeSize);
            imagedestroy($resized);

            imagepng($canvas, $t['path'], 6);
            imagedestroy($canvas);

            $this->info('Wrote '.basename($t['path'])." ({$canvasSize}x{$canvasSize})");
        }

        imagedestroy($sourceImg);

        $this->newLine();
        $this->info('PWA icons generated. Reload the site and re-run Lighthouse PWA audit.');

        return self::SUCCESS;
    }

    /**
     * Resize the source image to fit inside $targetSize x $targetSize, preserving aspect.
     */
    private function resizeContain($src, int $srcW, int $srcH, int $targetSize)
    {
        $scale = $targetSize / max($srcW, $srcH);
        $newW = (int) round($srcW * $scale);
        $newH = (int) round($srcH * $scale);

        $dst = imagecreatetruecolor($newW, $newH);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefill($dst, 0, 0, $transparent);

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $srcW, $srcH);

        return $dst;
    }
}
