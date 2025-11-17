<?php

use App\Http\Controllers\CampistaController;
use App\Http\Controllers\ConfidenteController;
use App\Http\Controllers\TriboController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CampistaController::class, 'index']);
Route::post('/adicionar-a-tribo/{campista}/{tribo}', [CampistaController::class, 'adicionarATribo']);
Route::post('/remover-da-tribo/{campista}', [CampistaController::class, 'removerDaTribo']);
Route::get('/monta-tribos', [CampistaController::class, 'montaTribos']);
Route::get('/conhecidos/{campista}', [CampistaController::class, 'getConhecidos']);
Route::post('/conhecidos/adicionar', [CampistaController::class, 'adicionarConhecido']);
Route::post('/conhecidos/remover', [CampistaController::class, 'removerConhecido']);
Route::get('/confidentes/{campista}', [CampistaController::class, 'getConfidentesConhecidos']);
Route::post('/confidentes/adicionar', [CampistaController::class, 'adicionarConfidenteConhecido']);
Route::post('/confidentes/remover', [CampistaController::class, 'removerConfidenteConhecido']);
Route::get('/confidentes-api', [ConfidenteController::class, 'apiIndex']);
Route::post('/campistas/adicionar', [CampistaController::class, 'adicionarCampista']);
Route::put('/campistas/editar/{id}', [CampistaController::class, 'atualizarCampista']);
Route::delete('/campistas/remover/{id}', [CampistaController::class, 'removerCampista']);
Route::post('/campistas/importar-csv', [CampistaController::class, 'importarCSV']);

// Rotas de Tribos
Route::get('/tribos', [TriboController::class, 'index']);
Route::post('/tribos/adicionar', [TriboController::class, 'store']);
Route::put('/tribos/editar/{tribo}', [TriboController::class, 'update']);
Route::delete('/tribos/remover/{tribo}', [TriboController::class, 'destroy']);

// Rotas de Confidentes
Route::get('/confidentes', [ConfidenteController::class, 'index']);
Route::post('/confidentes-crud/adicionar', [ConfidenteController::class, 'store']);
Route::put('/confidentes-crud/editar/{id}', [ConfidenteController::class, 'update']);
Route::delete('/confidentes-crud/remover/{id}', [ConfidenteController::class, 'destroy']);
