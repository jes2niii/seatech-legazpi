<?php

namespace App\Http\Controllers\Admin;

use App\Exports\StudentsExport;
use App\Http\Controllers\Concerns\Searchable;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    use Searchable;

    public function __construct()
    {
        $this->middleware('permission:manage enrollments');
    }

    public function index(Request $request)
    {
        $query = Student::with('user');
        $query = $this->applySearch($query, $request, ['first_name', 'last_name', 'email', 'mobile_number']);
        $students = $query->latest()->paginate(10)->withQueryString();

        return view('admin.students.index', compact('students'));
    }

    public function export()
    {
        $filename = 'students-'.now()->format('Y-m-d-His').'.xlsx';

        return Excel::download(new StudentsExport, $filename);
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'mobile_number' => 'nullable|string|max:20',
            'email' => 'required|email|unique:students,email',
            'seaman_book_number' => 'nullable|string|max:50',
        ]);

        Student::create($data);

        return redirect()->route('admin.students.index')->with('success', 'Student created successfully.');
    }

    public function show(Student $student)
    {
        $student->load('user', 'enrollments');

        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'mobile_number' => 'nullable|string|max:20',
            'email' => 'required|email|unique:students,email,'.$student->id,
            'seaman_book_number' => 'nullable|string|max:50',
        ]);

        $student->update($data);

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
    }
}
