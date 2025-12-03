<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfigSefaz;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;

class ConfigSefazController extends Controller
{
    /**
     * Display the SEFAZ configuration form
     */
    public function index()
    {
        $config = ConfigSefaz::first();
        return view('config.sefaz', compact('config'));
    }

    /**
     * Store or update SEFAZ configuration
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'razao_social' => 'required|string|max:255',
            'cnpj' => ['required', 'string', 'size:14', 'regex:/^\d{14}$/'],
            'inscricao_estadual' => 'required|string|max:20',
            'regime_tributario' => 'required|in:1,2,3',
            'crt' => 'required|in:1,2,3',
            'uf' => 'required|string|size:2|in:AC,AL,AP,AM,BA,CE,DF,ES,GO,MA,MT,MS,MG,PA,PB,PR,PE,PI,RJ,RN,RS,RO,RR,SC,SP,SE,TO',
            'ambiente' => 'required|in:1,2',
            
            // Certificado Digital
            'certificado' => 'nullable|file|mimes:pfx,p12|max:5120',
            'senha_certificado' => 'required_with:certificado|string|min:4',
            
            // NFC-e
            'serie_nfce' => 'required|integer|min:1|max:999',
            'numero_inicial_nfce' => 'required|integer|min:1',
            'token_nfce' => 'nullable|string|max:100',
            'csc_nfce' => 'nullable|string|size:36',
            'id_token_nfce' => 'nullable|string|max:10',
            
            // NF-e
            'serie_nfe' => 'required|integer|min:1|max:999',
            'numero_inicial_nfe' => 'required|integer|min:1',
            'token_nfe' => 'nullable|string|max:100',
            
            // Contingência
            'modo_contingencia' => 'nullable|in:1,2,3',
            'justificativa_contingencia' => 'nullable|string|max:255',
        ], [
            'cnpj.regex' => 'CNPJ deve conter apenas números',
            'csc_nfce.size' => 'CSC NFC-e deve ter 36 caracteres',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        
        try {
            $data = $request->all();
            
            // Remover senha do array para não salvar no banco
            $senhaCertificado = $request->senha_certificado;
            unset($data['senha_certificado']);
            
            // Upload do certificado digital
            if ($request->hasFile('certificado')) {
                $certificadoFile = $request->file('certificado');
                $certificadoPath = $certificadoFile->store('certificados', 'private');
                $data['certificado_path'] = $certificadoPath;
                
                // Verificar se o certificado é válido
                $validacao = $this->validarCertificado($certificadoPath, $senhaCertificado, $data['cnpj']);
                
                if (!$validacao['success']) {
                    throw new \Exception('Certificado digital inválido: ' . $validacao['message']);
                }
            }
            
            // Encriptar dados sensíveis usando a encriptação do Laravel
            if ($request->filled('token_nfce')) {
                $data['token_nfce'] = Crypt::encryptString($request->token_nfce);
            }
            if ($request->filled('csc_nfce')) {
                $data['csc_nfce'] = Crypt::encryptString($request->csc_nfce);
            }
            if ($request->filled('token_nfe')) {
                $data['token_nfe'] = Crypt::encryptString($request->token_nfe);
            }
            
            $config = ConfigSefaz::updateOrCreate(
                ['id' => $request->id],
                $data
            );
            
            DB::commit();
            
            return redirect()->route('config.sefaz')
                ->with('success', 'Configurações da SEFAZ salvas com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Remover certificado carregado em caso de erro
            if (isset($certificadoPath) && Storage::disk('private')->exists($certificadoPath)) {
                Storage::disk('private')->delete($certificadoPath);
            }
            
            return redirect()->back()
                ->with('error', 'Erro ao salvar configurações: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Validate digital certificate
     */
    private function validarCertificado($certificadoPath, $senha, $cnpj)
    {
        try {
            $certificadoFullPath = Storage::disk('private')->path($certificadoPath);
            
            // Abrir certificado
            $pkcs12 = file_get_contents($certificadoFullPath);
            $certs = [];
            
            if (!openssl_pkcs12_read($pkcs12, $certs, $senha)) {
                return [
                    'success' => false,
                    'message' => 'Senha incorreta ou certificado inválido'
                ];
            }
            
            // Verificar validade do certificado
            $certInfo = openssl_x509_parse($certs['cert']);
            
            if (!$certInfo) {
                return [
                    'success' => false,
                    'message' => 'Não foi possível ler as informações do certificado'
                ];
            }
            
            // Verificar validade
            if (time() > $certInfo['validTo_time_t']) {
                return [
                    'success' => false,
                    'message' => 'Certificado expirado em ' . date('d/m/Y', $certInfo['validTo_time_t'])
                ];
            }
            
            if (time() < $certInfo['validFrom_time_t']) {
                return [
                    'success' => false,
                    'message' => 'Certificado ainda não é válido. Validade a partir de ' . date('d/m/Y', $certInfo['validFrom_time_t'])
                ];
            }
            
            // Verificar se o certificado corresponde ao CNPJ informado
            $cnpjCertificado = $this->extrairCNPJDoCertificado($certInfo);
            
            if ($cnpjCertificado && $cnpjCertificado !== $cnpj) {
                return [
                    'success' => false,
                    'message' => 'CNPJ do certificado (' . $cnpjCertificado . ') não corresponde ao CNPJ informado'
                ];
            }
            
            // Verificar se é um certificado A1 (arquivo)
            $privateKey = openssl_pkey_get_private($certs['pkey']);
            if (!$privateKey) {
                return [
                    'success' => false,
                    'message' => 'Chave privada do certificado inválida'
                ];
            }
            
            openssl_free_key($privateKey);
            
            return [
                'success' => true,
                'data' => $certInfo
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao validar certificado: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Extract CNPJ from certificate
     */
    private function extrairCNPJDoCertificado($certInfo)
    {
        // O CNPJ pode estar em diferentes campos dependendo do certificado
        $campos = [
            'serialNumber',
            'OU',
            'CN'
        ];
        
        foreach ($campos as $campo) {
            if (isset($certInfo['subject'][$campo])) {
                $valor = $certInfo['subject'][$campo];
                // Extrair apenas números
                $cnpj = preg_replace('/[^0-9]/', '', $valor);
                if (strlen($cnpj) === 14) {
                    return $cnpj;
                }
            }
        }
        
        return null;
    }

    /**
     * Test SEFAZ connection
     */
    public function testConnection(Request $request)
    {
        try {
            $config = ConfigSefaz::first();
            
            if (!$config) {
                return response()->json([
                    'success' => false,
                    'message' => 'Configurações não encontradas'
                ], 404);
            }
            
            // Verificar se tem certificado configurado
            if (!$config->certificado_path) {
                return response()->json([
                    'success' => false,
                    'message' => 'Certificado digital não configurado'
                ], 400);
            }
            
            // Testar conexão com webservice da SEFAZ
            $status = $this->testarConexaoSefaz($config);
            
            return response()->json($status);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test SEFAZ connection
     */
    private function testarConexaoSefaz($config)
    {
        try {
            // URLs de status dos serviços SEFAZ por UF
            $urlsStatus = [
                'SP' => $config->ambiente == 1 
                    ? 'https://homologacao.nfe.fazenda.sp.gov.br/ws/nfestatus.asmx' 
                    : 'https://nfe.fazenda.sp.gov.br/ws/nfestatus.asmx',
                'RJ' => $config->ambiente == 1 
                    ? 'https://homologacao.nfe.fazenda.rj.gov.br/ws/nfestatus.asmx' 
                    : 'https://nfe.fazenda.rj.gov.br/ws/nfestatus.asmx',
                // Adicione outras UFs conforme necessário
            ];
            
            $uf = strtoupper($config->uf);
            
            if (!isset($urlsStatus[$uf])) {
                return [
                    'success' => false,
                    'message' => 'Teste de conexão não disponível para esta UF'
                ];
            }
            
            $url = $urlsStatus[$uf];
            
            // Configurar cURL para teste básico
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_FAILONERROR => true,
                CURLOPT_NOBODY => true, // HEAD request apenas
            ]);
            
            curl_exec($ch);
            
            if (curl_errno($ch)) {
                $error = curl_error($ch);
                curl_close($ch);
                
                return [
                    'success' => false,
                    'message' => 'Erro de conexão: ' . $error
                ];
            }
            
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            // Verificar códigos de resposta aceitáveis
            $codigosValidos = [200, 401, 403, 404, 500];
            
            if (in_array($httpCode, $codigosValidos)) {
                return [
                    'success' => true,
                    'message' => 'Servidor SEFAZ respondendo',
                    'data' => [
                        'http_code' => $httpCode,
                        'url' => $url,
                        'ambiente' => $config->ambiente == 1 ? 'Homologação' : 'Produção',
                        'uf' => $uf
                    ]
                ];
            }
            
            return [
                'success' => false,
                'message' => "Servidor retornou código HTTP não esperado: {$httpCode}"
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get certificate information
     */
    public function getCertificateInfo(Request $request)
    {
        try {
            $config = ConfigSefaz::first();
            
            if (!$config || !$config->certificado_path) {
                return response()->json([
                    'success' => false,
                    'message' => 'Certificado não configurado'
                ], 404);
            }
            
            // Requer senha via request para validação temporária
            $senha = $request->input('password');
            
            if (!$senha) {
                return response()->json([
                    'success' => false,
                    'message' => 'Senha do certificado é necessária'
                ], 400);
            }
            
            $certificadoPath = Storage::disk('private')->path($config->certificado_path);
            
            if (!file_exists($certificadoPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Arquivo do certificado não encontrado'
                ], 404);
            }
            
            $pkcs12 = file_get_contents($certificadoPath);
            $certs = [];
            
            if (!openssl_pkcs12_read($pkcs12, $certs, $senha)) {
                // Limpar senha da memória
                unset($senha, $pkcs12);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Senha incorreta ou certificado inválido'
                ], 401);
            }
            
            $certInfo = openssl_x509_parse($certs['cert']);
            
            if (!$certInfo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não foi possível ler as informações do certificado'
                ], 500);
            }
            
            // Extrair CNPJ do certificado
            $cnpjCertificado = $this->extrairCNPJDoCertificado($certInfo);
            
            $dadosCertificado = [
                'nome' => $certInfo['subject']['CN'] ?? 'Não informado',
                'validade_de' => date('d/m/Y', $certInfo['validFrom_time_t']),
                'validade_ate' => date('d/m/Y', $certInfo['validTo_time_t']),
                'cpf_cnpj' => $cnpjCertificado ?? 'Não identificado',
                'emissor' => $certInfo['issuer']['CN'] ?? 'Não informado',
                'dias_restantes' => max(0, ceil(($certInfo['validTo_time_t'] - time()) / 86400)),
                'valido' => time() <= $certInfo['validTo_time_t'] && time() >= $certInfo['validFrom_time_t'],
                'tipo' => isset($certInfo['extensions']['keyUsage']) ? 'A1' : 'Desconhecido'
            ];
            
            // Limpar dados sensíveis da memória
            unset($certs, $senha, $pkcs12);
            
            return response()->json([
                'success' => true,
                'data' => $dadosCertificado
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate certificate with password
     */
    public function validateCertificate(Request $request)
    {
        try {
            $config = ConfigSefaz::first();
            
            if (!$config || !$config->certificado_path) {
                return response()->json([
                    'success' => false,
                    'message' => 'Certificado não configurado'
                ], 404);
            }
            
            $senha = $request->input('password');
            
            if (!$senha) {
                return response()->json([
                    'success' => false,
                    'message' => 'Senha do certificado é necessária'
                ], 400);
            }
            
            $certificadoPath = Storage::disk('private')->path($config->certificado_path);
            
            // Validar certificado
            $validacao = $this->validarCertificado(
                $config->certificado_path,
                $senha,
                $config->cnpj
            );
            
            return response()->json($validacao);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete certificate
     */
    public function deleteCertificate(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $config = ConfigSefaz::first();
            
            if (!$config) {
                return response()->json([
                    'success' => false,
                    'message' => 'Configuração não encontrada'
                ], 404);
            }
            
            // Verificar senha para exclusão
            if (!$request->has('confirm_password')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Confirmação de senha necessária'
                ], 400);
            }
            
            // Verificar se a senha está correta
            $senha = $request->input('confirm_password');
            $certificadoPath = $config->certificado_path;
            
            if ($certificadoPath && Storage::disk('private')->exists($certificadoPath)) {
                $pkcs12 = file_get_contents(Storage::disk('private')->path($certificadoPath));
                $certs = [];
                
                if (!openssl_pkcs12_read($pkcs12, $certs, $senha)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Senha incorreta'
                    ], 401);
                }
            }
            
            // Remover arquivo do certificado
            if ($certificadoPath && Storage::disk('private')->exists($certificadoPath)) {
                Storage::disk('private')->delete($certificadoPath);
            }
            
            // Atualizar registro
            $config->update([
                'certificado_path' => null
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Certificado removido com sucesso'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover certificado: ' . $e->getMessage()
            ], 500);
        }
    }
}