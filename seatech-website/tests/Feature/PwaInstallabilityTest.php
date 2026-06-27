<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PwaInstallabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_manifest_json_is_served_with_required_pwa_fields(): void
    {
        $response = $this->get('/manifest.json');
        $response->assertOk();
        $this->assertStringContainsString('application/manifest+json', $response->headers->get('Content-Type') ?? '');

        $manifest = $response->json();
        $this->assertIsArray($manifest);

        // Required fields per the Web App Manifest spec.
        $this->assertSame('SEATECH', $manifest['short_name'] ?? null);
        $this->assertSame('/', $manifest['start_url'] ?? null);
        $this->assertSame('/', $manifest['scope'] ?? null);
        $this->assertSame('standalone', $manifest['display'] ?? null);
        $this->assertSame('#003366', $manifest['theme_color'] ?? null);
        $this->assertNotEmpty($manifest['name'] ?? '');
        $this->assertNotEmpty($manifest['icons'] ?? []);

        // At least one icon must be 192x192 and one must be 512x512.
        $sizes = array_column($manifest['icons'], 'sizes');
        $this->assertContains('192x192', $sizes);
        $this->assertContains('512x512', $sizes);

        // A maskable icon should be present for Android adaptive icons.
        $purposes = array_column($manifest['icons'], 'purpose');
        $this->assertContains('maskable', $purposes);
    }

    public function test_home_page_includes_pwa_meta_tags(): void
    {
        $response = $this->get('/');
        $response->assertOk();

        $response->assertSee('rel="manifest"', false);
        $response->assertSee('href="'.url('manifest.json').'"', false);
        $response->assertSee('<meta name="theme-color" content="#003366">', false);
        $response->assertSee('rel="apple-touch-icon"', false);
        $response->assertSee('href="'.url('images/icon-192.png').'"', false);
        $response->assertSee('<meta name="apple-mobile-web-app-capable" content="yes">', false);
    }

    public function test_service_worker_file_is_served_as_javascript(): void
    {
        $response = $this->get('/sw.js');
        $response->assertOk();
        $this->assertStringContainsString('javascript', $response->headers->get('Content-Type') ?? '');
        $response->assertSee('CACHE_NAME', false);
        $response->assertSee('seatech-v1', false);
    }

    public function test_offline_page_is_served(): void
    {
        $response = $this->get('/offline.html');
        $response->assertOk();
        $response->assertSee("You're offline", false);
        $response->assertSee('Try again', false);
    }

    public function test_pwa_assets_are_physically_present(): void
    {
        $this->assertFileExists(public_path('manifest.json'));
        $this->assertFileExists(public_path('sw.js'));
        $this->assertFileExists(public_path('offline.html'));
        $this->assertFileExists(public_path('images/icon-192.png'));
        $this->assertFileExists(public_path('images/icon-512.png'));
        $this->assertFileExists(public_path('images/icon-maskable-512.png'));
    }
}
