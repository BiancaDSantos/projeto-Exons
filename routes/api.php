<?php

use App\Http\Controllers\compromissoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\consultorController;


Route::prefix('consultor')->group(function () {
    Route::get('/', [ConsultorController::class, 'buscaTodos']); 
    Route::get('/{id}', [ConsultorController::class, 'buscaUm']);
    Route::post('/', [ConsultorController::class, 'cadastrar']); 
    Route::put('/{id}', [ConsultorController::class, 'atualizar']); 
    Route::delete('/{id}', [ConsultorController::class, 'deletar']);
});

Route::prefix('compromisso')->group(function () {
    Route::post('/', [compromissoController::class, 'cadastrar']);
    Route::get('/', [compromissoController::class, 'buscaTodos']);
    Route::get('{id}', [compromissoController::class, 'buscaUm']);
    Route::put('{id}', [compromissoController::class, 'atualizar']);   
    Route::delete('{id}', [compromissoController::class, 'deletar']);
    Route::get('{id}/valor-total', [compromissoController::class, 'calcularValorTotal']);
});
