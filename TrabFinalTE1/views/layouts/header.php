<?php
// views/layouts/header.php

// Define o tema atual (padrão é 'light')
$theme = $_COOKIE[COOKIE_NAME_THEME] ?? DEFAULT_THEME;
$body_class = ($theme === 'dark') ? 'bg-dark text-white' : 'bg-light text-dark';
$navbar_class = ($theme === 'dark') ? 'navbar-dark bg-dark' : 'navbar-light bg-light';
?>
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="<?= $theme ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? APP_NAME ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        /* Estilos personalizados para o modo escuro */
        .jumbotron {
            padding: 4rem 2rem;
            margin-bottom: 2rem;
            background-color: var(--bs-body-bg);
            border-radius: .3rem;
        }
        /* Ajuste para o modo escuro */
        [data-bs-theme="dark"] .jumbotron {
            background-color: #212529; /* Um pouco mais claro que o body */
            border: 1px solid #495057;
        }
    </style>
</head>
<body class="<?= $body_class ?>">

<nav class="navbar navbar-expand-lg <?= $navbar_class ?> shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>"><?= APP_NAME ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?= BASE_URL ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>carros">Carros</a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>admin/carros/create">Novo Carro</a>
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>toggle-theme">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-moon-stars-fill" viewBox="0 0 16 16">
                            <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.27 7.29 7.29 7.29 1.31 0 2.51-.41 3.5-.97A.768.768 0 0 1 15.858 13a7.208 7.208 0 0 1-3.46.878c-4.021 0-7.29-3.27-7.29-7.29 0-1.31.41-2.51.97-3.5a.768.768 0 0 1-.858-.08zm.205 1.774a.768.768 0 0 0-.858.08 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.27 7.29 7.29 7.29 1.31 0 2.51-.41 3.5-.97a.768.768 0 0 0-.858-.08 7.208 7.208 0 0 1-3.46-.878c-4.021 0-7.29-3.27-7.29-7.29 0-1.31.41-2.51.97-3.5z"/>
                        </svg>
                        Modo <?= ($theme === 'dark') ? 'Claro' : 'Escuro' ?>
                    </a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-sm btn-outline-danger ms-2" href="<?= BASE_URL ?>logout">Sair (<?= $_SESSION['user_email'] ?>)</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-sm btn-outline-success ms-2" href="<?= BASE_URL ?>login">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main>
