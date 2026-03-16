<?php

use App\Http\Controllers\PublicController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\PanelController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/propiedades', [PublicController::class, 'propiedades'])->name('propiedades');
Route::get('/propiedades/{id}', [PublicController::class, 'show'])->name('propiedades.show');
Route::post('/propiedades/{id}/consulta', [PublicController::class, 'sendInquiry'])->name('propiedades.consulta');

// Rutas de autenticación (Breeze)
require __DIR__.'/auth.php';

// Dashboard redirect after login
Route::get('/dashboard', function () {
    if (auth()->user()->isSuperadmin()) {
        return redirect()->route('admin.dashboard');
    }
    if (auth()->user()->isInmobiliaria()) {
        return redirect()->route('panel.dashboard');
    }
    return redirect('/');
})->middleware('auth')->name('dashboard');

// Rutas Admin (Superadmin)
Route::prefix('admin')->middleware(['auth', 'superadmin'])->name('admin.')->group(function () {
    Route::get('/', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/inmobiliarias', [SuperAdminController::class, 'inmobiliarias'])->name('inmobiliarias');
    Route::post('/inmobiliarias/{id}/aprobar', [SuperAdminController::class, 'aprobarInmobiliaria'])->name('inmobiliarias.aprobar');
    Route::post('/inmobiliarias/{id}/rechazar', [SuperAdminController::class, 'rechazarInmobiliaria'])->name('inmobiliarias.rechazar');
    
    Route::get('/propiedades', [SuperAdminController::class, 'propiedades'])->name('propiedades');
    Route::post('/propiedades/{id}/destacado', [SuperAdminController::class, 'toggleDestacado'])->name('propiedades.destacado');
    
    Route::get('/consultas', [SuperAdminController::class, 'consultas'])->name('consultas');
    Route::post('/consultas/{id}/leida', [SuperAdminController::class, 'marcarLeidaConsulta'])->name('consultas.leida');
    
    Route::get('/reportes', [SuperAdminController::class, 'reportes'])->name('reportes');
});

// Rutas Panel (Inmobiliaria)
Route::prefix('panel')->middleware(['auth', 'inmobiliaria'])->name('panel.')->group(function () {
    Route::get('/', [PanelController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/propiedades', [PanelController::class, 'propiedades'])->name('propiedades');
    Route::get('/propiedades/create', [PanelController::class, 'createProperty'])->name('propiedades.create');
    Route::post('/propiedades', [PanelController::class, 'storeProperty'])->name('propiedades.store');
    Route::get('/propiedades/{id}/edit', [PanelController::class, 'editProperty'])->name('propiedades.edit');
    Route::put('/propiedades/{id}', [PanelController::class, 'updateProperty'])->name('propiedades.update');
    Route::delete('/propiedades/{id}', [PanelController::class, 'destroyProperty'])->name('propiedades.destroy');
    
    Route::get('/consultas', [PanelController::class, 'consultas'])->name('consultas');
    Route::post('/consultas/{id}/leida', [PanelController::class, 'marcarLeida'])->name('consultas.leida');
    
    Route::get('/perfil', [PanelController::class, 'perfil'])->name('perfil');
    Route::put('/perfil', [PanelController::class, 'updatePerfil'])->name('perfil.update');
});
