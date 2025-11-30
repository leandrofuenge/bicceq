<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Cabeçalho -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Dashboard - Bicceq ERP</h1>
                <p class="text-gray-600">Visão geral do seu negócio</p>
            </div>

            <!-- Cards de Métricas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total de Vendas Hoje -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                      <p class="text-sm font-medium text-gray-600">Vendas Hoje</p>
                      <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($vendasHoje, 2, ',', '.') }}</p>
            </div>
                    </div>
                </div>

         <!-- Caixa -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Caixa</p>
                <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($valorCaixa, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>


                <!-- Produtos com Estoque Baixo -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 rounded-lg">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                          <p class="text-sm font-medium text-gray-600">Estoque Baixo</p>
                          <p class="text-2xl font-bold text-gray-900">{{ $estoqueBaixo }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total de Clientes -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                              <p class="text-sm font-medium text-gray-600">Total Clientes</p>
                              <p class="text-2xl font-bold text-gray-900">{{ $totalClientes }}</p>
                        </div>
                    </div>
                </div>
            </div>

    <!-- Total Produtos -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <div class="flex items-center">
            <div class="p-3 bg-orange-100 rounded-lg">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Produtos</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalProdutos }}</p>
            </div>
        </div>
    </div>
</div>


            <!-- Ações Rápidas -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-lg font-semibold mb-4">Ações Rápidas</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <button class="bg-blue-600 text-white text-center py-3 px-4 rounded-lg hover:bg-blue-700 transition cursor-not-allowed opacity-50">
                        Novo Cliente
                    </button>
                    <button class="bg-green-600 text-white text-center py-3 px-4 rounded-lg hover:bg-green-700 transition cursor-not-allowed opacity-50">
                        Novo Produto
                    </button>
                    <button class="bg-orange-600 text-white text-center py-3 px-4 rounded-lg hover:bg-orange-700 transition cursor-not-allowed opacity-50">
                        Nova Venda
                    </button>
                    <button class="bg-purple-600 text-white text-center py-3 px-4 rounded-lg hover:bg-purple-700 transition cursor-not-allowed opacity-50">
                        Abrir Caixa
                    </button>
                </div>
                <p class="text-sm text-gray-500 mt-2 text-center">
                    * Módulos em desenvolvimento
                </p>
            </div>

            <!-- Gráfico de Faturamento (Placeholder) -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Faturamento dos Últimos 7 Dias</h2>
                <div class="h-64 flex items-center justify-center border-2 border-dashed border-gray-300 rounded-lg">
                    <p class="text-gray-500">Gráfico será implementado posteriormente</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>