<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\StudentLoginController;
use App\Http\Controllers\Auth\TeacherLoginController;
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\TeacherPortalController;

// --- ADMIN AUTH ROUTES ---
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// --- STUDENT AUTH ROUTES ---
Route::prefix('student')->name('student.')->group(function () {
    Route::get('/login', [StudentLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [StudentLoginController::class, 'login']);
    Route::post('/logout', [StudentLoginController::class, 'logout'])->name('logout');

    Route::middleware('auth:student')->group(function () {
        Route::get('/dashboard', [StudentPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/detailed-schedule', [StudentPortalController::class, 'detailedSchedule'])->name('detailed-schedule');
        Route::get('/profile', [StudentPortalController::class, 'profile'])->name('profile');
        Route::post('/profile', [StudentPortalController::class, 'updateProfile'])->name('profile.update');
        Route::get('/schedule', [StudentPortalController::class, 'schedule'])->name('schedule');
        Route::get('/grades', [StudentPortalController::class, 'grades'])->name('grades');
        Route::get('/support', [StudentPortalController::class, 'support'])->name('support');
    });
});

// --- TEACHER PORTAL ---
Route::prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/login', [TeacherLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [TeacherLoginController::class, 'login']);
    Route::post('/logout', [TeacherLoginController::class, 'logout'])->name('logout');

    Route::middleware('auth:teacher')->group(function () {
        Route::get('/dashboard', [TeacherPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/schedule', [TeacherPortalController::class, 'schedule'])->name('schedule');
        Route::get('/subjects', [TeacherPortalController::class, 'subjects'])->name('subjects');
        Route::get('/subjects/{schedule}/students', [TeacherPortalController::class, 'students'])->name('students');
        Route::get('/attendance/{schedule}', [TeacherPortalController::class, 'attendance'])->name('attendance');
        Route::post('/attendance/{schedule}/save', [TeacherPortalController::class, 'saveAttendance'])->name('attendance.save');
        
        Route::get('/grades/{schedule}', [TeacherPortalController::class, 'grades'])->name('grades');
        Route::post('/grades/{schedule}/save', [TeacherPortalController::class, 'saveGrades'])->name('grades.save');

        Route::get('/profile', [TeacherPortalController::class, 'profile'])->name('profile');
        Route::post('/profile', [TeacherPortalController::class, 'updateProfile'])->name('profile.update');
    });
});

// --- PROTECTED ADMIN ROUTES ---
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    });

    Route::resource('departments', DepartmentController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('classrooms', ClassroomController::class);
    Route::get('/classrooms/{id}/assign-subject', [App\Http\Controllers\ClassroomController::class, 'showAssignSubject'])->name('classrooms.show_assign_subject');
    Route::post('/classrooms/{id}/assign-subject', [App\Http\Controllers\ClassroomController::class, 'assignSubject'])->name('classrooms.assign_subject');
    Route::post('/classrooms/schedules/{schedule_id}/attendance', [ClassroomController::class, 'saveAttendance'])->name('classrooms.save_attendance');
    Route::get('/classrooms/preview-schedule', [App\Http\Controllers\ClassroomController::class, 'previewSchedule'])->name('classrooms.preview_schedule');
    Route::delete('/classrooms/{id}/remove-subject/{schedule_id}', [App\Http\Controllers\ClassroomController::class, 'removeSubject'])->name('classrooms.remove_subject');
    Route::get('/classrooms/{id}/subjects/{subject_id}/edit-configuration', [App\Http\Controllers\ClassroomController::class, 'editSubjectConfiguration'])->name('classrooms.edit_subject_configuration');
    Route::put('/classrooms/{id}/subjects/{subject_id}/update-configuration', [App\Http\Controllers\ClassroomController::class, 'updateSubjectConfiguration'])->name('classrooms.update_subject_configuration');
    Route::post('/schedules/{schedule_id}/enroll', [ClassroomController::class, 'enrollStudent'])->name('classrooms.enroll_student');
    Route::get('/schedules/{schedule_id}/enroll', function() {
        return redirect()->route('classrooms.index')->with('error', '⚠️ Bạn không thể truy cập trực tiếp link này. Hãy chọn sinh viên và nhấn nút "Ghi danh" từ bảng điều khiển.');
    });
    Route::delete('/registrations/{registration_id}', [ClassroomController::class, 'unenrollStudent'])->name('classrooms.unenroll_student');

    Route::resource('students', StudentController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('grades', GradeController::class);
    Route::get('grades-bulk', [GradeController::class, 'enterBulk'])->name('grades.bulk');
    Route::post('grades-bulk', [GradeController::class, 'storeBulk'])->name('grades.store_bulk');
    Route::resource('schedules', ScheduleController::class);
    Route::post('chatbot', [ChatbotController::class, 'handle']);

    // Attendance Routes
    Route::get('/attendance', [App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/create', [App\Http\Controllers\AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance/store', [App\Http\Controllers\AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/export', [App\Http\Controllers\AttendanceController::class, 'export'])->name('attendance.export');

    // Attendance Rules
    Route::get('/attendance-rules', [App\Http\Controllers\AttendanceRuleController::class, 'index'])->name('attendance-rules.index');
    Route::post('/attendance-rules', [App\Http\Controllers\AttendanceRuleController::class, 'store'])->name('attendance-rules.store');
    Route::delete('/attendance-rules/{attendance_rule}', [App\Http\Controllers\AttendanceRuleController::class, 'destroy'])->name('attendance-rules.destroy');
});

// Portal Routes (Legacy/Conceptual)
Route::prefix('portal')->group(function() {
    Route::get('/{student_id}', [PortalController::class, 'dashboard'])->name('portal.dashboard');
    Route::get('/{student_id}/grades', [PortalController::class, 'grades'])->name('portal.grades');
    Route::get('/{student_id}/schedule', [PortalController::class, 'schedule'])->name('portal.schedule');
    Route::get('/{student_id}/registration', [PortalController::class, 'registration'])->name('portal.registration');
    Route::post('/{student_id}/registration', [PortalController::class, 'store_registration'])->name('portal.store_registration');
});
