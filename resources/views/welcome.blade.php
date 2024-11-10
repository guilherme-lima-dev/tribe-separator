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
            <div class="px-4 md:px-10 mx-auto w-full">
                <div class="flex flex-wrap mb-4">
                    <button onclick="montarTribos()"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Montar Tribos
                    </button>
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
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Buscar campistas..."
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
                                    <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                        @if(!empty($campista->tribo_id))
                                            <form action="/remover-da-tribo/{{ $campista->id }}" method="POST">
                                                @csrf
                                                <button
                                                    class="bg-red-500 active:bg-red-600 text-white font-bold text-xs px-4 py-2 rounded"
                                                    type="submit">
                                                    Remover da Tribo
                                                </button>
                                            </form>
                                        @else
                                            <button onclick="abrirListaTribos({{ $campista->id }})"
                                                    class="bg-pink-500 active:bg-pink-600 uppercase text-white font-bold hover:shadow-md shadow text-xs px-4 py-2 rounded outline-none focus:outline-none mb-1"
                                                    type="button">
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
                                                        >
                                                            Adicionar à {{ $tribo->nome_tribo }}
                                                        </button>
                                                        <div class="font-bold">
                                                            {{ $campista->retornaInfracaoNessaTribo($tribo->id) }}
                                                        </div>
                                                    </form>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
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
        // Obter valor de entrada e normalizar para minúsculas
        const input = document.getElementById("searchInput");
        const filter = input.value.toLowerCase();
        const table = document.getElementById("campistaTable");
        const tr = table.getElementsByTagName("tr");

        // Percorrer todas as linhas da tabela e ocultar as que não correspondem à busca
        for (let i = 1; i < tr.length; i++) {
            const td = tr[i].getElementsByTagName("th")[1]; // Coluna de Nome do Campista
            if (td) {
                const txtValue = td.textContent || td.innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" charset="utf-8"></script>
<script src="https://unpkg.com/@popperjs/core@2.9.1/dist/umd/popper.min.js" charset="utf-8"></script>
</body>
</html>
