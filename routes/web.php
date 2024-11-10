<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\CampistaController::class, 'index']);
Route::post('/adicionar-a-tribo/{campista}/{tribo}', [\App\Http\Controllers\CampistaController::class, 'adicionarATribo']);
Route::post('/remover-da-tribo/{campista}', [\App\Http\Controllers\CampistaController::class, 'removerDaTribo']);
Route::get('/monta-tribos', [\App\Http\Controllers\CampistaController::class, 'montaTribos']);
