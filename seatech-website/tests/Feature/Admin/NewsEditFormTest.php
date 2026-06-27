<?php

namespace Tests\Feature\Admin;

use App\Models\NewsPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class NewsEditFormTest extends TestCase
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
            'email' => 'super-admin-news-'.uniqid().'@test.local',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('Super Admin');

        return $admin;
    }

    public function test_edit_page_renders_without_url_generation_error(): void
    {
        $admin = $this->superAdmin();
        $post = NewsPost::create([
            'title' => 'Test Article',
            'slug' => 'test-article',
            'body' => 'Body content here.',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.news.edit', $post));

        $response->assertOk();
    }

    public function test_edit_form_action_uses_news_parameter_key(): void
    {
        $admin = $this->superAdmin();
        $post = NewsPost::create([
            'title' => 'Test Article',
            'slug' => 'test-article',
            'body' => 'Body content here.',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.news.edit', $post));

        $response->assertOk();
        // The form action should target the update endpoint (no /edit suffix),
        // with the model id in place of {news}.
        $expectedAction = route('admin.news.update', ['news' => $post->id]);
        $response->assertSee('action="'.$expectedAction.'"', false);
    }

    public function test_index_page_edit_link_and_delete_form_use_news_parameter_key(): void
    {
        $admin = $this->superAdmin();
        $post = NewsPost::create([
            'title' => 'Listed Article',
            'slug' => 'listed-article',
            'body' => 'Body.',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.news.index'));

        $response->assertOk();
        $response->assertSee('href="'.route('admin.news.edit', ['news' => $post->id]).'"', false);
        $response->assertSee('action="'.route('admin.news.destroy', ['news' => $post->id]).'"', false);
    }
}
