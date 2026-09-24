<?php

use App\Http\Controllers\Admin\CulturaController;
use App\Http\Controllers\Admin\EstoqueController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PropriedadeController;
use App\Http\Controllers\Admin\RecursoController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TalhaoController;
use App\Http\Controllers\Admin\TarefaController;
use App\Http\Controllers\Agronomo\DashboardController as AgronomoDashboardController;
use App\Http\Controllers\Operador\DashboardController as OperadorDashboardController;
use App\Http\Controllers\Operador\TarefaController as OperadorTarefaController;
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
        //dashboard e usuarios
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');

        //propriedades
        Route::get('/propriedades', [PropriedadeController::class, 'index'])->name('propriedades.index');
        Route::get('/propriedades/create', [PropriedadeController::class, 'create'])->name('propriedades.create');
        Route::post('/propriedades', [PropriedadeController::class, 'store'])->name('propriedades.store');
        Route::get('/propriedades/{propriedade}', [PropriedadeController::class, 'show'])->name('propriedades.show');

        //talhoes
        Route::get('/propriedades/{propriedade}/talhoes', [TalhaoController::class, 'index'])->name('propriedades.talhoes.index');
        Route::get('/propriedades/{propriedade}/talhoes/create', [TalhaoController::class, 'create'])->name('propriedades.talhoes.create');
        Route::post('/propriedades/{propriedade}/talhoes', [TalhaoController::class, 'store'])->name('propriedades.talhoes.store');
        Route::get('/propriedades/{propriedade}/talhoes/{talhao}', [TalhaoController::class, 'show'])->name('propriedades.talhoes.show');
        Route::get('/propriedades/{propriedade}/talhoes/{talhao}/edit', [TalhaoController::class, 'edit'])->name('propriedades.talhoes.edit');
        Route::put('/propriedades/{propriedade}/talhoes/{talhao}', [TalhaoController::class, 'update'])->name('propriedades.talhoes.update');
        Route::delete('/propriedades/{propriedade}/talhoes/{talhao}', [TalhaoController::class, 'destroy'])->name('propriedades.talhoes.destroy');

        //culturas
        Route::get('/culturas', [CulturaController::class, 'index'])->name('culturas.index');
        Route::post('/culturas', [CulturaController::class, 'store'])->name('culturas.store');
        Route::put('/culturas/{cultura}', [CulturaController::class, 'update'])->name('culturas.update');
        Route::delete('/culturas/{cultura}', [CulturaController::class, 'destroy'])->name('culturas.destroy');

        //estoque de produtos por propriedade
        Route::get('/estoque', [EstoqueController::class, 'index'])->name('estoque.index');
        Route::get('/estoque/create', [EstoqueController::class, 'create'])->name('estoque.create');
        Route::post('/estoque', [EstoqueController::class, 'store'])->name('estoque.store');
        Route::get('/estoque/{estoque}', [EstoqueController::class, 'show'])->name('estoque.show');
        Route::get('/estoque/{estoque}/edit', [EstoqueController::class, 'edit'])->name('estoque.edit');
        Route::put('/estoque/{estoque}', [EstoqueController::class, 'update'])->name('estoque.update');
        Route::delete('/estoque/{estoque}', [EstoqueController::class, 'destroy'])->name('estoque.destroy');

        //recursos
        Route::get('/recursos', [RecursoController::class, 'index'])->name('recursos.index');
        Route::get('/recursos/create', [RecursoController::class, 'create'])->name('recursos.create');
        Route::post('/recursos', [RecursoController::class, 'store'])->name('recursos.store');
        Route::get('/recursos/{recurso}', [RecursoController::class, 'show'])->name('recursos.show');
        Route::get('/recursos/{recurso}/edit', [RecursoController::class, 'edit'])->name('recursos.edit');
        Route::put('/recursos/{recurso}', [RecursoController::class, 'update'])->name('recursos.update');

        //tarefas
        Route::get('/tarefas', [TarefaController::class, 'index'])->name('tarefas.index');
        Route::get('/tarefas/create', [TarefaController::class, 'create'])->name('tarefas.create');
        Route::post('/tarefas', [TarefaController::class, 'store'])->name('tarefas.store');
        Route::get('/tarefas/{tarefa}', [TarefaController::class, 'show'])->name('tarefas.show');
        Route::get('/tarefas/{tarefa}/edit', [TarefaController::class, 'edit'])->name('tarefas.edit');
        Route::put('/tarefas/{tarefa}', [TarefaController::class, 'update'])->name('tarefas.update');
    });

    Route::prefix('agronomo')->name('agronomo.')->middleware('perfil:agronomo')->group(function () {
        Route::get('/dashboard', [AgronomoDashboardController::class, 'index'])->name('dashboard');
    });

    Route::prefix('operador')->name('operador.')->middleware('perfil:operador')->group(function () {
        Route::get('/dashboard', [OperadorDashboardController::class, 'index'])->name('dashboard');

        //tarefas
        Route::get('/tarefas', [OperadorTarefaController::class, 'index'])->name('tarefas.index');
        Route::get('/tarefas/{tarefa}', [OperadorTarefaController::class, 'show'])->name('tarefas.show');
        Route::patch('/tarefas/{tarefa}/status', [OperadorTarefaController::class, 'atualizarStatus'])->name('tarefas.status');
    });
});

require __DIR__ . '/auth.php';
