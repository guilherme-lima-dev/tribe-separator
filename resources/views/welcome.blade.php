<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="theme-color" content="#2E8B57"/>
    <link rel="shortcut icon" href="./assets/img/favicon.ico"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet"/>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .bg-maanaim {
            background: linear-gradient(135deg, #2E8B57 0%, #228B22 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .badge-valid {
            @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800;
        }
        
        .badge-invalid {
            @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800;
        }
        
        .btn-primary {
            @apply bg-[#2E8B57] hover:bg-[#228B22] text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200;
        }
        
        .btn-secondary {
            @apply bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200;
        }
        
        .btn-danger {
            @apply bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200;
        }
        
        .input-modern {
            @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2E8B57] focus:border-transparent transition-all;
        }
        
        .modal-backdrop {
            backdrop-filter: blur(4px);
        }
        
        .tribo-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 0;
            border: 1px solid #e5e7eb;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .tribo-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #2E8B57 0%, #228B22 100%);
            z-index: 1;
        }
        
        .tribo-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border-color: #2E8B57;
        }
        
        .tribo-card-content {
            padding: 1.5rem;
        }
        
        .stat-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            background: linear-gradient(to bottom right, #f9fafb, #f3f4f6);
            color: #374151;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
        }
        
        .stat-badge:hover {
            background: linear-gradient(to bottom right, #f3f4f6, #e5e7eb);
            border-color: #d1d5db;
            transform: translateY(-1px);
        }
        
        .tribo-header {
            background: linear-gradient(to right, #2E8B57, #228B22);
            color: white;
            margin-bottom: 1rem;
            padding: 1rem 1.5rem;
            border-radius: 1rem 1rem 0 0;
        }
        
        .campista-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.625rem;
            border-radius: 0.5rem;
            border: 1px solid;
            transition: all 0.2s ease;
        }
        
        .campista-item:hover {
            transform: translateX(2px);
        }
        
        .action-card {
            cursor: pointer;
            border: 2px solid transparent;
        }
        
        .action-card:hover {
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        /* Animações */
        .modal-enter {
            animation: modalFadeIn 0.3s ease-out;
        }
        
        .modal-exit {
            animation: modalFadeOut 0.3s ease-in;
        }
        
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        @keyframes modalFadeOut {
            from {
                opacity: 1;
                transform: scale(1);
            }
            to {
                opacity: 0;
                transform: scale(0.9);
            }
        }
        
        .list-item-enter {
            animation: slideInLeft 0.3s ease-out;
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #2E8B57;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    <title>Maanaim | Separação de Tribos</title>
</head>
<body class="bg-gray-50 antialiased">
    <!-- Navbar -->
    <nav class="bg-maanaim shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <i class="fas fa-users text-white text-xl mr-3"></i>
                    <h1 class="text-white text-lg font-bold">Maanaim - Separação de Tribos</h1>
                </div>
                <div class="flex gap-3">
                    <button onclick="abrirModalAdicionarTribo()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>Nova Tribo
                    </button>
                    <button onclick="abrirModalAdicionarCampista()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-user-plus mr-2"></i>Novo Campista
                    </button>
                    <a href="/confidentes" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-user-tie mr-2"></i>Confidentes
                    </a>
                </div>
            </div>
            </div>
        </nav>

    <!-- Alertas -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
                @if (session('importacao_detalhes'))
                    @php
                        $detalhes = session('importacao_detalhes');
                    @endphp
                    <div class="mt-3 text-sm">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-2">
                            <div>
                                <span class="font-medium">Total processado:</span> {{ $detalhes['total'] }}
                                @if(isset($detalhes['total_esperado']) && $detalhes['total_esperado'] != $detalhes['total'])
                                    <span class="text-yellow-600 text-xs">(esperado: {{ $detalhes['total_esperado'] }})</span>
                                @endif
                            </div>
                            <div class="text-green-700">
                                <span class="font-medium">Sucessos:</span> {{ $detalhes['sucessos'] }}
                            </div>
                            <div class="text-red-700">
                                <span class="font-medium">Erros:</span> {{ $detalhes['erros'] }}
                            </div>
                            @if(isset($detalhes['linhas_vazias']) && $detalhes['linhas_vazias'] > 0)
                                <div class="text-gray-600">
                                    <span class="font-medium">Linhas vazias:</span> {{ $detalhes['linhas_vazias'] }}
                                </div>
                            @endif
                        </div>
                        @if (!empty($detalhes['erros_detalhados']))
                            <details class="mt-2" open>
                                <summary class="cursor-pointer text-red-600 font-medium hover:text-red-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Ver detalhes dos erros ({{ count($detalhes['erros_detalhados']) }})
                                </summary>
                                <div class="mt-2 max-h-96 overflow-y-auto border border-red-200 rounded-lg p-3 bg-red-50">
                                    <ul class="space-y-1.5 text-xs">
                                        @foreach ($detalhes['erros_detalhados'] as $erro)
                                            <li class="text-red-700 flex items-start">
                                                <span class="text-red-500 mr-2">•</span>
                                                <span class="flex-1">{{ $erro }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </details>
                        @endif
                    </div>
                @endif
                    </div>
                @endif

                @if (session('warning'))
            <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg mb-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span class="font-semibold">{{ session('warning') }}</span>
                </div>
                @if (session('campistas_nao_alocados'))
                    @php
                        $naoAlocados = session('campistas_nao_alocados');
                    @endphp
                    <details class="mt-3">
                        <summary class="cursor-pointer text-red-600 font-medium text-sm">Ver campistas não alocados ({{ count($naoAlocados) }})</summary>
                        <ul class="mt-2 space-y-2 text-sm">
                            @foreach ($naoAlocados as $campista)
                                <li class="bg-red-50 border border-red-200 rounded p-2">
                                    <div class="font-medium text-red-800">{{ $campista['nome'] }}</div>
                                    <div class="text-xs text-red-600 mt-1 italic">
                                        <i class="fas fa-info-circle mr-1"></i>{{ $campista['motivo'] }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </details>
                @endif
                    </div>
                @endif

                @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-4 flex items-center">
                <i class="fas fa-times-circle mr-2"></i>
                <span>{{ session('error') }}</span>
                    </div>
                @endif
            </div>

    <!-- Loading Overlay Global -->
    <div id="loadingOverlay" class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black bg-opacity-50 modal-backdrop">
        <div class="bg-white rounded-xl shadow-2xl p-8 text-center modal-enter">
            <div class="spinner mx-auto mb-4"></div>
            <p id="loadingText" class="text-gray-700 font-medium text-lg">Processando...</p>
        </div>
    </div>

    <!-- Conteúdo Principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Ações Principais -->
        <div class="mb-6">
            <h2 class="text-base font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-bolt mr-2 text-yellow-500"></i>
                Ações Rápidas
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <!-- Botão Montar Tribos -->
                <button id="btnMontarTribos" onclick="montarTribos(event)" 
                        class="action-card bg-gradient-to-br from-[#2E8B57] to-[#228B22] hover:from-[#228B22] hover:to-[#1e7a1e] text-white p-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center flex-1">
                            <div class="bg-white/20 rounded-lg p-2.5 mr-3 group-hover:bg-white/30 transition-colors">
                                <i class="fas fa-magic text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-bold mb-0.5">Montar Tribos</h3>
                                <p class="text-xs text-white/85">Distribuição automática respeitando as regras</p>
                            </div>
                        </div>
                        <div class="ml-3">
                            <i class="fas fa-arrow-right text-white/60 group-hover:text-white group-hover:translate-x-1 transition-all text-sm"></i>
                        </div>
                    </div>
                    </button>

                <!-- Botão Importar CSV -->
                <button onclick="abrirModalImportarCSV()" 
                        class="action-card bg-gradient-to-br from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white p-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center flex-1">
                            <div class="bg-white/20 rounded-lg p-2.5 mr-3 group-hover:bg-white/30 transition-colors">
                                <i class="fas fa-file-upload text-lg"></i>
                </div>
                            <div class="flex-1">
                                <h3 class="text-base font-bold mb-0.5">Importar CSV</h3>
                                <p class="text-xs text-white/85">Importe múltiplos campistas de uma vez</p>
                            </div>
                        </div>
                        <div class="ml-3">
                            <i class="fas fa-arrow-right text-white/60 group-hover:text-white group-hover:translate-x-1 transition-all text-sm"></i>
                        </div>
                    </div>
                </button>
            </div>
        </div>

        <!-- Painel de Estatísticas Globais -->
        @php
            $totalCampistas = $campistas->count();
            $campistasSemTribo = $campistas->where('tribo_id', null)->count();
            $campistasComTribo = $totalCampistas - $campistasSemTribo;
            $pesoMedioGlobal = $campistas->avg('peso');
            $alturaMediaGlobal = $campistas->avg('altura');
            $percentualAlocado = $totalCampistas > 0 ? ($campistasComTribo / $totalCampistas) * 100 : 0;
            
            // Contar campistas inválidos
            $campistasInvalidos = $campistas->filter(function($c) {
                return $c->tribo && !$c->campistaAtendeARegra();
            });
            $totalInvalidos = $campistasInvalidos->count();
        @endphp
        
        @if($totalInvalidos > 0)
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2 text-xl"></i>
                        <div>
                            <p class="font-semibold">Atenção: {{ $totalInvalidos }} campista(s) inválido(s) detectado(s)</p>
                            <p class="text-sm mt-1">Alguns campistas estão em tribos onde conhecem outros campistas ou confidentes.</p>
                        </div>
                    </div>
                    <button onclick="document.getElementById('campistaTable').scrollIntoView({ behavior: 'smooth' }); document.getElementById('campistaCardsContainer')?.scrollIntoView({ behavior: 'smooth' });" 
                            class="ml-4 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-arrow-down mr-2"></i>Ver Campistas
                    </button>
                </div>
            </div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Card 1: Total de Campistas -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total de Campistas</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalCampistas }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-4">
                        <i class="fas fa-users text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>
            
            <!-- Card 2: Campistas Alocados -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Campistas Alocados</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $campistasComTribo }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ number_format($percentualAlocado, 1) }}% do total</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-4">
                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <!-- Card 3: Campistas Sem Tribo -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 {{ $campistasSemTribo > 0 ? 'border-yellow-500' : 'border-gray-300' }} card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Sem Tribo</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $campistasSemTribo }}</p>
                        @if($campistasSemTribo > 0)
                            <p class="text-xs text-yellow-600 mt-1 font-medium">Requer atenção</p>
                        @endif
                    </div>
                    <div class="rounded-full p-4 {{ $campistasSemTribo > 0 ? 'bg-yellow-100' : 'bg-gray-100' }}">
                        <i class="fas fa-user-slash text-2xl {{ $campistasSemTribo > 0 ? 'text-yellow-600' : 'text-gray-600' }}"></i>
                    </div>
                </div>
            </div>
            
            <!-- Card 4: Campistas Inválidos -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 {{ $totalInvalidos > 0 ? 'border-red-500' : 'border-gray-300' }} card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Campistas Inválidos</p>
                        <p class="text-3xl font-bold {{ $totalInvalidos > 0 ? 'text-red-600' : 'text-gray-800' }}">{{ $totalInvalidos }}</p>
                        @if($totalInvalidos > 0)
                            <p class="text-xs text-red-600 mt-1 font-medium">Requer correção</p>
                        @else
                            <p class="text-xs text-gray-500 mt-1">Todos válidos</p>
                        @endif
                    </div>
                    <div class="rounded-full p-4 {{ $totalInvalidos > 0 ? 'bg-red-100' : 'bg-gray-100' }}">
                        <i class="fas fa-exclamation-triangle text-2xl {{ $totalInvalidos > 0 ? 'text-red-600' : 'text-gray-600' }}"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Card de Médias Globais (linha separada) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Médias Globais</p>
                        <p class="text-lg font-bold text-gray-800">{{ number_format($pesoMedioGlobal, 1) }} kg</p>
                        <p class="text-lg font-bold text-gray-800">{{ number_format($alturaMediaGlobal, 1) }} cm</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-4">
                        <i class="fas fa-chart-line text-2xl text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid de Tribos -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-layer-group mr-2 text-[#2E8B57]"></i>
                Tribos
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($tribos as $tribo)
                                            @php
                                                $totalCampistas = $tribo->campistas->count();
                                                $pesoMedio = $totalCampistas > 0 ? $tribo->campistas->avg('peso') : 0;
                                                $alturaMedia = $totalCampistas > 0 ? $tribo->campistas->avg('altura') : 0;
                                                $numHomens = $tribo->campistas->where('genero', 'm')->count();
                                                $numMulheres = $tribo->campistas->where('genero', 'f')->count();
                        
                        // Cálculo de balanceamento
                        $desvioPeso = $totalCampistas > 0 && $pesoMedioGlobal > 0 ? abs($pesoMedio - $pesoMedioGlobal) : 0;
                        $desvioAltura = $totalCampistas > 0 && $alturaMediaGlobal > 0 ? abs($alturaMedia - $alturaMediaGlobal) : 0;
                        $desvioPercentualPeso = $pesoMedioGlobal > 0 ? ($desvioPeso / $pesoMedioGlobal) * 100 : 0;
                        $desvioPercentualAltura = $alturaMediaGlobal > 0 ? ($desvioAltura / $alturaMediaGlobal) * 100 : 0;
                        
                        $balanceamentoPeso = $desvioPercentualPeso < 5 ? 'ótimo' : ($desvioPercentualPeso < 10 ? 'bom' : 'ruim');
                        $balanceamentoAltura = $desvioPercentualAltura < 5 ? 'ótimo' : ($desvioPercentualAltura < 10 ? 'bom' : 'ruim');
                                            @endphp
                    <div class="tribo-card">
                        <!-- Header com gradiente -->
                        <div class="tribo-header">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold mb-1 flex items-center">
                                        <i class="fas fa-layer-group mr-2"></i>
                                        Tribo {{ $tribo->nome_tribo }}
                                    </h3>
                                    @if($tribo->confidentes->count() > 0)
                                        <div class="flex items-center gap-1.5 mt-1.5 flex-wrap">
                                            <i class="fas fa-user-tie text-purple-400 text-xs"></i>
                                            <span class="text-xs opacity-90">
                                                {{ $tribo->confidentes->pluck('nome')->join(', ', ' e ') }}
                                            </span>
                                        </div>
                                    @endif
                                            </div>
                                <div class="flex gap-2 ml-3">
                                    <button onclick="abrirModalEditarTribo({{ $tribo->id }}, '{{ $tribo->nome_tribo }}')" 
                                            class="opacity-80 hover:opacity-100 hover:bg-white hover:bg-opacity-20 rounded-lg p-2 transition-all"
                                            title="Editar tribo">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="removerTribo({{ $tribo->id }})" 
                                            class="opacity-80 hover:opacity-100 hover:bg-white hover:bg-opacity-20 rounded-lg p-2 transition-all"
                                            title="Excluir tribo">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                        </div>
                                    </div>
                                </div>

                        <div class="tribo-card-content">
                            <!-- Status Badge -->
                            <div class="mb-4">
                                @if($tribo->estaValida())
                                    <span class="badge-valid">
                                        <i class="fas fa-check-circle mr-1"></i>Válida
                                    </span>
                                @else
                                    <span class="badge-invalid">
                                        <i class="fas fa-times-circle mr-1"></i>Inválida
                                    </span>
                                @endif
                                </div>

                            <!-- Estatísticas -->
                            <div class="grid grid-cols-2 gap-2.5 mb-4">
                                <div class="stat-badge">
                                    <i class="fas fa-users mr-1.5" style="color: #2E8B57;"></i>
                                    <span class="font-semibold">{{ $totalCampistas }}</span>
                                    <span class="text-gray-500">/13</span>
                                </div>
                                <div class="stat-badge">
                                    <i class="fas fa-mars mr-1.5" style="color: #3b82f6;"></i>
                                    <span class="font-semibold">{{ $numHomens }}</span>
                                    <span class="text-gray-500">H</span>
                                </div>
                                <div class="stat-badge">
                                    <i class="fas fa-venus mr-1.5" style="color: #ec4899;"></i>
                                    <span class="font-semibold">{{ $numMulheres }}</span>
                                    <span class="text-gray-500">M</span>
                                </div>
                                <div class="stat-badge relative group cursor-help {{ $balanceamentoPeso === 'ruim' ? 'border-2 border-red-300' : ($balanceamentoPeso === 'bom' ? 'border border-yellow-300' : '') }}">
                                    <div class="flex items-center justify-between w-full">
                                        <div class="flex items-center">
                                            <i class="fas fa-weight mr-1.5" style="color: #f97316;"></i>
                                            <span class="font-semibold">{{ number_format($pesoMedio, 1) }}</span>
                                            <span class="text-gray-500">kg</span>
                                        </div>
                                        @if($totalCampistas > 0 && $pesoMedioGlobal > 0)
                                            @if($balanceamentoPeso === 'ótimo')
                                                <span class="ml-2 text-xs text-green-600"><i class="fas fa-check-circle"></i></span>
                                            @elseif($balanceamentoPeso === 'bom')
                                                <span class="ml-2 text-xs text-yellow-600"><i class="fas fa-exclamation-circle"></i></span>
                                            @else
                                                <span class="ml-2 text-xs text-red-600"><i class="fas fa-times-circle"></i></span>
                                            @endif
                                        @endif
                                    </div>
                                    @if($totalCampistas > 0 && $pesoMedioGlobal > 0)
                                        <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10 pointer-events-none shadow-xl">
                                            Média global: {{ number_format($pesoMedioGlobal, 1) }}kg<br>
                                            Desvio: {{ number_format($desvioPercentualPeso, 1) }}%
                                            @if($balanceamentoPeso === 'ótimo')
                                                <span style="color: #4ade80;">✓ Ótimo</span>
                                            @elseif($balanceamentoPeso === 'bom')
                                                <span style="color: #fbbf24;">⚠ Aceitável</span>
                                            @else
                                                <span style="color: #f87171;">✗ Desbalanceado</span>
                                            @endif
                                        </span>
                                    @endif
                                </div>
                                <div class="stat-badge relative group cursor-help {{ $balanceamentoAltura === 'ruim' ? 'border-2 border-red-300' : ($balanceamentoAltura === 'bom' ? 'border border-yellow-300' : '') }}">
                                    <div class="flex items-center justify-between w-full">
                                        <div class="flex items-center">
                                            <i class="fas fa-ruler-vertical mr-1.5" style="color: #a855f7;"></i>
                                            <span class="font-semibold">{{ number_format($alturaMedia, 1) }}</span>
                                            <span class="text-gray-500">cm</span>
                                        </div>
                                        @if($totalCampistas > 0 && $alturaMediaGlobal > 0)
                                            @if($balanceamentoAltura === 'ótimo')
                                                <span class="ml-2 text-xs text-green-600"><i class="fas fa-check-circle"></i></span>
                                            @elseif($balanceamentoAltura === 'bom')
                                                <span class="ml-2 text-xs text-yellow-600"><i class="fas fa-exclamation-circle"></i></span>
                                            @else
                                                <span class="ml-2 text-xs text-red-600"><i class="fas fa-times-circle"></i></span>
                                            @endif
                                        @endif
                                    </div>
                                    @if($totalCampistas > 0 && $alturaMediaGlobal > 0)
                                        <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10 pointer-events-none shadow-xl">
                                            Média global: {{ number_format($alturaMediaGlobal, 1) }}cm<br>
                                            Desvio: {{ number_format($desvioPercentualAltura, 1) }}%
                                            @if($balanceamentoAltura === 'ótimo')
                                                <span style="color: #4ade80;">✓ Ótimo</span>
                                            @elseif($balanceamentoAltura === 'bom')
                                                <span style="color: #fbbf24;">⚠ Aceitável</span>
                                            @else
                                                <span style="color: #f87171;">✗ Desbalanceado</span>
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            @if($totalCampistas > 0 && ($pesoMedioGlobal > 0 || $alturaMediaGlobal > 0))
                                <!-- Indicador Visual de Balanceamento -->
                                <div class="mb-4 p-3 rounded-xl {{ $balanceamentoPeso === 'ótimo' && $balanceamentoAltura === 'ótimo' ? 'bg-gradient-to-r from-green-50 to-green-100 border-2 border-green-200' : ($balanceamentoPeso === 'ruim' || $balanceamentoAltura === 'ruim' ? 'bg-gradient-to-r from-red-50 to-red-100 border-2 border-red-200' : 'bg-gradient-to-r from-yellow-50 to-yellow-100 border-2 border-yellow-200') }}">
                                    <p class="text-xs text-center font-semibold">
                                        @if($balanceamentoPeso === 'ótimo' && $balanceamentoAltura === 'ótimo')
                                            <i class="fas fa-check-circle text-green-600 mr-1"></i>
                                            <span class="text-green-700">Tribo bem balanceada</span>
                                        @elseif($balanceamentoPeso === 'ruim' || $balanceamentoAltura === 'ruim')
                                            <i class="fas fa-exclamation-triangle text-red-600 mr-1"></i>
                                            <span class="text-red-700">Tribo desbalanceada</span>
                                        @else
                                            <i class="fas fa-info-circle text-yellow-600 mr-1"></i>
                                            <span class="text-yellow-700">Balanceamento aceitável</span>
                                        @endif
                                    </p>
                                </div>
                            @endif

                            <!-- Confidentes da Tribo -->
                            @if($tribo->confidentes->count() > 0)
                                <div class="border-t border-gray-200 pt-4 mb-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <p class="text-xs font-semibold text-gray-700 uppercase tracking-wide">
                                            <i class="fas fa-user-tie mr-1.5" style="color: #9333ea;"></i>Confidentes
                                        </p>
                                        <span class="text-xs text-gray-500 bg-purple-100 px-2 py-1 rounded-full">
                                            {{ $tribo->confidentes->count() }} {{ $tribo->confidentes->count() === 1 ? 'confidente' : 'confidentes' }}
                                        </span>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($tribo->confidentes as $confidente)
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                                <i class="fas fa-user-tie mr-1.5 text-purple-600"></i>
                                                {{ $confidente->nome }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Lista de Campistas -->
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-xs font-semibold text-gray-700 uppercase tracking-wide">
                                        <i class="fas fa-users mr-1.5" style="color: #2E8B57;"></i>Campistas
                                    </p>
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                        {{ $totalCampistas }} {{ $totalCampistas === 1 ? 'campista' : 'campistas' }}
                                    </span>
                                </div>
                                <div class="max-h-48 overflow-y-auto space-y-1.5 pr-1">
                                    @forelse($tribo->campistas as $key => $campista)
                                        @php
                                            $estaValido = $campista->campistaAtendeARegra();
                                            $motivoInvalidade = $campista->retornaMotivoInvalidade();
                                        @endphp
                                        <div class="campista-item {{ $estaValido ? 'bg-gray-50 border-gray-200 hover:bg-gray-100' : 'bg-red-50 border-red-300 border-l-4 hover:bg-red-100' }}">
                                            <div class="flex items-start justify-between">
                                                <span class="text-sm font-medium text-gray-800 flex items-center flex-1">
                                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-opacity-10 text-xs font-bold mr-2 {{ $estaValido ? '' : 'bg-red-200' }}" style="{{ $estaValido ? 'background-color: rgba(46, 139, 87, 0.1); color: #2E8B57;' : 'color: #dc2626;' }}">
                                                        {{ $key + 1 }}
                                                    </span>
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-2">
                                                            <span>{{ $campista->nome }}</span>
                                                            @if(!$estaValido)
                                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-200 text-red-800 border border-red-300" title="{{ $motivoInvalidade }}">
                                                                    <i class="fas fa-exclamation-triangle text-xs mr-1"></i>
                                                                </span>
                                                            @endif
                                                        </div>
                                                        @if(!$estaValido && $motivoInvalidade)
                                                            <p class="text-xs text-red-600 mt-0.5 italic">
                                                                {{ $motivoInvalidade }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </span>
                                            <form action="/remover-da-tribo/{{ $campista->id }}" method="POST" class="inline" onsubmit="return confirmarRemoverDaTribo(event, '{{ $campista->nome }}')">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg p-1.5 transition-all"
                                                        title="Remover da tribo">
                                                    <i class="fas fa-times text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @empty
                                        <div class="text-center py-6">
                                            <i class="fas fa-user-slash text-3xl text-gray-300 mb-2"></i>
                                            <p class="text-sm text-gray-400 italic">Nenhum campista alocado</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-layer-group text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">Nenhuma tribo cadastrada</p>
                        <button onclick="abrirModalAdicionarTribo()" class="btn-primary mt-4">
                            <i class="fas fa-plus mr-2"></i>Criar Primeira Tribo
                        </button>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Lista de Campistas -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 gap-3">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-users mr-2 text-[#2E8B57]"></i>
                    Campistas
                </h2>
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="text" id="searchInput" onkeyup="filterTable()"
                           placeholder="Buscar campistas..."
                           class="input-modern w-full sm:w-64">
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-600 whitespace-nowrap">Itens por página:</label>
                        <select id="itemsPerPage" onchange="changeItemsPerPage()" class="input-modern w-24 text-sm">
                            <option value="10">10</option>
                            <option value="25" selected>25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="all">Todos</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Versão Desktop (tabela) -->
            <div class="hidden md:block overflow-x-auto">
                <table id="campistaTable" class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nome</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Gênero</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Peso</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Altura</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Ações</th>
                            </tr>
                            </thead>
                    <tbody id="campistaTableBody" class="divide-y divide-gray-200">
                            @foreach($campistas as $campista)
                            @php
                                $estaValido = $campista->campistaAtendeARegra();
                                $motivoInvalidade = $campista->retornaMotivoInvalidade();
                            @endphp
                            <tr id="row-{{ $campista->id }}" class="campista-row {{ $estaValido ? '' : 'bg-red-50 border-l-4 border-red-500' }} hover:bg-gray-50 transition-colors" data-campista-id="{{ $campista->id }}">
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $campista->id }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                    <div class="flex items-center gap-2">
                                        <span>{{ $campista->nome }}</span>
                                        @if(!$estaValido && $campista->tribo)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-300" title="{{ $motivoInvalidade }}">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>Inválido
                                            </span>
                                        @endif
                                    </div>
                                    @if(!$estaValido && $motivoInvalidade)
                                        <p class="text-xs text-red-600 mt-1 italic">
                                            <i class="fas fa-info-circle mr-1"></i>{{ $motivoInvalidade }}
                                        </p>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    @if($campista->genero == 'm')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                            <i class="fas fa-mars mr-1"></i>Masculino
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-pink-100 text-pink-800">
                                            <i class="fas fa-venus mr-1"></i>Feminino
                                        </span>
                                    @endif
                                    </td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $campista->peso }} kg</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $campista->altura }} cm</td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-2">
                                        @if(!empty($campista->tribo_id))
                                            <form action="/remover-da-tribo/{{ $campista->id }}" method="POST" class="inline" onsubmit="return confirmarRemoverDaTribo(event, '{{ $campista->nome }}')">
                                                @csrf
                                                <button type="submit" class="text-orange-500 hover:text-orange-700 transition-colors" title="Remover da Tribo">
                                                    <i class="fas fa-user-minus"></i>
                                                </button>
                                            </form>
                                        @else
                                            <div class="relative">
                                                <button onclick="abrirListaTribos({{ $campista->id }})"
                                                        class="text-[#2E8B57] hover:text-[#228B22] transition-colors" title="Adicionar a Tribo">
                                                    <i class="fas fa-user-plus"></i>
                                                </button>
                                                <div id="tribos-{{ $campista->id }}" class="hidden absolute mt-2 left-0 bg-white rounded-lg shadow-xl p-3 z-20 border w-96 max-h-96 overflow-y-auto">
                                                    @foreach($tribos as $tribo)
                                                        @php
                                                            $infracoes = $campista->retornaInfracaoNessaTribo($tribo->id);
                                                            $podeAdicionar = is_null($infracoes);
                                                        @endphp
                                                        <div class="mb-2 p-3 rounded-lg border {{ $podeAdicionar ? 'border-gray-200 hover:border-[#2E8B57] hover:bg-green-50' : 'border-red-200 bg-red-50' }} transition-all">
                                                            <form action="/adicionar-a-tribo/{{ $campista->id }}/{{ $tribo->id }}" method="POST">
                                                            @csrf
                                                                <button type="submit" 
                                                                        {{ $podeAdicionar ? '' : 'disabled' }}
                                                                        class="w-full text-left {{ $podeAdicionar ? 'cursor-pointer' : 'cursor-not-allowed' }}">
                                                                    <div class="flex items-center justify-between">
                                                                        <div class="flex items-center">
                                                                            <i class="fas fa-layer-group mr-2 {{ $podeAdicionar ? 'text-[#2E8B57]' : 'text-red-400' }}"></i>
                                                                            <span class="font-medium {{ $podeAdicionar ? 'text-gray-800' : 'text-gray-400' }}">
                                                                                {{ $tribo->nome_tribo }}
                                                                            </span>
                                                                        </div>
                                                                        @if($podeAdicionar)
                                                                            <span class="text-xs text-green-600 font-medium">
                                                                                <i class="fas fa-check-circle mr-1"></i>Disponível
                                                                            </span>
                                                                @else
                                                                            <span class="text-xs text-red-600 font-medium">
                                                                                <i class="fas fa-times-circle mr-1"></i>Bloqueado
                                                                            </span>
                                                                @endif
                                                                    </div>
                                                            </button>
                                                                
                                                                @if(!$podeAdicionar)
                                                                    <div class="mt-2 pt-2 border-t border-red-200">
                                                                        <p class="text-xs text-red-600">
                                                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                                                            <strong>Motivo:</strong> {{ $infracoes }}
                                                                        </p>
                                                            </div>
                                                                @endif
                                                        </form>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        <button onclick="abrirModalEditarCampista({{ $campista->id }}, {{ json_encode($campista->nome) }}, {{ json_encode($campista->genero) }}, {{ $campista->peso }}, {{ $campista->altura }})" 
                                                class="text-green-500 hover:text-green-700 transition-colors" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="abrirModalConhecidos({{ $campista->id }}, '{{ $campista->nome }}')" 
                                                class="text-blue-500 hover:text-blue-700 transition-colors" title="Conhecidos">
                                            <i class="fas fa-user-friends"></i>
                                        </button>
                                        <button onclick="abrirModalConfidentes({{ $campista->id }}, '{{ $campista->nome }}')" 
                                                class="text-purple-500 hover:text-purple-700 transition-colors" title="Confidentes">
                                            <i class="fas fa-user-tie"></i>
                                        </button>
                                        <button onclick="removerCampista({{ $campista->id }}, '{{ $campista->nome }}', {{ !empty($campista->tribo_id) ? 'true' : 'false' }})" 
                                                class="text-red-500 hover:text-red-700 transition-colors" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
            
            <!-- Versão Mobile (cards) -->
            <div id="campistaCardsContainer" class="md:hidden space-y-3">
                @foreach($campistas as $campista)
                    @php
                        $estaValido = $campista->campistaAtendeARegra();
                        $motivoInvalidade = $campista->retornaMotivoInvalidade();
                    @endphp
                    <div id="card-{{ $campista->id }}" class="campista-card bg-white rounded-lg shadow p-4 {{ $estaValido ? '' : 'border-l-4 border-red-500 bg-red-50' }}" data-campista-id="{{ $campista->id }}">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="font-bold text-gray-900">{{ $campista->nome }}</h3>
                                    @if(!$estaValido && $campista->tribo)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-300">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Inválido
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600">ID: {{ $campista->id }}</p>
                                @if(!$estaValido && $motivoInvalidade)
                                    <p class="text-xs text-red-600 mt-1 italic">
                                        <i class="fas fa-info-circle mr-1"></i>{{ $motivoInvalidade }}
                                    </p>
                                @endif
                </div>
                            @if($campista->genero == 'm')
                                <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                    <i class="fas fa-mars mr-1"></i>M
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs bg-pink-100 text-pink-800">
                                    <i class="fas fa-venus mr-1"></i>F
                                </span>
                            @endif
                        </div>
                        
                        <div class="grid grid-cols-2 gap-2 mb-3 text-sm">
                            <div class="text-gray-600">
                                <i class="fas fa-weight mr-1"></i>{{ $campista->peso }} kg
                            </div>
                            <div class="text-gray-600">
                                <i class="fas fa-ruler-vertical mr-1"></i>{{ $campista->altura }} cm
            </div>
        </div>

                        <div class="flex flex-wrap gap-2">
                            <button onclick="abrirModalEditarCampista({{ $campista->id }}, {{ json_encode($campista->nome) }}, {{ json_encode($campista->genero) }}, {{ $campista->peso }}, {{ $campista->altura }})" class="text-xs px-3 py-1 bg-green-100 text-green-700 rounded-lg">
                                <i class="fas fa-edit mr-1"></i>Editar
                            </button>
                            @if(!empty($campista->tribo_id))
                                <form action="/remover-da-tribo/{{ $campista->id }}" method="POST" class="inline" onsubmit="return confirmarRemoverDaTribo(event, '{{ $campista->nome }}')">
                                    @csrf
                                    <button type="submit" class="text-xs px-3 py-1 bg-orange-100 text-orange-700 rounded-lg">
                                        <i class="fas fa-user-minus mr-1"></i>Remover da Tribo
                                    </button>
                                </form>
                            @else
                                <button onclick="abrirListaTribos({{ $campista->id }})" class="text-xs px-3 py-1 bg-[#2E8B57] text-white rounded-lg">
                                    <i class="fas fa-user-plus mr-1"></i>Adicionar a Tribo
                                </button>
                            @endif
                            <button onclick="abrirModalConhecidos({{ $campista->id }}, '{{ $campista->nome }}')" class="text-xs px-3 py-1 bg-blue-100 text-blue-700 rounded-lg">
                                <i class="fas fa-user-friends mr-1"></i>Conhecidos
                            </button>
                            <button onclick="abrirModalConfidentes({{ $campista->id }}, '{{ $campista->nome }}')" class="text-xs px-3 py-1 bg-purple-100 text-purple-700 rounded-lg">
                                <i class="fas fa-user-tie mr-1"></i>Confidentes
                            </button>
                            <button onclick="removerCampista({{ $campista->id }}, '{{ $campista->nome }}', {{ !empty($campista->tribo_id) ? 'true' : 'false' }})" class="text-xs px-3 py-1 bg-red-100 text-red-700 rounded-lg">
                                <i class="fas fa-trash mr-1"></i>Excluir
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Controles de Paginação -->
            <div id="paginationControls" class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4 pt-4 border-t border-gray-200">
                <div class="text-sm text-gray-600">
                    <span id="paginationInfo">Mostrando 0 de 0 campistas</span>
                </div>
                <div class="flex items-center gap-2">
                    <button id="prevPageBtn" onclick="changePage(-1)" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        <i class="fas fa-chevron-left mr-1"></i>Anterior
                    </button>
                    <div class="flex items-center gap-1">
                        <span id="currentPage" class="px-3 py-2 text-sm font-medium text-gray-700">1</span>
                        <span class="text-gray-500">de</span>
                        <span id="totalPages" class="px-3 py-2 text-sm font-medium text-gray-700">1</span>
                    </div>
                    <button id="nextPageBtn" onclick="changePage(1)" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        Próximo<i class="fas fa-chevron-right ml-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Adicionar/Editar Tribo -->
    <div id="modalTribo" class="fixed inset-0 hidden z-50 flex items-center justify-center modal-backdrop">
        <div onclick="fecharModalTribo()" class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div id="modalTriboContent" class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-6 relative z-10 mx-4 max-h-[90vh] overflow-y-auto">
            <button onclick="fecharModalTribo()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
                </button>
            <h2 class="text-2xl font-bold text-gray-800 mb-4" id="modalTriboTitulo">Adicionar Tribo</h2>
            <form id="formTribo" onsubmit="salvarTribo(event)">
                <input type="hidden" id="triboId">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-layer-group mr-2 text-[#2E8B57]"></i>Nome da Tribo
                    </label>
                    <input type="text" id="nomeTribo" required class="input-modern" placeholder="Ex: Tribo Leão">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-tie mr-2 text-purple-600"></i>Confidentes da Tribo
                    </label>
                    <p class="text-xs text-gray-500 mb-2">Selecione os confidentes que serão atribuídos a esta tribo</p>
                    <div id="confidentesContainer" class="border border-gray-300 rounded-lg p-3 max-h-48 overflow-y-auto bg-gray-50">
                        @forelse($confidentes as $confidente)
                            <label class="flex items-center p-2 hover:bg-gray-100 rounded cursor-pointer transition-colors mb-1">
                                <input type="checkbox" 
                                       name="confidentes_ids[]" 
                                       value="{{ $confidente->id }}" 
                                       class="confidente-checkbox mr-3 rounded border-gray-300 text-[#2E8B57] focus:ring-[#2E8B57]"
                                       data-confidente-id="{{ $confidente->id }}">
                                <span class="text-sm text-gray-700 flex-1">
                                    {{ $confidente->nome }}
                                    @if($confidente->tribo_id)
                                        <span class="text-xs text-orange-600 ml-2">
                                            <i class="fas fa-info-circle"></i> Já atribuído à {{ $confidente->tribo->nome_tribo ?? 'outra tribo' }}
                                        </span>
                                    @endif
                                </span>
                            </label>
                        @empty
                            <p class="text-sm text-gray-400 italic text-center py-4">
                                <i class="fas fa-info-circle mr-2"></i>
                                Nenhum confidente cadastrado. Cadastre confidentes primeiro.
                            </p>
                        @endforelse
                    </div>
                    @if($confidentes->count() === 0)
                        <p class="text-xs text-yellow-600 mt-2">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Você precisa cadastrar confidentes antes de criar tribos.
                        </p>
                    @endif
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-save mr-2"></i>Salvar
                    </button>
                    <button type="button" onclick="fecharModalTribo()" class="btn-secondary">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Adicionar/Editar Campista -->
    <div id="modalAdicionarCampista" class="fixed inset-0 hidden z-50 flex items-center justify-center modal-backdrop">
        <div onclick="fecharModalAdicionarCampista()" class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div id="modalAdicionarCampistaContent" class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 relative z-10 mx-4">
            <button onclick="fecharModalAdicionarCampista()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
                </button>
            <h2 class="text-2xl font-bold text-gray-800 mb-4" id="modalCampistaTitulo">Adicionar Campista</h2>
            <form id="formAdicionarCampista" onsubmit="salvarCampista(event)">
                <input type="hidden" id="campistaId" value="">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nome</label>
                    <input type="text" id="nomeCampista" required class="input-modern" placeholder="Nome completo">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gênero</label>
                    <select id="generoCampista" required class="input-modern">
                        <option value="">Selecione...</option>
                        <option value="m">Masculino</option>
                        <option value="f">Feminino</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Peso (kg)</label>
                        <input type="number" id="pesoCampista" required step="0.1" class="input-modern" placeholder="70.5">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Altura (cm)</label>
                        <input type="number" id="alturaCampista" required step="0.1" class="input-modern" placeholder="175.0">
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn-primary flex-1" id="btnSalvarCampista">
                        <i class="fas fa-save mr-2"></i>Adicionar
                    </button>
                    <button type="button" onclick="fecharModalAdicionarCampista()" class="btn-secondary">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Conhecidos -->
    <div id="modalConhecidos" class="fixed inset-0 hidden z-50 flex items-center justify-center modal-backdrop">
        <div onclick="fecharModal()" class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div id="modalConhecidosContent" class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-6 relative z-10 mx-4">
            <button onclick="fecharModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
                </button>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Conhecidos de <span id="campistaNome"></span></h2>
            <div class="overflow-y-auto max-h-60 mb-4">
                <ul id="listaConhecidos" class="space-y-2">
                    <!-- Itens serão inseridos aqui -->
                    </ul>
                </div>
            <div class="border-t pt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Adicionar Conhecido(s)</label>
                <input type="text" 
                       id="buscarConhecido" 
                       placeholder="Buscar campista..." 
                       onkeyup="filtrarConhecidos()"
                       class="input-modern mb-3 w-full">
                <div id="listaConhecidosCheckboxes" class="max-h-60 overflow-y-auto border border-gray-300 rounded-lg p-3 mb-3 bg-gray-50">
                    @foreach($campistas as $campista)
                        <label class="conhecido-checkbox-item flex items-center p-2 hover:bg-gray-100 rounded cursor-pointer" data-nome="{{ strtolower($campista->nome) }}">
                            <input type="checkbox" 
                                   value="{{ $campista->id }}" 
                                   class="conhecido-checkbox mr-3 h-4 w-4 text-[#2E8B57] focus:ring-[#2E8B57] border-gray-300 rounded">
                            <span class="text-sm text-gray-700">{{ $campista->nome }}</span>
                        </label>
                    @endforeach
                </div>
                <button onclick="adicionarConhecido()" class="btn-primary w-full">
                    <i class="fas fa-plus mr-2"></i>Adicionar Conhecido(s) Selecionado(s)
                    </button>
                </div>
            </div>
        </div>

    <!-- Modal Confidentes -->
    <div id="modalConfidentes" class="fixed inset-0 hidden z-50 flex items-center justify-center modal-backdrop">
        <div onclick="fecharModalConfidentes()" class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div id="modalConfidentesContent" class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-6 relative z-10 mx-4">
            <button onclick="fecharModalConfidentes()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
                </button>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Confidentes Conhecidos de <span id="campistaNomeConfidentes"></span></h2>
            <div class="overflow-y-auto max-h-60 mb-4">
                <ul id="listaConfidentes" class="space-y-2">
                    <!-- Itens serão inseridos aqui -->
                    </ul>
                </div>
            <div class="border-t pt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Adicionar Confidente(s)</label>
                <div class="max-h-60 overflow-y-auto border border-gray-300 rounded-lg p-3 mb-3 bg-gray-50">
                    @foreach($confidentes as $confidente)
                        <label class="flex items-center p-2 hover:bg-gray-100 rounded cursor-pointer">
                            <input type="checkbox" 
                                   value="{{ $confidente->id }}" 
                                   class="confidente-checkbox mr-3 h-4 w-4 text-[#2E8B57] focus:ring-[#2E8B57] border-gray-300 rounded">
                            <span class="text-sm text-gray-700">{{ $confidente->nome }}</span>
                        </label>
                    @endforeach
                </div>
                <button onclick="adicionarConfidente()" class="btn-primary w-full">
                    <i class="fas fa-plus mr-2"></i>Adicionar Confidente(s) Selecionado(s)
                    </button>
                </div>
            </div>
        </div>

    <!-- Modal Importar CSV -->
    <div id="modalImportarCSV" class="fixed inset-0 hidden z-50 flex items-center justify-center modal-backdrop">
        <div onclick="fecharModalImportarCSV()" class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div id="modalImportarCSVContent" class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-6 relative z-10 mx-4">
            <button onclick="fecharModalImportarCSV()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-file-csv mr-2 text-blue-600"></i>Importar Campistas via CSV
            </h2>
            
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4 rounded">
                <p class="text-sm text-blue-700">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Formato esperado:</strong> O arquivo CSV deve conter as colunas: Nome completo, Sexo, Peso e Altura.
                </p>
            </div>

            <form id="formImportarCSV" action="/campistas/importar-csv" method="POST" enctype="multipart/form-data" onsubmit="importarCSV(event)">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-file-upload mr-2"></i>Selecione o arquivo CSV
                    </label>
                    <input type="file" 
                           id="arquivo_csv" 
                           name="arquivo_csv" 
                           accept=".csv,.txt"
                           required
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-500 mt-2">
                        Formatos aceitos: CSV, TXT (máx. 10MB)
                    </p>
                    </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                    <p class="text-xs text-yellow-800">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        <strong>Atenção:</strong> Campistas com o mesmo nome serão atualizados automaticamente.
                    </p>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn-primary flex-1 bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-upload mr-2"></i>Importar
                    </button>
                    <button type="button" onclick="fecharModalImportarCSV()" class="btn-secondary">
                        Cancelar
                    </button>
            </div>
            </form>
    </div>
</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
<script>
        // Select2 removido - agora usamos checkboxes para seleção múltipla

        // Funções de Loading
        let loadingTimeout = null;
        
        function mostrarLoading(texto = 'Processando...') {
            // Limpar timeout anterior se existir
            if (loadingTimeout) {
                clearTimeout(loadingTimeout);
                loadingTimeout = null;
            }
            
            const overlay = document.getElementById('loadingOverlay');
            const text = document.getElementById('loadingText');
            
            if (overlay && text) {
                text.textContent = texto;
                overlay.classList.remove('hidden');
                
                // Timeout de segurança: esconder loading após 30 segundos
                loadingTimeout = setTimeout(() => {
                    console.warn('Loading timeout - escondendo automaticamente');
                    esconderLoading();
                }, 30000);
            }
        }

        function esconderLoading() {
            // Limpar timeout se existir
            if (loadingTimeout) {
                clearTimeout(loadingTimeout);
                loadingTimeout = null;
            }
            
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.classList.add('hidden');
            }
        }

        // Funções de Tribo
        function abrirModalAdicionarTribo() {
            document.getElementById('modalTriboTitulo').textContent = 'Adicionar Tribo';
            document.getElementById('triboId').value = '';
            document.getElementById('nomeTribo').value = '';
            
            // Desmarcar todos os checkboxes
            document.querySelectorAll('.confidente-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            
            const modal = document.getElementById('modalTribo');
            const content = document.getElementById('modalTriboContent');
            modal.classList.remove('hidden');
            content.classList.add('modal-enter');
        }

        function abrirModalEditarTribo(id, nome) {
            document.getElementById('modalTriboTitulo').textContent = 'Editar Tribo';
            document.getElementById('triboId').value = id;
            document.getElementById('nomeTribo').value = nome;
            
            // Desmarcar todos os checkboxes primeiro
            document.querySelectorAll('.confidente-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            
            // Buscar os confidentes da tribo e marcar os checkboxes
            mostrarLoading('Carregando confidentes da tribo...');
            fetch(`/tribos`)
                .then(response => response.json())
                .then(tribos => {
                    const tribo = tribos.find(t => t.id == id);
                    if (tribo && tribo.confidentes) {
                        tribo.confidentes.forEach(confidente => {
                            const checkbox = document.querySelector(`input[data-confidente-id="${confidente.id}"]`);
                            if (checkbox) {
                                checkbox.checked = true;
                            }
                        });
                    }
                    esconderLoading();
                })
                .catch(error => {
                    console.error('Erro ao carregar confidentes:', error);
                    esconderLoading();
                });
            
            const modal = document.getElementById('modalTribo');
            const content = document.getElementById('modalTriboContent');
            modal.classList.remove('hidden');
            content.classList.add('modal-enter');
        }

        function fecharModalTribo() {
            const modal = document.getElementById('modalTribo');
            const content = document.getElementById('modalTriboContent');
            content.classList.add('modal-exit');
            setTimeout(() => {
                modal.classList.add('hidden');
                content.classList.remove('modal-exit', 'modal-enter');
            }, 300);
        }

        function salvarTribo(event) {
            event.preventDefault();
            const btn = event.target.querySelector('button[type="submit"]');
            const btnTextOriginal = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Salvando...';
            
            const id = document.getElementById('triboId').value;
            const nome = document.getElementById('nomeTribo').value;
            
            // Coletar os confidentes selecionados
            const confidentesSelecionados = [];
            document.querySelectorAll('.confidente-checkbox:checked').forEach(checkbox => {
                confidentesSelecionados.push(parseInt(checkbox.value));
            });
            
            const url = id ? `/tribos/editar/${id}` : '/tribos/adicionar';
            const method = id ? 'PUT' : 'POST';

            mostrarLoading('Salvando tribo...');

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    nome_tribo: nome,
                    confidentes_ids: confidentesSelecionados
                })
            })
            .then(response => response.json())
            .then(data => {
                esconderLoading();
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Erro ao salvar tribo.');
                    btn.disabled = false;
                    btn.innerHTML = btnTextOriginal;
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                esconderLoading();
                alert('Erro ao salvar tribo.');
                btn.disabled = false;
                btn.innerHTML = btnTextOriginal;
            });
        }

        function removerTribo(id) {
            if (!confirm('Tem certeza que deseja excluir esta tribo?')) return;
            
            mostrarLoading('Removendo tribo...');

            fetch(`/tribos/remover/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                esconderLoading();
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Erro ao remover tribo.');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                esconderLoading();
                alert('Erro ao remover tribo.');
            });
        }

        // Funções existentes (mantidas)
    function abrirListaTribos(campistaId) {
            // Fechar outras listas abertas
            document.querySelectorAll('[id^="tribos-"]').forEach(lista => {
                if (lista.id !== `tribos-${campistaId}`) {
                    lista.classList.add('hidden');
                }
            });
            
        const listaTribos = document.getElementById(`tribos-${campistaId}`);
            if (listaTribos) {
                listaTribos.classList.toggle('hidden');
            }
        }
        
        // Fechar lista de tribos ao clicar fora
        document.addEventListener('click', function(event) {
            if (!event.target.closest('[id^="tribos-"]') && !event.target.closest('button[onclick*="abrirListaTribos"]')) {
                document.querySelectorAll('[id^="tribos-"]').forEach(lista => {
                    lista.classList.add('hidden');
                });
            }
        });

        function montarTribos(event) {
            if (confirm('Isso irá redistribuir todos os campistas. Deseja continuar?')) {
                const btn = event ? event.target : document.getElementById('btnMontarTribos');
                const btnTextOriginal = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processando...';
                
                mostrarLoading('Distribuindo campistas nas tribos...');
        window.location.href = '/monta-tribos';
            }
        }
        
        function confirmarRemoverDaTribo(event, nome) {
            if (!confirm(`Tem certeza que deseja remover "${nome}" da tribo?`)) {
                event.preventDefault();
                return false;
            }
            return true;
    }

        // Variáveis de paginação
        let currentPage = 1;
        let itemsPerPage = 25;
        let filteredItems = [];

        // Funções para gerenciar parâmetros da URL
        function getUrlParams() {
            const params = new URLSearchParams(window.location.search);
            return {
                page: parseInt(params.get('page')) || 1,
                perPage: params.get('perPage') || '25',
                search: params.get('search') || ''
            };
        }

        function updateUrlParams(page, perPage, search = '') {
            const params = new URLSearchParams();
            if (page > 1) params.set('page', page);
            if (perPage !== '25') params.set('perPage', perPage);
            if (search) params.set('search', search);
            
            const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
            window.history.pushState({ page, perPage, search }, '', newUrl);
        }

        // Inicializar paginação ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            initializePagination();
        });

        // Suportar botão voltar/avançar do navegador
        window.addEventListener('popstate', function(event) {
            if (event.state) {
                currentPage = event.state.page || 1;
                itemsPerPage = event.state.perPage === 'all' ? 'all' : parseInt(event.state.perPage) || 25;
                
                // Restaurar busca
                const searchInput = document.getElementById("searchInput");
                if (searchInput) {
                    searchInput.value = event.state.search || '';
                }
                
                // Restaurar select
                const select = document.getElementById('itemsPerPage');
                if (select) {
                    select.value = itemsPerPage;
                }
                
                // Atualizar filtros e renderizar
                updateFilteredItems();
                renderPage();
            } else {
                // Se não houver state, ler da URL
                const urlParams = getUrlParams();
                currentPage = urlParams.page;
                itemsPerPage = urlParams.perPage === 'all' ? 'all' : parseInt(urlParams.perPage);
                
                const searchInput = document.getElementById("searchInput");
                if (searchInput) {
                    searchInput.value = urlParams.search;
                }
                
                const select = document.getElementById('itemsPerPage');
                if (select) {
                    select.value = itemsPerPage;
                }
                
                updateFilteredItems();
                renderPage();
            }
        });

        function initializePagination() {
            // Ler parâmetros da URL
            const urlParams = getUrlParams();
            currentPage = urlParams.page;
            itemsPerPage = urlParams.perPage === 'all' ? 'all' : parseInt(urlParams.perPage);
            
            // Restaurar busca se houver
            if (urlParams.search) {
                const searchInput = document.getElementById("searchInput");
                if (searchInput) {
                    searchInput.value = urlParams.search;
                }
            }
            
            // Restaurar select de itens por página
            const select = document.getElementById('itemsPerPage');
            if (select) {
                select.value = itemsPerPage;
            }
            
            // Coletar todos os itens visíveis
            updateFilteredItems();
            // Renderizar página
            renderPage();
        }

        function updateFilteredItems() {
            const searchTerm = document.getElementById("searchInput").value.toLowerCase();
            const allRows = document.querySelectorAll('.campista-row');
            const allCards = document.querySelectorAll('.campista-card');
            
            filteredItems = [];
            
            // Processar linhas da tabela (desktop)
            allRows.forEach((row, index) => {
                const cells = row.getElementsByTagName("td");
                let rowContent = "";
            for (let j = 0; j < cells.length; j++) {
                rowContent += cells[j].textContent.toLowerCase() + " ";
            }

                const isVisible = !searchTerm || rowContent.includes(searchTerm);
                row.style.display = isVisible ? "" : "none";
                
                if (isVisible) {
                    filteredItems.push({
                        type: 'row',
                        element: row,
                        id: row.getAttribute('data-campista-id')
                    });
                }
            });
            
            // Processar cards (mobile)
            allCards.forEach((card) => {
                const cardText = card.textContent.toLowerCase();
                const isVisible = !searchTerm || cardText.includes(searchTerm);
                card.style.display = isVisible ? "" : "none";
                
                if (isVisible) {
                    // Verificar se já não foi adicionado (pode ter duplicado)
                    const id = card.getAttribute('data-campista-id');
                    if (!filteredItems.find(item => item.id === id && item.type === 'card')) {
                        filteredItems.push({
                            type: 'card',
                            element: card,
                            id: id
                        });
                    }
                }
            });
        }

        function renderPage() {
            const startIndex = itemsPerPage === 'all' ? 0 : (currentPage - 1) * itemsPerPage;
            const endIndex = itemsPerPage === 'all' ? filteredItems.length : startIndex + itemsPerPage;
            
            // Ocultar todos os itens primeiro
            filteredItems.forEach(item => {
                if (item.type === 'row') {
                    item.element.style.display = 'none';
                } else if (item.type === 'card') {
                    item.element.style.display = 'none';
                }
            });
            
            // Mostrar apenas os itens da página atual
            const itemsToShow = filteredItems.slice(startIndex, endIndex);
            itemsToShow.forEach(item => {
                item.element.style.display = '';
            });
            
            // Atualizar controles de paginação
            updatePaginationControls();
        }

        function updatePaginationControls() {
            const totalItems = filteredItems.length;
            const totalPages = itemsPerPage === 'all' ? 1 : Math.ceil(totalItems / itemsPerPage);
            
            // Atualizar informações
            const startItem = itemsPerPage === 'all' ? 1 : (currentPage - 1) * itemsPerPage + 1;
            const endItem = itemsPerPage === 'all' ? totalItems : Math.min(currentPage * itemsPerPage, totalItems);
            
            document.getElementById('paginationInfo').textContent = 
                totalItems > 0 
                    ? `Mostrando ${startItem} a ${endItem} de ${totalItems} campista${totalItems !== 1 ? 's' : ''}`
                    : 'Nenhum campista encontrado';
            
            // Atualizar página atual e total
            document.getElementById('currentPage').textContent = currentPage;
            document.getElementById('totalPages').textContent = totalPages;
            
            // Atualizar botões
            document.getElementById('prevPageBtn').disabled = currentPage === 1;
            document.getElementById('nextPageBtn').disabled = currentPage >= totalPages || itemsPerPage === 'all';
            
            // Ocultar controles se não houver itens
            document.getElementById('paginationControls').style.display = totalItems === 0 ? 'none' : 'flex';
        }

        function changePage(direction) {
            const totalPages = itemsPerPage === 'all' ? 1 : Math.ceil(filteredItems.length / itemsPerPage);
            const newPage = currentPage + direction;
            
            if (newPage >= 1 && newPage <= totalPages) {
                currentPage = newPage;
                renderPage();
                
                // Atualizar URL
                const searchTerm = document.getElementById("searchInput")?.value || '';
                updateUrlParams(currentPage, itemsPerPage, searchTerm);
                
                // Scroll suave para o topo da tabela
                document.getElementById('campistaTable')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                document.getElementById('campistaCardsContainer')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        function changeItemsPerPage() {
            const select = document.getElementById('itemsPerPage');
            itemsPerPage = select.value === 'all' ? 'all' : parseInt(select.value);
            currentPage = 1; // Resetar para primeira página
            renderPage();
            
            // Atualizar URL
            const searchTerm = document.getElementById("searchInput")?.value || '';
            updateUrlParams(currentPage, itemsPerPage, searchTerm);
        }

        function filterTable() {
            const input = document.getElementById("searchInput");
            const searchTerm = input.value.toLowerCase();
            
            // Atualizar lista de itens filtrados
            updateFilteredItems();
            
            // Resetar para primeira página
            currentPage = 1;
            
            // Atualizar URL
            updateUrlParams(currentPage, itemsPerPage, input.value);
            
            // Renderizar página
            renderPage();
        }

        function abrirModalAdicionarCampista() {
            // Limpar formulário e configurar para adicionar
            document.getElementById('modalCampistaTitulo').textContent = 'Adicionar Campista';
            document.getElementById('campistaId').value = '';
            document.getElementById('nomeCampista').value = '';
            document.getElementById('generoCampista').value = '';
            document.getElementById('pesoCampista').value = '';
            document.getElementById('alturaCampista').value = '';
            document.getElementById('btnSalvarCampista').innerHTML = '<i class="fas fa-save mr-2"></i>Adicionar';
            
            const modal = document.getElementById("modalAdicionarCampista");
            const content = document.getElementById("modalAdicionarCampistaContent");
            modal.classList.remove("hidden");
            content.classList.add('modal-enter');
        }

        function abrirModalEditarCampista(id, nome, genero, peso, altura) {
            // Preencher formulário com dados do campista
            document.getElementById('modalCampistaTitulo').textContent = 'Editar Campista';
            document.getElementById('campistaId').value = id;
            document.getElementById('nomeCampista').value = nome;
            document.getElementById('generoCampista').value = genero;
            document.getElementById('pesoCampista').value = peso;
            document.getElementById('alturaCampista').value = altura;
            document.getElementById('btnSalvarCampista').innerHTML = '<i class="fas fa-save mr-2"></i>Salvar Alterações';
            
            const modal = document.getElementById("modalAdicionarCampista");
            const content = document.getElementById("modalAdicionarCampistaContent");
            modal.classList.remove("hidden");
            content.classList.add('modal-enter');
        }

        function fecharModalAdicionarCampista() {
            const modal = document.getElementById("modalAdicionarCampista");
            const content = document.getElementById("modalAdicionarCampistaContent");
            content.classList.add('modal-exit');
            setTimeout(() => {
                modal.classList.add("hidden");
                content.classList.remove('modal-exit', 'modal-enter');
                // Limpar formulário
                document.getElementById('formAdicionarCampista').reset();
                document.getElementById('campistaId').value = '';
            }, 300);
        }

        function salvarCampista(event) {
            event.preventDefault();
            const btn = event.target.querySelector('button[type="submit"]');
            const btnTextOriginal = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Salvando...';
            
            // Preservar página atual antes de salvar
            const paginaAtual = currentPage;
            
            const id = document.getElementById("campistaId").value;
            const nome = document.getElementById("nomeCampista").value;
            const genero = document.getElementById("generoCampista").value;
            const peso = document.getElementById("pesoCampista").value;
            const altura = document.getElementById("alturaCampista").value;

            const isEdit = id !== '';
            const url = isEdit ? `/campistas/editar/${id}` : '/campistas/adicionar';
            const method = isEdit ? 'PUT' : 'POST';
            const loadingText = isEdit ? 'Atualizando campista...' : 'Adicionando campista...';

            mostrarLoading(loadingText);

            fetch(url, {
                method: method,
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ nome, genero, peso, altura })
            })
            .then(response => response.json())
            .then(data => {
                esconderLoading();
                if (data.success) {
                    fecharModalAdicionarCampista();
                    
                    if (isEdit) {
                        // Atualizar apenas o campista editado no DOM
                        atualizarCampistaNoDOM(id, nome, genero, peso, altura);
                        // Restaurar página atual
                        currentPage = paginaAtual;
                        renderPage();
                        
                        // Atualizar URL para manter a página
                        const searchTerm = document.getElementById("searchInput")?.value || '';
                        updateUrlParams(currentPage, itemsPerPage, searchTerm);
                    } else {
                        // Para novo campista, recarregar a página mas manter a última página
                        // (ou podemos adicionar ao DOM também, mas é mais complexo)
                        location.reload();
                    }
                } else {
                    alert(data.message || (isEdit ? "Erro ao atualizar campista." : "Erro ao adicionar campista."));
                    btn.disabled = false;
                    btn.innerHTML = btnTextOriginal;
                }
            })
            .catch(error => {
                console.error("Erro:", error);
                esconderLoading();
                alert(isEdit ? "Erro ao atualizar campista." : "Erro ao adicionar campista.");
                btn.disabled = false;
                btn.innerHTML = btnTextOriginal;
            });
        }

        function atualizarCampistaNoDOM(id, nome, genero, peso, altura) {
            // Atualizar na tabela (desktop)
            const row = document.getElementById(`row-${id}`);
            if (row) {
                const cells = row.getElementsByTagName('td');
                if (cells.length >= 5) {
                    // Atualizar nome (célula 1)
                    const nomeCell = cells[1];
                    nomeCell.innerHTML = `
                        <div class="flex items-center gap-2">
                            <span>${nome}</span>
                        </div>
                    `;
                    
                    // Atualizar gênero (célula 2)
                    const generoCell = cells[2];
                    if (genero === 'm') {
                        generoCell.innerHTML = '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800"><i class="fas fa-mars mr-1"></i>Masculino</span>';
                    } else {
                        generoCell.innerHTML = '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-pink-100 text-pink-800"><i class="fas fa-venus mr-1"></i>Feminino</span>';
                    }
                    
                    // Atualizar peso (célula 3)
                    cells[3].textContent = `${peso} kg`;
                    
                    // Atualizar altura (célula 4)
                    cells[4].textContent = `${altura} cm`;
                }
                
                // Atualizar onclick do botão editar
                const editBtn = row.querySelector('button[onclick*="abrirModalEditarCampista"]');
                if (editBtn) {
                    editBtn.setAttribute('onclick', `abrirModalEditarCampista(${id}, ${JSON.stringify(nome)}, ${JSON.stringify(genero)}, ${peso}, ${altura})`);
                }
            }
            
            // Atualizar no card (mobile)
            const card = document.getElementById(`card-${id}`);
            if (card) {
                const nomeElement = card.querySelector('h3');
                if (nomeElement) nomeElement.textContent = nome;
                
                // Atualizar gênero (span dentro do flex justify-between)
                const generoSpan = card.querySelector('.flex.justify-between span:last-child');
                if (generoSpan) {
                    if (genero === 'm') {
                        generoSpan.innerHTML = '<i class="fas fa-mars mr-1"></i>M';
                        generoSpan.className = 'px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800';
                    } else {
                        generoSpan.innerHTML = '<i class="fas fa-venus mr-1"></i>F';
                        generoSpan.className = 'px-2 py-1 rounded-full text-xs bg-pink-100 text-pink-800';
                    }
                }
                
                // Atualizar peso e altura (grid com 2 colunas)
                const gridDiv = card.querySelector('.grid.grid-cols-2');
                if (gridDiv) {
                    const divs = gridDiv.querySelectorAll('div');
                    if (divs.length >= 2) {
                        divs[0].innerHTML = `<i class="fas fa-weight mr-1"></i>${peso} kg`;
                        divs[1].innerHTML = `<i class="fas fa-ruler-vertical mr-1"></i>${altura} cm`;
                    }
                }
                
                // Atualizar onclick do botão editar
                const editBtn = card.querySelector('button[onclick*="abrirModalEditarCampista"]');
                if (editBtn) {
                    editBtn.setAttribute('onclick', `abrirModalEditarCampista(${id}, ${JSON.stringify(nome)}, ${JSON.stringify(genero)}, ${peso}, ${altura})`);
                }
            }
            
            // Atualizar lista de itens filtrados se necessário
            updateFilteredItems();
        }

        function removerCampista(campistaId, nome, emTribo) {
            let mensagem = `Tem certeza que deseja excluir "${nome}"?`;
            if (emTribo) {
                mensagem += '\n\nAVISO: Este campista está atribuído a uma tribo e será removido dela.';
            }
            
            if (!confirm(mensagem)) return;

            const row = document.getElementById(`row-${campistaId}`) || document.getElementById(`card-${campistaId}`);
            if (row) {
                row.style.transition = 'opacity 0.3s';
                row.style.opacity = '0.5';
            }
            
            mostrarLoading('Removendo campista...');

            fetch(`/campistas/remover/${campistaId}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(response => response.json())
            .then(data => {
                esconderLoading();
                if (data.success) {
                    if (row) {
                        row.style.opacity = '0';
                        setTimeout(() => location.reload(), 300);
        } else {
                        location.reload();
                    }
                } else {
                    alert(data.message || "Erro ao remover campista.");
                    if (row) {
                        row.style.opacity = '1';
                    }
                }
            })
            .catch(error => {
                console.error("Erro:", error);
                esconderLoading();
                alert("Erro ao remover campista.");
                if (row) {
                    row.style.opacity = '1';
                }
            });
    }

    function abrirModalConhecidos(campistaId, campistaNome, skipLoading = false) {
        // Limpar campo de busca
        const buscarInput = document.getElementById("buscarConhecido");
        if (buscarInput) {
            buscarInput.value = "";
        }
        
        // Limpar checkboxes ao abrir o modal
        document.querySelectorAll('.conhecido-checkbox').forEach(cb => {
            // Não permitir que o próprio campista seja selecionado como conhecido
            if (cb.value == campistaId) {
                cb.disabled = true;
                cb.checked = false;
            } else {
                cb.disabled = false;
                cb.checked = false;
            }
        });
        
        // Mostrar todos os itens novamente (resetar filtro)
        document.querySelectorAll('.conhecido-checkbox-item').forEach(item => {
            item.style.display = '';
        });
        
        document.getElementById("campistaNome").innerText = campistaNome;
        document.getElementById("modalConhecidos").dataset.campistaId = campistaId;
        const listaConhecidos = document.getElementById("listaConhecidos");
        listaConhecidos.innerHTML = "";

            const modal = document.getElementById("modalConhecidos");
            const content = document.getElementById("modalConhecidosContent");
            modal.classList.remove("hidden");
            content.classList.add('modal-enter');

            if (!skipLoading) {
                mostrarLoading('Carregando conhecidos...');
            }

        fetch(`/conhecidos/${campistaId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json().catch(err => {
                    throw new Error('Resposta inválida do servidor');
                });
            })
            .then(data => {
                    esconderLoading();
                    if (data && data.conhecidos) {
                        data.conhecidos.forEach((conhecido, index) => {
                        const li = document.createElement("li");
                            li.className = "flex justify-between items-center p-3 bg-gray-50 rounded-lg list-item-enter";
                            li.style.animationDelay = `${index * 0.05}s`;
                            li.innerHTML = `
                                <span class="text-gray-700">${conhecido.nome}</span>
                                <button onclick="removerConhecido(${campistaId}, ${conhecido.id}, this.parentElement)" 
                                        class="text-red-500 hover:text-red-700 transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            `;
                        listaConhecidos.appendChild(li);
                    });
                    } else {
                        console.warn('Dados de conhecidos não encontrados na resposta');
                    }
            })
                .catch(error => {
                    console.error('Erro ao carregar conhecidos:', error);
                    esconderLoading();
                    alert('Erro ao carregar conhecidos. Por favor, tente novamente.');
                });
    }

    function fecharModal() {
            const modal = document.getElementById("modalConhecidos");
            const content = document.getElementById("modalConhecidosContent");
            content.classList.add('modal-exit');
            setTimeout(() => {
                modal.classList.add("hidden");
                content.classList.remove('modal-exit', 'modal-enter');
            }, 300);
    }

    function adicionarConhecido() {
        // Mostrar loading IMEDIATAMENTE antes de qualquer processamento
        mostrarLoading('Adicionando conhecido(s)...');
        
        const campistaId = document.getElementById("modalConhecidos").dataset.campistaId;
        
        // Buscar checkboxes selecionados
        const checkboxes = document.querySelectorAll('.conhecido-checkbox:checked');
        const novoConhecidoIds = Array.from(checkboxes).map(cb => cb.value);
            
            if (novoConhecidoIds.length === 0) {
                esconderLoading();
                alert('Por favor, selecione pelo menos um conhecido.');
                return;
            }

            // Buscar o botão pelo onclick que contém esta função
            const btn = document.querySelector('button[onclick="adicionarConhecido()"]');
            const btnTextOriginal = btn ? btn.innerHTML : '';
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adicionando...';
            }

        fetch(`/conhecidos/adicionar`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({campistaId, novoConhecidoId: novoConhecidoIds})
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json().catch(err => {
                    throw new Error('Resposta inválida do servidor');
                });
            })
            .then(data => {
                esconderLoading();
                if (data && data.success) {
                    const campistaNome = document.getElementById("campistaNome").innerText;
                    // Desmarcar checkboxes selecionados
                    checkboxes.forEach(cb => cb.checked = false);
                    // Recarregar modal sem mostrar loading novamente (já está escondido)
                    abrirModalConhecidos(campistaId, campistaNome, true);
                } else {
                    alert(data?.message || "Erro ao adicionar conhecido(s).");
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = btnTextOriginal;
                    }
                }
            })
            .catch(error => {
                console.error("Erro:", error);
                esconderLoading();
                alert("Erro ao adicionar conhecido(s). Por favor, tente novamente.");
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = btnTextOriginal;
                }
            });
        }

    function filtrarConhecidos() {
        const busca = document.getElementById("buscarConhecido").value.toLowerCase();
        const itens = document.querySelectorAll('.conhecido-checkbox-item');
        
        itens.forEach(item => {
            const nome = item.getAttribute('data-nome');
            if (nome.includes(busca)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function removerConhecido(campistaId, conhecidoId, liElement) {
            liElement.style.opacity = '0.5';
            mostrarLoading('Removendo conhecido...');

        fetch(`/conhecidos/remover`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({campistaId, conhecidoId})
        })
            .then(response => response.json())
            .then(data => {
                esconderLoading();
                if (data.success) {
                    liElement.style.transition = 'opacity 0.3s, transform 0.3s';
                    liElement.style.opacity = '0';
                    liElement.style.transform = 'translateX(-20px)';
                    setTimeout(() => liElement.remove(), 300);
                } else {
                    alert(data.message || "Erro ao remover conhecido.");
                    liElement.style.opacity = '1';
                }
            })
            .catch(error => {
                console.error("Erro:", error);
                esconderLoading();
                alert("Erro ao remover conhecido.");
                liElement.style.opacity = '1';
            });
    }

    function abrirModalConfidentes(campistaId, campistaNome, skipLoading = false) {
        // Limpar checkboxes ao abrir o modal
        document.querySelectorAll('.confidente-checkbox').forEach(cb => cb.checked = false);
        document.getElementById("campistaNomeConfidentes").innerText = campistaNome;
        document.getElementById("modalConfidentes").dataset.campistaId = campistaId;
        const listaConfidentes = document.getElementById("listaConfidentes");
        listaConfidentes.innerHTML = "";

            const modal = document.getElementById("modalConfidentes");
            const content = document.getElementById("modalConfidentesContent");
            modal.classList.remove("hidden");
            content.classList.add('modal-enter');

            if (!skipLoading) {
                mostrarLoading('Carregando confidentes...');
            }

        fetch(`/confidentes/${campistaId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json().catch(err => {
                    throw new Error('Resposta inválida do servidor');
                });
            })
            .then(data => {
                    esconderLoading();
                    if (data && data.confidentes) {
                        data.confidentes.forEach((confidente, index) => {
                        const li = document.createElement("li");
                            li.className = "flex justify-between items-center p-3 bg-gray-50 rounded-lg list-item-enter";
                            li.style.animationDelay = `${index * 0.05}s`;
                            li.innerHTML = `
                                <span class="text-gray-700">${confidente.nome}</span>
                                <button onclick="removerConfidente(${campistaId}, ${confidente.id}, this.parentElement)" 
                                        class="text-red-500 hover:text-red-700 transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            `;
                        listaConfidentes.appendChild(li);
                    });
                    } else {
                        console.warn('Dados de confidentes não encontrados na resposta');
                    }
            })
                .catch(error => {
                    console.error('Erro ao carregar confidentes:', error);
                    esconderLoading();
                    alert('Erro ao carregar confidentes. Por favor, tente novamente.');
                });
    }

    function fecharModalConfidentes() {
            const modal = document.getElementById("modalConfidentes");
            const content = document.getElementById("modalConfidentesContent");
            content.classList.add('modal-exit');
            setTimeout(() => {
                modal.classList.add("hidden");
                content.classList.remove('modal-exit', 'modal-enter');
            }, 300);
        }

        // Funções de Importação CSV
        function abrirModalImportarCSV() {
            const modal = document.getElementById("modalImportarCSV");
            const content = document.getElementById("modalImportarCSVContent");
            modal.classList.remove("hidden");
            content.classList.add('modal-enter');
        }

        function fecharModalImportarCSV() {
            const modal = document.getElementById("modalImportarCSV");
            const content = document.getElementById("modalImportarCSVContent");
            content.classList.add('modal-exit');
            setTimeout(() => {
                modal.classList.add("hidden");
                content.classList.remove('modal-exit', 'modal-enter');
                // Limpar formulário
                document.getElementById("formImportarCSV").reset();
            }, 300);
        }

        function importarCSV(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            const btn = form.querySelector('button[type="submit"]');
            const btnTextOriginal = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Importando...';
            
            mostrarLoading('Importando campistas do CSV...');

            fetch('/campistas/importar-csv', {
                method: 'POST',
            headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    return response.json();
                }
            })
            .then(data => {
                esconderLoading();
                if (data && !data.success) {
                    alert(data.message || 'Erro ao importar CSV.');
                    btn.disabled = false;
                    btn.innerHTML = btnTextOriginal;
                }
                // Se houver redirect, a página será recarregada automaticamente
            })
            .catch(error => {
                console.error('Erro:', error);
                esconderLoading();
                alert('Erro ao importar CSV.');
                btn.disabled = false;
                btn.innerHTML = btnTextOriginal;
            });
        }

    function adicionarConfidente() {
        // Mostrar loading IMEDIATAMENTE antes de qualquer processamento
        mostrarLoading('Adicionando confidente(s)...');
        
        const campistaId = document.getElementById("modalConfidentes").dataset.campistaId;
        
        // Buscar checkboxes selecionados
        const checkboxes = document.querySelectorAll('.confidente-checkbox:checked');
        const novoConfidenteIds = Array.from(checkboxes).map(cb => cb.value);
            
            if (novoConfidenteIds.length === 0) {
                esconderLoading();
                alert('Por favor, selecione pelo menos um confidente.');
                return;
            }

            // Buscar o botão pelo onclick que contém esta função
            const btn = document.querySelector('button[onclick="adicionarConfidente()"]');
            const btnTextOriginal = btn ? btn.innerHTML : '';
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adicionando...';
            }

        fetch(`/confidentes/adicionar`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ campistaId, novoConfidenteId: novoConfidenteIds })
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json().catch(err => {
                    throw new Error('Resposta inválida do servidor');
                });
            })
            .then(data => {
                esconderLoading();
                if (data && data.success) {
                    const campistaNome = document.getElementById("campistaNomeConfidentes").innerText;
                    // Desmarcar checkboxes selecionados
                    checkboxes.forEach(cb => cb.checked = false);
                    // Recarregar modal sem mostrar loading novamente (já está escondido)
                    abrirModalConfidentes(campistaId, campistaNome, true);
                } else {
                    alert(data?.message || "Erro ao adicionar confidente(s).");
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = btnTextOriginal;
                    }
                }
            })
            .catch(error => {
                console.error("Erro:", error);
                esconderLoading();
                alert("Erro ao adicionar confidente(s). Por favor, tente novamente.");
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = btnTextOriginal;
                }
            });
    }

    function removerConfidente(campistaId, confidenteId, liElement) {
            liElement.style.opacity = '0.5';
            mostrarLoading('Removendo confidente...');

        fetch(`/confidentes/remover`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ campistaId, confidenteId })
        })
            .then(response => response.json())
            .then(data => {
                esconderLoading();
                if (data.success) {
                    liElement.style.transition = 'opacity 0.3s, transform 0.3s';
                    liElement.style.opacity = '0';
                    liElement.style.transform = 'translateX(-20px)';
                    setTimeout(() => liElement.remove(), 300);
                } else {
                    alert(data.message || "Erro ao remover confidente.");
                    liElement.style.opacity = '1';
                }
            })
            .catch(error => {
                console.error("Erro:", error);
                esconderLoading();
                alert("Erro ao remover confidente.");
                liElement.style.opacity = '1';
            });
        }
</script>
</body>
</html>
