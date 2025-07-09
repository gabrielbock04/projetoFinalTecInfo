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
    <script src="../script/scriptCpfFuncionario.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../styles/stylesCadFunc.css">
    <link rel="stylesheet" href="../styles/stylesResponsividade.css">
    <link rel="stylesheet" href="../styles/stylesTemaEscuro.css">

</head>

<body>
    <div class="funcionario-container">
        <h1>Cadastrar Funcionário</h1>
        <form action="processar_funcionario.php" method="POST" onsubmit="return validarFormulario()">
            <label>Nome: <input type="text" name="nome" required minlength="3"></label>
            <label>Sobrenome: <input type="text" name="sobrenome" required></label>
            <label>Email: <input type="email" name="email" required></label>
            <label>Senha: <input type="password" name="senha" required minlength="6"></label>
            <label>Data de Nascimento: <input type="date" name="data_nascimento" required></label>

            <!-- Tipo de Documento -->
            <label>Tipo de Documento:
                <select id="tipoDocumento" onchange="alternarCampos()" required>
                    <option value="">Selecione</option>
                    <option value="cpf">CPF</option>
                    <option value="cnpj">CNPJ</option>
                </select>
            </label>

            <!-- Campo CPF -->
            <div id="campoCPF" style="display:none;">
                <label>CPF:
                    <input type="text" name="cpf" id="cpf" pattern="\\d{11}" placeholder="Apenas números">
                </label>
            </div>

            <!-- Campo CNPJ -->
            <div id="campoCNPJ" style="display:none;">
                <label>CNPJ:
                    <input type="text" name="cnpj" id="cnpj" pattern="\\d{14}" placeholder="Apenas números">
                </label>
            </div>

            <!-- Campo oculto para enviar o que for escolhido -->
            <input type="hidden" name="cpf_cnpj" id="cpfCnpjFinal">

            <label>Sexo:
                <select name="sexo" required>
                    <option value="M">Masculino</option>
                    <option value="F">Feminino</option>
                </select>
            </label>

            <label>Telefone: <input type="text" name="telefone" required></label>
            <label>Endereço: <input type="text" name="endereco" required></label>
            <label>Estado Civil: <input type="text" name="estado_civil"></label>
            <label>Raça/Cor: <input type="text" name="raca_cor"></label>
            <label>Escolaridade: <input type="text" name="escolaridade"></label>
            <label>Nacionalidade: <input type="text" name="nacionalidade"></label>
            <label>RG: <input type="text" name="rg"></label>
            <label>Foto de Perfil:
                <input type="file" name="foto" accept="image/*">
            </label>


            <button type="submit">Salvar</button>
        </form>

        <a href="../admin/painel_admin.php">Voltar</a>
    </div>
    <script src="../script/scriptTemaEscuro.js"></script>
</body>