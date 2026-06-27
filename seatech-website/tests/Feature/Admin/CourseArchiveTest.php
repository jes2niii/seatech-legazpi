<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CourseArchiveTest extends TestCase
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

    private function makeCourse(array $attrs = []): Course
    {
        $category = Category::firstOrCreate(
            ['slug' => 'basic-training'],
            ['name' => 'Basic Training', 'is_active' => true]
        );
        $defaultCode = 'STCW-'.substr(md5(uniqid('', true)), 0, 6);

        return Course::create(array_merge([
            'category_id' => $category->id,
            'code' => $defaultCode,
            'title' => 'Basic Safety Training',
            'slug' => 'basic-safety-training-'.$defaultCode,
            'fee' => 15000,
            'is_active' => true,
        ], $attrs));
    }

    public function test_admin_can_archive_a_course(): void
    {
        $admin = User::factory()->create(['email_verified_at' => now()]);
        $admin->assignRole('Super Admin');

        $course = $this->makeCourse();
        $this->assertNull($course->archived_at);

        $this->actingAs($admin)
            ->post(route('admin.courses.archive', $course))
            ->assertRedirect(route('admin.courses.index', ['filter' => 'archived']));

        $course->refresh();
        $this->assertNotNull($course->archived_at);
    }

    public function test_admin_can_restore_an_archived_course(): void
    {
        $admin = User::factory()->create(['email_verified_at' => now()]);
        $admin->assignRole('Super Admin');

        $course = $this->makeCourse(['archived_at' => now()]);

        $this->actingAs($admin)
            ->post(route('admin.courses.restore', $course))
            ->assertRedirect(route('admin.courses.index'));

        $course->refresh();
        $this->assertNull($course->archived_at);
    }

    public function test_archived_courses_are_hidden_from_public_index(): void
    {
        $this->makeCourse(['code' => 'A1', 'title' => 'Visible', 'slug' => 'visible']);
        $this->makeCourse(['code' => 'A2', 'title' => 'Hidden', 'slug' => 'hidden', 'archived_at' => now()]);

        $response = $this->get(route('courses'));
        $response->assertOk();
        $response->assertSee('Visible');
        $response->assertDontSee('Hidden');
    }

    public function test_archived_course_detail_returns_404(): void
    {
        $course = $this->makeCourse(['archived_at' => now()]);
        $this->get(route('courses.show', $course))->assertNotFound();
    }
}
