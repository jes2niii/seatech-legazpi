<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $rows = [
            [
                'key' => 'stats.graduates',
                'value' => '5000',
                'type' => 'int',
                'group' => 'stats',
                'label' => 'Number of Graduates (display value)',
                'sort_order' => 2,
            ],
            [
                'key' => 'stats.certified_instructors',
                'value' => '25',
                'type' => 'int',
                'group' => 'stats',
                'label' => 'Certified Instructors (display value)',
                'sort_order' => 3,
            ],
        ];

        foreach ($rows as $row) {
            $exists = DB::table('site_settings')->where('key', $row['key'])->exists();
            if (! $exists) {
                DB::table('site_settings')->insert(array_merge($row, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }
    }

    public function down(): void
    {
        DB::table('site_settings')->whereIn('key', [
            'stats.graduates',
            'stats.certified_instructors',
        ])->delete();
    }
};
