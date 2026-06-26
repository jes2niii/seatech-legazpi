<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = [
        'key', 'value', 'type', 'group', 'label', 'description', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('site_settings');
        });

        static::deleted(function () {
            Cache::forget('site_settings');
        });
    }

    public static function getAllCached(): array
    {
        return Cache::remember('site_settings', 86400, function () {
            return static::pluck('value', 'key')->toArray();
        });
    }

    public static function getGroupedCached(): array
    {
        return Cache::remember('site_settings_grouped', 86400, function () {
            return static::orderBy('sort_order')
                ->get()
                ->groupBy('group')
                ->toArray();
        });
    }
}
