<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CertificatesExport;
use App\Http\Controllers\Concerns\Searchable;
use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CertificateController extends Controller
{
    use Searchable;

    public function __construct()
    {
        $this->middleware('permission:manage certificates');
    }

    public function index(Request $request)
    {
        $query = Certificate::with(['student', 'course']);
        $query = $this->applySearch($query, $request, ['certificate_number'], [
            'student' => ['first_name', 'last_name', 'email'],
        ]);
        $certificates = $query->latest()->paginate(10)->withQueryString();

        return view('admin.certificates.index', compact('certificates'));
    }

    public function export()
    {
        $filename = 'certificates-'.now()->format('Y-m-d-His').'.xlsx';

        return Excel::download(new CertificatesExport, $filename);
    }

    public function create()
    {
        $students = Student::orderBy('last_name')->get();
        $courses = Course::where('is_active', true)->orderBy('title')->get();
        $enrollments = Enrollment::with('student')
            ->whereIn('status', ['completed', 'approved'])
            ->orderByDesc('id')
            ->get();

        return view('admin.certificates.create', compact('students', 'courses', 'enrollments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'enrollment_id' => 'nullable|exists:enrollments,id',
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'certificate_number' => 'required|string|max:100|unique:certificates,certificate_number',
            'issued_date' => 'required|date',
            'qr_code' => 'nullable|string',
            'is_verified' => 'boolean',
        ]);

        $data['is_verified'] = $request->boolean('is_verified');
        $data['qr_code'] = $this->buildQrUrl($data['certificate_number']);

        Certificate::create($data);

        return redirect()->route('admin.certificates.index')->with('success', 'Certificate created successfully.');
    }

    public function show(Certificate $certificate)
    {
        $certificate->load(['student', 'course', 'enrollment']);

        return view('admin.certificates.show', compact('certificate'));
    }

    public function edit(Certificate $certificate)
    {
        $students = Student::orderBy('last_name')->get();
        $courses = Course::where('is_active', true)->orderBy('title')->get();
        $enrollments = Enrollment::with('student')
            ->whereIn('status', ['completed', 'approved'])
            ->orderByDesc('id')
            ->get();

        return view('admin.certificates.edit', compact('certificate', 'students', 'courses', 'enrollments'));
    }

    public function update(Request $request, Certificate $certificate)
    {
        $data = $request->validate([
            'enrollment_id' => 'nullable|exists:enrollments,id',
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'certificate_number' => 'required|string|max:100|unique:certificates,certificate_number,'.$certificate->id,
            'issued_date' => 'required|date',
            'qr_code' => 'nullable|string',
            'is_verified' => 'boolean',
        ]);

        $data['is_verified'] = $request->boolean('is_verified');
        $data['qr_code'] = $this->buildQrUrl($data['certificate_number']);

        $certificate->update($data);

        return redirect()->route('admin.certificates.index')->with('success', 'Certificate updated successfully.');
    }

    private function buildQrUrl(string $certificateNumber): string
    {
        return route('verify.certificate.scan', ['number' => $certificateNumber], true);
    }

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();

        return redirect()->route('admin.certificates.index')->with('success', 'Certificate deleted successfully.');
    }
}
