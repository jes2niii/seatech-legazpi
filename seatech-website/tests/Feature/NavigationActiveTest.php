<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class NavigationActiveTest extends TestCase
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

    private function admin(): User
    {
        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin-nav-'.uniqid().'@test.local',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('Super Admin');

        return $admin;
    }

    public function test_admin_courses_index_marks_courses_link_active_and_schedules_inactive(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get(route('admin.courses.index'));
        $response->assertOk();

        // The Courses link should be the one carrying the active classes.
        $response->assertSeeInOrder([
            'href="'.route('admin.courses.index').'"',
            'bg-[#004080] text-white font-semibold',
        ], false);

        // Schedules link should NOT carry the active classes.
        $schedulesLink = '<a href="'.route('admin.schedules.index').'"';
        $body = $response->getContent();
        $pos = strpos($body, $schedulesLink);
        $this->assertNotFalse($pos);
        $slice = substr($body, $pos, 400);
        $this->assertStringNotContainsString('bg-[#004080] text-white font-semibold', $slice);
    }

    public function test_admin_courses_create_keeps_courses_active_via_wildcard(): void
    {
        $admin = $this->admin();
        $response = $this->actingAs($admin)->get(route('admin.courses.create'));
        $response->assertOk();

        $response->assertSeeInOrder([
            'href="'.route('admin.courses.index').'"',
            'bg-[#004080] text-white font-semibold',
        ], false);
    }

    public function test_admin_activity_log_show_marks_recent_changes_active(): void
    {
        $admin = $this->admin();
        $response = $this->actingAs($admin)->get(route('admin.activity-log.index'));
        $response->assertOk();

        $response->assertSeeInOrder([
            'href="'.route('admin.activity-log.index').'"',
            'bg-[#004080] text-white font-semibold',
        ], false);
    }

    public function test_admin_dashboard_marks_dashboard_active(): void
    {
        $admin = $this->admin();
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertOk();

        $response->assertSeeInOrder([
            'href="'.route('admin.dashboard').'"',
            'bg-[#004080] text-white font-semibold',
        ], false);
    }

    public function test_admin_inactive_section_does_not_get_active_class(): void
    {
        $admin = $this->admin();
        $response = $this->actingAs($admin)->get(route('admin.courses.index'));

        // On Courses page, Enrollments link should NOT have the active styling.
        $enrollmentsLink = '<a href="'.route('admin.enrollments.index').'"';
        $body = $response->getContent();
        $pos = strpos($body, $enrollmentsLink);
        $this->assertNotFalse($pos);
        $slice = substr($body, $pos, 400);
        $this->assertStringNotContainsString('bg-[#004080] text-white font-semibold', $slice);
    }

    public function test_public_home_link_is_active_on_home(): void
    {
        $response = $this->get(route('home'));
        $response->assertOk();
        $response->assertSeeInOrder([
            'href="'.route('home').'"',
            'text-[#003366] font-semibold border-[#D4A017]',
        ], false);
    }

    public function test_public_courses_link_is_active_on_courses_list(): void
    {
        $response = $this->get(route('courses'));
        $response->assertOk();
        $response->assertSeeInOrder([
            'href="'.route('courses').'"',
            'text-[#003366] font-semibold border-[#D4A017]',
        ], false);
    }

    public function test_public_about_link_is_active_on_about(): void
    {
        $response = $this->get(route('about'));
        $response->assertOk();
        $response->assertSeeInOrder([
            'href="'.route('about').'"',
            'text-[#003366] font-semibold border-[#D4A017]',
        ], false);
    }

    public function test_public_courses_link_remains_active_on_course_detail_via_wildcard(): void
    {
        $category = Category::create([
            'name' => 'Basic',
            'slug' => 'basic',
            'is_active' => true,
        ]);
        $course = Course::create([
            'category_id' => $category->id,
            'code' => 'STCW-NAV',
            'title' => 'Navigation',
            'slug' => 'navigation',
            'fee' => 1000,
            'is_active' => true,
        ]);

        $response = $this->get(route('courses.show', $course));
        $response->assertOk();
        $response->assertSeeInOrder([
            'href="'.route('courses').'"',
            'text-[#003366] font-semibold border-[#D4A017]',
        ], false);
    }
}
