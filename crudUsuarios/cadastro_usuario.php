<?php

include_once '../conexao/config.php';
include_once '../conexao/funcoes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = new Usuario($db);
    $nome = $_POST['nome'];
    $sexo = $_POST['sexo'];
    $fone = $_POST['fone'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirmarSenha = $_POST['confirmar_senha'];

    if ($senha !== $confirmarSenha) {
        echo "<script>alert('As senhas não coincidem.'); window.history.back();</script>";
    } else {
        $usuario->criar($nome, $sexo, $fone, $email, $senha, $confirmarSenha);
        header('Location: ../login.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Adicionar Usuário</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./styles/cadastro_usuario.css">
    <link rel="stylesheet" href="../styles/stylesCadUsu.css">
    <link rel="stylesheet" href="../styles/stylesResponsividade.css">
    <link rel="stylesheet" href="../styles/stylesTemaEscuro.css">

</head>

<body>
    <div class="container-cadastro">
        <h1>Adicionar Usuário</h1>
        <form method="POST">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" required pattern="[A-Za-zÀ-ÿ\s]+" title="Digite apenas letras."
                oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '')">

            <label>Sexo:</label>
            <label for="masculino">
                <input type="radio" id="masculino" name="sexo" value="M" required> Masculino
            </label>
            <label for="feminino">
                <input type="radio" id="feminino" name="sexo" value="F" required> Feminino
            </label>

            <label for="fone">Fone:</label>
            <input type="text" name="fone" required pattern="\d+" title="Digite apenas números."
                oninput="this.value = this.value.replace(/[^0-9]/g, '')">

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" required>

            <label for="confirmar_senha">Confirmar Senha:</label>
            <input type="password" name="confirmar_senha" required>

            <input type="submit" value="Adicionar">
        </form>
        <a href="../index.php" class="btn-voltar">Voltar</a>
    </div>
    <script src="../script/scriptTemaEscuro.js"></script>
</body>

</html>