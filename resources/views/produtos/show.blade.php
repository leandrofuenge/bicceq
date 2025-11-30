<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes do Produto') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('produtos.edit', $produto) }}" class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700">
                    Editar
                </a>
                <a href="{{ route('produtos.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Alertas -->
                    @if($produto->estoque_baixo)
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <strong>Atenção:</strong> Este produto está com estoque baixo!
                            </div>
                        </div>
                    @endif

                    @if($produto->estoque == 0)
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
                            <strong>Atenção:</strong> Este produto está sem estoque!
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Informações Básicas -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Produto</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nome</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $produto->nome }}</dd>
                                </div>
                                
                                @if($produto->codigo_barras)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Código de Barras</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $produto->codigo_barras }}</dd>
                                </div>
                                @endif
                                
                                @if($produto->categoria)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Categoria</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $produto->categoria }}</dd>
                                </div>
                                @endif
                                
                                @if($produto->descricao)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Descrição</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $produto->descricao }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        <!-- Informações Financeiras e Estoque -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Valores e Estoque</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Preço de Custo</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($produto->preco_custo > 0)
                                            R$ {{ number_format($produto->preco_custo, 2, ',', '.') }}
                                        @else
                                            <span class="text-gray-400">Não informado</span>
                                        @endif
                                    </dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Preço de Venda</dt>
                                    <dd class="mt-1 text-lg font-semibold text-green-600">
                                        R$ {{ number_format($produto->preco_venda, 2, ',', '.') }}
                                    </dd>
                                </div>

                                @if($produto->preco_custo > 0)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Margem de Lucro</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @php
                                            $lucro = $produto->preco_venda - $produto->preco_custo;
                                            $margem = ($lucro / $produto->preco_custo) * 100;
                                        @endphp
                                        R$ {{ number_format($lucro, 2, ',', '.') }} ({{ number_format($margem, 1, ',', '.') }}%)
                                    </dd>
                                </div>
                                @endif

                                <div class="pt-4 border-t border-gray-200">
                                    <dt class="text-sm font-medium text-gray-500">Estoque Atual</dt>
                                    <dd class="mt-1 text-2xl font-bold 
                                        {{ $produto->estoque_baixo ? 'text-red-600' : ($produto->estoque == 0 ? 'text-yellow-600' : 'text-green-600') }}">
                                        {{ $produto->estoque }} unidades
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Estoque Mínimo</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $produto->estoque_minimo }} unidades</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
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
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Datas -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-500">
                            <div>
                                <strong>Cadastrado em:</strong> {{ $produto->created_at->format('d/m/Y H:i') }}
                            </div>
                            <div>
                                <strong>Última atualização:</strong> {{ $produto->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>