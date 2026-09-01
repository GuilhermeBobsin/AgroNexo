<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Agronomo\DashboardController as AgronomoDashboardController;
use App\Http\Controllers\Operador\DashboardController as OperadorDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', function () {
        return redirect()->route(
            'dashboard.' . auth()->user()->perfil
        );
    })->name('dashboard');

    Route::middleware('perfil:admin')->get('/dashboard/admin', [AdminDashboardController::class, 'index'])->name('dashboard.admin');
    Route::middleware('perfil:agronomo')->get('/dashboard/agronomo', [AgronomoDashboardController::class, 'index'])->name('dashboard.agronomo');
    Route::middleware('perfil:operador')->get('/dashboard/operador', [OperadorDashboardController::class, 'index'])->name('dashboard.operador');

    Route::prefix('admin')->name('admin.')->middleware('perfil:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
    });

    Route::prefix('agronomo')->name('agronomo.')->middleware('perfil:agronomo')->group(function () {
        Route::get('/dashboard', [AgronomoDashboardController::class, 'index'])->name('dashboard');
    });

    Route::prefix('operador')->name('operador.')->middleware('perfil:operador')->group(function () {
        Route::get('/dashboard', [OperadorDashboardController::class, 'index'])->name('dashboard');
    });
});

require __DIR__ . '/auth.php';
