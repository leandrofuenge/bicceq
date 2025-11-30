<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes da Venda') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('vendas.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                    Voltar para Vendas
                </a>
                <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Imprimir
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Cabeçalho da Venda -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informações da Venda</h3>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Número da Venda:</dt>
                                    <dd class="text-sm text-gray-900 font-bold">#{{ $venda->numero_venda }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Data e Hora:</dt>
                                    <dd class="text-sm text-gray-900">{{ $venda->created_at->format('d/m/Y H:i') }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Vendedor:</dt>
                                    <dd class="text-sm text-gray-900">{{ $venda->vendedor->name }}</dd>
                                </div>
                                @if($venda->cliente)
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Cliente:</dt>
                                    <dd class="text-sm text-gray-900">{{ $venda->cliente->nome }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Pagamento</h3>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Forma de Pagamento:</dt>
                                    <dd class="text-sm text-gray-900">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $venda->forma_pagamento == 'dinheiro' ? 'bg-green-100 text-green-800' : 
                                               ($venda->forma_pagamento == 'cartao' ? 'bg-blue-100 text-blue-800' : 
                                               'bg-purple-100 text-purple-800') }}">
                                            {{ ucfirst($venda->forma_pagamento) }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Status:</dt>
                                    <dd class="text-sm text-gray-900">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $venda->status == 'concluida' ? 'bg-green-100 text-green-800' : 
                                               ($venda->status == 'pendente' ? 'bg-yellow-100 text-yellow-800' : 
                                               'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($venda->status) }}
                                        </span>
                                    </dd>
                                </div>
                                @if($venda->observacoes)
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Observações:</dt>
                                    <dd class="text-sm text-gray-900">{{ $venda->observacoes }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Itens da Venda -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Produtos Vendidos</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produto</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preço Unit.</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($venda->itens as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $item->produto->nome }}</div>
                                            @if($item->produto->codigo_barras)
                                            <div class="text-sm text-gray-500">{{ $item->produto->codigo_barras }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $item->quantidade }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Resumo Financeiro -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Resumo Financeiro</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Subtotal:</span>
                                    <span class="text-sm text-gray-900">R$ {{ number_format($venda->itens->sum('subtotal'), 2, ',', '.') }}</span>
                                </div>
                                @if($venda->desconto > 0)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Desconto:</span>
                                    <span class="text-sm text-red-600">- R$ {{ number_format($venda->desconto, 2, ',', '.') }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between text-lg font-bold border-t pt-2">
                                    <span>Total:</span>
                                    <span>R$ {{ number_format($venda->total, 2, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Valor Pago:</span>
                                    <span class="text-sm text-gray-900">R$ {{ number_format($venda->valor_pago, 2, ',', '.') }}</span>
                                </div>
                                @if($venda->troco > 0)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Troco:</span>
                                    <span class="text-sm text-green-600">R$ {{ number_format($venda->troco, 2, ',', '.') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Ações -->
                    @if($venda->status == 'concluida')
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <form action="{{ route('vendas.destroy', $venda) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700" 
                                    onclick="return confirm('ATENÇÃO: Esta ação irá cancelar a venda e estornar o estoque dos produtos. Tem certeza?')">
                                Cancelar Venda
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Estilo para impressão -->
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
            }
            .bg-white {
                background: white !important;
                box-shadow: none !important;
            }
        }
    </style>
</x-app-layout>