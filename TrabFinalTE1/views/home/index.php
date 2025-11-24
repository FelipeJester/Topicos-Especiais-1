<?php
// views/home/index.php
include ROOT_PATH . '/views/layouts/header.php';
?>

<div class="container mt-5">
    <?php if (isset($flash) && $flash): ?>
        <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
            <?= $flash['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="jumbotron">
        <h1 class="display-4"><?= $title ?? APP_NAME ?></h1>
        <p class="lead">Este é o sistema de Concessionária desenvolvido em PHP Puro com arquitetura MVC e PostgreSQL.</p>
        <hr class="my-4">
        <p>Utilize o menu de navegação para acessar as funcionalidades.</p>
        <a class="btn btn-primary btn-lg" href="<?= BASE_URL ?>carros" role="button">Ver Carros</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a class="btn btn-success btn-lg" href="<?= BASE_URL ?>admin/carros/create" role="button">Cadastrar Novo Carro</a>
        <?php else: ?>
            <a class="btn btn-secondary btn-lg" href="<?= BASE_URL ?>login" role="button">Login Administrativo</a>
        <?php endif; ?>
    </div>
</div>

<?php
include ROOT_PATH . '/views/layouts/footer.php';
?>
