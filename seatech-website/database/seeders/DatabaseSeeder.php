<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        $superAdmin = User::where('email', 'admin@seatechmaritime.com')->first();
        if (! $superAdmin) {
            $superAdmin = User::factory()->create([
                'name' => 'Super Admin',
                'email' => 'admin@seatechmaritime.com',
            ]);
        }

        $superAdmin->assignRole('Super Admin');

        $instructor = User::where('email', 'instructor@seatechmaritime.com')->first();
        if (! $instructor) {
            $instructor = User::create([
                'name' => 'Capt. Juan Dela Cruz',
                'email' => 'instructor@seatechmaritime.com',
                'password' => Hash::make('password'),
            ]);
        }
        $instructor->assignRole('Instructor');
    }
}
