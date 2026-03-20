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
Route::delete('/registrations/{registration_id}', [ClassroomController::class, 'unenrollStudent'])->name('classrooms.unenroll_student');

Route::resource('students', StudentController::class);
Route::resource('subjects', SubjectController::class);
Route::resource('grades', GradeController::class);
Route::get('grades-bulk', [GradeController::class, 'enterBulk'])->name('grades.bulk');
Route::post('grades-bulk', [GradeController::class, 'storeBulk'])->name('grades.store_bulk');
Route::resource('schedules', ScheduleController::class);
Route::post('chatbot', [ChatbotController::class, 'handle']);

// Portal Routes (Conceptual for now)
Route::get('/portal/{student_id}', [PortalController::class, 'dashboard'])->name('portal.dashboard');
Route::get('/portal/{student_id}/grades', [PortalController::class, 'grades'])->name('portal.grades');
Route::get('/portal/{student_id}/schedule', [PortalController::class, 'schedule'])->name('portal.schedule');
Route::get('/portal/{student_id}/registration', [PortalController::class, 'registration'])->name('portal.registration');
Route::post('/portal/{student_id}/registration', [PortalController::class, 'store_registration'])->name('portal.store_registration');

// Attendance Routes
Route::get('/attendance', [App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance.index');
Route::get('/attendance/create', [App\Http\Controllers\AttendanceController::class, 'create'])->name('attendance.create');
Route::post('/attendance/store', [App\Http\Controllers\AttendanceController::class, 'store'])->name('attendance.store');
Route::get('/attendance/report', [App\Http\Controllers\AttendanceController::class, 'report'])->name('attendance.report');
Route::get('/attendance/export', [App\Http\Controllers\AttendanceController::class, 'export'])->name('attendance.export');

// Attendance Rules
Route::get('/attendance-rules', [App\Http\Controllers\AttendanceRuleController::class, 'index'])->name('attendance-rules.index');
Route::post('/attendance-rules', [App\Http\Controllers\AttendanceRuleController::class, 'store'])->name('attendance-rules.store');
