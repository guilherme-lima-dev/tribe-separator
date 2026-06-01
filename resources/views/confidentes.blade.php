<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="theme-color" content="#059669"/>
    <link rel="shortcut icon" href="./assets/img/favicon.ico"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/assets/css/maanaim.css"/>
    <title>Maanaim | Gerenciar Confidentes</title>
</head>
<body class="antialiased">
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="/" class="navbar-brand">
                <div class="navbar-brand-icon">
                    <i class="fas fa-arrow-left"></i>
                </div>
                <div class="navbar-brand-text">
                    <div class="navbar-brand-title">Confidentes</div>
                    <div class="navbar-brand-sub">Gerenciar confidentes do acampamento</div>
                </div>
            </a>
            <div class="navbar-actions">
                <button onclick="abrirModalAdicionarConfidente()" class="navbar-btn navbar-btn-primary">
                    <i class="fas fa-plus"></i><span>Novo Confidente</span>
                </button>
            </div>
            <button class="navbar-mobile-toggle" onclick="toggleMobileNav()" aria-label="Menu">
                <i class="fas fa-bars" id="mobileNavIcon"></i>
            </button>
        </div>
        <div class="navbar-mobile-menu" id="mobileNavMenu">
            <button onclick="abrirModalAdicionarConfidente(); toggleMobileNav()" class="navbar-btn navbar-btn-primary">
                <i class="fas fa-plus"></i>Novo Confidente
            </button>
            <a href="/" class="navbar-btn">
                <i class="fas fa-arrow-left"></i>Voltar ao Início
            </a>
        </div>
    </nav>

    <!-- Alertas -->
    <div class="page-container !pb-0 !pt-4">
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle alert-icon"></i>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle alert-icon"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <i class="fas fa-times-circle alert-icon"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <!-- Loading Overlay Global -->
    <div id="loadingOverlay" class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black/40 modal-backdrop">
        <div class="loading-card modal-enter">
            <div class="spinner mx-auto mb-4"></div>
            <p id="loadingText" class="text-gray-700 font-semibold">Processando...</p>
        </div>
    </div>

    <!-- Conteúdo Principal -->
    <div class="page-container">
        <div class="content-panel">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div class="section-header !mb-0">
                    <div class="section-header-icon section-header-icon-purple">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div>
                        <div class="section-title">Confidentes Cadastrados</div>
                        <div class="section-subtitle">{{ $confidentes->count() }} confidente(s) no sistema</div>
                    </div>
                </div>
                <button onclick="abrirModalAdicionarConfidente()" class="btn-primary">
                    <i class="fas fa-plus"></i>Novo Confidente
                </button>
            </div>
            
            @if($confidentes->count() > 0)
                <!-- Versão Desktop (tabela) -->
                <div class="hidden md:block table-container">
                    <table id="confidenteTable" class="table-modern">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Tribo</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="confidenteTableBody">
                            @foreach($confidentes as $confidente)
                            <tr id="confidente-row-{{ $confidente->id }}">
                                <td class="text-gray-500 font-mono text-sm">{{ $confidente->id }}</td>
                                <td class="font-semibold">{{ $confidente->nome }}</td>
                                <td>
                                    @if($confidente->tribo)
                                        <span class="badge-valid">
                                            <i class="fas fa-layer-group"></i>{{ $confidente->tribo->nome_tribo }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm">Sem tribo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex gap-1">
                                        <button onclick="abrirModalEditarConfidente({{ $confidente->id }}, '{{ addslashes($confidente->nome) }}', {{ $confidente->tribo_id ?? 'null' }})" 
                                                class="btn-icon btn-icon-edit" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="removerConfidenteCRUD({{ $confidente->id }}, '{{ addslashes($confidente->nome) }}', {{ $confidente->tribo_id ? 'true' : 'false' }}, {{ $confidente->campistas()->count() }})" 
                                                class="btn-icon btn-icon-delete" title="Excluir">
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
                        <div id="confidente-card-{{ $confidente->id }}" class="card p-4">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="font-bold text-gray-900">{{ $confidente->nome }}</h3>
                                    <p class="text-sm text-gray-500 mt-0.5">ID: {{ $confidente->id }}</p>
                                </div>
                                @if($confidente->tribo)
                                    <span class="badge-valid">
                                        <i class="fas fa-layer-group"></i>{{ $confidente->tribo->nome_tribo }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">Sem tribo</span>
                                @endif
                            </div>
                            <div class="flex gap-2 pt-3 border-t border-gray-100">
                                <button onclick="abrirModalEditarConfidente({{ $confidente->id }}, '{{ addslashes($confidente->nome) }}', {{ $confidente->tribo_id ?? 'null' }})" 
                                        class="btn-secondary text-xs !py-1.5 !px-3 flex-1">
                                    <i class="fas fa-edit"></i>Editar
                                </button>
                                <button onclick="removerConfidenteCRUD({{ $confidente->id }}, '{{ addslashes($confidente->nome) }}', {{ $confidente->tribo_id ? 'true' : 'false' }}, {{ $confidente->campistas()->count() }})" 
                                        class="btn-danger text-xs !py-1.5 !px-3 flex-1">
                                    <i class="fas fa-trash"></i>Excluir
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-user-tie empty-state-icon"></i>
                    <h3 class="empty-state-title">Nenhum confidente cadastrado</h3>
                    <p class="empty-state-desc">Comece adicionando seu primeiro confidente ao sistema.</p>
                    <button onclick="abrirModalAdicionarConfidente()" class="btn-primary">
                        <i class="fas fa-plus"></i>Adicionar Primeiro Confidente
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Adicionar/Editar Confidente -->
    <div id="modalConfidenteCRUD" class="fixed inset-0 hidden z-50 flex items-center justify-center modal-backdrop">
        <div onclick="fecharModalConfidenteCRUD()" class="absolute inset-0 bg-black/40"></div>
        <div id="modalConfidenteCRUDContent" class="modal-content w-full max-w-lg p-6 relative z-10 mx-4">
            <button onclick="fecharModalConfidenteCRUD()" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
            <h2 class="modal-title" id="modalConfidenteCRUDTitulo">Adicionar Confidente</h2>
            <form id="formConfidenteCRUD" onsubmit="salvarConfidenteCRUD(event)">
                <input type="hidden" id="confidenteId">
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user-tie mr-2 text-purple-600"></i>Nome do Confidente
                    </label>
                    <input type="text" id="nomeConfidente" required class="input-modern" placeholder="Ex: João Silva">
                </div>
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-layer-group mr-2 text-emerald-600"></i>Tribo (Opcional)
                    </label>
                    <select id="triboIdConfidente" class="input-modern">
                        <option value="">Selecione uma tribo (opcional)</option>
                        @foreach($tribos as $tribo)
                            <option value="{{ $tribo->id }}">{{ $tribo->nome_tribo }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1.5">Você pode atribuir o confidente a uma tribo agora ou depois</p>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn-primary flex-1" id="btnSalvarConfidente">
                        <i class="fas fa-save"></i>Adicionar
                    </button>
                    <button type="button" onclick="fecharModalConfidenteCRUD()" class="btn-secondary">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleMobileNav() {
            const menu = document.getElementById('mobileNavMenu');
            const icon = document.getElementById('mobileNavIcon');
            if (menu && icon) {
                const isOpen = menu.classList.toggle('open');
                icon.className = isOpen ? 'fas fa-times' : 'fas fa-bars';
            }
        }

        function mostrarLoading(texto = 'Processando...') {
            document.getElementById('loadingText').textContent = texto;
            document.getElementById('loadingOverlay').classList.remove('hidden');
        }

        function esconderLoading() {
            document.getElementById('loadingOverlay').classList.add('hidden');
        }

        function abrirModalAdicionarConfidente() {
            document.getElementById('modalConfidenteCRUDTitulo').textContent = 'Adicionar Confidente';
            document.getElementById('confidenteId').value = '';
            document.getElementById('nomeConfidente').value = '';
            document.getElementById('triboIdConfidente').value = '';
            document.getElementById('btnSalvarConfidente').innerHTML = '<i class="fas fa-save"></i>Adicionar';
            
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
            document.getElementById('btnSalvarConfidente').innerHTML = '<i class="fas fa-save"></i>Salvar';
            
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
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>Salvando...';
            
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
                    if (row) row.style.opacity = '1';
                }
            })
            .catch(error => {
                console.error("Erro:", error);
                esconderLoading();
                alert("Erro ao remover confidente.");
                if (row) row.style.opacity = '1';
            });
        }
    </script>
</body>
</html>
