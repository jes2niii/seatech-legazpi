<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentPortalController extends Controller
{
    public function dashboard()
    {
        $student = $this->getStudent();

        $stats = [
            'active' => $student ? $student->enrollments()->whereIn('status', ['pending', 'approved'])->count() : 0,
            'completed' => $student ? $student->enrollments()->where('status', 'completed')->count() : 0,
            'certificates' => $student ? $student->certificates()->count() : 0,
        ];

        $recent_enrollments = $student
            ? $student->enrollments()
                ->with(['trainingSchedule.course'])
                ->latest()
                ->take(5)
                ->get()
            : collect();

        return view('student.dashboard', compact('stats', 'recent_enrollments'));
    }

    public function enrollments(Request $request)
    {
        $student = $this->getStudent();

        $query = $student ? $student->enrollments()->with(['trainingSchedule.course'])->latest() : null;

        $filter = $request->get('status', 'all');
        if ($query && $filter !== 'all' && in_array($filter, ['pending', 'approved', 'rejected', 'completed', 'cancelled'])) {
            $query->where('status', $filter);
        }

        $enrollments = $query ? $query->paginate(10)->withQueryString() : collect();

        return view('student.enrollments', compact('enrollments', 'filter'));
    }

    public function certificates()
    {
        $student = $this->getStudent();

        $certificates = $student
            ? $student->certificates()->with('course')->latest('issued_date')->get()
            : collect();

        return view('student.certificates', compact('certificates'));
    }

    private function getStudent(): ?Student
    {
        $user = Auth::user();
        if (! $user) {
            return null;
        }

        return Student::where('user_id', $user->id)
            ->orWhere('email', $user->email)
            ->first();
    }
}
