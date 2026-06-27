<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class HamburgerMenuTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'Super Admin']);
        Role::firstOrCreate(['name' => 'Student']);

        $permissions = [
            'manage courses', 'manage schedules', 'manage enrollments',
            'manage users', 'manage news', 'manage gallery',
            'manage inquiries', 'manage certificates', 'view reports',
            'manage settings',
        ];
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }
        Role::findByName('Super Admin')->givePermissionTo(Permission::all());
    }

    private function superAdmin(): User
    {
        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'super-admin-hb-'.uniqid().'@test.local',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('Super Admin');

        return $admin;
    }

    private function student(): User
    {
        $student = User::factory()->create([
            'name' => 'Test Student',
            'email' => 'student-hb-'.uniqid().'@test.local',
            'email_verified_at' => now(),
        ]);
        $student->assignRole('Student');

        return $student;
    }

    public function test_public_home_has_hamburger_button_and_offcanvas_drawer(): void
    {
        $response = $this->get('/');
        $response->assertOk();

        // Body has Alpine state for the mobile nav.
        $response->assertSee('x-data="', false);
        $response->assertSee('mobileNavOpen', false);

        // Hamburger button is present with the right aria-controls hook.
        $response->assertSee('aria-controls="public-mobile-nav"', false);
        $response->assertSee('aria-label="Open menu"', false);
        $response->assertSee('lg:hidden', false);

        // Offcanvas drawer container is present (aria-labelled).
        $response->assertSee('id="public-mobile-nav"', false);
        $response->assertSee('role="dialog"', false);
        $response->assertSee('aria-label="Mobile navigation"', false);

        // Drawer has a close (×) button.
        $response->assertSee('aria-label="Close menu"', false);

        // All 7 public nav links are inside the drawer.
        $drawer = $this->extractOffcanvas($response->getContent(), 'public-mobile-nav');
        foreach (['Home', 'About', 'Courses', 'Calendar', 'Facilities', 'News', 'Contact'] as $link) {
            $this->assertStringContainsString('>'.$link.'<', $drawer, "Drawer should contain link: {$link}");
        }
    }

    public function test_admin_dashboard_has_working_offcanvas_sidebar(): void
    {
        $admin = $this->superAdmin();
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertOk();

        // Body has Alpine state for the sidebar.
        $response->assertSee('x-data="', false);
        $response->assertSee('sidebarOpen', false);

        // Sidebar uses offcanvas classes (translate-x-* with lg:translate-x-0).
        $content = $response->getContent();
        $this->assertStringContainsString('fixed inset-y-0 left-0 z-40', $content);
        $this->assertStringContainsString('-translate-x-full', $content);
        $this->assertStringContainsString('lg:translate-x-0', $content);

        // Sidebar close (×) button is present (visible only on mobile).
        $this->assertStringContainsString('aria-label="Close menu"', $content);

        // Backdrop element is present.
        $this->assertStringContainsString('x-show="sidebarOpen"', $content);
        $this->assertStringContainsString('lg:hidden fixed inset-0 bg-black', $content);
    }

    public function test_student_dashboard_has_offcanvas_sidebar(): void
    {
        $student = $this->student();
        $response = $this->actingAs($student)->get(route('student.dashboard'));
        $response->assertOk();

        // Body has Alpine state.
        $response->assertSee('x-data="', false);
        $response->assertSee('sidebarOpen', false);

        // Sidebar is the offcanvas drawer with the student-mobile-nav id.
        $content = $response->getContent();
        $this->assertStringContainsString('id="student-mobile-nav"', $content);
        $this->assertStringContainsString('fixed inset-y-0 left-0 z-40', $content);
        $this->assertStringContainsString('-translate-x-full', $content);
        $this->assertStringContainsString('lg:translate-x-0', $content);

        // Sidebar close button.
        $this->assertStringContainsString('aria-label="Close menu"', $content);
    }

    public function test_student_header_has_hamburger_button(): void
    {
        $student = $this->student();
        $response = $this->actingAs($student)->get(route('student.dashboard'));
        $response->assertOk();

        $response->assertSee('aria-controls="student-mobile-nav"', false);
        $response->assertSee('aria-label="Open menu"', false);
    }

    public function test_admin_header_existing_hamburger_is_now_wired_to_xdata(): void
    {
        $admin = $this->superAdmin();
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertOk();

        // The existing admin-header hamburger should still be present,
        // and now its click handler resolves to a defined `sidebarOpen` state.
        $response->assertSee('@click="sidebarOpen = !sidebarOpen"', false);
        $response->assertSee('lg:hidden', false);
    }

    /**
     * Extract the HTML of the off-canvas drawer with the given id.
     */
    private function extractOffcanvas(string $html, string $id): string
    {
        if (preg_match('/<div[^>]*id="'.preg_quote($id, '/').'"[^>]*>(.*?)<\/div>\s*<\/header>/s', $html, $matches)) {
            return $matches[0];
        }
        // Fallback: find the first matching <aside> or <div> and grab to its closing tag.
        if (preg_match('/<div[^>]*id="'.preg_quote($id, '/').'"[\s\S]*?<\/nav>\s*<\/div>/s', $html, $matches)) {
            return $matches[0];
        }

        return $html;
    }
}
