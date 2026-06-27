<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Course;
use App\Models\TrainingSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ScheduleAssignInstructorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'Super Admin']);
        Role::firstOrCreate(['name' => 'Instructor']);

        // Mirror the RolePermissionSeeder so Super Admin has all permissions.
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

    public function test_admin_can_assign_instructor_to_a_schedule(): void
    {
        $admin = User::factory()->create(['email_verified_at' => now()]);
        $admin->assignRole('Super Admin');

        $instructor = User::factory()->create(['name' => 'Capt. Test']);
        $instructor->assignRole('Instructor');

        $category = Category::create([
            'name' => 'Basic Training',
            'slug' => 'basic-training',
            'is_active' => true,
        ]);
        $course = Course::create([
            'category_id' => $category->id,
            'code' => 'STCW-BT',
            'title' => 'Basic Safety Training',
            'slug' => 'basic-safety-training',
            'fee' => 15000,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.schedules.store'), [
                'course_id' => $course->id,
                'instructor_id' => $instructor->id,
                'start_date' => '2026-09-01',
                'end_date' => '2026-09-05',
                'venue' => 'SEATECH Building A',
                'capacity' => 30,
                'status' => 'upcoming',
            ]);

        $response->assertRedirect(route('admin.schedules.index'));
        $this->assertDatabaseHas('training_schedules', [
            'course_id' => $course->id,
            'instructor_id' => $instructor->id,
        ]);
    }

    public function test_non_instructor_user_cannot_be_assigned_even_if_id_provided(): void
    {
        $admin = User::factory()->create(['email_verified_at' => now()]);
        $admin->assignRole('Super Admin');

        $student = User::factory()->create(); // no role

        $category = Category::create([
            'name' => 'Basic Training',
            'slug' => 'basic-training',
            'is_active' => true,
        ]);
        $course = Course::create([
            'category_id' => $category->id,
            'code' => 'STCW-BT',
            'title' => 'Basic Safety Training',
            'slug' => 'basic-safety-training',
            'fee' => 15000,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.schedules.store'), [
                'course_id' => $course->id,
                'instructor_id' => $student->id,
                'start_date' => '2026-09-01',
                'end_date' => '2026-09-05',
                'venue' => 'SEATECH Building A',
                'capacity' => 30,
                'status' => 'upcoming',
            ])->assertRedirect(route('admin.schedules.index'));

        $schedule = TrainingSchedule::first();
        $this->assertNotNull($schedule);
        $this->assertNull($schedule->instructor_id, 'Non-instructor user should be ignored on save.');
    }

    public function test_instructor_relation_returns_assigned_user(): void
    {
        $instructor = User::factory()->create(['name' => 'Capt. Hook']);
        $instructor->assignRole('Instructor');

        $category = Category::create([
            'name' => 'Basic Training',
            'slug' => 'basic-training',
            'is_active' => true,
        ]);
        $course = Course::create([
            'category_id' => $category->id,
            'code' => 'STCW-BT',
            'title' => 'Basic Safety Training',
            'slug' => 'basic-safety-training',
            'fee' => 15000,
            'is_active' => true,
        ]);
        $schedule = TrainingSchedule::create([
            'course_id' => $course->id,
            'instructor_id' => $instructor->id,
            'start_date' => '2026-09-01',
            'end_date' => '2026-09-05',
            'capacity' => 30,
            'status' => 'upcoming',
        ]);

        $this->assertEquals('Capt. Hook', $schedule->instructor->name);
    }
}
