<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Produtos</title>
</head>
<body>
    <h1>Cadastro de Produtos</h1>

    @if(session('mensagemSucesso'))
        <p style="color: green;">{{ session('mensagemSucesso') }}</p>
    @endif

    <form action="/produtos" method="POST">
        @csrf
        <label>Nome do Produto:</label><br>
        <input type="text" name="nomeProduto"><br>

        <label>Preço:</label><br>
        <input type="text" name="precoProduto"><br>

        <button type="submit">Salvar</button>
    </form>

    <hr>

    <h2>Produtos Cadastrados</h2>
    <ul>
        @foreach($listaProdutos as $produto)
            <li>{{ $produto->nomeProduto }} - R$ {{ $produto->precoProduto }}</li>
        @endforeach
    </ul>
</body>
</html>