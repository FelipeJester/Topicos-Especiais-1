<?php
// views/carros/index.php
include ROOT_PATH . '/views/layouts/header.php';
?>

<div class="container mt-5">
    <h1><?= $title ?? 'Nossos Carros' ?></h1>

    <?php if (isset($flash) && $flash): ?>
        <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
            <?= $flash['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="<?= BASE_URL ?>admin/carros/create" class="btn btn-success mb-3">Cadastrar Novo Carro</a>
    <?php endif; ?>

    <?php if (empty($carros)): ?>
        <div class="alert alert-info">Nenhum carro cadastrado.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Ano</th>
                        <th>Cor</th>
                        <th>Preço</th>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <th>Ações</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carros as $carro): ?>
                        <tr>
                            <td><?= $carro['id'] ?></td>
                            <td><?= htmlspecialchars($carro['marca']) ?></td>
                            <td><?= htmlspecialchars($carro['modelo']) ?></td>
                            <td><?= $carro['ano'] ?></td>
                            <td><?= htmlspecialchars($carro['cor']) ?></td>
                            <td>R$ <?= number_format($carro['preco'], 2, ',', '.') ?></td>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <td>
                                    <a href="<?= BASE_URL ?>admin/carros/edit/<?= $carro['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                                    <form action="<?= BASE_URL ?>admin/carros/delete/<?= $carro['id'] ?>" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este carro?');">
                                        <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php
include ROOT_PATH . '/views/layouts/footer.php';
?>
