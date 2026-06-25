<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage enrollments');
    }

    public function index(Request $request)
    {
        $query = Enrollment::with(['student', 'trainingSchedule.course']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $enrollments = $query->latest()->paginate(10);
        return view('admin.enrollments.index', compact('enrollments'));
    }

    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['student', 'trainingSchedule.course', 'approver']);
        return view('admin.enrollments.show', compact('enrollment'));
    }

    public function approve(Enrollment $enrollment)
    {
        $enrollment->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        return redirect()->route('admin.enrollments.index')->with('success', 'Enrollment approved.');
    }

    public function reject(Request $request, Enrollment $enrollment)
    {
        $request->validate(['notes' => 'nullable|string']);
        $enrollment->update([
            'status' => 'rejected',
            'notes' => $request->notes,
            'approved_by' => auth()->id(),
        ]);

        return redirect()->route('admin.enrollments.index')->with('success', 'Enrollment rejected.');
    }

    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();
        return redirect()->route('admin.enrollments.index')->with('success', 'Enrollment deleted.');
    }
}
