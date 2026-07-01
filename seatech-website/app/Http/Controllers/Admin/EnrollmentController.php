<?php

namespace App\Http\Controllers\Admin;

use App\Exports\EnrollmentsExport;
use App\Http\Controllers\Concerns\Searchable;
use App\Http\Controllers\Controller;
use App\Mail\EnrollmentApproved;
use App\Mail\EnrollmentRejected;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class EnrollmentController extends Controller
{
    use Searchable;

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

        $query = $this->applySearch($query, $request, ['certificate_number'], [
            'student' => ['first_name', 'last_name', 'email'],
            'trainingSchedule.course' => ['title', 'code'],
        ]);

        $enrollments = $query->latest()->paginate(10)->withQueryString();

        return view('admin.enrollments.index', compact('enrollments'));
    }

    public function export(Request $request)
    {
        $filename = 'enrollments-'.now()->format('Y-m-d-His').'.xlsx';

        return Excel::download(new EnrollmentsExport, $filename);
    }

    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['student', 'trainingSchedule.course', 'approver']);

        $requirementLabels = config('enrollment.requirements', []);

        return view('admin.enrollments.show', compact('enrollment', 'requirementLabels'));
    }

    public function approve(Enrollment $enrollment)
    {
        $enrollment->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        $enrollment->load('student', 'trainingSchedule.course');
        if ($enrollment->student?->email) {
            Mail::to($enrollment->student->email)->send(new EnrollmentApproved($enrollment));
        }

        return redirect()->route('admin.enrollments.index')->with('success', 'Enrollment approved. Notification email sent.');
    }

    public function reject(Request $request, Enrollment $enrollment)
    {
        $request->validate(['notes' => 'nullable|string']);
        $enrollment->update([
            'status' => 'rejected',
            'notes' => $request->notes,
            'approved_by' => auth()->id(),
        ]);

        $enrollment->load('student', 'trainingSchedule.course');
        if ($enrollment->student?->email) {
            Mail::to($enrollment->student->email)->send(new EnrollmentRejected($enrollment, $request->notes));
        }

        return redirect()->route('admin.enrollments.index')->with('success', 'Enrollment rejected. Notification email sent.');
    }

    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();

        return redirect()->route('admin.enrollments.index')->with('success', 'Enrollment deleted.');
    }
}
