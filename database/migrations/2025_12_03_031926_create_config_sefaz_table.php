<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigSefazTable extends Migration
{
    public function up()
    {
        Schema::create('config_sefaz', function (Blueprint $table) {
            $table->id();
            
            // Dados da Empresa
            $table->string('razao_social');
            $table->string('cnpj', 14)->unique();
            $table->string('inscricao_estadual', 20);
            $table->tinyInteger('regime_tributario')->comment('1=Simples Nacional, 2=Excesso Sublimite, 3=Regime Normal');
            $table->tinyInteger('crt')->comment('1=Simples Nacional, 2=Excesso Sublimite, 3=Regime Normal');
            $table->char('uf', 2);
            $table->tinyInteger('ambiente')->default(1)->comment('1=Homologação, 2=Produção');
            
            // Certificado Digital
            $table->string('certificado_path')->nullable();
            
            // NFC-e
            $table->integer('serie_nfce')->default(1);
            $table->integer('numero_inicial_nfce')->default(1);
            $table->text('token_nfce')->nullable()->encrypted();
            $table->text('csc_nfce')->nullable()->encrypted();
            $table->string('id_token_nfce', 10)->nullable();
            
            // NF-e
            $table->integer('serie_nfe')->default(1);
            $table->integer('numero_inicial_nfe')->default(1);
            $table->text('token_nfe')->nullable()->encrypted();
            
            // Contingência
            $table->tinyInteger('modo_contingencia')->nullable()->comment('1=FS, 2=SP, 3=Offline');
            $table->string('justificativa_contingencia', 255)->nullable();
            
            // Informações Adicionais
            $table->string('logotipo')->nullable();
            $table->text('informacoes_complementares')->nullable();
            $table->string('email_administrativo')->nullable();
            $table->string('telefone_suporte')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('config_sefaz');
    }
}