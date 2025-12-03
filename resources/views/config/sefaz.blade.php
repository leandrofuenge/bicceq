<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Configurações SEFAZ') }}
            </h2>
            <div class="flex gap-2">
                <button type="button" id="btnTestarConexao" 
                    class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                    Testar Conexão
                </button>
                <button type="button" id="btnVerCertificado" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Ver Certificado
                </button>
                <a href="{{ route('dashboard') }}" 
                    class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Alertas -->
            <div id="alertContainer"></div>
            
            <!-- Informações do Certificado -->
            <div id="certificateInfo" class="hidden bg-white p-6 rounded-lg shadow-sm mb-6 border border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informações do Certificado Digital</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <p><span class="font-medium">Nome/Razão Social:</span> <span id="certNome"></span></p>
                        <p><span class="font-medium">CPF/CNPJ:</span> <span id="certCpfCnpj"></span></p>
                        <p><span class="font-medium">Emissor:</span> <span id="certEmissor"></span></p>
                    </div>
                    <div class="space-y-2">
                        <p><span class="font-medium">Válido de:</span> <span id="certValidadeDe"></span></p>
                        <p><span class="font-medium">Válido até:</span> <span id="certValidadeAte"></span></p>
                        <p><span class="font-medium">Dias restantes:</span> <span id="certDiasRestantes"></span></p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form id="sefazForm" action="{{ route('config.sefaz.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        @if(isset($config) && $config->id)
                            <input type="hidden" name="id" value="{{ $config->id }}">
                        @endif
                        
                        <div class="space-y-8">
                            <!-- Seção 1: Dados da Empresa -->
                            <div class="border-b border-gray-200 pb-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-6">Dados da Empresa</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Razão Social -->
                                    <div>
                                        <label for="razao_social" class="block text-sm font-medium text-gray-700">
                                            Razão Social *
                                        </label>
                                        <input type="text" name="razao_social" id="razao_social" 
                                            value="{{ old('razao_social', $config->razao_social ?? '') }}"
                                            required
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    
                                    <!-- CNPJ -->
                                    <div>
                                        <label for="cnpj" class="block text-sm font-medium text-gray-700">
                                            CNPJ *
                                        </label>
                                        <input type="text" name="cnpj" id="cnpj" 
                                            value="{{ old('cnpj', $config->cnpj ?? '') }}"
                                            required maxlength="14"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            oninput="formatarCNPJ(this)">
                                    </div>
                                    
                                    <!-- Inscrição Estadual -->
                                    <div>
                                        <label for="inscricao_estadual" class="block text-sm font-medium text-gray-700">
                                            Inscrição Estadual *
                                        </label>
                                        <input type="text" name="inscricao_estadual" id="inscricao_estadual" 
                                            value="{{ old('inscricao_estadual', $config->inscricao_estadual ?? '') }}"
                                            required
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    
                                    <!-- Regime Tributário -->
                                    <div>
                                        <label for="regime_tributario" class="block text-sm font-medium text-gray-700">
                                            Regime Tributário *
                                        </label>
                                        <select name="regime_tributario" id="regime_tributario" required
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Selecione...</option>
                                            <option value="1" {{ (old('regime_tributario', $config->regime_tributario ?? '') == 1) ? 'selected' : '' }}>
                                                Simples Nacional
                                            </option>
                                            <option value="2" {{ (old('regime_tributario', $config->regime_tributario ?? '') == 2) ? 'selected' : '' }}>
                                                Simples Nacional - Excesso de Sublimite
                                            </option>
                                            <option value="3" {{ (old('regime_tributario', $config->regime_tributario ?? '') == 3) ? 'selected' : '' }}>
                                                Regime Normal
                                            </option>
                                        </select>
                                    </div>
                                    
                                    <!-- CRT -->
                                    <div>
                                        <label for="crt" class="block text-sm font-medium text-gray-700">
                                            Código de Regime Tributário (CRT) *
                                        </label>
                                        <select name="crt" id="crt" required
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Selecione...</option>
                                            <option value="1" {{ (old('crt', $config->crt ?? '') == 1) ? 'selected' : '' }}>
                                                Simples Nacional
                                            </option>
                                            <option value="2" {{ (old('crt', $config->crt ?? '') == 2) ? 'selected' : '' }}>
                                                Simples Nacional - Excesso de Sublimite
                                            </option>
                                            <option value="3" {{ (old('crt', $config->crt ?? '') == 3) ? 'selected' : '' }}>
                                                Regime Normal
                                            </option>
                                        </select>
                                    </div>
                                    
                                    <!-- UF -->
                                    <div>
                                        <label for="uf" class="block text-sm font-medium text-gray-700">
                                            UF *
                                        </label>
                                        <select name="uf" id="uf" required
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Selecione o estado...</option>
                                            <option value="AC" {{ (old('uf', $config->uf ?? '') == 'AC') ? 'selected' : '' }}>AC</option>
                                            <option value="AL" {{ (old('uf', $config->uf ?? '') == 'AL') ? 'selected' : '' }}>AL</option>
                                            <option value="AP" {{ (old('uf', $config->uf ?? '') == 'AP') ? 'selected' : '' }}>AP</option>
                                            <option value="AM" {{ (old('uf', $config->uf ?? '') == 'AM') ? 'selected' : '' }}>AM</option>
                                            <option value="BA" {{ (old('uf', $config->uf ?? '') == 'BA') ? 'selected' : '' }}>BA</option>
                                            <option value="CE" {{ (old('uf', $config->uf ?? '') == 'CE') ? 'selected' : '' }}>CE</option>
                                            <option value="DF" {{ (old('uf', $config->uf ?? '') == 'DF') ? 'selected' : '' }}>DF</option>
                                            <option value="ES" {{ (old('uf', $config->uf ?? '') == 'ES') ? 'selected' : '' }}>ES</option>
                                            <option value="GO" {{ (old('uf', $config->uf ?? '') == 'GO') ? 'selected' : '' }}>GO</option>
                                            <option value="MA" {{ (old('uf', $config->uf ?? '') == 'MA') ? 'selected' : '' }}>MA</option>
                                            <option value="MT" {{ (old('uf', $config->uf ?? '') == 'MT') ? 'selected' : '' }}>MT</option>
                                            <option value="MS" {{ (old('uf', $config->uf ?? '') == 'MS') ? 'selected' : '' }}>MS</option>
                                            <option value="MG" {{ (old('uf', $config->uf ?? '') == 'MG') ? 'selected' : '' }}>MG</option>
                                            <option value="PA" {{ (old('uf', $config->uf ?? '') == 'PA') ? 'selected' : '' }}>PA</option>
                                            <option value="PB" {{ (old('uf', $config->uf ?? '') == 'PB') ? 'selected' : '' }}>PB</option>
                                            <option value="PR" {{ (old('uf', $config->uf ?? '') == 'PR') ? 'selected' : '' }}>PR</option>
                                            <option value="PE" {{ (old('uf', $config->uf ?? '') == 'PE') ? 'selected' : '' }}>PE</option>
                                            <option value="PI" {{ (old('uf', $config->uf ?? '') == 'PI') ? 'selected' : '' }}>PI</option>
                                            <option value="RJ" {{ (old('uf', $config->uf ?? '') == 'RJ') ? 'selected' : '' }}>RJ</option>
                                            <option value="RN" {{ (old('uf', $config->uf ?? '') == 'RN') ? 'selected' : '' }}>RN</option>
                                            <option value="RS" {{ (old('uf', $config->uf ?? '') == 'RS') ? 'selected' : '' }}>RS</option>
                                            <option value="RO" {{ (old('uf', $config->uf ?? '') == 'RO') ? 'selected' : '' }}>RO</option>
                                            <option value="RR" {{ (old('uf', $config->uf ?? '') == 'RR') ? 'selected' : '' }}>RR</option>
                                            <option value="SC" {{ (old('uf', $config->uf ?? '') == 'SC') ? 'selected' : '' }}>SC</option>
                                            <option value="SP" {{ (old('uf', $config->uf ?? '') == 'SP') ? 'selected' : '' }}>SP</option>
                                            <option value="SE" {{ (old('uf', $config->uf ?? '') == 'SE') ? 'selected' : '' }}>SE</option>
                                            <option value="TO" {{ (old('uf', $config->uf ?? '') == 'TO') ? 'selected' : '' }}>TO</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Ambiente -->
                                    <div>
                                        <label for="ambiente" class="block text-sm font-medium text-gray-700">
                                            Ambiente *
                                        </label>
                                        <select name="ambiente" id="ambiente" required
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Selecione...</option>
                                            <option value="1" {{ (old('ambiente', $config->ambiente ?? '') == 1) ? 'selected' : '' }}>
                                                Homologação
                                            </option>
                                            <option value="2" {{ (old('ambiente', $config->ambiente ?? '') == 2) ? 'selected' : '' }}>
                                                Produção
                                            </option>
                                        </select>
                                        <p class="mt-1 text-sm text-gray-500">
                                            <span class="font-medium">Atenção:</span> Use Homologação para testes e Produção para emissões reais.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Seção 2: Certificado Digital -->
                            <div class="border-b border-gray-200 pb-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-6">Certificado Digital</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Upload Certificado -->
                                    <div class="md:col-span-2">
                                        <label for="certificado" class="block text-sm font-medium text-gray-700">
                                            Certificado Digital (.pfx ou .p12)
                                            @if(isset($config) && $config->certificado_path)
                                                <span class="text-green-600 ml-2">✓ Configurado</span>
                                            @endif
                                        </label>
                                        <input type="file" name="certificado" id="certificado" 
                                            accept=".pfx,.p12"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <p class="mt-1 text-sm text-gray-500">
                                            Arquivo de certificado digital no formato .pfx ou .p12 (máx. 5MB)
                                        </p>
                                    </div>
                                    
                                    <!-- Senha do Certificado -->
                                    <div class="md:col-span-2">
                                        <label for="senha_certificado" class="block text-sm font-medium text-gray-700">
                                            Senha do Certificado *
                                        </label>
                                        <input type="password" name="senha_certificado" id="senha_certificado" 
                                            required
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <p class="mt-1 text-sm text-gray-500">
                                            Senha de acesso ao certificado digital
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Seção 3: Configurações NFC-e -->
                            <div class="border-b border-gray-200 pb-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-6">Configurações NFC-e</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Série NFC-e -->
                                    <div>
                                        <label for="serie_nfce" class="block text-sm font-medium text-gray-700">
                                            Série NFC-e *
                                        </label>
                                        <input type="number" name="serie_nfce" id="serie_nfce" 
                                            value="{{ old('serie_nfce', $config->serie_nfce ?? '1') }}"
                                            required min="1"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <p class="mt-1 text-sm text-gray-500">
                                            Normalmente começa em 1
                                        </p>
                                    </div>
                                    
                                    <!-- Número Inicial NFC-e -->
                                    <div>
                                        <label for="numero_inicial_nfce" class="block text-sm font-medium text-gray-700">
                                            Número Inicial NFC-e *
                                        </label>
                                        <input type="number" name="numero_inicial_nfce" id="numero_inicial_nfce" 
                                            value="{{ old('numero_inicial_nfce', $config->numero_inicial_nfce ?? '1') }}"
                                            required min="1"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    
                                    <!-- Token NFC-e -->
                                    <div>
                                        <label for="token_nfce" class="block text-sm font-medium text-gray-700">
                                            Token NFC-e
                                        </label>
                                        <input type="text" name="token_nfce" id="token_nfce" 
                                            value="{{ old('token_nfce', $config->token_nfce ?? '') }}"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <p class="mt-1 text-sm text-gray-500">
                                            Token fornecido pela SEFAZ (alguns estados)
                                        </p>
                                    </div>
                                    
                                    <!-- CSC NFC-e -->
                                    <div>
                                        <label for="csc_nfce" class="block text-sm font-medium text-gray-700">
                                            CSC (Código de Segurança do Contribuinte)
                                        </label>
                                        <input type="text" name="csc_nfce" id="csc_nfce" 
                                            value="{{ old('csc_nfce', $config->csc_nfce ?? '') }}"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    
                                    <!-- ID Token NFC-e -->
                                    <div>
                                        <label for="id_token_nfce" class="block text-sm font-medium text-gray-700">
                                            ID Token CSC
                                        </label>
                                        <input type="text" name="id_token_nfce" id="id_token_nfce" 
                                            value="{{ old('id_token_nfce', $config->id_token_nfce ?? '') }}"
                                            maxlength="10"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <p class="mt-1 text-sm text-gray-500">
                                            ID do CSC (normalmente 6 dígitos)
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Seção 4: Configurações NF-e -->
                            <div class="border-b border-gray-200 pb-8">
                                <h3 class="text-lg font-medium text-gray-900 mb-6">Configurações NF-e</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Série NF-e -->
                                    <div>
                                        <label for="serie_nfe" class="block text-sm font-medium text-gray-700">
                                            Série NF-e *
                                        </label>
                                        <input type="number" name="serie_nfe" id="serie_nfe" 
                                            value="{{ old('serie_nfe', $config->serie_nfe ?? '1') }}"
                                            required min="1"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    
                                    <!-- Número Inicial NF-e -->
                                    <div>
                                        <label for="numero_inicial_nfe" class="block text-sm font-medium text-gray-700">
                                            Número Inicial NF-e *
                                        </label>
                                        <input type="number" name="numero_inicial_nfe" id="numero_inicial_nfe" 
                                            value="{{ old('numero_inicial_nfe', $config->numero_inicial_nfe ?? '1') }}"
                                            required min="1"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    
                                    <!-- Token NF-e -->
                                    <div class="md:col-span-2">
                                        <label for="token_nfe" class="block text-sm font-medium text-gray-700">
                                            Token NF-e
                                        </label>
                                        <input type="text" name="token_nfe" id="token_nfe" 
                                            value="{{ old('token_nfe', $config->token_nfe ?? '') }}"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <p class="mt-1 text-sm text-gray-500">
                                            Token fornecido pela SEFAZ para NF-e
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Seção 5: Contingência -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-6">Configurações de Contingência</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Modo de Contingência -->
                                    <div>
                                        <label for="modo_contingencia" class="block text-sm font-medium text-gray-700">
                                            Modo de Contingência
                                        </label>
                                        <select name="modo_contingencia" id="modo_contingencia"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Normal (sem contingência)</option>
                                            <option value="1" {{ (old('modo_contingencia', $config->modo_contingencia ?? '') == 1) ? 'selected' : '' }}>
                                                Formulário de Segurança - FS
                                            </option>
                                            <option value="2" {{ (old('modo_contingencia', $config->modo_contingencia ?? '') == 2) ? 'selected' : '' }}>
                                                Serviço próprio - SP
                                            </option>
                                            <option value="3" {{ (old('modo_contingencia', $config->modo_contingencia ?? '') == 3) ? 'selected' : '' }}>
                                                Contingência offline
                                            </option>
                                        </select>
                                    </div>
                                    
                                    <!-- Justificativa Contingência -->
                                    <div class="md:col-span-2">
                                        <label for="justificativa_contingencia" class="block text-sm font-medium text-gray-700">
                                            Justificativa da Contingência
                                        </label>
                                        <textarea name="justificativa_contingencia" id="justificativa_contingencia" rows="3"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('justificativa_contingencia', $config->justificativa_contingencia ?? '') }}</textarea>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Informe o motivo da contingência quando ativada
                                        </p>
                                    </div>
                                    
                                    <!-- Email Administrativo -->
                                    <div>
                                        <label for="email_administrativo" class="block text-sm font-medium text-gray-700">
                                            E-mail Administrativo
                                        </label>
                                        <input type="email" name="email_administrativo" id="email_administrativo" 
                                            value="{{ old('email_administrativo', $config->email_administrativo ?? '') }}"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <p class="mt-1 text-sm text-gray-500">
                                            Para recebimento de avisos e alertas
                                        </p>
                                    </div>
                                    
                                    <!-- Telefone Suporte -->
                                    <div>
                                        <label for="telefone_suporte" class="block text-sm font-medium text-gray-700">
                                            Telefone de Suporte
                                        </label>
                                        <input type="text" name="telefone_suporte" id="telefone_suporte" 
                                            value="{{ old('telefone_suporte', $config->telefone_suporte ?? '') }}"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            oninput="formatarTelefone(this)">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Botões de Ação -->
                            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                                <button type="button" id="btnCancelar" 
                                    class="bg-gray-500 text-white px-6 py-3 rounded-md hover:bg-gray-600">
                                    Cancelar
                                </button>
                                <button type="submit" 
                                    class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    Salvar Configurações
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Senha do Certificado -->
    <div id="certPasswordModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informe a Senha do Certificado</h3>
            <div class="mb-4">
                <label for="modal_cert_password" class="block text-sm font-medium text-gray-700">
                    Senha do Certificado
                </label>
                <input type="password" id="modal_cert_password" 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="mt-1 text-sm text-gray-500">
                    A senha será usada apenas para esta sessão
                </p>
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" id="btnCancelModal" 
                    class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    Cancelar
                </button>
                <button type="button" id="btnConfirmCertPassword" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Confirmar
                </button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Formatar CNPJ
        function formatarCNPJ(input) {
            let valor = input.value.replace(/\D/g, '');
            
            if (valor.length > 2) {
                valor = valor.substring(0, 2) + '.' + valor.substring(2);
            }
            if (valor.length > 6) {
                valor = valor.substring(0, 6) + '.' + valor.substring(6);
            }
            if (valor.length > 10) {
                valor = valor.substring(0, 10) + '/' + valor.substring(10);
            }
            if (valor.length > 15) {
                valor = valor.substring(0, 15) + '-' + valor.substring(15, 17);
            }
            
            input.value = valor;
        }

        // Formatar Telefone
        function formatarTelefone(input) {
            let valor = input.value.replace(/\D/g, '');
            
            if (valor.length > 0) {
                valor = '(' + valor.substring(0, 2) + ') ' + valor.substring(2);
            }
            if (valor.length > 10) {
                valor = valor.substring(0, 10) + '-' + valor.substring(10, 15);
            }
            
            input.value = valor;
        }

        // Mostrar alerta
        function mostrarAlerta(tipo, mensagem) {
            const alertContainer = document.getElementById('alertContainer');
            
            const alertDiv = document.createElement('div');
            alertDiv.className = `mb-4 p-4 rounded-md ${
                tipo === 'success' ? 'bg-green-50 text-green-800 border border-green-200' :
                tipo === 'error' ? 'bg-red-50 text-red-800 border border-red-200' :
                'bg-blue-50 text-blue-800 border border-blue-200'
            }`;
            
            alertDiv.innerHTML = `
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 ${
                            tipo === 'success' ? 'text-green-400' :
                            tipo === 'error' ? 'text-red-400' : 'text-blue-400'
                        }" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="${
                                tipo === 'success' ? 
                                'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z' :
                                tipo === 'error' ?
                                'M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z' :
                                'M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z'
                            }" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm">${mensagem}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button type="button" onclick="this.parentElement.parentElement.remove()" 
                            class="inline-flex rounded-md focus:outline-none">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            
            alertContainer.appendChild(alertDiv);
            
            // Auto-remover após 10 segundos para sucesso
            if (tipo === 'success') {
                setTimeout(() => {
                    if (alertDiv.parentElement) {
                        alertDiv.remove();
                    }
                }, 10000);
            }
        }

        // Testar Conexão com SEFAZ
        document.getElementById('btnTestarConexao').addEventListener('click', async function() {
            const btn = this;
            const originalText = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Testando...';
            
            try {
                const response = await fetch('{{ route("config.sefaz.test") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    mostrarAlerta('success', data.message);
                } else {
                    mostrarAlerta('error', data.message);
                }
                
            } catch (error) {
                mostrarAlerta('error', 'Erro ao testar conexão: ' + error.message);
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        });

        // Verificar Informações do Certificado
        let certificatePassword = null;
        const certPasswordModal = document.getElementById('certPasswordModal');
        const modalCertPassword = document.getElementById('modal_cert_password');
        
        document.getElementById('btnVerCertificado').addEventListener('click', function() {
            certPasswordModal.classList.remove('hidden');
            certPasswordModal.classList.add('flex');
            modalCertPassword.focus();
        });
        
        document.getElementById('btnCancelModal').addEventListener('click', function() {
            certPasswordModal.classList.add('hidden');
            certPasswordModal.classList.remove('flex');
            modalCertPassword.value = '';
        });
        
        document.getElementById('btnConfirmCertPassword').addEventListener('click', async function() {
            const password = modalCertPassword.value.trim();
            
            if (!password) {
                mostrarAlerta('error', 'Informe a senha do certificado');
                return;
            }
            
            certificatePassword = password;
            certPasswordModal.classList.add('hidden');
            certPasswordModal.classList.remove('flex');
            modalCertPassword.value = '';
            
            // Buscar informações do certificado
            const btn = document.getElementById('btnVerCertificado');
            const originalText = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Carregando...';
            
            try {
                // Enviar senha para sessão (simplificado - em produção use método mais seguro)
                const formData = new FormData();
                formData.append('password', password);
                formData.append('_token', '{{ csrf_token() }}');
                
                const response = await fetch('{{ route("config.sefaz.set-cert-password") }}', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Buscar informações do certificado
                    const certResponse = await fetch('{{ route("config.sefaz.certificate-info") }}');
                    const certData = await certResponse.json();
                    
                    if (certData.success) {
                        document.getElementById('certificateInfo').classList.remove('hidden');
                        document.getElementById('certNome').textContent = certData.data.nome;
                        document.getElementById('certCpfCnpj').textContent = certData.data.cpf_cnpj;
                        document.getElementById('certEmissor').textContent = certData.data.emissor;
                        document.getElementById('certValidadeDe').textContent = certData.data.validade_de;
                        document.getElementById('certValidadeAte').textContent = certData.data.validade_ate;
                        
                        const diasRestantes = certData.data.dias_restantes;
                        document.getElementById('certDiasRestantes').textContent = diasRestantes + ' dias';
                        
                        if (diasRestantes < 30) {
                            document.getElementById('certDiasRestantes').className = 'text-red-600 font-bold';
                            mostrarAlerta('error', `Atenção! O certificado expira em ${diasRestantes} dias. Renove-o o quanto antes.`);
                        } else if (diasRestantes < 90) {
                            document.getElementById('certDiasRestantes').className = 'text-yellow-600 font-bold';
                            mostrarAlerta('warning', `O certificado expira em ${diasRestantes} dias. Considere renová-lo em breve.`);
                        } else {
                            document.getElementById('certDiasRestantes').className = 'text-green-600 font-bold';
                        }
                        
                        mostrarAlerta('success', 'Informações do certificado carregadas com sucesso!');
                    } else {
                        mostrarAlerta('error', certData.message);
                    }
                } else {
                    mostrarAlerta('error', data.message);
                }
                
            } catch (error) {
                mostrarAlerta('error', 'Erro ao carregar informações do certificado: ' + error.message);
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        });

        // Cancelar
        document.getElementById('btnCancelar').addEventListener('click', function() {
            if (confirm('Tem certeza que deseja cancelar? Todas as alterações serão perdidas.')) {
                window.location.href = '{{ route("config.sefaz") }}';
            }
        });

        // Validação do formulário
        document.getElementById('sefazForm').addEventListener('submit', function(e) {
            const cnpj = document.getElementById('cnpj').value.replace(/\D/g, '');
            
            if (cnpj.length !== 14) {
                e.preventDefault();
                mostrarAlerta('error', 'CNPJ inválido. Deve conter 14 dígitos.');
                document.getElementById('cnpj').focus();
                return;
            }
            
            // Validar série e números iniciais
            const serieNfce = document.getElementById('serie_nfce').value;
            const numeroNfce = document.getElementById('numero_inicial_nfce').value;
            const serieNfe = document.getElementById('serie_nfe').value;
            const numeroNfe = document.getElementById('numero_inicial_nfe').value;
            
            if (serieNfce < 1 || numeroNfce < 1 || serieNfe < 1 || numeroNfe < 1) {
                e.preventDefault();
                mostrarAlerta('error', 'Série e número inicial devem ser maiores que zero.');
                return;
            }
            
            // Mostrar loading no botão submit
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Salvando...';
        });

        // Formatar campos ao carregar
        const cnpjInput = document.getElementById('cnpj');
        if (cnpjInput.value) {
            formatarCNPJ({ value: cnpjInput.value });
        }
        
        const telefoneInput = document.getElementById('telefone_suporte');
        if (telefoneInput.value) {
            formatarTelefone({ value: telefoneInput.value });
        }
    });
    </script>
</x-app-layout>