<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\EnrollmentSubmitted;
use App\Models\Category;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\TrainingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EnrollmentController extends Controller
{
    public function step1()
    {
        $categories = Category::where('is_active', true)
            ->with(['courses' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order')->with(['trainingSchedules' => function ($q) {
                    $q->whereIn('status', ['upcoming', 'ongoing'])->orderBy('start_date')->with('instructor');
                }]);
            }])
            ->orderBy('sort_order')
            ->get();

        return view('public.enroll.step1', compact('categories'));
    }

    public function postStep1(Request $request)
    {
        $validated = $request->validate([
            'training_schedule_id' => 'required|exists:training_schedules,id',
        ]);

        session(['enrollment.training_schedule_id' => $validated['training_schedule_id']]);

        return redirect()->route('enroll.step2');
    }

    public function step2()
    {
        return view('public.enroll.step2');
    }

    public function postStep2(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'civil_status' => 'nullable|in:single,married,widowed,separated',
            'address' => 'nullable|string',
            'place_of_birth' => 'nullable|string|max:255',
            'mobile_number' => 'required|string|max:20',
            'email' => 'required|email',
            'seaman_book_number' => 'nullable|string|max:50',
            'rank' => 'nullable|string|max:255',
        ]);

        foreach ($validated as $key => $value) {
            session(["enrollment.{$key}" => $value]);
        }

        return redirect()->route('enroll.step2_5');
    }

    public function step2_5()
    {
        return view('public.enroll.step2_5');
    }

    public function postStep2_5(Request $request)
    {
        $validated = $request->validate([
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_relationship' => 'required|in:Parent,Spouse,Sibling,Guardian,Friend,Other',
            'emergency_contact_mobile' => 'required|string|max:20',
        ]);

        foreach ($validated as $key => $value) {
            session(["enrollment.{$key}" => $value]);
        }

        return redirect()->route('enroll.step3');
    }

    public function step3()
    {
        return view('public.enroll.step3');
    }

    public function postStep3(Request $request)
    {
        $validated = $request->validate([
            'requirements' => 'nullable|array',
            'documents' => 'nullable|array|max:6',
            'documents.*' => 'file|mimes:jpeg,jpg,png,webp,pdf|max:5120',
        ]);

        session(['enrollment.requirements' => $validated['requirements'] ?? []]);

        $tempPaths = [];
        if ($request->hasFile('documents')) {
            $sessionId = session()->getId();
            foreach ($request->file('documents') as $file) {
                $tempPaths[] = $file->store("enrollment-temp/{$sessionId}", 'local');
            }
        }
        session(['enrollment.temp_documents' => $tempPaths]);

        return redirect()->route('enroll.review');
    }

    public function review()
    {
        $data = session('enrollment');

        if (! $data || ! isset($data['training_schedule_id'])) {
            return redirect()->route('enroll.step1');
        }

        $schedule = TrainingSchedule::with('course')->find($data['training_schedule_id']);

        return view('public.enroll.review', compact('data', 'schedule'));
    }

    public function submit(Request $request)
    {
        $data = session('enrollment');

        if (! $data || ! isset($data['training_schedule_id'])) {
            return redirect()->route('enroll.step1');
        }

        $student = Student::firstOrCreate(
            ['email' => $data['email']],
            [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'gender' => $data['gender'] ?? null,
                'civil_status' => $data['civil_status'] ?? null,
                'address' => $data['address'] ?? null,
                'place_of_birth' => $data['place_of_birth'] ?? null,
                'mobile_number' => $data['mobile_number'] ?? null,
                'seaman_book_number' => $data['seaman_book_number'] ?? null,
                'rank' => $data['rank'] ?? null,
            ]
        );

        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'training_schedule_id' => $data['training_schedule_id'],
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'requirements' => $data['requirements'] ?? [],
            'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
            'emergency_contact_relationship' => $data['emergency_contact_relationship'] ?? null,
            'emergency_contact_mobile' => $data['emergency_contact_mobile'] ?? null,
        ]);

        $tempDocs = $data['temp_documents'] ?? [];
        foreach ($tempDocs as $path) {
            if (\Storage::disk('local')->exists($path)) {
                $enrollment->addMedia(\Storage::disk('local')->path($path))
                    ->preservingOriginal()
                    ->toMediaCollection('documents');
                \Storage::disk('local')->delete($path);
            }
        }

        if ($student->email) {
            Mail::to($student->email)->send(new EnrollmentSubmitted($enrollment));
        }

        session()->forget('enrollment');

        return redirect()->route('enroll.confirmation', $enrollment);
    }

    public function confirmation(Enrollment $enrollment)
    {
        return view('public.enroll.confirmation', compact('enrollment'));
    }
}
