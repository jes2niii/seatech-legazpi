<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Seeder;

class TestCertificateSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'basic-training'],
            ['name' => 'Basic Training', 'description' => 'STCW basic safety', 'sort_order' => 1, 'is_active' => true]
        );

        $course = Course::firstOrCreate(
            ['code' => 'STCW-BT'],
            [
                'category_id' => $category->id,
                'title' => 'Basic Safety Training',
                'slug' => 'basic-safety-training',
                'description' => 'STCW-compliant basic safety training for all seafarers.',
                'duration' => '5 days',
                'fee' => 15000,
                'max_participants' => 30,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $student = Student::firstOrCreate(
            ['email' => 'juan.delacruz@example.com'],
            [
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'mobile_number' => '0917-123-4567',
            ]
        );

        $this->command->info("Seeded category: {$category->id}, course: {$course->id}, student: {$student->id}");
    }
}
