<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SiteSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage settings');
    }

    public function edit()
    {
        $settings = SiteSetting::orderBy('group')->orderBy('sort_order')->get()->groupBy('group');
        $groups = [
            'company' => 'Company Identity',
            'address' => 'Address',
            'contact' => 'Contact Information',
            'hours' => 'Office Hours',
            'social' => 'Social Media',
            'maps' => 'Maps',
            'about' => 'About Page Content',
            'stats' => 'Statistics',
            'seo' => 'SEO Defaults',
        ];

        return view('admin.settings.edit', compact('settings', 'groups'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token', '_method');
        $flat = $this->flatten($data);

        DB::transaction(function () use ($flat) {
            foreach ($flat as $key => $value) {
                if (! is_string($value)) {
                    continue;
                }
                SiteSetting::where('key', $key)->update(['value' => $value]);
            }
        });

        Cache::forget('site_settings');
        Cache::forget('site_settings_grouped');

        return redirect()->route('admin.settings.edit')->with('success', 'Site settings updated successfully.');
    }

    private function flatten(array $data, string $prefix = ''): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            $newKey = $prefix === '' ? (string) $key : $prefix.'.'.$key;
            if (is_array($value)) {
                $result = array_merge($result, $this->flatten($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }
}
