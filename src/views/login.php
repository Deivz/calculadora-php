<?php
require 'topo.php'
?>
<?php if (isset($_SESSION['mensagem'])) : ?>
    <div class="container mt-3 alert alert-<?= $_SESSION['tipoMensagem']; ?>">
        <?= $_SESSION['mensagem']; ?>
    </div>
<?php 
    unset($_SESSION['mensagem']);
    unset($_SESSION['tipoMensagem']);
endif ?>

<?php
    if(isset($_SESSION['sucesso'])):
?>
    <div class="container mt-3 alert alert-success">
        <?= $_SESSION['sucesso'] ?>
    </div>
<?php endif ?>

<main>
    <form class="mt-3 container" action="login" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input name="email" type="email" class="form-control id="email" placeholder="nome@exemplo.com" value="<?= $_SESSION['email'] ?>">
        </div>
        <div class="mb-3">
            <label for="senha" class="form-label">Senha</label>
            <input name="senha" type="password" class="form-control" id="senha">
        </div>
        <button type="submit" class="btn btn-primary">Entrar</button>
    </form>
</main>

<?php
    unset($_SESSION['sucesso']);
    unset($_SESSION['email']);
?>

<?php
require 'rodape.php'
?>