<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Produtos') }}
            </h2>
            <a href="{{ route('produtos.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                Novo Produto
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Filtros e Busca -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Barra de Busca -->
                        <div class="md:col-span-2">
                            <form action="{{ route('produtos.index') }}" method="GET">
                                <div class="flex">
                                    <input type="text" name="search" value="{{ request('search') }}" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                           placeholder="Buscar por nome, código ou categoria...">
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r-md hover:bg-blue-700">
                                        Buscar
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Filtro Estoque Baixo -->
                        <div class="flex items-center">
                            <a href="{{ route('produtos.index', ['estoque_baixo' => true]) }}" 
                               class="bg-red-100 text-red-700 px-4 py-2 rounded-md hover:bg-red-200 flex items-center {{ request('estoque_baixo') ? 'bg-red-200 border border-red-300' : '' }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                Estoque Baixo
                            </a>
                            @if(request('estoque_baixo'))
                                <a href="{{ route('produtos.index') }}" class="ml-2 text-gray-500 hover:text-gray-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Tabela de Produtos -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preço</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estoque</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($produtos as $produto)
                                <tr class="{{ $produto->estoque_baixo ? 'bg-red-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $produto->nome }}</div>
                                                @if($produto->codigo_barras)
                                                    <div class="text-sm text-gray-500">{{ $produto->codigo_barras }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $produto->categoria ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">R$ {{ number_format($produto->preco_venda, 2, ',', '.') }}</div>
                                        @if($produto->preco_custo > 0)
                                            <div class="text-sm text-gray-500">Custo: R$ {{ number_format($produto->preco_custo, 2, ',', '.') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $produto->estoque }} uni</div>
                                        <div class="text-xs text-gray-500">Mín: {{ $produto->estoque_minimo }} uni</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($produto->estoque_baixo)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Estoque Baixo
                                            </span>
                                        @elseif($produto->estoque == 0)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Sem Estoque
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Disponível
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('produtos.show', $produto) }}" class="text-blue-600 hover:text-blue-900 mr-3">Ver</a>
                                        <a href="{{ route('produtos.edit', $produto) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                                        <form action="{{ route('produtos.destroy', $produto) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Nenhum produto cadastrado.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-4">
                        {{ $produtos->links() }}
                    </div>

                    <!-- Resumo -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                            <div>
                                <strong>Total de Produtos:</strong> {{ $produtos->total() }}
                            </div>
                            <div class="text-red-600">
                                <strong>Com Estoque Baixo:</strong> {{ \App\Models\Produto::estoqueBaixo()->count() }}
                            </div>
                            <div class="text-yellow-600">
                                <strong>Sem Estoque:</strong> {{ \App\Models\Produto::where('estoque', 0)->count() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>