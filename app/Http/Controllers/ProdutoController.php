<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $query = Produto::query();
        
        // Busca
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('codigo_barras', 'like', "%{$search}%")
                  ->orWhere('categoria', 'like', "%{$search}%");
            });
        }

        // Filtro por estoque baixo
        if ($request->has('estoque_baixo')) {
            $query->whereRaw('estoque <= estoque_minimo');
        }

        $produtos = $query->latest()->paginate(15);
        return view('produtos.index', compact('produtos'));
    }

    public function create()
    {
        return view('produtos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'codigo_barras' => 'nullable|string|unique:produtos',
            'categoria' => 'nullable|string|max:100',
            'preco_custo' => 'nullable|numeric|min:0',
            'preco_venda' => 'required|numeric|min:0',
            'estoque' => 'required|integer|min:0',
            'estoque_minimo' => 'required|integer|min:0',
        ]);

        Produto::create($request->all());

        return redirect()->route('produtos.index')
            ->with('success', 'Produto cadastrado com sucesso!');
    }

    public function show(Produto $produto)
    {
        return view('produtos.show', compact('produto'));
    }

    public function edit(Produto $produto)
    {
        return view('produtos.edit', compact('produto'));
    }

    public function update(Request $request, Produto $produto)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'codigo_barras' => 'nullable|string|unique:produtos,codigo_barras,' . $produto->id,
            'categoria' => 'nullable|string|max:100',
            'preco_custo' => 'nullable|numeric|min:0',
            'preco_venda' => 'required|numeric|min:0',
            'estoque' => 'required|integer|min:0',
            'estoque_minimo' => 'required|integer|min:0',
        ]);

        $produto->update($request->all());

        return redirect()->route('produtos.index')
            ->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Produto $produto)
    {
        $produto->delete();

        return redirect()->route('produtos.index')
            ->with('success', 'Produto excluído com sucesso!');
    }
}