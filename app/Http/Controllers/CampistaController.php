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
        $confidentes = Confidente::all();
        return response()->view('welcome', compact('campistas', 'tribos', 'confidentes'));
    }

    public function adicionarATribo(Request $request, Campista $campista, Tribo $tribo): RedirectResponse
    {
        // Usar retornaInfracaoNessaTribo() que valida TODAS as regras
        $infracao = $campista->retornaInfracaoNessaTribo($tribo->id);
        if (is_null($infracao)) {
            $campista->tribo_id = $tribo->id;
            $campista->save();
            return redirect()->back()->with('success', 'Campista adicionado com sucesso.');
        }
        return redirect()->back()->with('warning', 'Campista não atende aos critérios da tribo: ' . $infracao);
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
        // 1. LIMPAR todas as tribos antes de redistribuir
        Campista::query()->update(['tribo_id' => null]);

        // 2. Distribuir cada campista
        foreach ($campistas as $campista) {
            foreach ($tribos as $tribo) {
                // Verificar limite de 13 campistas
                if ($tribo->campistas()->count() >= 13) {
                    continue;
                }

                // USAR retornaInfracaoNessaTribo() que já valida TODAS as regras
                // (conhecidos, confidentes, limite, gênero)
                if (is_null($campista->retornaInfracaoNessaTribo($tribo->id))) {
                    $campista->tribo_id = $tribo->id;
                    $campista->save();
                    break; // Campista alocado, ir para o próximo
                }
            }
        }
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
        $confidente = Confidente::find($request->novoConfidenteId);

        if ($campista && $confidente) {
            $campista->confidentesConhecidos()->attach($confidente->id);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Campista ou confidente não encontrado.']);
    }

    public function removerConfidenteConhecido(Request $request)
    {
        $campista = Campista::find($request->campistaId);
        $confidente = Confidente::find($request->confidenteId);

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

    // CampistaController.php

    public function adicionarCampista(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'genero' => 'required|in:m,f',
            'peso' => 'required|numeric',
            'altura' => 'required|numeric'
        ]);

        $campista = Campista::create($validated);

        return response()->json(['success' => true, 'campista' => $campista]);
    }

    public function atualizarCampista(Request $request, $id)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'genero' => 'required|in:m,f',
            'peso' => 'required|numeric',
            'altura' => 'required|numeric'
        ]);

        $campista = Campista::find($id);

        if (!$campista) {
            return response()->json(['success' => false, 'message' => 'Campista não encontrado'], 404);
        }

        $campista->update($validated);

        return response()->json(['success' => true, 'campista' => $campista]);
    }

    public function removerCampista($id)
    {
        $campista = Campista::find($id);

        if ($campista) {
            $campista->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Campista não encontrado']);
    }

    public function importarCSV(Request $request)
    {
        $request->validate([
            'arquivo_csv' => 'required|file|mimes:csv,txt|max:10240'
        ]);

        $arquivo = $request->file('arquivo_csv');
        $caminho = $arquivo->getRealPath();
        
        $dados = [];
        $sucessos = 0;
        $erros = 0;
        $linhasProcessadas = 0;
        $errosDetalhados = [];

        // Ler o arquivo CSV
        // Tentar detectar o encoding e converter para UTF-8
        $conteudo = file_get_contents($caminho);
        $encoding = mb_detect_encoding($conteudo, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
        if ($encoding && $encoding !== 'UTF-8') {
            $conteudo = mb_convert_encoding($conteudo, 'UTF-8', $encoding);
            file_put_contents($caminho, $conteudo);
        }
        
        if (($handle = fopen($caminho, 'r')) !== false) {
            // Pular o cabeçalho (primeira linha)
            $cabecalho = fgetcsv($handle, 0, ',');
            
            while (($linha = fgetcsv($handle, 0, ',')) !== false) {
                $linhasProcessadas++;
                $nomeCampista = null;
                
                try {
                    // Mapear colunas do CSV
                    // Coluna 1 (índice 1): Nome completo
                    // Coluna 5 (índice 4): Sexo
                    // Coluna 10 (índice 9): Peso
                    // Coluna 11 (índice 10): Altura
                    
                    if (count($linha) < 11) {
                        $erros++;
                        $nomeCampista = !empty($linha[1]) ? trim($linha[1]) : 'Nome não disponível';
                        $errosDetalhados[] = "Linha {$linhasProcessadas} - Campista: '{$nomeCampista}' - Erro: Dados insuficientes na linha";
                        continue;
                    }

                    $nome = trim($linha[1] ?? '');
                    $nomeCampista = $nome ?: 'Nome não disponível';
                    $sexo = trim($linha[4] ?? '');
                    $peso = trim($linha[9] ?? '');
                    $altura = trim($linha[10] ?? '');

                    // Validar nome
                    if (empty($nome)) {
                        $erros++;
                        $errosDetalhados[] = "Linha {$linhasProcessadas} - Campista: '{$nomeCampista}' - Erro: Nome vazio";
                        continue;
                    }

                    // Converter sexo - normalizar e verificar variações
                    $genero = null;
                    // Normalizar: remover espaços, converter para minúsculas, remover acentos
                    $sexoLimpo = mb_strtolower(trim($sexo), 'UTF-8');
                    $sexoLimpo = preg_replace('/\s+/', '', $sexoLimpo); // Remove todos os espaços
                    
                    // Verificar masculino (várias variações possíveis)
                    if (strpos($sexoLimpo, 'masculino') !== false || 
                        $sexoLimpo === 'm' || 
                        $sexoLimpo === 'mas' ||
                        $sexoLimpo === 'masculino') {
                        $genero = 'm';
                    } 
                    // Verificar feminino (várias variações possíveis)
                    elseif (strpos($sexoLimpo, 'feminino') !== false || 
                            $sexoLimpo === 'f' || 
                            $sexoLimpo === 'fem' ||
                            $sexoLimpo === 'feminino') {
                        $genero = 'f';
                    }

                    if (!$genero) {
                        $erros++;
                        $errosDetalhados[] = "Linha {$linhasProcessadas} - Campista: '{$nomeCampista}' - Erro: Sexo inválido ('{$sexo}') - valor normalizado: '{$sexoLimpo}'";
                        continue;
                    }

                    // Processar peso
                    $pesoLimpo = $this->limparNumero($peso);
                    if ($pesoLimpo === null || $pesoLimpo <= 0) {
                        $erros++;
                        $errosDetalhados[] = "Linha {$linhasProcessadas} - Campista: '{$nomeCampista}' - Erro: Peso inválido ({$peso})";
                        continue;
                    }

                    // Processar altura
                    $alturaLimpa = $this->limparAltura($altura);
                    if ($alturaLimpa === null || $alturaLimpa <= 0) {
                        $erros++;
                        $errosDetalhados[] = "Linha {$linhasProcessadas} - Campista: '{$nomeCampista}' - Erro: Altura inválida ({$altura})";
                        continue;
                    }

                    // Verificar se campista já existe (por nome)
                    $campistaExistente = Campista::where('nome', $nome)->first();
                    if ($campistaExistente) {
                        // Atualizar campista existente
                        try {
                            $campistaExistente->genero = $genero;
                            $campistaExistente->peso = $pesoLimpo;
                            $campistaExistente->altura = $alturaLimpa;
                            $campistaExistente->save();
                            $sucessos++;
                        } catch (\Exception $e) {
                            $erros++;
                            $errosDetalhados[] = "Linha {$linhasProcessadas} - Campista: '{$nomeCampista}' - Erro ao atualizar: " . $e->getMessage();
                        }
                    } else {
                        // Criar novo campista
                        try {
                            Campista::create([
                                'nome' => $nome,
                                'genero' => $genero,
                                'peso' => $pesoLimpo,
                                'altura' => $alturaLimpa
                            ]);
                            $sucessos++;
                        } catch (\Exception $e) {
                            $erros++;
                            $errosDetalhados[] = "Linha {$linhasProcessadas} - Campista: '{$nomeCampista}' - Erro ao criar: " . $e->getMessage();
                        }
                    }

                } catch (\Exception $e) {
                    $erros++;
                    $nomeErro = $nomeCampista ?? (!empty($linha[1]) ? trim($linha[1]) : 'Nome não disponível');
                    $errosDetalhados[] = "Linha {$linhasProcessadas} - Campista: '{$nomeErro}' - Erro inesperado: " . $e->getMessage();
                }
            }
            fclose($handle);
        }

        $mensagem = "Importação concluída! {$sucessos} campista(s) importado(s) com sucesso.";
        if ($erros > 0) {
            $mensagem .= " {$erros} erro(s) encontrado(s). Verifique os detalhes abaixo.";
        }

        return redirect()->back()->with([
            'success' => $mensagem,
            'importacao_detalhes' => [
                'sucessos' => $sucessos,
                'erros' => $erros,
                'total' => $linhasProcessadas,
                'erros_detalhados' => $errosDetalhados // Mostrar todos os erros
            ]
        ]);
    }

    /**
     * Limpa e converte string numérica para float
     */
    private function limparNumero($valor)
    {
        if (empty($valor)) {
            return null;
        }

        // Remover "kg", espaços e outros caracteres não numéricos (exceto vírgula e ponto)
        $limpo = preg_replace('/[^0-9,.]/', '', $valor);
        
        // Converter vírgula para ponto
        $limpo = str_replace(',', '.', $limpo);
        
        // Converter para float
        $numero = floatval($limpo);
        
        return $numero > 0 ? $numero : null;
    }

    /**
     * Limpa e converte altura para centímetros
     */
    private function limparAltura($valor)
    {
        if (empty($valor)) {
            return null;
        }

        // Remover "m", "cm", espaços e outros caracteres não numéricos (exceto vírgula e ponto)
        $limpo = preg_replace('/[^0-9,.]/', '', $valor);
        
        // Converter vírgula para ponto
        $limpo = str_replace(',', '.', $limpo);
        
        // Converter para float
        $numero = floatval($limpo);
        
        // Se o número for menor que 3, provavelmente está em metros, converter para cm
        if ($numero > 0 && $numero < 3) {
            $numero = $numero * 100;
        }
        
        return $numero > 0 ? $numero : null;
    }
}
