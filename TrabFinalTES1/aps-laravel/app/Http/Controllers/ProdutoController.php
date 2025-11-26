<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = Produto::all();
        return view('produtos.index', compact('produtos'));
    }

    public function store(Request $request)
    {
        // 1. Validação dos dados, incluindo a imagem
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            // Validação da imagem: deve ser um arquivo, uma imagem, nos formatos jpg ou png, e ter no máximo 2MB (2048 KB)
            'imagem' => 'nullable|file|image|mimes:jpg,png|max:2048' 
        ]);

        // 2. Lógica de Upload da Imagem
        if ($request->hasFile('imagem')) {
            // Salva o arquivo na pasta 'produtos' dentro de storage/app/public
            // O método 'store' retorna o caminho do arquivo (ex: produtos/nome_aleatorio.jpg)
            $path = $request->file('imagem')->store('produtos', 'public');
            
            // Adiciona o caminho do arquivo aos dados validados para salvar no banco
            $validated['imagem_path'] = $path;
        }

        // 3. Criação do Produto
        Produto::create($validated);

        // 4. Redirecionamento
        return redirect()->route('produtos.index')->with('success', 'Produto cadastrado com sucesso!');
    }

    /**
     * Mostra o formulário para editar um produto específico.
     */
    public function edit($id)
    {
        $produto = Produto::findOrFail($id);
        
        // Você precisará criar a view 'produtos.edit'
        return view('produtos.edit', compact('produto'));
    }

    /**
     * Atualiza o produto no banco de dados.
     */
    public function update(Request $request, $id)
    {
        // Validação dos dados (use a mesma validação do store)
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'imagem' => 'nullable|file|image|mimes:jpg,png|max:2048' 
        ]);

        $produto = Produto::findOrFail($id);

        // Lógica de Upload da Imagem para o update
        if ($request->hasFile('imagem')) {
            // Opcional: Deletar a imagem antiga antes de salvar a nova
            if ($produto->imagem_path) {
                \Storage::disk('public')->delete($produto->imagem_path);
            }
            
            $path = $request->file('imagem')->store('produtos', 'public');
            $validated['imagem_path'] = $path;
        }

        $produto->update($validated);

        // Redireciona para a lista de produtos com mensagem de sucesso
        return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso!');
    }

    /**
     * Remove o produto do banco de dados.
     */
    public function destroy($id)
    {
        $produto = Produto::findOrFail($id);

        // Deletar a imagem do storage antes de deletar o registro
        if ($produto->imagem_path) {
            \Storage::disk('public')->delete($produto->imagem_path);
        }

        $produto->delete();

        // Redireciona para a lista de produtos com mensagem de sucesso
        return redirect()->route('produtos.index')->with('success', 'Produto excluído com sucesso!');
    }
}