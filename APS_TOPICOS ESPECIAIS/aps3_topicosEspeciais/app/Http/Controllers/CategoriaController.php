<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function index()
    {
        $listaCategorias = Categoria::all();
        return view('categorias', compact('listaCategorias'));
    }

    public function store(Request $requisicao)
    {
        $requisicao->validate([
            'nomeCategoria' => 'required|min:3'
        ]);

        $novaCategoria = new Categoria();
        $novaCategoria->nomeCategoria = $requisicao->nomeCategoria;
        $novaCategoria->descricaoCategoria = $requisicao->descricaoCategoria;
        $novaCategoria->save();

        return redirect('/categorias')->with('mensagemSucesso', 'Categoria cadastrada com sucesso!');
    }
}