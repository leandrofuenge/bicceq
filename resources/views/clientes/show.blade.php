<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes do Cliente') }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('clientes.edit', $cliente) }}" class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700">
                    Editar
                </a>
                <a href="{{ route('clientes.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Informações Básicas -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações Básicas</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nome</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $cliente->nome }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $cliente->email ?? 'Não informado' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Telefone</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $cliente->telefone ?? 'Não informado' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">CPF/CNPJ</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $cliente->cpf_cnpj ?? 'Não informado' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Endereço -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Endereço</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Endereço Completo</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $cliente->endereco ?? 'Não informado' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Cidade</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $cliente->cidade ?? 'Não informado' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Estado</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $cliente->estado ?? 'Não informado' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">CEP</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $cliente->cep ?? 'Não informado' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informações do Sistema -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações do Sistema</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Data de Cadastro</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $cliente->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Última Atualização</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $cliente->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Ações -->
                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between">
                        <form action="{{ route('clientes.destroy', $cliente) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700" 
                                    onclick="return confirm('Tem certeza que deseja excluir este cliente? Esta ação não pode ser desfeita.')">
                                Excluir Cliente
                            </button>
                        </form>
                        
                        <div class="space-x-2">
                            <a href="{{ route('clientes.edit', $cliente) }}" class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700">
                                Editar
                            </a>
                            <a href="{{ route('clientes.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                                Lista de Clientes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>