<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Facility;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $categories = [
            'classrooms' => 'Classrooms',
            'simulators' => 'Simulators',
            'safety' => 'Safety Training Areas',
            'assessment' => 'Assessment Rooms',
        ];

        return view('public.facilities', compact('facilities', 'categories'));
    }
}
