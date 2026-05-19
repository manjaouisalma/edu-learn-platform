<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Professor\DashboardController as ProfessorDashboard;
use App\Http\Controllers\Professor\CourseController as ProfessorCourseController;
use App\Http\Controllers\Professor\ExamController as ProfessorExamController;
use App\Http\Controllers\Professor\StudentController;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\ExamController as StudentExamController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AIController;

// ─── Root ─────────────────────────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

// ─── Auth ─────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login'])->name('login.post');
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ─── Admin Routes ─────────────────────────────────────────────────────────────
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {

    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users',       [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}',[AdminUserController::class, 'show'])->name('users.show');

    // Professors CRUD
    Route::get('/professors',                    [AdminUserController::class, 'professors'])->name('professors.index');
    Route::get('/professors/create',             [AdminUserController::class, 'createProfessor'])->name('professors.create');
    Route::post('/professors',                   [AdminUserController::class, 'storeProfessor'])->name('professors.store');
    Route::get('/professors/{professor}/edit',   [AdminUserController::class, 'editProfessor'])->name('professors.edit');
    Route::put('/professors/{professor}',        [AdminUserController::class, 'updateProfessor'])->name('professors.update');
    Route::delete('/professors/{professor}',     [AdminUserController::class, 'destroyProfessor'])->name('professors.destroy');

    // Courses CRUD
    Route::get('/courses',               [AdminCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create',        [AdminCourseController::class, 'create'])->name('courses.create');
    Route::post('/courses',              [AdminCourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [AdminCourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}',      [AdminCourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}',   [AdminCourseController::class, 'destroy'])->name('courses.destroy');
});

// ─── Professor Routes ─────────────────────────────────────────────────────────
Route::prefix('professor')->middleware(['auth', 'role:professor'])->name('professor.')->group(function () {

    Route::get('/', [ProfessorDashboard::class, 'index'])->name('dashboard');

    // Courses CRUD
    Route::get('/courses',               [ProfessorCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create',        [ProfessorCourseController::class, 'create'])->name('courses.create');
    Route::post('/courses',              [ProfessorCourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [ProfessorCourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}',      [ProfessorCourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}',   [ProfessorCourseController::class, 'destroy'])->name('courses.destroy');

    // Students & Grades
    Route::get('/courses/{course}/students',                     [ProfessorCourseController::class, 'students'])->name('courses.students');
    Route::get('/courses/{course}/students/{enrollment}/grade',  [ProfessorCourseController::class, 'gradeForm'])->name('courses.grade.form');
    Route::post('/courses/{course}/students/{enrollment}/grade', [ProfessorCourseController::class, 'saveGrade'])->name('courses.grade.save');
    Route::post('/courses/{course}/students/{enrollment}/certificate', [ProfessorCourseController::class, 'issueCertificate'])->name('courses.certificate.issue');

    // Students Management
    Route::get('/students',                                [StudentController::class, 'index'])->name('students.index');
    Route::post('/students',                               [StudentController::class, 'store'])->name('students.store');
    Route::put('/students/{student}',                      [StudentController::class, 'update'])->name('students.update');
    Route::patch('/enrollments/{enrollment}/toggle-block', [StudentController::class, 'toggleBlock'])->name('students.toggleBlock');
    Route::delete('/enrollments/{enrollment}',             [StudentController::class, 'destroy'])->name('students.destroy');

    // Exams
    Route::get('/courses/{course}/exam',  [ProfessorExamController::class, 'index'])->name('exams.index');
    Route::post('/courses/{course}/exam', [ProfessorExamController::class, 'store'])->name('exams.store');
    Route::delete('/exams/{exam}',        [ProfessorExamController::class, 'destroy'])->name('exams.destroy');
});

// ─── Student Routes ───────────────────────────────────────────────────────────
Route::prefix('student')->middleware(['auth', 'role:student'])->name('student.')->group(function () {

    Route::get('/', [StudentDashboard::class, 'index'])->name('dashboard');

    // Courses
    Route::get('/courses',                      [StudentDashboard::class, 'courses'])->name('courses.index');
    Route::get('/courses/{course}',             [StudentDashboard::class, 'show'])->name('courses.show');
    Route::post('/courses/{course}/enroll',     [StudentDashboard::class, 'enroll'])->name('courses.enroll');
    Route::delete('/courses/{course}/unenroll', [StudentDashboard::class, 'unenroll'])->name('courses.unenroll');

    // My Courses & Grades
    Route::get('/my-courses', [StudentDashboard::class, 'myCourses'])->name('my-courses');
    Route::get('/my-grades',  [StudentDashboard::class, 'grades'])->name('grades');

    // Profile
    Route::get('/profile',    [StudentDashboard::class, 'profile'])->name('profile');
    Route::put('/profile',    [StudentDashboard::class, 'updateProfile'])->name('profile.update');

    // Exams
    Route::get('/courses/{course}/exam',               [StudentExamController::class, 'show'])->name('exams.show');
    Route::post('/courses/{course}/exam/submit',       [StudentExamController::class, 'submit'])->name('exams.submit');
    Route::get('/courses/{course}/result/{attempt}',   [StudentExamController::class, 'result'])->name('exams.result');
    Route::get('/certificates/{certificate}/download', [StudentExamController::class, 'downloadCertificate'])->name('certificates.download');
});

Route::post('/ai/generate-questions', [AIController::class, 'generateQuestions'])->name('ai.generate');