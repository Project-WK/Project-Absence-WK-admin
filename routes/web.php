<?php

use Illuminate\Support\Facades\Route;

// Import Controllers (Sesuai Namespace Folder)
use App\Http\Controllers\Auth\AuthAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\Admin\AttendanceController;


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
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('employees/bulk-delete', [EmployeeController::class, 'bulkDestroy'])->name('employees.bulkDestroy');
        Route::resource('employees', EmployeeController::class);
        Route::patch('/employees/{id}/verify', [EmployeeController::class, 'verify'])->name('employees.verify');
        Route::patch('/employees/{id}/shift', [EmployeeController::class, 'updateShift'])->name('employees.updateShift');
        Route::patch('/employees/{id}/promote', [EmployeeController::class, 'promoteToLeader'])->name('employees.promote');
        Route::post('projects/bulk-delete', [ProjectController::class, 'bulkDestroy'])->name('projects.bulkDestroy');
        Route::resource('projects', ProjectController::class);
        Route::resource('shifts', ShiftController::class)->except(['show']);
        Route::resource('holidays', HolidayController::class)->except(['show']);
        Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
        Route::get('/attendances/map', [AttendanceController::class, 'map'])->name('attendances.map');
        Route::get('/leaves', [AttendanceController::class, 'leaves'])->name('leaves.index');
        Route::patch('/leaves/{id}/approve', [AttendanceController::class, 'approveLeave'])->name('leaves.approve');
        Route::patch('/leaves/{id}/reject', [AttendanceController::class, 'rejectLeave'])->name('leaves.reject');

    });

});