<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;
use App\Models\Produto;
use App\Models\Venda; // Se tiver modelo de Venda
use App\Models\Caixa; // Se tiver modelo de Caixa

class DashboardController extends Controller
{
    public function index()
    {
        $dados = [
            'vendasHoje' => $this->getVendasHoje(),
            'valorCaixa' => $this->getValorCaixa(),
            'estoqueBaixo' => Produto::estoqueBaixo()->count(),
            'totalClientes' => Cliente::count(),
            'totalProdutos' => Produto::count(),
        ];

        return view('dashboard', $dados);
    }

    /**
     * Busca o total de vendas do dia atual
     */
    private function getVendasHoje()
    {
        // Se você tiver um modelo Venda
        if (class_exists('App\Models\Venda')) {
            return \App\Models\Venda::whereDate('created_at', today())->sum('total') ?? 0;
        }
        
        // Ou se tiver uma tabela de vendas diretamente
        try {
            return DB::table('vendas')
                ->whereDate('created_at', today())
                ->sum('total') ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Busca o valor atual do caixa
     */
    private function getValorCaixa()
    {
        // Se você tiver um modelo Caixa
        if (class_exists('App\Models\Caixa')) {
            $caixa = \App\Models\Caixa::where('status', 'aberto')->first();
            return $caixa ? $caixa->saldo_final : 0;
        }
        
        // Ou busca direto da tabela
        try {
            $caixa = DB::table('caixas')
                ->where('status', 'aberto')
                ->first();
            return $caixa ? $caixa->saldo_final : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
}