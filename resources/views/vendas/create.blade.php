<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Nova Venda') }}
            </h2>
            <a href="{{ route('vendas.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form id="vendaForm" action="{{ route('vendas.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Coluna 1: Informações da Venda -->
                            <div class="lg:col-span-1">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informações da Venda</h3>
                                    
                                    <!-- Número da Venda -->
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Número da Venda</label>
                                        <div class="mt-1 text-lg font-bold text-gray-900">#{{ $numeroVenda }}</div>
                                    </div>

                                    <!-- Cliente -->
                                    <div class="mb-4">
                                        <label for="cliente_id" class="block text-sm font-medium text-gray-700">Cliente (Opcional)</label>
                                        <select name="cliente_id" id="cliente_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Selecione um cliente</option>
                                            @foreach($clientes as $cliente)
                                                <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Forma de Pagamento -->
                                    <div class="mb-4">
                                        <label for="forma_pagamento" class="block text-sm font-medium text-gray-700">Forma de Pagamento *</label>
                                        <select name="forma_pagamento" id="forma_pagamento" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="dinheiro">Dinheiro</option>
                                            <option value="cartao">Cartão</option>
                                            <option value="pix">PIX</option>
                                            <option value="transferencia">Transferência</option>
                                        </select>
                                    </div>

                                    <!-- Observações -->
                                    <div class="mb-4">
                                        <label for="observacoes" class="block text-sm font-medium text-gray-700">Observações</label>
                                        <textarea name="observacoes" id="observacoes" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    </div>
                                </div>
                            </div>

                            

                            <!-- Coluna 2: Produtos -->
                            <div class="lg:col-span-2">
                                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Adicionar Produtos</h3>
                                    
                                    <!-- Seleção de Produto -->
                                    <div class="flex gap-2 mb-4">
                                        <select id="produtoSelect" class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Selecione um produto</option>
                                            @foreach($produtos as $produto)
                                                <option value="{{ $produto->id }}" data-preco="{{ $produto->preco_venda }}" data-estoque="{{ $produto->estoque }}">
                                                    {{ $produto->nome }} - R$ {{ number_format($produto->preco_venda, 2, ',', '.') }} (Estoque: {{ $produto->estoque }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="number" id="quantidadeInput" min="1" value="1" class="w-20 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" id="adicionarProduto"
    style="
        background:#000;
        color:#fff;
        padding:8px 16px;
        border-radius:6px;
        border:none;
        cursor:pointer;
    ">
    Adicionar
</button>

                                    </div>





                                    <!-- Lista de Produtos Adicionados -->
                                    <div id="listaProdutos" class="space-y-2">
                                        <!-- Produtos serão adicionados aqui via JavaScript -->
                                    </div>
                                </div>

                                <!-- Resumo da Venda -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Resumo da Venda</h3>
                                    
                                    <div class="space-y-2 mb-4">
                                        <div class="flex justify-between">
                                            <span>Subtotal:</span>
                                            <span id="subtotal">R$ 0,00</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <label for="desconto" class="text-sm text-gray-600">Desconto:</label>
                                            <div class="flex items-center gap-2">
                                                <input type="number" name="desconto" id="desconto" value="0" min="0" step="0.01" class="w-24 px-2 py-1 border border-gray-300 rounded text-right">
                                                <span>R$</span>
                                            </div>
                                        </div>
                                        <div class="flex justify-between text-lg font-bold border-t pt-2">
                                            <span>Total:</span>
                                            <span id="total">R$ 0,00</span>
                                        </div>
                                    </div>

                                    <!-- Valor Pago e Troco -->
                                    <div class="space-y-2 mb-4">
                                        <div class="flex justify-between items-center">
                                            <label for="valor_pago" class="text-sm font-medium text-gray-700">Valor Pago:</label>
                                            <input type="number" name="valor_pago" id="valor_pago" value="0" min="0" step="0.01" required class="w-32 px-3 py-2 border border-gray-300 rounded-md shadow-sm text-right">
                                        </div>
                                        <div class="flex justify-between text-lg font-bold border-t pt-2">
                                            <span>Troco:</span>
                                            <span id="troco">R$ 0,00</span>
                                        </div>
                                    </div>

                                    <!-- Botão Finalizar -->
                                    <button type="submit" id="finalizarVenda" class="w-full bg-blue-600 text-white px-4 py-3 rounded-md hover:bg-blue-700 font-medium text-lg">
                                        Finalizar Venda
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>






<script>
document.addEventListener('DOMContentLoaded', function () {

    let produtosAdicionados = [];
    let subtotal = 0;

    const produtoSelect   = document.getElementById('produtoSelect');
    const quantidadeInput = document.getElementById('quantidadeInput');
    const clienteSelect   = document.getElementById('cliente_id');

    const subtotalEl = document.getElementById('subtotal');
    const totalEl    = document.getElementById('total');
    const valorPago  = document.getElementById('valor_pago');
    const desconto   = document.getElementById('desconto');
    const trocoEl    = document.getElementById('troco');
    const btnFinal   = document.getElementById('finalizarVenda');

    // Inicialmente desativa a finalização
    btnFinal.disabled = true;
    btnFinal.classList.add('opacity-50','cursor-not-allowed');

    // ================================
    //  CONTROLE DO CLIENTE
    // ================================
    clienteSelect.addEventListener('change', validarFinalizacao);

    function validarFinalizacao() {
        if (!clienteSelect.value || produtosAdicionados.length === 0) {
            btnFinal.disabled = true;
            btnFinal.classList.add('opacity-50','cursor-not-allowed');
        } else {
            btnFinal.disabled = false;
            btnFinal.classList.remove('opacity-50','cursor-not-allowed');
        }
    }

    // ================================
    //  ADICIONAR PRODUTO
    // ================================
    document.getElementById('adicionarProduto').addEventListener('click', function() {

        const produtoId = produtoSelect.value;
        const quantidade = parseInt(quantidadeInput.value);

        if (!produtoId || quantidade < 1) {
            alert('Selecione um produto e informe a quantidade!');
            return;
        }

        const produtoOption = produtoSelect.options[produtoSelect.selectedIndex];
        const preco    = parseFloat(produtoOption.dataset.preco);
        const estoque  = parseInt(produtoOption.dataset.estoque);
        const nome     = produtoOption.text.split(' - ')[0];

        if (quantidade > estoque) {
            alert(`Estoque insuficiente! Disponível: ${estoque} unidades`);
            return;
        }

        const existente = produtosAdicionados.find(p => p.id == produtoId);

        if (existente) {
            existente.quantidade += quantidade;
        } else {
            produtosAdicionados.push({
                id: produtoId,
                nome: nome,
                preco: preco,
                quantidade: quantidade
            });
        }

        atualizarListaProdutos();
        atualizarResumo();
        validarFinalizacao();

        quantidadeInput.value = 1;
    });

    // ================================
    //  LISTA DE PRODUTOS
    // ================================
    function atualizarListaProdutos() {

        const lista = document.getElementById('listaProdutos');
        lista.innerHTML = '';

        produtosAdicionados.forEach((produto, index) => {

            const div = document.createElement('div');
            div.className = 'flex justify-between items-center bg-white p-3 rounded border';

            div.innerHTML = `
                <div>
                    <span class="font-medium">${produto.nome}</span><br>
                    <span class="text-sm text-gray-600">
                        ${produto.quantidade} x R$ ${produto.preco.toFixed(2).replace('.', ',')}
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="font-bold">
                        R$ ${(produto.preco * produto.quantidade).toFixed(2).replace('.', ',')}
                    </span>

                    <button type="button"
                        onclick="removerProduto(${index})"
                        class="text-red-600 hover:text-red-900">
                        ✕
                    </button>
                </div>
            `;

            lista.appendChild(div);
        });
    }

    window.removerProduto = function(index) {
        produtosAdicionados.splice(index, 1);
        atualizarListaProdutos();
        atualizarResumo();
        validarFinalizacao();
    };

    // ================================
    //  RESUMO / TOTAL
    // ================================
    function atualizarResumo() {

        subtotal = produtosAdicionados.reduce(
            (sum, prod) => sum + (prod.preco * prod.quantidade), 0
        );

        const valorDesconto = parseFloat(desconto.value) || 0;
        let total = subtotal - valorDesconto;

        if (total < 0) total = 0;

        subtotalEl.textContent = `R$ ${subtotal.toFixed(2).replace('.', ',')}`;
        totalEl.textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;

        calcularTroco();
    }

    // ================================
    //  TROCO EM TEMPO REAL
    // ================================
    function calcularTroco() {

        const totalVenda = parseFloat(
            totalEl.textContent.replace('R$','').replace('.','').replace(',','.')
        ) || 0;

        const pago = parseFloat(valorPago.value) || 0;

        const troco = pago - totalVenda;

        if (troco >= 0) {
            trocoEl.textContent = `R$ ${troco.toFixed(2).replace('.', ',')}`;
            trocoEl.classList.remove('text-red-600');
            trocoEl.classList.add('text-green-600');
        } else {
            trocoEl.textContent = `Falta R$ ${Math.abs(troco).toFixed(2).replace('.', ',')}`;
            trocoEl.classList.remove('text-green-600');
            trocoEl.classList.add('text-red-600');
        }
    }

    valorPago.addEventListener('input', calcularTroco);
    desconto.addEventListener('input', atualizarResumo);

    // ================================
    //  ENVIO DO FORMULÁRIO
    // ================================
    document.getElementById('vendaForm').addEventListener('submit', function(e) {

        // Garantia final
        if (!clienteSelect.value) {
            e.preventDefault();
            alert('Selecione um cliente para finalizar a venda.');
            return;
        }

        if (produtosAdicionados.length === 0) {
            e.preventDefault();
            alert('Adicione pelo menos um produto!');
            return;
        }

        produtosAdicionados.forEach((produto, index) => {

            const inputProduto = document.createElement('input');
            inputProduto.type = 'hidden';
            inputProduto.name = `itens[${index}][produto_id]`;
            inputProduto.value = produto.id;
            this.appendChild(inputProduto);

            const inputQuantidade = document.createElement('input');
            inputQuantidade.type = 'hidden';
            inputQuantidade.name = `itens[${index}][quantidade]`;
            inputQuantidade.value = produto.quantidade;
            this.appendChild(inputQuantidade);

        });

    });

});
</script>








</x-app-layout>