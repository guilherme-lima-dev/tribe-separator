<?php

namespace App\Http\Controllers;

use App\Models\Confidente;
use App\Models\Tribo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConfidenteController extends Controller
{
    public function index()
    {
        $confidentes = Confidente::with('tribo')->get();
        $tribos = Tribo::all();
        return response()->view('confidentes', compact('confidentes', 'tribos'));
    }

    public function apiIndex()
    {
        $confidentes = Confidente::all();
        return response()->json($confidentes);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:confidentes,nome',
            'tribo_id' => 'nullable|exists:tribo,id',
        ]);

        $confidente = Confidente::create($validated);

        return response()->json(['success' => true, 'confidente' => $confidente]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:confidentes,nome,' . $id,
            'tribo_id' => 'nullable|exists:tribo,id',
        ]);

        $confidente = Confidente::find($id);

        if (!$confidente) {
            return response()->json(['success' => false, 'message' => 'Confidente não encontrado'], 404);
        }

        $confidente->update($validated);

        return response()->json(['success' => true, 'confidente' => $confidente]);
    }

    public function destroy($id): JsonResponse
    {
        $confidente = Confidente::find($id);

        if (!$confidente) {
            return response()->json(['success' => false, 'message' => 'Confidente não encontrado'], 404);
        }

        // Verificar se o confidente está associado a alguma tribo
        if ($confidente->tribo_id) {
            return response()->json([
                'success' => false, 
                'message' => 'Não é possível excluir o confidente pois ele está associado a uma tribo. Remova-o da tribo primeiro.'
            ], 400);
        }

        // Verificar se há campistas que conhecem este confidente
        if ($confidente->campistas()->count() > 0) {
            return response()->json([
                'success' => false, 
                'message' => 'Não é possível excluir o confidente pois há campistas que o conhecem. Remova essas associações primeiro.'
            ], 400);
        }

        $confidente->delete();

        return response()->json(['success' => true]);
    }
}

