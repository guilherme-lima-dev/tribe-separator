<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="theme-color" content="#2E8B57"/>
    <link rel="shortcut icon" href="./assets/img/favicon.ico"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .bg-maanaim {
            background: linear-gradient(135deg, #2E8B57 0%, #228B22 100%);
        }
        
        .btn-primary {
            @apply bg-[#2E8B57] hover:bg-[#228B22] text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200;
        }
        
        .btn-secondary {
            @apply bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200;
        }
        
        .input-modern {
            @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2E8B57] focus:border-transparent transition-all;
        }
        
        .modal-backdrop {
            backdrop-filter: blur(4px);
        }
        
        .modal-enter {
            animation: modalEnter 0.3s ease-out;
        }
        
        .modal-exit {
            animation: modalExit 0.3s ease-in;
        }
        
        @keyframes modalEnter {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-10px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        
        @keyframes modalExit {
            from {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
            to {
                opacity: 0;
                transform: scale(0.95) translateY(-10px);
            }
        }
        
        .spinner {
            border: 4px solid #f3f4f6;
            border-top: 4px solid #2E8B57;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    <title>Maanaim | Gerenciar Confidentes</title>
</head>
<body class="bg-gray-50 antialiased">
    <!-- Navbar -->
    <nav class="bg-maanaim shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-arrow-left text-xl mr-3"></i>
                        <i class="fas fa-user-tie text-xl mr-3"></i>
                        <h1 class="text-white text-lg font-bold">Gerenciar Confidentes</h1>
                    </a>
                </div>
                <div class="flex gap-3">
                    <button onclick="abrirModalAdicionarConfidente()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>Novo Confidente
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Alertas -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('warning'))
            <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg mb-4 flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span>{{ session('warning') }}</span>
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
        <!-- Seção de Gerenciamento de Confidentes -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-user-tie mr-2 text-purple-600"></i>
                    Confidentes Cadastrados
                </h2>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button onclick="abrirModalAdicionarConfidente()" class="btn-primary">
                        <i class="fas fa-plus mr-2"></i>Novo Confidente
                    </button>
                </div>
            </div>
            
            @if($confidentes->count() > 0)
                <!-- Versão Desktop (tabela) -->
                <div class="hidden md:block overflow-x-auto bg-white rounded-lg shadow-md">
                    <table id="confidenteTable" class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nome</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tribo</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Ações</th>
                            </tr>
                        </thead>
                        <tbody id="confidenteTableBody" class="divide-y divide-gray-200">
                            @foreach($confidentes as $confidente)
                            <tr id="confidente-row-{{ $confidente->id }}" class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $confidente->id }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $confidente->nome }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    @if($confidente->tribo)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                            <i class="fas fa-layer-group mr-1"></i>{{ $confidente->tribo->nome_tribo }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">Sem tribo</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-2">
                                    <button onclick="abrirModalEditarConfidente({{ $confidente->id }}, '{{ addslashes($confidente->nome) }}', {{ $confidente->tribo_id ?? 'null' }})" 
                                            class="text-green-500 hover:text-green-700 transition-colors" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                        <button onclick="removerConfidenteCRUD({{ $confidente->id }}, '{{ addslashes($confidente->nome) }}', {{ $confidente->tribo_id ? 'true' : 'false' }}, {{ $confidente->campistas()->count() }})" 
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
                <div id="confidenteCardsContainer" class="md:hidden space-y-3">
                    @foreach($confidentes as $confidente)
                        <div id="confidente-card-{{ $confidente->id }}" class="bg-white rounded-lg shadow p-4">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="font-bold text-gray-900">{{ $confidente->nome }}</h3>
                                    <p class="text-sm text-gray-600">ID: {{ $confidente->id }}</p>
                                </div>
                                @if($confidente->tribo)
                                    <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                        <i class="fas fa-layer-group mr-1"></i>{{ $confidente->tribo->nome_tribo }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">Sem tribo</span>
                                @endif
                            </div>
                            
                            <div class="flex flex-wrap gap-2">
                            <button onclick="abrirModalEditarConfidente({{ $confidente->id }}, '{{ addslashes($confidente->nome) }}', {{ $confidente->tribo_id ?? 'null' }})" 
                                    class="text-xs px-3 py-1 bg-green-100 text-green-700 rounded-lg">
                                <i class="fas fa-edit mr-1"></i>Editar
                            </button>
                                <button onclick="removerConfidenteCRUD({{ $confidente->id }}, '{{ addslashes($confidente->nome) }}', {{ $confidente->tribo_id ? 'true' : 'false' }}, {{ $confidente->campistas()->count() }})" 
                                        class="text-xs px-3 py-1 bg-red-100 text-red-700 rounded-lg">
                                    <i class="fas fa-trash mr-1"></i>Excluir
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <i class="fas fa-user-tie text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Nenhum confidente cadastrado</h3>
                    <p class="text-gray-500 mb-4">Comece adicionando seu primeiro confidente.</p>
                    <button onclick="abrirModalAdicionarConfidente()" class="btn-primary">
                        <i class="fas fa-plus mr-2"></i>Adicionar Primeiro Confidente
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Adicionar/Editar Confidente -->
    <div id="modalConfidenteCRUD" class="fixed inset-0 hidden z-50 flex items-center justify-center modal-backdrop">
        <div onclick="fecharModalConfidenteCRUD()" class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div id="modalConfidenteCRUDContent" class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-6 relative z-10 mx-4">
            <button onclick="fecharModalConfidenteCRUD()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
            <h2 class="text-2xl font-bold text-gray-800 mb-4" id="modalConfidenteCRUDTitulo">Adicionar Confidente</h2>
            <form id="formConfidenteCRUD" onsubmit="salvarConfidenteCRUD(event)">
                <input type="hidden" id="confidenteId">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-tie mr-2 text-purple-600"></i>Nome do Confidente
                    </label>
                    <input type="text" id="nomeConfidente" required class="input-modern" placeholder="Ex: João Silva">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-layer-group mr-2 text-[#2E8B57]"></i>Tribo (Opcional)
                    </label>
                    <select id="triboIdConfidente" class="input-modern">
                        <option value="">Selecione uma tribo (opcional)</option>
                        @foreach($tribos as $tribo)
                            <option value="{{ $tribo->id }}">{{ $tribo->nome_tribo }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Você pode adicionar o confidente a uma tribo agora ou depois</p>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn-primary flex-1" id="btnSalvarConfidente">
                        <i class="fas fa-save mr-2"></i>Adicionar
                    </button>
                    <button type="button" onclick="fecharModalConfidenteCRUD()" class="btn-secondary">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Funções de Loading
        function mostrarLoading(texto = 'Processando...') {
            document.getElementById('loadingText').textContent = texto;
            document.getElementById('loadingOverlay').classList.remove('hidden');
        }

        function esconderLoading() {
            document.getElementById('loadingOverlay').classList.add('hidden');
        }

        // Funções de Confidente CRUD
        function abrirModalAdicionarConfidente() {
            document.getElementById('modalConfidenteCRUDTitulo').textContent = 'Adicionar Confidente';
            document.getElementById('confidenteId').value = '';
            document.getElementById('nomeConfidente').value = '';
            document.getElementById('triboIdConfidente').value = '';
            document.getElementById('btnSalvarConfidente').innerHTML = '<i class="fas fa-save mr-2"></i>Adicionar';
            
            const modal = document.getElementById('modalConfidenteCRUD');
            const content = document.getElementById('modalConfidenteCRUDContent');
            modal.classList.remove('hidden');
            content.classList.add('modal-enter');
        }

        function abrirModalEditarConfidente(id, nome, triboId = null) {
            document.getElementById('modalConfidenteCRUDTitulo').textContent = 'Editar Confidente';
            document.getElementById('confidenteId').value = id;
            document.getElementById('nomeConfidente').value = nome;
            document.getElementById('triboIdConfidente').value = triboId || '';
            document.getElementById('btnSalvarConfidente').innerHTML = '<i class="fas fa-save mr-2"></i>Salvar';
            
            const modal = document.getElementById('modalConfidenteCRUD');
            const content = document.getElementById('modalConfidenteCRUDContent');
            modal.classList.remove('hidden');
            content.classList.add('modal-enter');
        }

        function fecharModalConfidenteCRUD() {
            const modal = document.getElementById('modalConfidenteCRUD');
            const content = document.getElementById('modalConfidenteCRUDContent');
            content.classList.add('modal-exit');
            setTimeout(() => {
                modal.classList.add('hidden');
                content.classList.remove('modal-exit', 'modal-enter');
            }, 300);
        }

        function salvarConfidenteCRUD(event) {
            event.preventDefault();
            
            const id = document.getElementById('confidenteId').value;
            const nome = document.getElementById('nomeConfidente').value;
            const triboId = document.getElementById('triboIdConfidente').value;
            const isEdit = id !== '';
            
            const btn = document.getElementById('btnSalvarConfidente');
            const btnTextOriginal = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Salvando...';
            
            mostrarLoading(isEdit ? 'Atualizando confidente...' : 'Adicionando confidente...');
            
            const url = isEdit ? `/confidentes-crud/editar/${id}` : '/confidentes-crud/adicionar';
            const method = isEdit ? 'PUT' : 'POST';
            
            const bodyData = { nome: nome };
            if (triboId) {
                bodyData.tribo_id = parseInt(triboId);
            } else {
                bodyData.tribo_id = null;
            }
            
            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(bodyData)
            })
            .then(response => response.json())
            .then(data => {
                esconderLoading();
                if (data.success) {
                    fecharModalConfidenteCRUD();
                    location.reload();
                } else {
                    alert(data.message || (isEdit ? "Erro ao atualizar confidente." : "Erro ao adicionar confidente."));
                    btn.disabled = false;
                    btn.innerHTML = btnTextOriginal;
                }
            })
            .catch(error => {
                console.error("Erro:", error);
                esconderLoading();
                alert(isEdit ? "Erro ao atualizar confidente." : "Erro ao adicionar confidente.");
                btn.disabled = false;
                btn.innerHTML = btnTextOriginal;
            });
        }

        function removerConfidenteCRUD(confidenteId, nome, emTribo, temCampistas) {
            let mensagem = `Tem certeza que deseja excluir "${nome}"?`;
            if (emTribo) {
                mensagem += '\n\nAVISO: Este confidente está atribuído a uma tribo. Remova-o da tribo primeiro.';
            }
            if (temCampistas > 0) {
                mensagem += `\n\nAVISO: Este confidente é conhecido por ${temCampistas} campista(s). Remova essas associações primeiro.`;
            }
            
            if (!confirm(mensagem)) return;

            const row = document.getElementById(`confidente-row-${confidenteId}`) || document.getElementById(`confidente-card-${confidenteId}`);
            if (row) {
                row.style.transition = 'opacity 0.3s';
                row.style.opacity = '0.5';
            }
            
            mostrarLoading('Removendo confidente...');

            fetch(`/confidentes-crud/remover/${confidenteId}`, {
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
                    alert(data.message || "Erro ao remover confidente.");
                    if (row) {
                        row.style.opacity = '1';
                    }
                }
            })
            .catch(error => {
                console.error("Erro:", error);
                esconderLoading();
                alert("Erro ao remover confidente.");
                if (row) {
                    row.style.opacity = '1';
                }
            });
        }
    </script>
</body>
</html>

