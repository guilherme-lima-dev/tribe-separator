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
        // Usar eager loading para otimização e ordenar alfabeticamente por nome
        $campistas = Campista::with(['conhecidos', 'confidentesConhecidos', 'tribo'])
            ->orderBy('nome', 'asc')
            ->get();
        $tribos = Tribo::with(['campistas', 'confidentes'])->get();
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
        // Usar eager loading para otimização
        $campistas = Campista::with(['conhecidos', 'confidentesConhecidos'])->get();
        $tribos = Tribo::with(['campistas', 'confidentes'])->get();
        
        $resultado = $this->distribuirCampistasNasTribos($campistas, $tribos);

        $todasValidas = $tribos->every(fn($tribo) => $tribo->estaValida());
        
        // Recarregar tribos para ter dados atualizados
        $tribos = Tribo::with(['campistas', 'confidentes'])->get();
        
        $mensagem = $todasValidas 
            ? "Todas as tribos foram montadas com sucesso! {$resultado['alocados']} campista(s) alocado(s)." 
            : "Distribuição concluída: {$resultado['alocados']} campista(s) alocado(s).";
        
        if ($resultado['naoAlocados'] > 0) {
            $mensagem .= " {$resultado['naoAlocados']} campista(s) não puderam ser alocados.";
        }
        
        $dadosRetorno = [
            $todasValidas ? 'success' : 'warning' => $mensagem
        ];
        
        if (!empty($resultado['campistasNaoAlocados'])) {
            $dadosRetorno['campistas_nao_alocados'] = $resultado['campistasNaoAlocados'];
        }
        
        return redirect()->back()->with($dadosRetorno);
    }

    /**
     * Calcula médias globais de peso, altura e proporção de gênero
     */
    private function calcularMediasGlobais($campistas)
    {
        $totalCampistas = $campistas->count();
        
        if ($totalCampistas === 0) {
            return [
                'peso' => 0,
                'altura' => 0,
                'proporcaoGenero' => ['m' => 0, 'f' => 0]
            ];
        }
        
        $pesoMedio = $campistas->avg('peso');
        $alturaMedia = $campistas->avg('altura');
        
        $numHomens = $campistas->where('genero', 'm')->count();
        $numMulheres = $campistas->where('genero', 'f')->count();
        
        return [
            'peso' => $pesoMedio ?? 0,
            'altura' => $alturaMedia ?? 0,
            'proporcaoGenero' => [
                'm' => $numHomens,
                'f' => $numMulheres,
                'total' => $totalCampistas
            ]
        ];
    }

    /**
     * Calcula score de balanceamento simulando a adição do campista à tribo
     * Quanto menor o score, melhor o balanceamento
     */
    private function calcularScoreBalanceamento($campista, $tribo, $mediasGlobais)
    {
        $campistasAtuais = $tribo->campistas;
        $totalAtual = $campistasAtuais->count();
        
        // Se tribo está vazia, usar valores do campista
        if ($totalAtual === 0) {
            $pesoMedioAtual = 0;
            $alturaMediaAtual = 0;
        } else {
            $pesoMedioAtual = $campistasAtuais->avg('peso');
            $alturaMediaAtual = $campistasAtuais->avg('altura');
        }
        
        // SIMULAR nova média SE adicionar este campista
        // Fórmula: nova_media = (media_atual * n + novo_valor) / (n + 1)
        $novoPesoMedio = (($pesoMedioAtual * $totalAtual) + $campista->peso) / ($totalAtual + 1);
        $novaAlturaMedia = (($alturaMediaAtual * $totalAtual) + $campista->altura) / ($totalAtual + 1);
        
        // Calcular desvio da média global (quanto menor, melhor)
        $desvioPeso = abs($novoPesoMedio - $mediasGlobais['peso']);
        $desvioAltura = abs($novaAlturaMedia - $mediasGlobais['altura']);
        
        // Calcular desvio percentual
        $desvioPercentualPeso = $mediasGlobais['peso'] > 0 
            ? ($desvioPeso / $mediasGlobais['peso']) * 100 
            : 0;
        $desvioPercentualAltura = $mediasGlobais['altura'] > 0 
            ? ($desvioAltura / $mediasGlobais['altura']) * 100 
            : 0;
        
        // Score: soma dos desvios percentuais (altura pesa menos)
        // Peso tem peso 1, altura tem peso 0.5
        return $desvioPercentualPeso + ($desvioPercentualAltura * 0.5);
    }

    private function distribuirCampistasNasTribos($campistas, $tribos)
    {
        // 1. LIMPAR todas as tribos antes de redistribuir
        Campista::query()->update(['tribo_id' => null]);
        
        // 2. Calcular médias globais
        $mediasGlobais = $this->calcularMediasGlobais($campistas);
        
        // 3. Ordenar campistas por restrições (mais restritivos primeiro)
        // Campistas com mais conhecidos/confidentes devem ser alocados primeiro
        $campistasOrdenados = $campistas->sortByDesc(function($c) {
            return $c->conhecidos->count() + $c->confidentesConhecidos->count();
        });
        
        $alocados = 0;
        $naoAlocados = 0;
        $campistasNaoAlocados = [];
        
        // 4. Distribuir cada campista
        foreach ($campistasOrdenados as $campista) {
            $melhorTribo = null;
            $menorScore = PHP_FLOAT_MAX;
            $motivoNaoAlocado = null;
            
            // Encontrar melhor tribo entre as válidas
            foreach ($tribos as $tribo) {
                // VALIDAR REGRAS SOCIAIS PRIMEIRO (prioridade absoluta)
                $infracao = $campista->retornaInfracaoNessaTribo($tribo->id);
                if (!is_null($infracao)) {
                    // Guardar motivo se for a primeira tentativa
                    if ($motivoNaoAlocado === null) {
                        $motivoNaoAlocado = $infracao;
                    }
                    continue; // Tribo inválida, pular
                }
                
                // Verificar limite de 13 campistas
                if ($tribo->campistas()->count() >= 13) {
                    continue; // Tribo cheia
                }
                
                // Calcular score de balanceamento
                $score = $this->calcularScoreBalanceamento($campista, $tribo, $mediasGlobais);
                
                // Escolher tribo com menor score (melhor balanceamento)
                if ($score < $menorScore) {
                    $menorScore = $score;
                    $melhorTribo = $tribo;
                }
            }
            
            // Adicionar à melhor tribo encontrada
            if ($melhorTribo) {
                $campista->tribo_id = $melhorTribo->id;
                $campista->save();
                $alocados++;
            } else {
                // Campista não pôde ser alocado
                $naoAlocados++;
                $campistasNaoAlocados[] = [
                    'nome' => $campista->nome,
                    'motivo' => $motivoNaoAlocado ?? 'Não há tribos disponíveis que respeitem as regras'
                ];
            }
        }
        
        return [
            'alocados' => $alocados,
            'naoAlocados' => $naoAlocados,
            'campistasNaoAlocados' => $campistasNaoAlocados
        ];
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
        
        if (!$campista) {
            return response()->json(['success' => false, 'message' => 'Campista não encontrado.']);
        }

        // Aceitar tanto um único ID quanto um array de IDs
        $conhecidosIds = $request->novoConhecidoId ?? [];
        if (!is_array($conhecidosIds)) {
            $conhecidosIds = [$conhecidosIds];
        }
        
        // Filtrar IDs vazios e evitar que o campista se adicione como conhecido
        $conhecidosIds = array_filter($conhecidosIds, function($id) use ($campista) {
            return !empty($id) && $id != $campista->id;
        });

        if (empty($conhecidosIds)) {
            return response()->json(['success' => false, 'message' => 'Nenhum conhecido selecionado ou seleção inválida.']);
        }

        // Verificar se todos os conhecidos existem
        $conhecidos = Campista::whereIn('id', $conhecidosIds)->get();
        if ($conhecidos->count() !== count($conhecidosIds)) {
            return response()->json(['success' => false, 'message' => 'Um ou mais conhecidos não foram encontrados.']);
        }

        // Adicionar todos os conhecidos (attach ignora duplicatas)
        $campista->conhecidos()->attach($conhecidosIds);
        
        return response()->json([
            'success' => true,
            'message' => count($conhecidosIds) > 1 
                ? count($conhecidosIds) . ' conhecidos adicionados com sucesso.' 
                : 'Conhecido adicionado com sucesso.'
        ]);
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
        
        if (!$campista) {
            return response()->json(['success' => false, 'message' => 'Campista não encontrado.']);
        }

        // Aceitar tanto um único ID quanto um array de IDs
        $confidentesIds = $request->novoConfidenteId ?? [];
        if (!is_array($confidentesIds)) {
            $confidentesIds = [$confidentesIds];
        }
        
        // Filtrar IDs vazios
        $confidentesIds = array_filter($confidentesIds, function($id) {
            return !empty($id);
        });

        if (empty($confidentesIds)) {
            return response()->json(['success' => false, 'message' => 'Nenhum confidente selecionado.']);
        }

        // Verificar se todos os confidentes existem
        $confidentes = Confidente::whereIn('id', $confidentesIds)->get();
        if ($confidentes->count() !== count($confidentesIds)) {
            return response()->json(['success' => false, 'message' => 'Um ou mais confidentes não foram encontrados.']);
        }

        // Adicionar todos os confidentes (attach ignora duplicatas)
        $campista->confidentesConhecidos()->attach($confidentesIds);
        
        return response()->json([
            'success' => true,
            'message' => count($confidentesIds) > 1 
                ? count($confidentesIds) . ' confidentes adicionados com sucesso.' 
                : 'Confidente adicionado com sucesso.'
        ]);
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
            $numeroLinha = 1; // Começar em 1 porque já pulamos o cabeçalho
            
            while (($linha = fgetcsv($handle, 0, ',')) !== false) {
                $numeroLinha++;
                $linhasProcessadas++;
                $nomeCampista = null;
                
                // Verificar se a linha está vazia
                if (empty(array_filter($linha, function($campo) { return trim($campo) !== ''; }))) {
                    $errosDetalhados[] = "Linha {$numeroLinha} - Linha vazia ignorada";
                    continue;
                }
                
                try {
                    // Mapear colunas do CSV
                    // Coluna 1 (índice 1): Nome completo
                    // Coluna 5 (índice 4): Sexo
                    // Coluna 10 (índice 9): Peso
                    // Coluna 11 (índice 10): Altura
                    
                    $numColunas = count($linha);
                    if ($numColunas < 11) {
                        $erros++;
                        $nomeCampista = !empty($linha[1]) ? trim($linha[1]) : 'Nome não disponível';
                        $errosDetalhados[] = "Linha {$numeroLinha} - Campista: '{$nomeCampista}' - Erro: Dados insuficientes na linha (apenas {$numColunas} colunas encontradas, esperado 11+)";
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
                        $errosDetalhados[] = "Linha {$numeroLinha} - Campista: '{$nomeCampista}' - Erro: Sexo inválido ('{$sexo}') - valor normalizado: '{$sexoLimpo}'";
                        continue;
                    }

                    // Processar peso
                    $pesoLimpo = $this->limparNumero($peso);
                    if ($pesoLimpo === null || $pesoLimpo <= 0) {
                        $erros++;
                        $errosDetalhados[] = "Linha {$numeroLinha} - Campista: '{$nomeCampista}' - Erro: Peso inválido ou vazio (valor original: '{$peso}')";
                        continue;
                    }

                    // Processar altura
                    $alturaLimpa = $this->limparAltura($altura);
                    if ($alturaLimpa === null || $alturaLimpa <= 0) {
                        $erros++;
                        $errosDetalhados[] = "Linha {$numeroLinha} - Campista: '{$nomeCampista}' - Erro: Altura inválida ou vazia (valor original: '{$altura}')";
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
                            $errosDetalhados[] = "Linha {$numeroLinha} - Campista: '{$nomeCampista}' - Erro ao atualizar: " . $e->getMessage();
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
                            $errosDetalhados[] = "Linha {$numeroLinha} - Campista: '{$nomeCampista}' - Erro ao criar: " . $e->getMessage();
                        }
                    }

                } catch (\Exception $e) {
                    $erros++;
                    $nomeErro = $nomeCampista ?? (!empty($linha[1]) ? trim($linha[1]) : 'Nome não disponível');
                    $errosDetalhados[] = "Linha {$numeroLinha} - Campista: '{$nomeErro}' - Erro inesperado: " . $e->getMessage();
                }
            }
            fclose($handle);
        }

        // Contar linhas vazias ignoradas
        $linhasVazias = count(array_filter($errosDetalhados, function($erro) {
            return strpos($erro, 'Linha vazia ignorada') !== false;
        }));
        
        $totalLinhasArquivo = $numeroLinha; // Último número de linha processado
        $totalEsperado = $totalLinhasArquivo - 1; // Menos o cabeçalho
        
        $mensagem = "Importação concluída! {$sucessos} campista(s) importado(s) com sucesso.";
        if ($erros > 0) {
            $mensagem .= " {$erros} erro(s) encontrado(s).";
        }
        if ($linhasVazias > 0) {
            $mensagem .= " {$linhasVazias} linha(s) vazia(s) ignorada(s).";
        }
        $mensagem .= " Total de linhas processadas: {$linhasProcessadas} (esperado: {$totalEsperado}).";

        return redirect()->back()->with([
            'success' => $mensagem,
            'importacao_detalhes' => [
                'sucessos' => $sucessos,
                'erros' => $erros,
                'total' => $linhasProcessadas,
                'total_esperado' => $totalEsperado,
                'linhas_vazias' => $linhasVazias,
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
