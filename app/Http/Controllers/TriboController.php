<?php

namespace App\Http\Controllers;

use App\Models\Confidente;
use App\Models\Tribo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TriboController extends Controller
{
    public function index()
    {
        $tribos = Tribo::with('confidentes')->get();
        return response()->json($tribos);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nome_tribo' => 'required|string|max:255|unique:tribo,nome_tribo',
            'confidentes_ids' => 'nullable|array',
            'confidentes_ids.*' => 'exists:confidentes,id',
        ]);

        $tribo = Tribo::create([
            'nome_tribo' => $validated['nome_tribo']
        ]);

        // Atribuir confidentes à tribo
        if (isset($validated['confidentes_ids']) && !empty($validated['confidentes_ids'])) {
            Confidente::whereIn('id', $validated['confidentes_ids'])
                ->update(['tribo_id' => $tribo->id]);
        }

        return response()->json(['success' => true, 'tribo' => $tribo]);
    }

    public function update(Request $request, Tribo $tribo): JsonResponse
    {
        $validated = $request->validate([
            'nome_tribo' => 'required|string|max:255|unique:tribo,nome_tribo,' . $tribo->id,
            'confidentes_ids' => 'nullable|array',
            'confidentes_ids.*' => 'exists:confidentes,id',
        ]);

        $tribo->update([
            'nome_tribo' => $validated['nome_tribo']
        ]);

        // Remover confidentes da tribo atual
        Confidente::where('tribo_id', $tribo->id)->update(['tribo_id' => null]);

        // Atribuir novos confidentes à tribo
        if (isset($validated['confidentes_ids']) && !empty($validated['confidentes_ids'])) {
            Confidente::whereIn('id', $validated['confidentes_ids'])
                ->update(['tribo_id' => $tribo->id]);
        }

        return response()->json(['success' => true, 'tribo' => $tribo]);
    }

    public function destroy(Tribo $tribo): JsonResponse
    {
        // Verificar se a tribo tem campistas ou confidentes
        if ($tribo->campistas()->count() > 0 || $tribo->confidentes()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Não é possível excluir uma tribo que possui campistas ou confidentes associados.'
            ], 422);
        }

        $tribo->delete();

        return response()->json(['success' => true]);
    }
}

