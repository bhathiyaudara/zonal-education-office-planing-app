<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Zonal;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('home');
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Zonal Admin routes
    Route::prefix('dashboard/zonal')->middleware(RoleMiddleware::class . ':ZONAL_ADMIN')->group(function () {
        // Schools
        Route::get('/schools', [Zonal\SchoolController::class, 'index'])->name('zonal.schools');
        Route::post('/schools', [Zonal\SchoolController::class, 'store'])->name('zonal.schools.store');
        Route::get('/schools/student-overview', [Zonal\SchoolController::class, 'studentOverview'])->name('zonal.schools.students');
        Route::get('/schools/remove', [Zonal\SchoolController::class, 'removeForm'])->name('zonal.schools.remove');
        Route::get('/schools/{id}', [Zonal\SchoolController::class, 'show'])->name('zonal.schools.show');
        Route::put('/schools/{id}', [Zonal\SchoolController::class, 'update'])->name('zonal.schools.update');
        Route::put('/schools/{id}/student-counts', [Zonal\SchoolController::class, 'updateStudentCounts'])->name('zonal.schools.update-counts');
        Route::delete('/schools/{id}', [Zonal\SchoolController::class, 'destroy'])->name('zonal.schools.destroy');

        // Teachers
        Route::get('/teachers', [Zonal\TeacherController::class, 'index'])->name('zonal.teachers');
        Route::get('/teachers/create', [Zonal\TeacherController::class, 'create'])->name('zonal.teachers.create');
        Route::get('/teachers/search', [Zonal\TeacherController::class, 'search'])->name('zonal.teachers.search');
        Route::get('/teachers/remove', [Zonal\TeacherController::class, 'removeForm'])->name('zonal.teachers.remove');
        Route::post('/teachers/remove', [Zonal\TeacherController::class, 'removeTeacher'])->name('zonal.teachers.remove.submit');
        Route::post('/teachers', [Zonal\TeacherController::class, 'store'])->name('zonal.teachers.store');
        Route::get('/teachers/{id}/edit', [Zonal\TeacherController::class, 'edit'])->name('zonal.teachers.edit');
        Route::put('/teachers/{id}', [Zonal\TeacherController::class, 'update'])->name('zonal.teachers.update');
        Route::get('/teachers/{id}', [Zonal\TeacherController::class, 'show'])->name('zonal.teachers.show');
        Route::delete('/teachers/{id}', [Zonal\TeacherController::class, 'destroy'])->name('zonal.teachers.destroy');

        // Subjects
        Route::get('/subjects', [Zonal\SubjectController::class, 'index'])->name('zonal.subjects');
        Route::post('/subjects', [Zonal\SubjectController::class, 'store'])->name('zonal.subjects.store');
        Route::put('/subjects/{id}', [Zonal\SubjectController::class, 'update'])->name('zonal.subjects.update');
        Route::delete('/subjects/{id}', [Zonal\SubjectController::class, 'destroy'])->name('zonal.subjects.destroy');


        // Users
        Route::get('/users', [Zonal\UserController::class, 'index'])->name('zonal.users');
        Route::post('/users', [Zonal\UserController::class, 'store'])->name('zonal.users.store');
        Route::put('/users/{id}', [Zonal\UserController::class, 'update'])->name('zonal.users.update');
        Route::delete('/users/{id}', [Zonal\UserController::class, 'destroy'])->name('zonal.users.destroy');

        // Audit
        Route::get('/audit', [Zonal\AuditController::class, 'index'])->name('zonal.audit');

        // Edit Requests
        Route::get('/edit-requests', function () {
            return view('dashboard.zonal.edit-requests.index');
        })->name('zonal.edit-requests');

        // Salary Requests
        Route::get('/salary-requests', function () {
            return view('dashboard.zonal.salary-requests.index');
        })->name('zonal.salary-requests');

        // Reports
        Route::get('/reports', function () {
            return view('dashboard.zonal.reports.index');
        })->name('zonal.reports');
    });

    // School Admin routes
    Route::prefix('dashboard/school')->middleware(RoleMiddleware::class . ':SCHOOL_ADMIN')->group(function () {
        Route::get('/teachers', [\App\Http\Controllers\School\TeacherController::class, 'index'])->name('school.teachers');
        Route::get('/teachers/new', [\App\Http\Controllers\School\TeacherController::class, 'create'])->name('school.teachers.create');
        Route::post('/teachers', [\App\Http\Controllers\School\TeacherController::class, 'store'])->name('school.teachers.store');
        
        // Profile
        Route::get('/edit', [\App\Http\Controllers\School\SchoolProfileController::class, 'edit'])->name('school.profile.edit');
        Route::put('/update', [\App\Http\Controllers\School\SchoolProfileController::class, 'update'])->name('school.profile.update');

        // Students & Classes
        Route::get('/students', [\App\Http\Controllers\School\StudentController::class, 'index'])->name('school.students');
        Route::put('/students/update', [\App\Http\Controllers\School\StudentController::class, 'updateCounts'])->name('school.students.update');
    });

    // Teacher routes
    Route::prefix('dashboard/teacher')->middleware(RoleMiddleware::class . ':TEACHER')->group(function () {
        Route::get('/salary-requests', [\App\Http\Controllers\TeacherPanel\SalaryRequestController::class, 'index'])->name('teacher.salary');
        Route::post('/salary-requests', [\App\Http\Controllers\TeacherPanel\SalaryRequestController::class, 'store'])->name('teacher.salary.store');
    });

    // Change Password (all roles)
    Route::post('/change-password', [\App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.change');
});

// Setup & Initialize Super Admin
Route::get('/init-super-admin', function () {
    try {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `user` MODIFY COLUMN `role` ENUM('SUPER_ADMIN', 'ZONAL_ADMIN', 'SCHOOL_ADMIN', 'TEACHER') NOT NULL DEFAULT 'TEACHER'");

        $user = \App\Models\User::updateOrCreate(
            ['email' => 'superadmin@wilgamuwa.com'],
            [
                'id' => '01JMQZXK00000000000SUPERADM',
                'name' => 'Super Administrator',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'SUPER_ADMIN',
                'isActive' => 1,
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Super Administrator role and user created successfully!',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    } catch (\Throwable $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});
