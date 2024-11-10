<?php

namespace App\Http\Controllers;

use App\Models\Campista;
use App\Models\Tribo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CampistaController extends Controller
{

    public function index()
    {
        $campistas = Campista::all();

        $tribos = Tribo::all();

        return response()->view('welcome', ['campistas' => $campistas, 'tribos' => $tribos]);
    }

    public function adicionarATribo(Request $request, Campista $campista, Tribo $tribo): RedirectResponse
    {
        $campista->tribo_id = $tribo->id;
        $campista->save();

        return redirect()->back();
    }

    public function removerDatribo(Request $request, Campista $campista): RedirectResponse
    {
        $campista->tribo_id = null;
        $campista->save();

        return redirect()->back();
    }

    public function montaTribos()
    {
        // Obter todos os campistas
        $campistas = Campista::all();

        // Obter todas as tribos
        $tribos = Tribo::all();

        // Inicializar as contagens de alocações
        $triboIndex = 0;
        $totalTribos = $tribos->count();
        $reprocessar = false;
        $quantidadeReprocessamento = 0;

        do{

            // Distribuir os campistas nas tribos respeitando as regras
            foreach ($campistas as $campista) {
                // Tentar alocar o campista em uma tribo que atenda às regras
                for ($i = 0; $i < $totalTribos; $i++) {
                    $tribo = $tribos[$triboIndex];

                    // Verifica se a tribo atende às regras ao adicionar o campista
                    $numHomens = $tribo->campistas()->where('genero', 'M')->count();
                    $totalCampistas = $tribo->campistas()->count();

                    if ($totalCampistas < 13) {
                        $campista->tribo_id = $tribo->id;
                        $campista->save();

                        if ($campista->campistaAtendeARegra()) {
                            break;
                        }

                        $campista->tribo_id = null;
                        $campista->save();
                        $reprocessar = true;

                         // Sai do loop se o campista for alocado
                    }

                    // Mudar para a próxima tribo para a próxima tentativa de alocação
                    $triboIndex = ($triboIndex + 1) % $totalTribos;
                }
            }

            $quantidadeReprocessamento++;

            if ($quantidadeReprocessamento > 26) {
                $reprocessar = false;
            }

        } while($reprocessar);


        // Verificar se todas as tribos estão válidas
        $todasValidas = true;
        foreach ($tribos as $tribo) {
            if (!$tribo->estaValida()) {
                $todasValidas = false;
                break;
            }
        }

        // Mensagem de sucesso com intervenção manual, se necessário
        if ($todasValidas) {
            return redirect()->back()->with('success', 'Todas as tribos foram montadas com sucesso.');
        } else {
            return redirect()->back()->with('warning', 'As tribos foram montadas, mas algumas podem precisar de intervenção manual.');
        }
    }


}
