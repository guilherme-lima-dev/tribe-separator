<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="theme-color" content="#000000"/>
    <link rel="shortcut icon" href="./assets/img/favicon.ico"/>
    <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/gh/creativetimofficial/tailwind-starter-kit/compiled-tailwind.min.css"/>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />

    <style>
        .bg-pink-500 {
            background-color: #2E8B57 !important;
        }

        .active:bg-pink-600 {
            background-color: #2E8B57 !important;
        }

        .bg-invalid {
            background-color: #FFCDD2; /* Fundo vermelho claro */
        }

         #modalConhecidos{
             box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important; /* Sombra suave */
             background: rgba(0,0,0,0.8);
         }

    </style>
    <title>Maanaim | Separação de tribos</title>
</head>
<body class="text-blueGray-700 antialiased">
<noscript>You need to enable JavaScript to run this app.</noscript>
<div id="root">
    <div class="relative bg-blueGray-50">
        <nav style="background: #2E8B57 !important"
             class="absolute top-0 left-0 w-full z-10 bg-transparent md:flex-row md:flex-nowrap md:justify-start flex items-center p-4">
            <div class="w-full mx-auto items-center flex justify-between md:flex-nowrap flex-wrap md:px-10 px-4">
                <a class="text-white text-sm uppercase hidden lg:inline-block font-semibold" href="./index.html">Maanaim
                    - Separação de tribos</a>
            </div>
        </nav>
        <div class="relative md:pt-32 pb-16 pt-12">
            <div>
                @if (session('success'))
                    <div class="bg-green-500 text-white text-sm font-bold px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('warning'))
                    <div class="bg-yellow-500 text-white text-sm font-bold px-4 py-3 rounded mb-4">
                        {{ session('warning') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-500 text-white text-sm font-bold px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
            <div class="px-4 md:px-10 mx-auto w-full">
                <div class="flex flex-wrap mb-4">
                    <button onclick="montarTribos()"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Montar Tribos
                    </button>

                </div>
                <div class="flex flex-wrap">
                    @foreach($tribos as $tribo)
                        <div class="w-full xl:w-4/12 mb-12 xl:mb-0 px-4">
                            <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
                                <div class="rounded-t mb-0 px-4 py-3 border-0">
                                    <div class="flex flex-wrap items-center">
                                        <div class="relative w-full px-4 max-w-full flex-grow flex-1">
                                            <h3 class="font-semibold text-base text-blueGray-700">
                                                Tribo {{ $tribo->nome_tribo }} - {{ $tribo->confidentes->pluck('nome')->join(', ', ' e ') }}
                                            </h3>
                                            <p class="text-sm {{ $tribo->estaValida() ? 'text-green-500' : 'text-red-500' }}">
                                                {{ $tribo->estaValida() ? 'Tribo está válida' : 'Tribo não está válida' }}
                                            </p>
                                            @php
                                                $totalCampistas = $tribo->campistas->count();
                                                $pesoMedio = $totalCampistas > 0 ? $tribo->campistas->avg('peso') : 0;
                                                $alturaMedia = $totalCampistas > 0 ? $tribo->campistas->avg('altura') : 0;
                                                $numHomens = $tribo->campistas->where('genero', 'm')->count();
                                                $numMulheres = $tribo->campistas->where('genero', 'f')->count();
                                            @endphp
                                            <div class="text-sm text-blueGray-600">
                                                <p>Média de peso: {{ number_format($pesoMedio, 2) }} kg</p>
                                                <p>Média de altura: {{ number_format($alturaMedia, 2) }} cm</p>
                                                <p>Homens: {{ $numHomens }}</p>
                                                <p>Mulheres: {{ $numMulheres }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="block w-full overflow-x-auto">
                                    <table class="items-center w-full bg-transparent border-collapse">
                                        <tbody>
                                        @forelse($tribo->campistas as $key => $campista)
                                            <tr class="{{ $campista->campistaAtendeARegra() ? '' : 'bg-invalid' }}">
                                                <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left">
                                                    {{ $key + 1 }} - {{ $campista->nome }}
                                                    <form action="/remover-da-tribo/{{ $campista->id }}" method="POST">
                                                        @csrf
                                                        <button class="text-red-500 font-bold text-xs rounded" type="submit">
                                                            Remover da Tribo
                                                        </button>
                                                    </form>
                                                </th>
                                            </tr>
                                        @empty
                                            <tr>
                                                <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left">
                                                    Nenhum campista alocado na tribo
                                                </th>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
        <div class="flex flex-wrap mt-2">
            <div class="w-full mb-4 px-4">
                <!-- Campo de busca -->
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Buscar campistas por nome, gênero, peso, altura..."
                       class="px-3 py-2 border rounded w-full mb-4"/>
            </div>
            <div class="w-full mb-12 xl:mb-0 px-4">
                <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <div class="flex flex-wrap items-center">
                            <div class="relative w-full px-4 max-w-full flex-grow flex-1">
                                <h3 class="font-semibold text-base text-blueGray-700">Campistas</h3>
                            </div>
                        </div>
                    </div>
                    <div class="block w-full overflow-x-auto">
                        <table id="campistaTable" class="items-center w-full bg-transparent border-collapse">
                            <thead>
                            <tr>
                                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                    ID
                                </th>
                                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                    Nome
                                </th>
                                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                    Gênero
                                </th>
                                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                    Peso
                                </th>
                                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                    Altura
                                </th>
                                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                    Ações
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($campistas as $campista)
                                <tr class="{{ $campista->campistaAtendeARegra() ? '' : 'bg-invalid' }}">
                                    <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left">{{ $campista->id }}</th>
                                    <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left">{{ $campista->nome }}</th>
                                    <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">{{ $campista->genero == 'm' ? 'Masculino' : 'Feminino' }}</td>
                                    <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">{{ $campista->peso }}
                                        Kg
                                    </td>
                                    <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">{{ $campista->altura }}
                                        cm
                                    </td>
                                    <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 flex ">
                                        @if(!empty($campista->tribo_id))
                                            <form action="/remover-da-tribo/{{ $campista->id }}" method="POST">
                                                @csrf
                                                <button
                                                    class="bg-red-500 active:bg-red-600 text-white font-bold text-xs px-4 py-2 rounded"
                                                    type="submit"
                                                    style="height: 36px; line-height: normal;"
                                                >
                                                    Remover da Tribo
                                                </button>
                                            </form>
                                        @else
                                            <div>
                                                <button onclick="abrirListaTribos({{ $campista->id }})"
                                                        class="bg-pink-500 active:bg-pink-600 uppercase text-white font-bold hover:shadow-md shadow text-xs px-4 py-2 rounded outline-none focus:outline-none mb-1"
                                                        type="button"
                                                        style="height: 36px; line-height: normal;"
                                                >
                                                    Adicionar a Tribo
                                                </button>
                                                <div id="tribos-{{ $campista->id }}" class="hidden mt-2">
                                                    @foreach($tribos as $tribo)
                                                        <form
                                                            action="/adicionar-a-tribo/{{ $campista->id }}/{{ $tribo->id }}"
                                                            method="POST">
                                                            @csrf
                                                            <button
                                                                @if(!is_null($campista->retornaInfracaoNessaTribo($tribo->id)))
                                                                    style="background: #ccc" disabled
                                                                class="cursor-not-allowed text-white font-bold text-xs px-4 py-2 rounded mb-1"
                                                                @else
                                                                    class="bg-green-500 hover:bg-green-700 text-white font-bold text-xs px-4 py-2 rounded mb-1"
                                                                @endif
                                                                type="submit"
                                                                style="height: 36px; line-height: normal;"

                                                            >
                                                                Adicionar à {{ $tribo->nome_tribo }}
                                                            </button>
                                                            <div class="font-bold">
                                                                {{ $campista->retornaInfracaoNessaTribo($tribo->id) }}
                                                            </div>
                                                        </form>
                                                    @endforeach
                                                </div>
                                            </div>

                                        @endif
                                        <button
                                            onclick="abrirModalConhecidos({{ $campista->id }}, '{{ $campista->nome }}')"
                                            class="bg-blue-500 ml-3 active:bg-blue-600 text-white font-bold text-xs px-4 py-2 rounded"
                                            type="button"
                                            style="height: 36px; line-height: normal;"
                                        >
                                            Ver conhecidos
                                        </button>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



        <!-- Modal com Estilos e Comportamento Atualizado -->
        <div id="modalConhecidos" class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black bg-opacity-30">
            <div onclick="fecharModal()" class="absolute inset-0"></div>
            <div class="bg-white w-50 max-w-lg p-6 rounded-lg shadow-lg relative z-10 mx-4"
                 style="box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);">

                <!-- Botão de Fechar -->
                <button onclick="fecharModal()" style="top: 0.5%; right: 2%;" class="absolute text-gray-500 hover:text-gray-800 text-2xl font-bold">
                    &times;
                </button>

                <!-- Cabeçalho da Modal -->
                <h2 class="text-2xl font-semibold text-center text-gray-800 mb-4">Conhecidos de <span id="campistaNome"></span></h2>

                <!-- Conteúdo da Modal -->
                <div class="overflow-y-auto max-h-60">
                    <ul id="listaConhecidos" class="list-disc list-inside text-gray-700 space-y-1">
                        <!-- Itens de conhecidos serão inseridos aqui -->
                    </ul>
                </div>

                <!-- Opção para Adicionar Conhecido -->
                <div class="mt-4">
                    <label for="novoConhecidoId" class="block text-gray-700 font-semibold mb-2">Adicionar Conhecido:</label>
                    <select id="novoConhecidoId" class="w-full px-3 py-2 border rounded mb-2">
                        <option value="">Selecione um Campista...</option>
                        @foreach($campistas as $campista)
                            <option value="{{ $campista->id }}">{{ $campista->nome }}</option>
                        @endforeach
                    </select>
                    <button onclick="adicionarConhecido()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full mt-2">
                        Adicionar Conhecido
                    </button>
                </div>

            </div>
        </div>




        <footer class="block py-4">
            <div class="container mx-auto px-4">
                <hr class="mb-4 border-b-1 border-blueGray-200"/>
                <div class="flex items-center md:justify-center justify-center">
                    <div class="text-sm text-blueGray-500 font-semibold py-1">
                        Copyright © <span id="javascript-date"></span>
                        <a href="https://www.creative-tim.com"
                           class="text-blueGray-500 hover:text-blueGray-700 text-sm font-semibold py-1">
                            MAANAIM
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Inicializar o Select2
        $('#novoConhecidoId').select2({
            placeholder: "Selecione um Campista...",
            allowClear: true,
            width: '100%',
        });
    });

    function abrirListaTribos(campistaId) {
        // Alterna a visibilidade da lista de tribos para o campista específico
        const listaTribos = document.getElementById(`tribos-${campistaId}`);
        if (listaTribos.classList.contains('hidden')) {
            listaTribos.classList.remove('hidden');
        } else {
            listaTribos.classList.add('hidden');
        }
    }

    function montarTribos() {
        // Redireciona para o endpoint de montagem de tribos
        window.location.href = '/monta-tribos';
    }

    function filterTable() {
        const input = document.getElementById("searchInput").value.toLowerCase();
        const table = document.getElementById("campistaTable");
        const rows = table.getElementsByTagName("tr");

        for (let i = 1; i < rows.length; i++) {
            // Capture todas as células (colunas) dentro da linha
            const cells = rows[i].getElementsByTagName("th");
            let rowContent = "";

            for (let j = 0; j < cells.length; j++) {
                rowContent += cells[j].textContent.toLowerCase() + " ";
            }

            // Verifique se a linha inclui o texto do filtro
            if (rowContent.includes(input)) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }


    async function handleCampistaAction(url, action) {
        const response = await fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
        if (response.ok) {
            document.location.reload(); // Refresh the list after AJAX update
        } else {
            alert("Falha na operação: " + action);
        }
    }

    function abrirModalConhecidos(campistaId, campistaNome) {
        // Defina o ID e o nome do campista para adicionar/remover conhecidos
        document.getElementById("campistaNome").innerText = campistaNome;
        document.getElementById("modalConhecidos").dataset.campistaId = campistaId;

        // Limpe qualquer conteúdo existente na lista de conhecidos
        const listaConhecidos = document.getElementById("listaConhecidos");
        listaConhecidos.innerHTML = "";

        // Fetch para obter os conhecidos do campista
        fetch(`/conhecidos/${campistaId}`)
            .then(response => response.json())
            .then(data => {
                data.conhecidos.forEach(conhecido => {
                    const li = document.createElement("li");
                    li.classList.add("flex", "justify-between", "items-center");
                    li.innerText = conhecido.nome;

                    // Botão de Remover ao lado do nome do conhecido
                    const removeButton = document.createElement("button");
                    removeButton.innerText = "Remover";
                    removeButton.className = "bg-red-500 hover:bg-red-700 text-white font-bold text-xs px-2 py-1 rounded ml-4 mt-1";
                    removeButton.onclick = () => removerConhecido(campistaId, conhecido.id, li);

                    li.appendChild(removeButton);
                    listaConhecidos.appendChild(li);
                });
            })
            .catch(error => console.error('Erro ao carregar conhecidos:', error));

        // Exibe a modal
        document.getElementById("modalConhecidos").classList.remove("hidden");
    }

    function fecharModal() {
        document.getElementById("modalConhecidos").classList.add("hidden");
    }

    function adicionarConhecido() {
        const campistaId = document.getElementById("modalConhecidos").dataset.campistaId;
        const novoConhecidoId = document.getElementById("novoConhecidoId").value; // Pega o valor do select

        fetch(`/conhecidos/adicionar`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ campistaId, novoConhecidoId })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    abrirModalConhecidos(campistaId, document.getElementById("campistaNome").innerText); // Recarregar a lista
                    $('#novoConhecidoId').val(null).trigger('change'); // Limpar o select após adicionar
                } else {
                    alert(data.message || "Erro ao adicionar conhecido.");
                }
            })
            .catch(error => console.error("Erro ao adicionar conhecido:", error));
    }


    function removerConhecido(campistaId, conhecidoId, liElement) {
        fetch(`/conhecidos/remover`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ campistaId, conhecidoId })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    liElement.remove(); // Remove o elemento da lista
                } else {
                    alert(data.message || "Erro ao remover conhecido.");
                }
            })
            .catch(error => console.error("Erro ao remover conhecido:", error));
    }


</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" charset="utf-8"></script>
<script src="https://unpkg.com/@popperjs/core@2.9.1/dist/umd/popper.min.js" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>

</body>
</html>
