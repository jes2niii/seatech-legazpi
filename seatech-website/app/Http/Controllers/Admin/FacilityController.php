<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\Searchable;
use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    use Searchable;

    public function __construct()
    {
        $this->middleware('permission:manage gallery');
    }

    public function index(Request $request)
    {
        $query = Facility::query();
        $query = $this->applySearch($query, $request, ['name', 'description']);
        $facilities = $query->latest()->paginate(10)->withQueryString();
        return view('admin.facilities.index', compact('facilities'));
    }

    public function create()
    {
        return view('admin.facilities.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'features' => 'nullable',
            'is_active' => 'boolean',
            'photos' => 'nullable|array|max:10',
            'photos.*' => 'image|mimes:jpeg,png,webp|max:2048',
        ]);

        $data['features'] = $this->parseFeatures($request->input('features'));
        $data['is_active'] = $request->boolean('is_active');

        $facility = Facility::create($data);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $facility->addMedia($photo)->toMediaCollection('photos');
            }
        }

        return redirect()->route('admin.facilities.index')->with('success', 'Facility created successfully.');
    }

    public function edit(Facility $facility)
    {
        return view('admin.facilities.edit', compact('facility'));
    }

    public function update(Request $request, Facility $facility)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'features' => 'nullable',
            'is_active' => 'boolean',
            'photos' => 'nullable|array|max:10',
            'photos.*' => 'image|mimes:jpeg,png,webp|max:2048',
        ]);

        $data['features'] = $this->parseFeatures($request->input('features'));
        $data['is_active'] = $request->boolean('is_active');

        $facility->update($data);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $facility->addMedia($photo)->toMediaCollection('photos');
            }
        }

        return redirect()->route('admin.facilities.index')->with('success', 'Facility updated successfully.');
    }

    private function parseFeatures(?string $value): ?array
    {
        if (empty($value)) {
            return null;
        }
        if (str_starts_with(trim($value), '[')) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }

    public function destroy(Facility $facility)
    {
        $facility->clearMediaCollection('photos');
        $facility->delete();
        return redirect()->route('admin.facilities.index')->with('success', 'Facility deleted successfully.');
    }
}
