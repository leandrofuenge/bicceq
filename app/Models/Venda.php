<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'user_id',
        'numero_venda',
        'total',
        'desconto',
        'valor_pago',
        'troco',
        'status',
        'forma_pagamento',
        'observacoes'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'desconto' => 'decimal:2',
        'valor_pago' => 'decimal:2',
        'troco' => 'decimal:2',
    ];

    // Relacionamentos
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function itens()
    {
        return $this->hasMany(VendaItem::class);
    }

    // Gera número único da venda
    public static function gerarNumeroVenda()
    {
        $ultimaVenda = self::latest()->first();
        $numero = $ultimaVenda ? intval($ultimaVenda->numero_venda) + 1 : 1;
        return str_pad($numero, 6, '0', STR_PAD_LEFT);
    }
}