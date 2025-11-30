<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\VendaController; // ← ADICIONAR ESTA LINHA
use Illuminate\Support\Facades\Route;

// Redirecionar raiz para dashboard
Route::get('/', function () {
    return redirect('/dashboard');
});

// Dashboard principal (usando o Controller)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas para Clientes
    Route::resource('clientes', ClienteController::class);

    // Rotas para Produtos
    Route::resource('produtos', ProdutoController::class);

    // Rotas de Vendas
    Route::resource('vendas', VendaController::class)->except(['edit', 'update']);

    // Rota adicional para relatório (futuro)
    Route::get('/vendas-relatorio', [VendaController::class, 'relatorio'])->name('vendas.relatorio');
});

require __DIR__.'/auth.php';