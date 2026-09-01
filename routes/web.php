<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Agronomo\DashboardController as AgronomoDashboardController;
use App\Http\Controllers\Operador\DashboardController as OperadorDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', function () {
        return redirect()->route('dashboard.' . auth()->user()->perfil);
    })->name('dashboard');

    Route::middleware('perfil:admin')->get('/dashboard/admin', [AdminDashboardController::class, 'index'])->name('dashboard.admin');
    Route::middleware('perfil:agronomo')->get('/dashboard/agronomo', [AgronomoDashboardController::class, 'index'])->name('dashboard.agronomo');
    Route::middleware('perfil:operador')->get('/dashboard/operador', [OperadorDashboardController::class, 'index'])->name('dashboard.operador');

    Route::get('/admin/usuarios', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.usuarios.index');
    
});

require __DIR__ . '/auth.php';
