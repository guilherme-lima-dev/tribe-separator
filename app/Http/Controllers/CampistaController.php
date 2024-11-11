<?php

namespace App\Http\Controllers;

use App\Models\Campista;
use App\Models\Confidente;
use App\Models\Tribo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CampistaController extends Controller
{
    public function index()
    {
        $campistas = Campista::all();
        $tribos = Tribo::all();
        return response()->view('welcome', compact('campistas', 'tribos'));
    }

    public function adicionarATribo(Request $request, Campista $campista, Tribo $tribo): RedirectResponse
    {
        if ($this->verificaRegrasTribo($tribo, $campista)) {
            $campista->tribo_id = $tribo->id;
            $campista->save();
            return redirect()->back()->with('success', 'Campista adicionado com sucesso.');
        }
        return redirect()->back()->with('warning', 'Campista não atende aos critérios da tribo.');
    }

    public function removerDatribo(Request $request, Campista $campista): RedirectResponse
    {
        $campista->tribo_id = null;
        $campista->save();
        return redirect()->back()->with('warning', 'Campista removido da tribo.');
    }

    public function montaTribos()
    {
        $campistas = Campista::all();
        $tribos = Tribo::all();
        $this->distribuirCampistasNasTribos($campistas, $tribos);

        $todasValidas = $tribos->every(fn($tribo) => $tribo->estaValida());
        return redirect()->back()->with($todasValidas ? 'success' : 'warning',
            $todasValidas ? 'Todas as tribos foram montadas com sucesso.' : 'Algumas tribos podem precisar de intervenção manual.');
    }

    private function distribuirCampistasNasTribos($campistas, $tribos)
    {
        foreach ($campistas as $campista) {
            foreach ($tribos as $tribo) {
                if ($tribo->campistas()->count() < 13 && $this->verificaRegrasTribo($tribo, $campista)) {
                    $campista->tribo_id = $tribo->id;
                    $campista->save();
                    break;
                }
            }
        }
    }

    private function verificaRegrasTribo(Tribo $tribo, Campista $campista): bool
    {
        $numHomens = $tribo->campistas()->where('genero', 'M')->count();
        return $tribo->campistas()->count() < 13 && ($campista->genero === 'F' || $numHomens < 7);
    }

    public function getConhecidos(Campista $campista)
    {
        return response()->json([
            'conhecidos' => $campista->conhecidos()->select(['id','nome'])->get()
        ]);
    }

    // CampistaController.php

    public function adicionarConhecido(Request $request)
    {
        $campista = Campista::find($request->campistaId);
        $conhecido = Campista::find($request->novoConhecidoId);

        if ($campista && $conhecido) {
            $campista->conhecidos()->attach($conhecido->id);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Campista ou conhecido não encontrado.']);
    }

    public function removerConhecido(Request $request)
    {
        $campista = Campista::find($request->campistaId);
        $conhecido = Campista::find($request->conhecidoId);

        if ($campista && $conhecido) {
            $campista->conhecidos()->detach($conhecido->id);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Campista ou conhecido não encontrado.']);
    }

    public function getConfidentesConhecidos(Campista $campista)
    {
        return response()->json([
            'confidentes' => $campista->confidentesConhecidos()->select(['nome', 'id'])->get()
        ]);
    }

    public function adicionarConfidenteConhecido(Request $request)
    {
        $campista = Campista::find($request->campistaId);
        $confidente = Campista::find($request->novoConfidenteId);

        if ($campista && $confidente) {
            $campista->confidentesConhecidos()->attach($confidente->id);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Campista ou confidente não encontrado.']);
    }

    public function removerConfidenteConhecido(Request $request)
    {
        $campista = Campista::find($request->campistaId);
        $confidente = Campista::find($request->confidenteId);

        if ($campista && $confidente) {
            $campista->confidentesConhecidos()->detach($confidente->id);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Campista ou confidente não encontrado.']);
    }

    // CampistaController.php (ou ConfidenteController.php se houver um controlador separado)

    public function getConfidentes()
    {
        // Obtenha todos os confidentes, por exemplo, apenas com os campos id e nome
        $confidentes = Confidente::select('id', 'nome')->get();

        return response()->json($confidentes);
    }


}
