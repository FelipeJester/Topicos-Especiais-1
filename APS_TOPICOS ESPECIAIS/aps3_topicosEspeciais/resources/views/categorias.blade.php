<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Categorias</title>
</head>
<body>
    <h1>Cadastro de Categorias</h1>

    @if(session('mensagemSucesso'))
        <p style="color: green;">{{ session('mensagemSucesso') }}</p>
    @endif

    <form action="/categorias" method="POST">
        @csrf
        <label>Nome da Categoria:</label><br>
        <input type="text" name="nomeCategoria"><br>

        <label>Descrição:</label><br>
        <textarea name="descricaoCategoria"></textarea><br>

        <button type="submit">Salvar</button>
    </form>

    <hr>

    <h2>Categorias Cadastradas</h2>
    <ul>
        @foreach($listaCategorias as $categoria)
            <li>{{ $categoria->nomeCategoria }} - {{ $categoria->descricaoCategoria }}</li>
        @endforeach
    </ul>
</body>
</html>