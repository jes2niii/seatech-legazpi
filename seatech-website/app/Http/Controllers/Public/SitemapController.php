<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\NewsPost;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $staticUrls = [
            ['url' => route('home'), 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['url' => route('about'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => route('courses'), 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['url' => route('calendar'), 'priority' => '0.7', 'changefreq' => 'daily'],
            ['url' => route('facilities'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => route('news'), 'priority' => '0.8', 'changefreq' => 'daily'],
            ['url' => route('contact'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['url' => route('verify.certificate'), 'priority' => '0.6', 'changefreq' => 'monthly'],
        ];

        $courseUrls = Course::where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(fn ($c) => [
                'url' => route('courses.show', $c),
                'priority' => '0.8',
                'changefreq' => 'weekly',
                'lastmod' => $c->updated_at?->toAtomString(),
            ])
            ->all();

        $newsUrls = NewsPost::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->get()
            ->map(fn ($n) => [
                'url' => route('news.show', $n),
                'priority' => '0.7',
                'changefreq' => 'monthly',
                'lastmod' => $n->updated_at?->toAtomString() ?? $n->published_at?->toAtomString(),
            ])
            ->all();

        $urls = array_merge($staticUrls, $courseUrls, $newsUrls);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $entry) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . e($entry['url']) . "</loc>\n";
            if (! empty($entry['lastmod'])) {
                $xml .= "    <lastmod>" . e($entry['lastmod']) . "</lastmod>\n";
            }
            $xml .= "    <changefreq>" . e($entry['changefreq']) . "</changefreq>\n";
            $xml .= "    <priority>" . e($entry['priority']) . "</priority>\n";
            $xml .= "  </url>\n";
        }
        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
    }

    public function robots(): Response
    {
        $sitemap = route('sitemap');
        $body = "User-agent: *\n";
        $body .= "Allow: /\n";
        $body .= "Disallow: /admin/\n";
        $body .= "Disallow: /registrar/\n";
        $body .= "Disallow: /coordinator/\n";
        $body .= "Disallow: /instructor/\n";
        $body .= "Disallow: /student/\n";
        $body .= "Disallow: /login\n";
        $body .= "Disallow: /register\n";
        $body .= "Disallow: /forgot-password\n";
        $body .= "Disallow: /reset-password\n";
        $body .= "Disallow: /verify-email\n";
        $body .= "Disallow: /confirm-password\n";
        $body .= "Disallow: /enroll/\n";
        $body .= "\n";
        $body .= "Sitemap: {$sitemap}\n";

        return response($body, 200, ['Content-Type' => 'text/plain; charset=utf-8']);
    }
}
