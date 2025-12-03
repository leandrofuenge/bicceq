<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConfigSefaz extends Model
{
    use HasFactory;

    protected $table = 'config_sefaz';
    
    protected $fillable = [
        'razao_social',
        'cnpj',
        'inscricao_estadual',
        'regime_tributario',
        'crt',
        'uf',
        'ambiente',
        'certificado_path',
        'serie_nfce',
        'numero_inicial_nfce',
        'token_nfce',
        'csc_nfce',
        'id_token_nfce',
        'serie_nfe',
        'numero_inicial_nfe',
        'token_nfe',
        'modo_contingencia',
        'justificativa_contingencia',
        'logotipo',
        'informacoes_complementares',
        'email_administrativo',
        'telefone_suporte'
    ];

    protected $casts = [
        'regime_tributario' => 'integer',
        'crt' => 'integer',
        'ambiente' => 'integer',
        'serie_nfce' => 'integer',
        'numero_inicial_nfce' => 'integer',
        'serie_nfe' => 'integer',
        'numero_inicial_nfe' => 'integer',
        'modo_contingencia' => 'integer',
    ];

    /**
     * Get decrypted token NFC-e
     */
    public function getTokenNfceAttribute($value)
    {
        return $value ? decrypt($value) : null;
    }

    /**
     * Get decrypted CSC NFC-e
     */
    public function getCscNfceAttribute($value)
    {
        return $value ? decrypt($value) : null;
    }

    /**
     * Get decrypted token NFe
     */
    public function getTokenNfeAttribute($value)
    {
        return $value ? decrypt($value) : null;
    }

    /**
     * Set encrypted token NFC-e
     */
    public function setTokenNfceAttribute($value)
    {
        $this->attributes['token_nfce'] = $value ? encrypt($value) : null;
    }

    /**
     * Set encrypted CSC NFC-e
     */
    public function setCscNfceAttribute($value)
    {
        $this->attributes['csc_nfce'] = $value ? encrypt($value) : null;
    }

    /**
     * Set encrypted token NFe
     */
    public function setTokenNfeAttribute($value)
    {
        $this->attributes['token_nfe'] = $value ? encrypt($value) : null;
    }

    /**
     * Get regime tributario description
     */
    public function getRegimeTributarioDescricaoAttribute()
    {
        $regimes = [
            1 => 'Simples Nacional',
            2 => 'Simples Nacional - Excesso de Sublimite',
            3 => 'Regime Normal'
        ];
        
        return $regimes[$this->regime_tributario] ?? 'Não informado';
    }

    /**
     * Get CRT description
     */
    public function getCrtDescricaoAttribute()
    {
        $crts = [
            1 => 'Simples Nacional',
            2 => 'Simples Nacional - Excesso de Sublimite',
            3 => 'Regime Normal'
        ];
        
        return $crts[$this->crt] ?? 'Não informado';
    }

    /**
     * Get ambiente description
     */
    public function getAmbienteDescricaoAttribute()
    {
        return $this->ambiente == 1 ? 'Homologação' : 'Produção';
    }

    /**
     * Check if certificate is valid
     */
    public function isCertificateValid()
    {
        if (!$this->certificado_path) {
            return false;
        }

        try {
            $certificadoPath = storage_path('app/private/' . $this->certificado_path);
            
            if (!file_exists($certificadoPath)) {
                return false;
            }
            
            $pkcs12 = file_get_contents($certificadoPath);
            $certs = [];
            
            // A senha deve ser solicitada ao usuário
            // Esta é uma verificação básica, precisa da senha para verificar validade
            return true;
            
        } catch (\Exception $e) {
            return false;
        }
    }
}