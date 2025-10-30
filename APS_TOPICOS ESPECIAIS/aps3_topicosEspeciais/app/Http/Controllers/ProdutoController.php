<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;

class ProdutoController extends Controller
{
    public function index()
    {
        $listaProdutos = Produto::all();
        return view('produtos', compact('listaProdutos'));
    }

    public function store(Request $requisicao)
    {
        $requisicao->validate([
            'nomeProduto' => 'required|min:3',
            'precoProduto' => 'required|numeric'
        ]);

        $novoProduto = new Produto();
        $novoProduto->nomeProduto = $requisicao->nomeProduto;
        $novoProduto->precoProduto = $requisicao->precoProduto;
        $novoProduto->save();

        return redirect('/produtos')->with('mensagemSucesso', 'Produto cadastrado com sucesso!');
    }
}