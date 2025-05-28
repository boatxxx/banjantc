<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\ParentNotificationController;
use App\Http\Controllers\FCMController;
Route::post('/store-token', [FCMController::class, 'storeToken'])->name('store.token');
use App\Http\Controllers\MqttController;
use App\Http\Controllers\SecurePageController;
Route::get('/secure-page', [SecurePageController::class, 'show'])->name('home');
Route::post('/verify-pin', [SecurePageController::class, 'verifyPin'])->name('verify.pin');
Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
Route::delete('/delete-old-subscription/{classroomId}', [NotificationController::class, 'deleteOldSubscription']);
Route::post('/api/change-classroom', [NotificationController::class, 'changeClassroom']);
Route::post('/update-subscription', [NotificationController::class, 'update']);
Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
Route::post('/students/store', [StudentController::class, 'store'])->name('students.store');
Route::get('/students/manage', [StudentController::class, 'manage'])->name('students.manage');
Route::post('/students/move/{id}', [StudentController::class, 'move'])->name('students.move');

// สำหรับแก้ไข/ลบ
Route::resource('students', StudentController::class)->only(['edit', 'update', 'destroy']);

Route::post('parent-notifications', [ParentNotificationController::class, 'store'])->name('parent-notifications.store');
Route::get('/send-notification1', [NotificationController::class, 'sendNotification']);

Broadcast::channel('classroom.{classroomId}', function ($user, $classroomId) {
    return true; // หรือใช้เงื่อนไขตรวจสอบสิทธิ์ เช่น return $user->id === $classroomId;
});
Route::get('/notifications/{classroom}', [NotificationController::class, 'index']);
Route::post('/toggle-notification', [NotificationController::class, 'toggleNotification'])->name('toggle.notification');



Route::get('/', [AttendanceController::class, 'welcome'])->name('welcome');
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::get('/notifications/{classroom}', [NotificationController::class, 'show'])->name('notifications.show');
Route::post('/notifications/{classroom}/toggle', [NotificationController::class, 'toggleNotification'])->name('notifications.toggle');
Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::post('/attendance1', [AttendanceController::class, 'index1'])->name('attendance.index1');
Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
Route::post('/attendance/fetch-students', [AttendanceController::class, 'fetchStudents'])->name('attendance.fetchStudents');
Route::post('/submit-attendance', [AttendanceController::class, 'submitAttendance'])->name('attendance.submitAttendance');
Route::post('/store', [AttendanceController::class, 'store'])->name('attendance.store');

Route::post('/store', [AttendanceController::class, 'store'])->name('attendance.store');
Route::get('/record/{mode?}', function ($mode = null) { 
    return view('record', compact('mode'));
})->name('record');

Route::get('/importexcel2', function () {
    return view('import');

})->name('import');
use Illuminate\Support\Facades\Http;


Route::post('/save-push-subscription', [NotificationController::class, 'saveSubscription']);

Route::post('/importExcel555', [AttendanceController::class, 'importExcel2'])->name('import.exce');

Route::get('/report', [AttendanceController::class, 'index3'])->name('report');

Route::get('/report1', [AttendanceController::class, 'select'])->name('report1');
Route::get('/attendance/report', [AttendanceController::class, 'reportAttendance'])->name('attendance.report');

Route::post('/port', [AttendanceController::class, 'port'])->name('port');
Route::post('/port1', [AttendanceController::class, 'port'])->name('port');
use App\Http\Controllers\RegistrationController;

Route::get('/registrations', [RegistrationController::class, 'index'])->name('registrations.index');
Route::get('/registrations/create', [RegistrationController::class, 'create'])->name('registrations.create');
Route::post('/registrations', [RegistrationController::class, 'store'])->name('registrations.store');  // เพิ่ม Route นี้
Route::patch('subjects/{id}/status', [RegistrationController::class, 'updateStatus'])->name('subjects.updateStatus');