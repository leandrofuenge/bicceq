<?php

namespace App\Services;

use App\Models\ConfigSefaz;
use Illuminate\Support\Facades\Storage;
use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use Exception;

class SefazService
{
    private $config;
    private $tools;
    
    public function __construct()
    {
        $this->config = ConfigSefaz::first();
        
        if (!$this->config) {
            throw new Exception('Configurações da SEFAZ não encontradas');
        }
    }
    
    /**
     * Inicializar ferramentas NFe
     */
    public function initNFeTools($certPassword)
    {
        try {
            // Carregar certificado
            $certificatePath = Storage::disk('private')->path($this->config->certificado_path);
            $certificate = Certificate::readPfx(file_get_contents($certificatePath), $certPassword);
            
            // Configurar ferramentas
            $config = [
                "atualizacao" => date('Y-m-d H:i:s'),
                "tpAmb" => $this->config->ambiente, // 1=homologação, 2=produção
                "razaosocial" => $this->config->razao_social,
                "cnpj" => $this->config->cnpj,
                "siglaUF" => $this->config->uf,
                "schemes" => "PL_009_V4",
                "versao" => "4.00",
                "tokenIBPT" => "",
                "CSC" => $this->config->csc_nfce,
                "CSCid" => $this->config->id_token_nfce
            ];
            
            $this->tools = new Tools(json_encode($config), $certificate);
            $this->tools->model('55'); // 55=NF-e, 65=NFC-e
            
            return $this->tools;
            
        } catch (Exception $e) {
            throw new Exception('Erro ao inicializar ferramentas NFe: ' . $e->getMessage());
        }
    }
    
    /**
     * Emitir NFC-e
     */
    public function emitirNFCe($dadosVenda, $certPassword)
    {
        try {
            $this->initNFeTools($certPassword);
            
            // Construir XML da NFC-e
            $xml = $this->construirXMLNFCe($dadosVenda);
            
            // Assinar XML
            $xmlAssinado = $this->tools->signNFe($xml);
            
            // Enviar para SEFAZ
            $response = $this->tools->sefazEnviaLote([$xmlAssinado], '1');
            
            // Consultar recibo
            $recibo = $this->extrairRecibo($response);
            $protocolo = $this->consultarRecibo($recibo);
            
            return [
                'success' => true,
                'xml' => $xmlAssinado,
                'recibo' => $recibo,
                'protocolo' => $protocolo,
                'chave' => $this->extrairChave($xmlAssinado)
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Emitir NF-e
     */
    public function emitirNFe($dadosVenda, $certPassword)
    {
        try {
            $this->initNFeTools($certPassword);
            
            // Construir XML da NF-e
            $xml = $this->construirXMLNFe($dadosVenda);
            
            // Assinar XML
            $xmlAssinado = $this->tools->signNFe($xml);
            
            // Enviar para SEFAZ
            $response = $this->tools->sefazEnviaLote([$xmlAssinado], '1');
            
            // Consultar recibo
            $recibo = $this->extrairRecibo($response);
            $protocolo = $this->consultarRecibo($recibo);
            
            return [
                'success' => true,
                'xml' => $xmlAssinado,
                'recibo' => $recibo,
                'protocolo' => $protocolo,
                'chave' => $this->extrairChave($xmlAssinado)
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Consultar status do serviço
     */
    public function statusServico($certPassword)
    {
        try {
            $this->initNFeTools($certPassword);
            
            $response = $this->tools->sefazStatus();
            
            return [
                'success' => true,
                'data' => $response
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Consultar NF-e/NFC-e por chave
     */
    public function consultarPorChave($chave, $certPassword)
    {
        try {
            $this->initNFeTools($certPassword);
            
            $response = $this->tools->sefazConsultaChave($chave);
            
            return [
                'success' => true,
                'data' => $response
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Cancelar NF-e/NFC-e
     */
    public function cancelar($chave, $justificativa, $certPassword)
    {
        try {
            $this->initNFeTools($certPassword);
            
            // Construir evento de cancelamento
            $response = $this->tools->sefazCancela($chave, $justificativa, '1');
            
            return [
                'success' => true,
                'data' => $response
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Construir XML da NFC-e
     */
    private function construirXMLNFCe($dadosVenda)
    {
        // Implementar construção do XML conforme manual da SEFAZ
        // Esta é uma estrutura simplificada
        
        $xml = new \DOMDocument('1.0', 'UTF-8');
        
        // ... lógica complexa para construir XML ...
        
        return $xml->saveXML();
    }
    
    /**
     * Construir XML da NF-e
     */
    private function construirXMLNFe($dadosVenda)
    {
        // Implementar construção do XML conforme manual da SEFAZ
        // Esta é uma estrutura simplificada
        
        $xml = new \DOMDocument('1.0', 'UTF-8');
        
        // ... lógica complexa para construir XML ...
        
        return $xml->saveXML();
    }
    
    /**
     * Extrair número do recibo da resposta
     */
    private function extrairRecibo($response)
    {
        // Implementar extração do recibo
        return '123456789012345'; // Exemplo
    }
    
    /**
     * Consultar recibo na SEFAZ
     */
    private function consultarRecibo($recibo)
    {
        // Implementar consulta de recibo
        return [
            'protocolo' => '123456789012345',
            'status' => '100',
            'mensagem' => 'Autorizado o uso da NF-e'
        ];
    }
    
    /**
     * Extrair chave do XML
     */
    private function extrairChave($xml)
    {
        // Implementar extração da chave
        return '12345678901234567890123456789012345678901234'; // Exemplo (44 dígitos)
    }
}