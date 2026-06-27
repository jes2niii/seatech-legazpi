<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ViewSiteLinkTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'Super Admin']);

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
            'email' => 'super-admin-viewsite-'.uniqid().'@test.local',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('Super Admin');

        return $admin;
    }

    public function test_admin_dashboard_header_has_view_site_link_pointing_to_homepage(): void
    {
        $admin = $this->superAdmin();
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertOk();

        $content = $response->getContent();

        // The link text appears.
        $response->assertSee('View Site', false);

        // It points to the public homepage.
        $response->assertSee('href="'.url('/').'"', false);
    }

    public function test_admin_dashboard_view_site_link_is_hidden_on_mobile(): void
    {
        $admin = $this->superAdmin();
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertOk();

        $content = $response->getContent();

        // The link should use the "hidden on mobile, visible on sm+" pattern
        // (matching the student header's link).
        $this->assertStringContainsString('hidden sm:inline-block', $content);
    }

    public function test_student_dashboard_still_has_view_site_link(): void
    {
        // Regression check: the student header already has the link — make
        // sure our admin-only change didn't disturb it.
        Role::firstOrCreate(['name' => 'Student']);
        $student = User::factory()->create([
            'name' => 'Test Student',
            'email' => 'student-viewsite-'.uniqid().'@test.local',
            'email_verified_at' => now(),
        ]);
        $student->assignRole('Student');

        $response = $this->actingAs($student)->get(route('student.dashboard'));
        $response->assertOk();
        $response->assertSee('View Site', false);
    }
}
