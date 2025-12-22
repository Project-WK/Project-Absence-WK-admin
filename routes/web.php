<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\auth\AuthAdminController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\EmployeeController;
use App\Http\Controllers\admin\ProjectController;
use App\Http\Controllers\admin\ShiftController;
use App\Http\Controllers\admin\HolidayController;
use App\Http\Controllers\admin\AttendanceController;
use App\Http\Controllers\admin\LeaveController;
use App\Http\Controllers\admin\LocationsController;

// Redirect Halaman Utama ke Login
Route::get('/', function () {
    return redirect()->route('login');
});

// Guest Routes (Hanya untuk yang BELUM Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthAdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthAdminController::class, 'login']);
});

//  Authenticated Routes (Hanya untuk Admin yang SUDAH Login)
Route::middleware(['auth'])->group(function () {
    
    // Logout
    Route::post('/logout', [AuthAdminController::class, 'logout'])->name('logout');

    // Group Admin (Prefix URL: /admin/...)
    Route::prefix('admin')->name('admin.')->group(function () {
        // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        // Route::post('employees/bulk-delete', [EmployeeController::class, 'bulkDestroy'])->name('employees.bulkDestroy');
        // Route::resource('employees', EmployeeController::class);
        // Route::patch('/employees/{id}/verify', [EmployeeController::class, 'verify'])->name('employees.verify');
        // Route::patch('/employees/{id}/shift', [EmployeeController::class, 'updateShift'])->name('employees.updateShift');
        // Route::patch('/employees/{id}/promote', [EmployeeController::class, 'promoteToLeader'])->name('employees.promote');
        // Route::post('projects/bulk-delete', [ProjectController::class, 'bulkDestroy'])->name('projects.bulkDestroy');
        // Route::resource('projects', ProjectController::class);
        // Route::resource('shifts', ShiftController::class)->except(['show']);
        // Route::resource('holidays', HolidayController::class)->except(['show']);
        // Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
        // Route::get('/attendances/map', [AttendanceController::class, 'map'])->name('attendances.map');
        // Route::get('/leaves', [AttendanceController::class, 'leaves'])->name('leaves.index');
        // Route::patch('/leaves/{id}/approve', [AttendanceController::class, 'approveLeave'])->name('leaves.approve');
        // Route::patch('/leaves/{id}/reject', [AttendanceController::class, 'rejectLeave'])->name('leaves.reject');

    });

});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// employee
Route::post('employees/bulk-delete', [EmployeeController::class, 'bulkDestroy'])->name('employees.bulkDestroy');
Route::resource('employees', EmployeeController::class);
Route::patch('/employees/{user_id}/verify', [EmployeeController::class, 'verify'])->name('employees.verify');
Route::patch('/employees/{user_id}/shift', [EmployeeController::class, 'updateShift'])->name('employees.updateShift');
Route::patch('/employees/{user_id}/promote', [EmployeeController::class, 'promoteToLeader'])->name('employees.promote');

// project
Route::post('projects/bulk-delete', [ProjectController::class, 'bulkDestroy'])->name('projects.bulkDestroy');
Route::resource('projects', ProjectController::class)->except(['show']);

// shift
Route::post('shifts/bulk-delete', [ShiftController::class, 'bulkDestroy'])->name('shifts.bulkDestroy');
Route::resource('shifts', ShiftController::class)->except(['show']);

// holidays
Route::post('holidays/bulk-delete', [HolidayController::class, 'bulkDestroy'])->name('holidays.bulkDestroy');
Route::resource('holidays', HolidayController::class)->except(['show']);

// location
Route::post('locations/bulk-delete', [LocationsController::class, 'bulkDestroy'])->name('locations.bulkDestroy');
Route::resource('locations', LocationsController::class)->except(['show']);

// attendance
Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
Route::get('/attendances/map', [AttendanceController::class, 'map'])->name('attendances.map');

// leave
Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
Route::patch('/leaves/{id}/approve', [LeaveController::class, 'approveLeave'])->name('leaves.approve');
Route::patch('/leaves/{id}/reject', [LeaveController::class, 'rejectLeave'])->name('leaves.reject');