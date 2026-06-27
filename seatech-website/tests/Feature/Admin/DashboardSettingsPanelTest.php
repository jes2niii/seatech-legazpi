<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DashboardSettingsPanelTest extends TestCase
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
            'email' => 'super-admin-dash-'.uniqid().'@test.local',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('Super Admin');

        return $admin;
    }

    public function test_dashboard_shows_all_five_setting_groups_with_seeded_values(): void
    {
        $admin = $this->superAdmin();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertOk();

        // Section title
        $response->assertSee('Site Settings');

        // All 5 group titles
        $response->assertSee('Company');
        $response->assertSee('Contact');
        $response->assertSee('Office Hours');
        $response->assertSee('Social Media');
        $response->assertSee('Statistics');

        // Seeded values (from the site_settings migration defaults).
        // The {{ }} echo escapes `&` to `&amp;`, so check for the escaped form.
        $response->assertSee('SEATECH Legazpi');
        $response->assertSee('info@seatechmaritime.com');
        $response->assertSee('8:00 AM - 5:00 PM');
        $response->assertSee('Maritime Training'); // company full name (escaped &amp; in HTML)
        $response->assertSee('Assessment Center'); // company full name (escaped &amp; in HTML)

        // Statistics get the "+" suffix
        $response->assertSee('15+');
    }

    public function test_dashboard_settings_edit_link_points_to_admin_settings_edit(): void
    {
        $admin = $this->superAdmin();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertOk();
        $response->assertSee('href="'.route('admin.settings.edit').'"', false);
    }

    public function test_dashboard_settings_card_handles_missing_social_value(): void
    {
        $admin = $this->superAdmin();

        // All social values are null in the seed; the partial should render "Not set".
        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertOk();
        // "Not set" appears multiple times (one per missing social row).
        $content = $response->getContent();
        $count = substr_count($content, 'Not set');
        $this->assertGreaterThanOrEqual(4, $count, 'Expected at least 4 "Not set" pills (one per social row).');
    }
}
