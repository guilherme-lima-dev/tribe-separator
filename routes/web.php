<?php

use App\Http\Controllers\CampistaController;
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
Route::get('/confidentes', [CampistaController::class, 'getConfidentes']);
