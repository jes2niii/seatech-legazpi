<?php

use App\Models\SiteSetting;

if (! function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        $all = SiteSetting::getAllCached();

        if (! array_key_exists($key, $all)) {
            return $default;
        }

        $value = $all[$key];

        if ($value === null) {
            return $default;
        }

        $row = \Illuminate\Support\Facades\DB::table('site_settings')
            ->where('key', $key)
            ->first();

        if (! $row) {
            return $value;
        }

        return match ($row->type) {
            'int', 'integer' => (int) $value,
            'bool', 'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($value, true),
            'float' => (float) $value,
            default => $value,
        };
    }
}

if (! function_exists('setting_group')) {
    function setting_group(string $prefix): array
    {
        $all = SiteSetting::getAllCached();
        $result = [];
        $prefixWithDot = $prefix . '.';

        foreach ($all as $key => $value) {
            if (str_starts_with($key, $prefixWithDot)) {
                $shortKey = substr($key, strlen($prefixWithDot));
                $result[$shortKey] = $value;
            }
        }

        return $result;
    }
}

if (! function_exists('format_rich_text')) {
    /**
     * Convert plain-text into safe HTML.
     *
     * Lines starting with "- ", "* ", or "• " become <ul><li> items.
     * A list with only one item is rendered as a <p> instead of <ul>.
     * Blank lines separate paragraphs.
     * All output is HTML-escaped (XSS-safe).
     */
    function format_rich_text(?string $text): string
    {
        if ($text === null || trim($text) === '') {
            return '';
        }

        $html = '';
        $inList = false;
        $listItems = [];
        $paragraphLines = [];

        $flushParagraph = function () use (&$paragraphLines, &$html) {
            if (! empty($paragraphLines)) {
                $p = e(implode(' ', $paragraphLines));
                $html .= "<p class=\"mb-3 leading-relaxed\">{$p}</p>\n";
                $paragraphLines = [];
            }
        };

        $closeList = function () use (&$inList, &$html, &$listItems) {
            if ($inList) {
                if (count($listItems) === 1) {
                    $html .= '<p class="mb-3 leading-relaxed">' . e($listItems[0]) . "</p>\n";
                } else {
                    $html .= "<ul class=\"list-disc pl-6 space-y-1 mb-4\">\n";
                    foreach ($listItems as $item) {
                        $html .= '  <li>' . e($item) . "</li>\n";
                    }
                    $html .= "</ul>\n";
                }
                $inList = false;
                $listItems = [];
            }
        };

        $lines = preg_split('/\R/u', $text);
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === '') {
                $flushParagraph();
                $closeList();
                continue;
            }
            if (preg_match('/^[-*•]\s+(.+)$/u', $trimmed, $m)) {
                $flushParagraph();
                $inList = true;
                $listItems[] = $m[1];
            } else {
                $closeList();
                $paragraphLines[] = $trimmed;
            }
        }
        $flushParagraph();
        $closeList();

        return trim($html);
    }
}

if (! function_exists('core_value_icon')) {
    /**
     * Return the SVG path markup for a preset core value icon name.
     */
    function core_value_icon(string $name): string
    {
        $paths = [
            'star'        => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.299.921-.755 1.688-1.539 1.118l-3.36-2.448a1 1 0 00-1.175 0l-3.371 2.448c-.783.57-1.838-.197-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.05 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"/>',
            'heart'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
            'shield'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
            'check'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'users'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>',
            'lightbulb'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>',
            'handshake'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 6l-4-4-4 4m8 0v6m0 0l4 4-4 4m4-4H6m12 0v-6m0 0l-4-4 4-4M6 12l4-4"/>',
            'compass'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 18a8 8 0 110-16 8 8 0 010 16z" fill="currentColor"/>',
            'anchor'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/><circle cx="12" cy="5" r="3" fill="currentColor"/>',
            'clock'       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'sliders'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>',
            'shield-alert'=> '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016zM12 9v2m0 4h.01"/>',
        ];

        $path = $paths[$name] ?? $paths['star'];

        return '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">' . $path . '</svg>';
    }
}
