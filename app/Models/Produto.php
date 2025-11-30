<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory; // ← CORRIGIDO: use Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'codigo_barras',
        'categoria',
        'preco_custo',
        'preco_venda',
        'estoque',
        'estoque_minimo',
        'ativo'
    ];

    protected $casts = [
        'preco_custo' => 'decimal:2',
        'preco_venda' => 'decimal:2',
        'ativo' => 'boolean'
    ];

    // Scope para produtos ativos
    public function scopeAtivo($query)
    {
        return $query->where('ativo', true);
    }

    // Scope para produtos com estoque baixo
    public function scopeEstoqueBaixo($query)
    {
        return $query->whereRaw('estoque <= estoque_minimo');
    }

    // Verificar se está com estoque baixo
    public function getEstoqueBaixoAttribute()
    {
        return $this->estoque <= $this->estoque_minimo;
    }
}