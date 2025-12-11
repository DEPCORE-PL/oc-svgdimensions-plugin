<?php namespace Depcore\SvgDimensions;

use System\Classes\PluginBase;
use System\Models\File;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'SVG Dimensions',
            'description' => 'Helpers for reading SVG and image dimensions without touching the database.',
            'author'      => 'Depcore',
            'icon'        => 'icon-picture-o'
        ];
    }

    /**
     * Rejestrujemy funkcje Twig
     */
    public function registerMarkupTags()
    {
        return [
            'functions' => [
                // Tylko SVG
                'svg_dimensions'   => [$this, 'twigSvgDimensions'],
                // Uniwersalne (rastry + SVG)
                'image_dimensions' => [$this, 'twigImageDimensions'],
            ],
        ];
    }

    /**
     * Funkcja Twig: svg_dimensions(file)
     */
    public function twigSvgDimensions($file): ?array
    {
        if (!$file instanceof File) {
            return null;
        }

        return $this->getSvgDimensionsFromFile($file);
    }

    /**
     * Funkcja Twig: image_dimensions(file)
     * - dla JPG/PNG itp. bierze $file->width / $file->height (Octobera)
     * - dla SVG korzysta z getSvgDimensionsFromFile()
     */
    public function twigImageDimensions($file): ?array
    {
        if (!$file instanceof File) {
            return null;
        }

        // Czy to SVG?
        $isSvg =
            $file->content_type === 'image/svg+xml' ||
            strtolower($file->getExtension()) === 'svg';

        if ($isSvg) {
            return $this->getSvgDimensionsFromFile($file);
        }

        // Dla rastrów October i tak potrafi podać wymiary
        $width  = $file->width;
        $height = $file->height;

        if (!empty($width) && !empty($height)) {
            return [
                'width'  => (int) round($width),
                'height' => (int) round($height),
            ];
        }

        return null;
    }

    /**
     * Wewnętrzny helper: odczytuje wymiary z pliku SVG
     */
   protected function getSvgDimensionsFromFile(File $file): ?array
{
    $isSvg =
        $file->content_type === 'image/svg+xml' ||
        strtolower($file->getExtension()) === 'svg';

    if (!$isSvg) {
        return null;
    }

    $path = $file->getLocalPath();
    if (!is_file($path)) {
        return null;
    }

    $svg = @simplexml_load_file($path);
    if (!$svg) {
        return null;
    }

    $attrs = $svg->attributes();

    $widthAttr  = isset($attrs->width)  ? (string) $attrs->width  : '';
    $heightAttr = isset($attrs->height) ? (string) $attrs->height : '';

    $clean = function (string $value) {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        $value = preg_replace('/[^0-9.\-]/', '', $value);

        return $value === '' ? null : (float) $value;
    };

    $w = $clean($widthAttr);
    $h = $clean($heightAttr);

    // Jeśli width/height są zdefiniowane – użyj
    if ($w !== null && $h !== null) {
        return [
            'width'  => (int) round($w),
            'height' => (int) round($h),
        ];
    }

    // Fallback: viewBox="min-x min-y width height"
    if (isset($attrs->viewBox)) {
        $parts = preg_split('/[\s,]+/', trim((string) $attrs->viewBox));

        if (count($parts) === 4) {
            return [
                'width'  => (int) round((float) $parts[2]),
                'height' => (int) round((float) $parts[3]),
            ];
        }
    }

    return null;
}
}
