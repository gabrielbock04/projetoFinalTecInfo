<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastrar Funcionário</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../styles/stylesCadFunc.css">

</head>

<body>
    <div class="funcionario-container">
        <h1>Cadastrar Funcionário</h1>
        <form action="processar_funcionario.php" method="POST">
            <label>Nome: <input type="text" name="nome" required></label>
            <label>Sobrenome: <input type="text" name="sobrenome" required></label>
            <label>Email: <input type="email" name="email" required></label>
            <label>Senha: <input type="password" name="senha" required></label>
            <label>Data de Nascimento: <input type="date" name="data_nascimento"></label>
            <label>CPF/CNPJ: <input type="text" name="cpf_cnpj" required></label>
            <label>Sexo:
                <select name="sexo">
                    <option value="M">Masculino</option>
                    <option value="F">Feminino</option>
                </select>
            </label>
            <label>Telefone: <input type="text" name="telefone"></label>
            <label>Endereço: <input type="text" name="endereco"></label>
            <label>Estado Civil: <input type="text" name="estado_civil"></label>
            <label>Raça/Cor: <input type="text" name="raca_cor"></label>
            <label>Escolaridade: <input type="text" name="escolaridade"></label>
            <label>Nacionalidade: <input type="text" name="nacionalidade"></label>
            <label>RG: <input type="text" name="rg"></label>
            <button type="submit">Salvar</button>
        </form>
        <a href="../admin/painel_admin.php">Voltar</a>
    </div>
</body>