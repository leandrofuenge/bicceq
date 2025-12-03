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
                                            <option value="cartaodebito">Cartão Débito</option>
                                            <option value="cartaocredito">Cartão Crédito</option>
                                            <option value="pix">PIX</option>
                                            <option value="transferencia">Transferência</option>
                                        </select>
                                    </div>

                                    <!-- Informações de Parcelas (apenas para visualização) -->
                                    <div id="infoParcelas" class="hidden bg-white p-4 rounded-lg border border-gray-200 mt-4">
                                        <h4 class="text-md font-medium text-gray-900 mb-3">Informações de Parcelamento</h4>
                                        
                                        <!-- Parcelas -->
                                        <div class="mb-3">
                                            <div class="flex items-center justify-between mb-1">
                                                <label for="parcelas" class="block text-sm font-medium text-gray-700">Número de Parcelas</label>
                                                <div id="valorParcela" class="text-sm text-gray-600"></div>
                                            </div>
                                            <select name="parcelas" id="parcelas" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="1">1x sem juros</option>
                                                <option value="2">2x sem juros</option>
                                                <option value="3">3x sem juros</option>
                                                <option value="4">4x sem juros</option>
                                                <option value="5">5x sem juros</option>
                                                <option value="6">6x sem juros</option>
                                                <option value="7">7x sem juros</option>
                                                <option value="8">8x sem juros</option>
                                                <option value="9">9x sem juros</option>
                                                <option value="10">10x sem juros</option>
                                                <option value="11">11x sem juros</option>
                                                <option value="12">12x sem juros</option>
                                            </select>
                                        </div>
                                        
                                        <!-- Apenas informação -->
                                        <div class="text-sm text-gray-500 p-3 bg-gray-50 rounded">
                                            <p class="font-medium mb-1">⚠️ Informação Importante:</p>
                                            <p>O processamento do cartão de crédito será realizado separadamente pelo sistema financeiro.</p>
                                        </div>
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
                                        <button type="button" id="adicionarProduto" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150">
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
                                    <div id="valorPagoSection" class="space-y-2 mb-4">
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
                                    <button type="submit" id="finalizarVenda" class="w-full bg-blue-600 text-white px-4 py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 font-medium text-lg transition duration-150">
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
        let taxaJuros = 0;

        const produtoSelect = document.getElementById('produtoSelect');
        const quantidadeInput = document.getElementById('quantidadeInput');
        const clienteSelect = document.getElementById('cliente_id');
        const formaPagamento = document.getElementById('forma_pagamento');
        const infoParcelas = document.getElementById('infoParcelas');
        const valorPagoSection = document.getElementById('valorPagoSection');
        const parcelasSelect = document.getElementById('parcelas');
        const valorParcelaEl = document.getElementById('valorParcela');

        const subtotalEl = document.getElementById('subtotal');
        const totalEl = document.getElementById('total');
        const valorPago = document.getElementById('valor_pago');
        const desconto = document.getElementById('desconto');
        const trocoEl = document.getElementById('troco');
        const btnFinal = document.getElementById('finalizarVenda');
        const btnAdicionarProduto = document.getElementById('adicionarProduto');

        // Inicialmente desativa os botões
        btnFinal.disabled = true;
        btnFinal.classList.add('opacity-50', 'cursor-not-allowed');
        btnAdicionarProduto.disabled = false;

        // Estados de loading
        const loadingState = {
            processandoVenda: false
        };

        // ================================
        //  INICIALIZAÇÃO
        // ================================
        function inicializar() {
            verificarDisponibilidadeProdutos();
            configurarFeedbackUI();
            
            // Inicializa a validação
            validarFinalizacao();
        }

        // ================================
        //  CONTROLE DO CLIENTE
        // ================================
        clienteSelect.addEventListener('change', function() {
            validarFinalizacao();
            if (this.value) {
                carregarDadosCliente(this.value);
            }
        });

        function validarFinalizacao() {
            const liberar = clienteSelect.value && produtosAdicionados.length > 0;

            btnFinal.disabled = !liberar;
            btnFinal.classList.toggle('opacity-50', !liberar);
            btnFinal.classList.toggle('cursor-not-allowed', !liberar);
            
            btnFinal.innerHTML = loadingState.processandoVenda 
                ? '<span class="flex items-center justify-center"><svg class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processando...</span>'
                : 'Finalizar Venda';
        }

        // ================================
        //  CONTROLE DA FORMA DE PAGAMENTO
        // ================================
        formaPagamento.addEventListener('change', function() {
            const valor = this.value;
            
            // Mostrar/ocultar informações de parcelas
            if (valor === 'cartaocredito') {
                infoParcelas.classList.remove('hidden');
                valorPagoSection.classList.add('hidden');
                configurarEventosParcelas();
            } else {
                infoParcelas.classList.add('hidden');
                valorPagoSection.classList.remove('hidden');
            }
            
            if (valor === 'dinheiro') {
                calcularTroco();
            }
        });

        // ================================
        //  CÁLCULO DE PARCELAS COM JUROS (apenas para visualização)
        // ================================
        function configurarEventosParcelas() {
            if (parcelasSelect) {
                parcelasSelect.addEventListener('change', calcularParcelas);
            }
            if (desconto) {
                desconto.addEventListener('input', calcularParcelas);
            }
            
            calcularParcelas();
        }

        function calcularParcelas() {
            if (!totalEl || !parcelasSelect) return;
            
            const total = parseFloat(totalEl.innerText.replace('R$', '').replace('.', '').replace(',', '.')) || 0;
            const numParcelas = parseInt(parcelasSelect.value) || 1;
            
            // Tabela de juros (apenas para informação)
            const tabelaJuros = {
                1: 0,    2: 0,    3: 0,
                4: 1.5,  5: 2.0,  6: 2.5,
                7: 3.0,  8: 3.5,  9: 4.0,
                10: 4.5, 11: 5.0, 12: 5.5
            };
            
            taxaJuros = tabelaJuros[numParcelas] || 0;
            const valorComJuros = total * (1 + taxaJuros / 100);
            const valorParcela = valorComJuros / numParcelas;
            
            if (valorParcelaEl) {
                valorParcelaEl.innerHTML = `
                    <div class="text-sm">
                        <div class="font-semibold">${numParcelas}x de R$ ${valorParcela.toFixed(2).replace('.', ',')}</div>
                        <div class="text-gray-600">
                            ${taxaJuros > 0 ? `<span class="text-red-500">(+${taxaJuros}% juros)</span>` : '<span class="text-green-500">Sem juros</span>'}
                        </div>
                    </div>
                `;
            }
        }

        // ================================
        //  FEEDBACK VISUAL (UI/UX)
        // ================================
        function configurarFeedbackUI() {
            // Configurações básicas de feedback
        }

        function mostrarErro(elemento, mensagem) {
            if (!elemento) return;
            
            removerErro(elemento);
            
            elemento.classList.add('border-red-500');
            
            const erroDiv = document.createElement('div');
            erroDiv.className = 'mt-1 text-sm text-red-600 flex items-center';
            erroDiv.innerHTML = `
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                ${mensagem}
            `;
            
            elemento.parentNode.appendChild(erroDiv);
            elemento.dataset.erroId = Date.now();
        }

        function removerErro(elemento) {
            if (!elemento) return;
            
            elemento.classList.remove('border-red-500');
            
            const erroId = elemento.dataset.erroId;
            if (erroId) {
                const erroDiv = elemento.parentNode.querySelector(`[data-erro-id="${erroId}"]`);
                if (erroDiv) erroDiv.remove();
            }
        }

        // ================================
        //  VERIFICAÇÃO DE PRODUTOS
        // ================================
        function verificarDisponibilidadeProdutos() {
            if (produtoSelect && produtoSelect.options.length <= 1) {
                produtoSelect.disabled = true;
                if (btnAdicionarProduto) btnAdicionarProduto.disabled = true;
                produtoSelect.innerHTML = '<option value="">Nenhum produto disponível</option>';
                mostrarAviso('Não há produtos disponíveis para venda.', 'produtos');
            } else if (produtoSelect && btnAdicionarProduto) {
                produtoSelect.disabled = false;
                btnAdicionarProduto.disabled = false;
            }
        }

        function mostrarAviso(mensagem, tipo) {
            console.warn(`Aviso (${tipo}): ${mensagem}`);
        }

        // ================================
        //  CARREGAMENTO DE DADOS DO CLIENTE
        // ================================
        async function carregarDadosCliente(clienteId) {
            try {
                // Simulação de carregamento de dados do cliente
                const clientes = {
                    '1': { email: 'cliente@exemplo.com', telefone: '11999999999' },
                    '2': { email: 'outro@exemplo.com', telefone: '11888888888' }
                };
                
                const dados = clientes[clienteId];
                if (dados) {
                    // Aqui você pode preencher dados do cliente se necessário
                    console.log('Dados do cliente carregados:', dados);
                }
            } catch (error) {
                console.error('Erro ao carregar dados do cliente:', error);
            }
        }

        // ================================
        //  ADICIONAR PRODUTO
        // ================================
        btnAdicionarProduto.addEventListener('click', function() {
            this.classList.add('opacity-50');
            const textoOriginal = this.innerHTML;
            this.innerHTML = 'Adicionando...';
            
            setTimeout(() => {
                this.classList.remove('opacity-50');
                this.innerHTML = textoOriginal;
            }, 300);
            
            const produtoId = produtoSelect.value;
            const quantidade = parseInt(quantidadeInput.value, 10);

            if (!produtoId || isNaN(quantidade) || quantidade < 1) {
                mostrarErro(quantidadeInput, 'Selecione um produto e informe uma quantidade válida!');
                return;
            }

            const produtoOption = produtoSelect.options[produtoSelect.selectedIndex];
            const preco = parseFloat(produtoOption.dataset.preco);
            const estoque = parseInt(produtoOption.dataset.estoque, 10);
            const nome = produtoOption.text.split(' - ')[0];

            const existente = produtosAdicionados.find(p => p.id == produtoId);
            const quantidadeAtual = existente ? existente.quantidade : 0;
            const novaQuantidade = quantidadeAtual + quantidade;

            if (novaQuantidade > estoque) {
                mostrarErro(quantidadeInput, 
                    `Estoque insuficiente! Disponível: ${estoque}, ` +
                    `Já no carrinho: ${quantidadeAtual}, ` +
                    `Tentando adicionar: ${quantidade}`
                );
                return;
            }

            if (existente) {
                existente.quantidade = novaQuantidade;
                mostrarFeedbackProduto(`Quantidade atualizada: ${nome} (${novaQuantidade} unidades)`);
            } else {
                produtosAdicionados.push({
                    id: produtoId,
                    nome,
                    preco,
                    quantidade
                });
                mostrarFeedbackProduto(`${nome} adicionado ao carrinho!`);
            }

            atualizarListaProdutos();
            atualizarResumo();
            validarFinalizacao();
            calcularParcelas();

            quantidadeInput.value = 1;
            produtoSelect.focus();
        });

        function mostrarFeedbackProduto(mensagem) {
            console.log('Produto:', mensagem);
        }

        // ================================
        //  LISTA DE PRODUTOS
        // ================================
        function atualizarListaProdutos() {
            const lista = document.getElementById('listaProdutos');
            if (!lista) return;
            
            lista.innerHTML = '';

            if (produtosAdicionados.length === 0) {
                lista.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <p>Nenhum produto adicionado</p>
                        <p class="text-sm mt-2">Adicione produtos usando o formulário acima</p>
                    </div>
                `;
                return;
            }

            produtosAdicionados.forEach((produto, index) => {
                const div = document.createElement('div');
                div.className = 'flex justify-between items-center bg-white p-3 rounded border hover:bg-gray-50 transition duration-150';
                div.innerHTML = `
                    <div class="flex-1">
                        <strong class="text-gray-800">${produto.nome}</strong>
                        <div class="flex items-center mt-1">
                            <button type="button" onclick="alterarQuantidade(${index}, -1)" 
                                class="w-6 h-6 flex items-center justify-center bg-gray-200 rounded-l hover:bg-gray-300">
                                −
                            </button>
                            <span class="w-10 text-center bg-gray-50 py-1">${produto.quantidade}</span>
                            <button type="button" onclick="alterarQuantidade(${index}, 1)"
                                class="w-6 h-6 flex items-center justify-center bg-gray-200 rounded-r hover:bg-gray-300">
                                +
                            </button>
                            <span class="ml-4 text-gray-600">
                                × R$ ${produto.preco.toFixed(2).replace('.', ',')}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <strong class="text-lg text-gray-900">
                            R$ ${(produto.preco * produto.quantidade).toFixed(2).replace('.', ',')}
                        </strong>
                        <button type="button" onclick="removerProduto(${index})"
                            class="text-red-600 hover:text-red-800 hover:bg-red-50 p-2 rounded-full transition duration-150"
                            title="Remover produto">
                            ✕
                        </button>
                    </div>
                `;
                lista.appendChild(div);
            });
        }

        window.alterarQuantidade = function(index, delta) {
            const produto = produtosAdicionados[index];
            const produtoOption = produtoSelect.querySelector(`option[value="${produto.id}"]`);
            const estoque = parseInt(produtoOption.dataset.estoque);
            
            const novaQuantidade = produto.quantidade + delta;
            
            if (novaQuantidade < 1) {
                removerProduto(index);
                return;
            }
            
            if (novaQuantidade > estoque) {
                mostrarErro(quantidadeInput, `Estoque máximo: ${estoque} unidades`);
                return;
            }
            
            produto.quantidade = novaQuantidade;
            atualizarListaProdutos();
            atualizarResumo();
            calcularParcelas();
        };

        window.removerProduto = function(index) {
            const produto = produtosAdicionados[index];
            produtosAdicionados.splice(index, 1);
            
            mostrarFeedbackProduto(`${produto.nome} removido do carrinho`);
            
            atualizarListaProdutos();
            atualizarResumo();
            calcularParcelas();
            validarFinalizacao();
        };

        // ================================
        //  RESUMO E CÁLCULOS
        // ================================
        function atualizarResumo() {
            subtotal = produtosAdicionados.reduce(
                (sum, p) => sum + (p.preco * p.quantidade),
                0
            );

            const desc = parseFloat(desconto.value) || 0;
            let total = subtotal - desc;
            if (total < 0) total = 0;

            if (subtotalEl) subtotalEl.innerText = `R$ ${subtotal.toFixed(2).replace('.', ',')}`;
            if (totalEl) totalEl.innerText = `R$ ${total.toFixed(2).replace('.', ',')}`;

            calcularTroco();
            calcularParcelas();
        }

        function calcularTroco() {
            if (!totalEl || !valorPago || !trocoEl) return;
            
            const total = parseFloat(totalEl.innerText.replace('R$', '').replace('.', '').replace(',', '.')) || 0;
            const pago = parseFloat(valorPago.value) || 0;
            const troco = pago - total;

            if (troco >= 0) {
                trocoEl.innerText = `R$ ${troco.toFixed(2).replace('.', ',')}`;
                trocoEl.classList.remove('text-red-600');
                trocoEl.classList.add('text-green-600');
            } else {
                trocoEl.innerText = `Falta R$ ${Math.abs(troco).toFixed(2).replace('.', ',')}`;
                trocoEl.classList.remove('text-green-600');
                trocoEl.classList.add('text-red-600');
            }
        }

        // Event listeners para cálculos em tempo real
        if (valorPago) {
            valorPago.addEventListener('input', calcularTroco);
        }
        if (desconto) {
            desconto.addEventListener('input', atualizarResumo);
        }

        // ================================
        //  SUBMIT FINAL
        // ================================
        document.getElementById('vendaForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Validações básicas
            if (!clienteSelect.value) {
                mostrarErro(clienteSelect, 'Selecione um cliente!');
                clienteSelect.focus();
                return;
            }

            if (produtosAdicionados.length === 0) {
                alert('Adicione pelo menos um produto!');
                produtoSelect.focus();
                return;
            }

            // Validação de estoque definitiva
            for (let produto of produtosAdicionados) {
                const option = produtoSelect.querySelector(`option[value="${produto.id}"]`);
                if (!option) continue;

                const estoque = parseInt(option.dataset.estoque);
                if (produto.quantidade > estoque) {
                    mostrarErro(quantidadeInput,
                        `Estoque insuficiente!\n\nProduto: ${produto.nome}\n`+
                        `Disponível: ${estoque}\nSolicitado: ${produto.quantidade}`
                    );
                    return;
                }
            }

            // Confirmação antes de finalizar
            if (!await confirmarVenda()) {
                return;
            }

            // Processamento com loading
            loadingState.processandoVenda = true;
            validarFinalizacao();

            try {
                // Simula processamento
                await new Promise(resolve => setTimeout(resolve, 1500));
                
                // Adiciona inputs hidden
                produtosAdicionados.forEach((produto, index) => {
                    this.insertAdjacentHTML('beforeend', `
                        <input type="hidden"
                               name="itens[${index}][produto_id]"
                               value="${produto.id}">
                        <input type="hidden"
                               name="itens[${index}][quantidade]"
                               value="${produto.quantidade}">
                    `);
                });

                // Adiciona dados das parcelas se necessário
                if (formaPagamento.value === 'cartaocredito') {
                    this.insertAdjacentHTML('beforeend', `
                        <input type="hidden" name="taxa_juros" value="${taxaJuros}">
                        <input type="hidden" name="numero_parcelas" value="${parcelasSelect.value}">
                    `);
                }

                // Submete o formulário
                this.submit();
                
            } catch (error) {
                console.error('Erro ao processar venda:', error);
                alert('Erro ao processar venda. Tente novamente.');
                loadingState.processandoVenda = false;
                validarFinalizacao();
            }
        });

        async function confirmarVenda() {
            const total = totalEl ? totalEl.innerText : 'R$ 0,00';
            const formaPagamentoText = formaPagamento.options[formaPagamento.selectedIndex].text;
            const clienteText = clienteSelect.options[clienteSelect.selectedIndex].text;
            
            const mensagem = `Confirma finalização da venda?\n\nCliente: ${clienteText}\nTotal: ${total}\nForma de pagamento: ${formaPagamentoText}\n${formaPagamento.value === 'cartaocredito' ? `Parcelas: ${parcelasSelect.value}x` : ''}\n\nItens (${produtosAdicionados.length}):\n${produtosAdicionados.map(p => `• ${p.quantidade}x ${p.nome} - R$ ${(p.preco * p.quantidade).toFixed(2)}`).join('\n')}`;
            
            return confirm(mensagem);
        }

        // ================================
        //  INICIALIZAÇÃO DA APLICAÇÃO
        // ================================
        inicializar();
    });
    </script>
</x-app-layout>