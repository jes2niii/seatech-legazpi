<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Course;
use App\Models\NewsPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ActivityLogFormattingTest extends TestCase
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
            'email' => 'super-admin-'.uniqid().'@test.local',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('Super Admin');

        return $admin;
    }

    private function makeCourse(): Course
    {
        $category = Category::firstOrCreate(
            ['slug' => 'basic-training'],
            ['name' => 'Basic Training', 'is_active' => true]
        );
        $code = 'STCW-'.substr(md5(uniqid('', true)), 0, 6);

        return Course::create([
            'category_id' => $category->id,
            'code' => $code,
            'title' => 'Radio Telephone Communication and Radar',
            'slug' => 'radio-telephone-communication-and-radar-'.$code,
            'fee' => 4200,
            'is_active' => true,
        ]);
    }

    public function test_index_page_renders_friendly_columns_and_hides_programmer_text(): void
    {
        $admin = $this->superAdmin();
        $course = $this->makeCourse();

        $response = $this->actingAs($admin)
            ->get(route('admin.activity-log.index'));

        $response->assertOk();
        $response->assertSee('Recent Changes');
        $response->assertSee('Course');
        $response->assertSee('Certificate');
        // The technical column "Causer ID" should be gone.
        $response->assertDontSee('Causer ID');
        // The old "Activity Log" heading is gone.
        $response->assertDontSee('>Activity Log<');
        // The old "Subject Type" filter is gone.
        $response->assertDontSee('Subject Type');
        // The raw App\Models namespace should never appear in the page body.
        $response->assertDontSee('App\\Models', false);
    }

    public function test_show_page_uses_friendly_labels_and_formatted_values(): void
    {
        $admin = $this->superAdmin();
        $course = $this->makeCourse();

        $activity = Activity::where('log_name', 'course')
            ->where('event', 'created')
            ->where('subject_type', Course::class)
            ->where('subject_id', $course->id)
            ->latest()
            ->firstOrFail();

        $response = $this->actingAs($admin)
            ->get(route('admin.activity-log.show', $activity));

        $response->assertOk();
        // Friendly labels appear
        $response->assertSee('Course Code');
        $response->assertSee('Title');
        $response->assertSee('Category');
        $response->assertSee('Fee');
        $response->assertSee('Status');
        // The "What changed" heading replaced "Changed Fields"
        $response->assertSee('What changed');
        $response->assertDontSee('Changed Fields');

        // Formatted values
        $response->assertSee('₱4,200.00');
        $response->assertSee('Active');

        // FK resolution: Category is resolved to "Basic Training"
        $response->assertSee('Basic Training');

        // No raw column names
        $response->assertDontSee('category_id');
        $response->assertDontSee('is_active');
        $response->assertDontSee('learning_outcomes');
        $response->assertDontSee('slug');
        $response->assertDontSee('archived_at');

        // No programmer metadata rows
        $response->assertDontSee('Batch UUID');
        $response->assertDontSee('Log Name');
        $response->assertDontSee('App\\Models', false);
    }

    public function test_show_page_for_deleted_news_hides_technical_garbage_and_shows_friendly_label(): void
    {
        $admin = $this->superAdmin();
        $post = NewsPost::create([
            'title' => 'Batch Opening for 2026',
            'slug' => 'batch-opening-2026',
            'body' => 'Full body content.',
            'is_published' => true,
            'published_at' => now(),
        ]);
        $post->delete();

        $activity = Activity::where('log_name', 'news')
            ->where('event', 'deleted')
            ->latest()
            ->firstOrFail();

        $response = $this->actingAs($admin)
            ->get(route('admin.activity-log.show', $activity));

        $response->assertOk();
        $response->assertSee('Batch Opening for 2026');
        $response->assertSee('(deleted)');
        $response->assertDontSee('App\\Models', false);
        $response->assertDontSee('Batch UUID');
    }
}
