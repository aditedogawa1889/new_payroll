<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware('menu.access')->prefix('admin')->group(function () {
        Route::resource('menus', \App\Http\Controllers\Admin\MenuController::class);
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::post('users/{user}/reset-password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::resource('employees', \App\Http\Controllers\Admin\EmployeeController::class);
        
        Route::resource('loans', \App\Http\Controllers\Admin\EmployeeLoanController::class);
        Route::get('loans/{loan}/template', [\App\Http\Controllers\Admin\EmployeeLoanController::class, 'downloadTemplate'])->name('loans.download-template');
        Route::post('loans/{loan}/import', [\App\Http\Controllers\Admin\EmployeeLoanController::class, 'importSchedule'])->name('loans.import-schedule');
        
        Route::resource('salary-components', \App\Http\Controllers\Admin\SalaryComponentController::class);
        Route::get('salary-settings', [\App\Http\Controllers\Admin\SalarySettingController::class, 'index'])->name('salary-settings.index');
        Route::get('salary-settings/{emp_number}/edit', [\App\Http\Controllers\Admin\SalarySettingController::class, 'edit'])->name('salary-settings.edit');
        Route::put('salary-settings/{emp_number}', [\App\Http\Controllers\Admin\SalarySettingController::class, 'update'])->name('salary-settings.update');
        
        Route::get('payroll/calculate', [\App\Http\Controllers\Admin\PayrollCalculationController::class, 'index'])->name('payroll.calculate');
        Route::post('payroll/calculate/{emp_number}', [\App\Http\Controllers\Admin\PayrollCalculationController::class, 'calculate'])->name('payroll.calculate.process');
        Route::post('payroll/process/{emp_number}', [\App\Http\Controllers\Admin\PayrollCalculationController::class, 'processPayment'])->name('payroll.process');
    });
});

require __DIR__.'/auth.php';
