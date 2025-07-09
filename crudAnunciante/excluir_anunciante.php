<?php
session_start();
include_once '../conexao/config.php';
include_once '../conexao/funcoes.php';

// Verifica se o anunciante está logado
if (!isset($_SESSION['anunciante_id'])) {
    header('Location: ../login.php');
    exit();
}

$id = $_SESSION['anunciante_id'];

$db = (new Database())->getConnection();
$anunciante = new Anunciante($db);

// Executa a exclusão
$excluido = $anunciante->excluir($id);

// Desloga e redireciona
if ($excluido) {
    session_destroy();
    header('Location: ../index.php'); // Ou para uma página de confirmação
    exit();
} else {
    $_SESSION['erro'] = "Erro ao excluir conta.";
    header('Location: editar_anunciante.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Anunciante - <?php echo $anunciante->getNome(); ?></title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../styles/stylesTemaEscuro.css">
</head>
<body>
    <div class="container">
        <h1>Excluir Anunciante - <?php echo $anunciante->getNome(); ?></h1>
        <p>Tem certeza de que deseja excluir sua conta? Esta ação é irreversível.</p>
        <form method="post" action="">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <button type="submit" class="btn-excluir">Excluir Conta</button>
            <a href="javascript:history.back()" class="btn-voltar">Voltar</a>
        </form>
    </div>
    <script src="../script/scriptTemaEscuro.js"></script>
</body>
</html>
