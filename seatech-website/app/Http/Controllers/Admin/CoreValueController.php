<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\Searchable;
use App\Http\Controllers\Controller;
use App\Models\CoreValue;
use Illuminate\Http\Request;

class CoreValueController extends Controller
{
    use Searchable;

    public function __construct()
    {
        $this->middleware('permission:manage gallery');
    }

    public function index(Request $request)
    {
        $query = CoreValue::query();
        $query = $this->applySearch($query, $request, ['name', 'description']);
        $values = $query->orderBy('sort_order')->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.core-values.index', compact('values'));
    }

    public function create()
    {
        return view('admin.core-values.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'required|string|max:50',
            'color' => 'required|string|max:20',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        CoreValue::create($data);

        return redirect()->route('admin.core-values.index')->with('success', 'Core value added successfully.');
    }

    public function edit(CoreValue $coreValue)
    {
        return view('admin.core-values.edit', ['value' => $coreValue]);
    }

    public function update(Request $request, CoreValue $coreValue)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'required|string|max:50',
            'color' => 'required|string|max:20',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $coreValue->update($data);

        return redirect()->route('admin.core-values.index')->with('success', 'Core value updated successfully.');
    }

    public function destroy(CoreValue $coreValue)
    {
        $coreValue->delete();

        return redirect()->route('admin.core-values.index')->with('success', 'Core value deleted successfully.');
    }
}
