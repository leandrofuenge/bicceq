<?php

namespace Database\Seeders;

use App\Models\Produto;
use Illuminate\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    public function run()
    {
        Produto::create([
            'nome' => 'Notebook Dell Inspiron',
            'descricao' => 'Notebook 15" i5 8GB RAM 256GB SSD',
            'preco_venda' => 2899.90,
            'estoque' => 8,
            'estoque_minimo' => 2
        ]);

        Produto::create([
            'nome' => 'Mouse Wireless Logitech',
            'descricao' => 'Mouse sem fio 2.4GHz',
            'preco_venda' => 79.90,
            'estoque' => 25,
            'estoque_minimo' => 5
        ]);

        Produto::create([
            'nome' => 'Teclado Mecânico Redragon',
            'descricao' => 'Teclado mecânico RGB switches blue',
            'preco_venda' => 299.90,
            'estoque' => 12,
            'estoque_minimo' => 3
        ]);

        Produto::create([
            'nome' => 'Monitor Samsung 24"',
            'descricao' => 'Monitor LED 24 polegadas Full HD',
            'preco_venda' => 699.90,
            'estoque' => 6,
            'estoque_minimo' => 2
        ]);

        Produto::create([
            'nome' => 'Headphone Sony WH-1000XM4',
            'descricao' => 'Fone de ouvido wireless cancelamento de ruído',
            'preco_venda' => 1499.90,
            'estoque' => 4,
            'estoque_minimo' => 1
        ]);
    }
}