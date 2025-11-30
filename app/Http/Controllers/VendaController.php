<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use App\Models\VendaItem;
use App\Models\Produto;
use App\Models\Cliente;
use Illuminate\Http\Request;

class VendaController extends Controller
{
    public function index()
    {
        $vendas = Venda::with(['cliente', 'vendedor'])
            ->latest()
            ->paginate(10);
            
        return view('vendas.index', compact('vendas'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nome')->get();
        $produtos = Produto::where('estoque', '>', 0)->orderBy('nome')->get();
        $numeroVenda = Venda::gerarNumeroVenda();
        
        return view('vendas.create', compact('clientes', 'produtos', 'numeroVenda'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'itens' => 'required|array|min:1',
            'itens.*.produto_id' => 'required|exists:produtos,id',
            'itens.*.quantidade' => 'required|integer|min:1',
            'desconto' => 'nullable|numeric|min:0',
            'valor_pago' => 'required|numeric|min:0',
            'forma_pagamento' => 'required|in:dinheiro,cartao,pix,transferencia',
            'observacoes' => 'nullable|string|max:500'
        ]);

        // Iniciar transação para garantir consistência
        return \DB::transaction(function () use ($request) {
            // Calcular total
            $total = 0;
            foreach ($request->itens as $item) {
                $produto = Produto::find($item['produto_id']);
                $subtotal = $produto->preco_venda * $item['quantidade'];
                $total += $subtotal;
            }

            $total -= $request->desconto ?? 0;

            // Criar venda
            $venda = Venda::create([
                'cliente_id' => $request->cliente_id,
                'user_id' => auth()->id(),
                'numero_venda' => Venda::gerarNumeroVenda(),
                'total' => $total,
                'desconto' => $request->desconto ?? 0,
                'valor_pago' => $request->valor_pago,
                'troco' => max(0, $request->valor_pago - $total),
                'forma_pagamento' => $request->forma_pagamento,
                'observacoes' => $request->observacoes,
                'status' => 'concluida'
            ]);

            // Criar itens e baixar estoque
            foreach ($request->itens as $item) {
                $produto = Produto::find($item['produto_id']);
                
                VendaItem::create([
                    'venda_id' => $venda->id,
                    'produto_id' => $item['produto_id'],
                    'quantidade' => $item['quantidade'],
                    'preco_unitario' => $produto->preco_venda,
                    'subtotal' => $produto->preco_venda * $item['quantidade']
                ]);

                // Baixar estoque
                $produto->decrement('estoque', $item['quantidade']);
            }

            return redirect()->route('vendas.show', $venda)
                ->with('success', 'Venda realizada com sucesso!');
        });
    }

    public function show(Venda $venda)
    {
        $venda->load(['cliente', 'vendedor', 'itens.produto']);
        return view('vendas.show', compact('venda'));
    }

    public function destroy(Venda $venda)
    {
        // Estornar estoque antes de excluir
        foreach ($venda->itens as $item) {
            $item->produto->increment('estoque', $item->quantidade);
        }

        $venda->delete();

        return redirect()->route('vendas.index')
            ->with('success', 'Venda cancelada e estoque estornado!');
    }
}