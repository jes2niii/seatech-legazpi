<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StickyHeaderTest extends TestCase
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
            'email' => 'super-admin-sticky-'.uniqid().'@test.local',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('Super Admin');

        return $admin;
    }

    private function student(): User
    {
        $student = User::factory()->create([
            'name' => 'Test Student',
            'email' => 'student-sticky-'.uniqid().'@test.local',
            'email_verified_at' => now(),
        ]);
        $student->assignRole('Student');

        return $student;
    }

    public function test_admin_layout_uses_scroll_container_and_sticky_header_classes(): void
    {
        $admin = $this->superAdmin();
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertOk();

        $content = $response->getContent();

        // Inner content column is the scroll container.
        $this->assertStringContainsString('h-screen overflow-y-auto', $content);

        // Admin <header> has the sticky classes.
        $this->assertMatchesRegularExpression(
            '/<header[^>]*\bsticky top-0 z-20\b[^>]*>/',
            $content,
            'Admin <header> should have sticky top-0 z-20'
        );
    }

    public function test_student_layout_uses_scroll_container_and_sticky_header_classes(): void
    {
        $student = $this->student();
        $response = $this->actingAs($student)->get(route('student.dashboard'));
        $response->assertOk();

        $content = $response->getContent();

        // Inner content column is the scroll container.
        $this->assertStringContainsString('h-screen overflow-y-auto', $content);

        // Student <header> has the sticky classes.
        $this->assertMatchesRegularExpression(
            '/<header[^>]*\bsticky top-0 z-20\b[^>]*>/',
            $content,
            'Student <header> should have sticky top-0 z-20'
        );
    }

    public function test_admin_and_student_headers_use_identical_responsive_padding_and_text_size(): void
    {
        $adminContent = $this->actingAs($this->superAdmin())->get(route('admin.dashboard'))->getContent();
        $studentContent = $this->actingAs($this->student())->get(route('student.dashboard'))->getContent();

        // Both headers should use the same responsive padding.
        $adminHasPadding = (bool) preg_match('/<header[^>]*\bpx-4 sm:px-6 py-3 sm:py-4\b[^>]*>/', $adminContent);
        $studentHasPadding = (bool) preg_match('/<header[^>]*\bpx-4 sm:px-6 py-3 sm:py-4\b[^>]*>/', $studentContent);
        $this->assertTrue($adminHasPadding, 'Admin header should use px-4 sm:px-6 py-3 sm:py-4 padding');
        $this->assertTrue($studentHasPadding, 'Student header should use px-4 sm:px-6 py-3 sm:py-4 padding');

        // Both headers should use the same responsive text size on the <h1>.
        $this->assertStringContainsString('text-base sm:text-lg font-semibold', $adminContent);
        $this->assertStringContainsString('text-base sm:text-lg font-semibold', $studentContent);
    }

    public function test_sticky_header_does_not_break_hamburger_button_or_xdata(): void
    {
        $adminContent = $this->actingAs($this->superAdmin())->get(route('admin.dashboard'))->getContent();
        $studentContent = $this->actingAs($this->student())->get(route('student.dashboard'))->getContent();

        // Body still has x-data so the hamburger works.
        $this->assertStringContainsString('x-data="{ sidebarOpen: false }"', $adminContent);
        $this->assertStringContainsString('x-data="{ sidebarOpen: false }"', $studentContent);

        // Hamburger button still present.
        $this->assertStringContainsString('aria-label="Open menu"', $adminContent);
        $this->assertStringContainsString('aria-label="Open menu"', $studentContent);
    }
}
