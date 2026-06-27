<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CertificateController as AdminCertificateController;
use App\Http\Controllers\Admin\CoreValueController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnrollmentController as AdminEnrollmentController;
use App\Http\Controllers\Admin\FacilityController as AdminFacilityController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\CertificateVerificationController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\CourseController;
use App\Http\Controllers\Public\EnrollmentController;
use App\Http\Controllers\Public\FacilityController;
use App\Http\Controllers\Public\NewsController;
use App\Http\Controllers\Public\SitemapController;
use App\Http\Controllers\Student\StudentPortalController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public pages
Route::get('/', [CourseController::class, 'home'])->name('home');
Route::view('/about', 'public.about')->name('about');
Route::get('/courses', [CourseController::class, 'index'])->name('courses');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/calendar', [CourseController::class, 'calendar'])->name('calendar');
Route::get('/facilities', [FacilityController::class, 'index'])->name('facilities');
Route::get('/news', [NewsController::class, 'index'])->name('news');
Route::get('/news/{article:slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Certificate verification
Route::get('/verify-certificate', [CertificateVerificationController::class, 'show'])->name('verify.certificate');
Route::post('/verify-certificate', [CertificateVerificationController::class, 'verify'])->name('verify.certificate.lookup');
Route::get('/verify-certificate/{number}', [CertificateVerificationController::class, 'scan'])->name('verify.certificate.scan');

// SEO
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// PWA static assets (also served directly by the web server in production).
Route::get('/manifest.json', function () {
    return response()->json(json_decode(file_get_contents(public_path('manifest.json')), true))
        ->header('Content-Type', 'application/manifest+json');
})->name('manifest');
Route::get('/sw.js', function () {
    return response(file_get_contents(public_path('sw.js')))
        ->header('Content-Type', 'application/javascript')
        ->header('Service-Worker-Allowed', '/');
})->name('service-worker');
Route::get('/offline.html', function () {
    return response(file_get_contents(public_path('offline.html')))
        ->header('Content-Type', 'text/html; charset=utf-8');
})->name('offline');

// Online enrollment
Route::get('/enroll', [EnrollmentController::class, 'step1'])->name('enroll.step1');
Route::post('/enroll/step1', [EnrollmentController::class, 'postStep1'])->name('enroll.postStep1');
Route::get('/enroll/step2', [EnrollmentController::class, 'step2'])->name('enroll.step2');
Route::post('/enroll/step2', [EnrollmentController::class, 'postStep2'])->name('enroll.postStep2');
Route::get('/enroll/step3', [EnrollmentController::class, 'step3'])->name('enroll.step3');
Route::post('/enroll/step3', [EnrollmentController::class, 'postStep3'])->name('enroll.postStep3');
Route::get('/enroll/review', [EnrollmentController::class, 'review'])->name('enroll.review');
Route::post('/enroll/submit', [EnrollmentController::class, 'submit'])->name('enroll.submit');
Route::get('/enroll/confirmation/{enrollment}', [EnrollmentController::class, 'confirmation'])->name('enroll.confirmation');

// Authenticated user routes
Route::get('/dashboard', function () {
    $user = Auth::user();
    if (! $user instanceof User) {
        abort(403);
    }
    if ($user->hasRole('Super Admin')) {
        return redirect()->route('admin.dashboard');
    }
    if ($user->hasRole('Registrar')) {
        return redirect()->route('registrar.dashboard');
    }
    if ($user->hasRole('Training Coordinator')) {
        return redirect()->route('coordinator.dashboard');
    }
    if ($user->hasRole('Instructor')) {
        return redirect()->route('instructor.dashboard');
    }

    return redirect()->route('student.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Student portal routes
Route::middleware(['auth', 'verified'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/enrollments', [StudentPortalController::class, 'enrollments'])->name('enrollments');
    Route::get('/certificates', [StudentPortalController::class, 'certificates'])->name('certificates');
});

// Super Admin routes
Route::middleware(['auth', 'verified', 'role:Super Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/categories', CategoryController::class)->except(['show']);
    Route::resource('/courses', AdminCourseController::class);
    Route::post('/courses/{course}/archive', [AdminCourseController::class, 'archive'])->name('courses.archive');
    Route::post('/courses/{course}/restore', [AdminCourseController::class, 'restore'])->name('courses.restore');
    Route::resource('/schedules', ScheduleController::class)->except(['show']);
    Route::resource('/enrollments', AdminEnrollmentController::class)->except(['create', 'store', 'edit', 'update']);
    Route::post('/enrollments/{enrollment}/approve', [AdminEnrollmentController::class, 'approve'])->name('enrollments.approve');
    Route::post('/enrollments/{enrollment}/reject', [AdminEnrollmentController::class, 'reject'])->name('enrollments.reject');
    Route::get('/enrollments-export', [AdminEnrollmentController::class, 'export'])->name('enrollments.export');
    Route::resource('/students', StudentController::class);
    Route::get('/students-export', [StudentController::class, 'export'])->name('students.export');
    Route::resource('/news', AdminNewsController::class)->except(['show']);
    Route::resource('/testimonials', TestimonialController::class)->except(['show']);
    Route::resource('/facilities', AdminFacilityController::class)->except(['show']);
    Route::delete('/facilities/{facility}/photos/{media}', [AdminFacilityController::class, 'deletePhoto'])->name('facilities.photos.destroy');
    Route::resource('/inquiries', InquiryController::class)->only(['index', 'show', 'destroy']);
    Route::resource('/certificates', AdminCertificateController::class);
    Route::get('/certificates-export', [AdminCertificateController::class, 'export'])->name('certificates.export');
    Route::resource('/team', TeamMemberController::class);
    Route::resource('/core-values', CoreValueController::class);
    Route::resource('/users', UserController::class);
    Route::get('/settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings', [SiteSettingController::class, 'update'])->name('settings.update');
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::get('/activity-log/{activity}', [ActivityLogController::class, 'show'])->name('activity-log.show');
});

// Registrar routes
Route::middleware(['auth', 'verified', 'role:Registrar'])->prefix('registrar')->name('registrar.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/enrollments', AdminEnrollmentController::class)->only(['index', 'show', 'destroy']);
    Route::post('/enrollments/{enrollment}/approve', [AdminEnrollmentController::class, 'approve'])->name('enrollments.approve');
    Route::post('/enrollments/{enrollment}/reject', [AdminEnrollmentController::class, 'reject'])->name('enrollments.reject');
    Route::get('/enrollments-export', [AdminEnrollmentController::class, 'export'])->name('enrollments.export');
    Route::resource('/students', StudentController::class)->only(['index', 'show']);
    Route::get('/students-export', [StudentController::class, 'export'])->name('students.export');
    Route::resource('/certificates', AdminCertificateController::class);
    Route::get('/certificates-export', [AdminCertificateController::class, 'export'])->name('certificates.export');
});

// Training Coordinator routes
Route::middleware(['auth', 'verified', 'role:Training Coordinator'])->prefix('coordinator')->name('coordinator.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/categories', CategoryController::class)->except(['show']);
    Route::resource('/courses', AdminCourseController::class);
    Route::post('/courses/{course}/archive', [AdminCourseController::class, 'archive'])->name('courses.archive');
    Route::post('/courses/{course}/restore', [AdminCourseController::class, 'restore'])->name('courses.restore');
    Route::resource('/schedules', ScheduleController::class)->except(['show']);
    Route::resource('/enrollments', AdminEnrollmentController::class)->only(['index', 'show', 'destroy']);
    Route::post('/enrollments/{enrollment}/approve', [AdminEnrollmentController::class, 'approve'])->name('enrollments.approve');
    Route::post('/enrollments/{enrollment}/reject', [AdminEnrollmentController::class, 'reject'])->name('enrollments.reject');
});

// Instructor routes
Route::middleware(['auth', 'verified', 'role:Instructor'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/courses', AdminCourseController::class)->only(['index', 'show']);
    Route::resource('/schedules', ScheduleController::class)->only(['index', 'show']);
});

require __DIR__.'/auth.php';
