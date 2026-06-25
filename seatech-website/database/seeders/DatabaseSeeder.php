<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        $superAdmin = User::where('email', 'admin@seatechmaritime.com')->first();
        if (!$superAdmin) {
            $superAdmin = User::factory()->create([
                'name' => 'Super Admin',
                'email' => 'admin@seatechmaritime.com',
            ]);
        }

        $superAdmin->assignRole('Super Admin');
    }
}
