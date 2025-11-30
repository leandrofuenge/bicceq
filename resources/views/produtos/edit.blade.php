<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Produto') }}
            </h2>
            <a href="{{ route('produtos.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('produtos.update', $produto) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nome do Produto -->
                            <div class="md:col-span-2">
                                <label for="nome" class="block text-sm font-medium text-gray-700">Nome do Produto *</label>
                                <input type="text" name="nome" id="nome" value="{{ old('nome', $produto->nome) }}" 
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       required>
                                @error('nome')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Código de Barras -->
                            <div>
                                <label for="codigo_barras" class="block text-sm font-medium text-gray-700">Código de Barras</label>
                                <input type="text" name="codigo_barras" id="codigo_barras" value="{{ old('codigo_barras', $produto->codigo_barras) }}"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Categoria -->
                            <div>
                                <label for="categoria" class="block text-sm font-medium text-gray-700">Categoria</label>
                                <input type="text" name="categoria" id="categoria" value="{{ old('categoria', $produto->categoria) }}"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Preço de Custo -->
                            <div>
                                <label for="preco_custo" class="block text-sm font-medium text-gray-700">Preço de Custo (R$)</label>
                                <input type="number" name="preco_custo" id="preco_custo" value="{{ old('preco_custo', $produto->preco_custo) }}" step="0.01" min="0"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Preço de Venda -->
                            <div>
                                <label for="preco_venda" class="block text-sm font-medium text-gray-700">Preço de Venda (R$) *</label>
                                <input type="number" name="preco_venda" id="preco_venda" value="{{ old('preco_venda', $produto->preco_venda) }}" step="0.01" min="0"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       required>
                                @error('preco_venda')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Estoque Atual -->
                            <div>
                                <label for="estoque" class="block text-sm font-medium text-gray-700">Estoque Atual</label>
                                <input type="number" name="estoque" id="estoque" value="{{ old('estoque', $produto->estoque) }}" min="0"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Estoque Mínimo -->
                            <div>
                                <label for="estoque_minimo" class="block text-sm font-medium text-gray-700">Estoque Mínimo</label>
                                <input type="number" name="estoque_minimo" id="estoque_minimo" value="{{ old('estoque_minimo', $produto->estoque_minimo) }}" min="0"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Descrição -->
                            <div class="md:col-span-2">
                                <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
                                <textarea name="descricao" id="descricao" rows="3"
                                          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('descricao', $produto->descricao) }}</textarea>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('produtos.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                Atualizar Produto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>