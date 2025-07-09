<?php
session_start();
include_once './conexao/config.php';
include_once './conexao/funcoes.php';

$db = (new Database())->getConnection();
$usuario = new Usuario($db);

$mensagem = "";

//verifica se as senhas são iguais + redefinir senha
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'];
    $senha = $_POST['senha'];
    $confirmar = $_POST['confirmar'];

    if ($senha !== $confirmar) {
        $mensagem = "As senhas não coincidem.";
    } else {
        $usuarioEncontrado = $usuario->verificarCodigo($codigo);

        if ($usuarioEncontrado) {
            if ($usuario->redefinirSenha($codigo, $senha)) {
                $mensagem = "Senha redefinida com sucesso! <a href='login.php'>Clique aqui para entrar</a>";
            } else {
                $mensagem = "Erro ao redefinir a senha.";
            }
        } else {
            $mensagem = "Código inválido.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./styles/stylesVerifCod.css">
    <link rel="stylesheet" href="./styles/stylesResponsividade.css">
    <link rel="stylesheet" href="./styles/stylesTemaEscuro.css">

</head>

<body>
    <div class="recuperar-container">
        <h1>Redefinir Senha</h1>

        <?php if (!empty($mensagem)) echo "<p>$mensagem</p>"; ?>

        <form method="POST">
            <label for="codigo">Código de Verificação:</label>
            <input type="text" name="codigo" required>

            <label for="senha">Nova Senha:</label>
            <input type="password" name="senha" required>

            <label for="confirmar">Confirmar Nova Senha:</label>
            <input type="password" name="confirmar" required>

            <input type="submit" value="Redefinir Senha">
        </form>
    </div>
    <script src="./script/scriptTemaEscuro.js"></script>
</body>

</html>