<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastrar Anunciante</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../styles/stylesCadAnunciante.css">
</head>

<body>
    <div class="funcionario-container">
        <h1>Cadastrar Anunciante</h1>
        <form action="processar_anunciante.php" method="POST">
            <label>Nome ou Empresa: <input type="text" name="nome" required></label>
            <label>Email: <input type="email" name="email" required></label>
            <label>Telefone: <input type="text" name="telefone"></label>
            <label>CPF: <input type="text" name="cpf_cnpj" required></label>
            <!-- <label>CNPJ: <input type="text" name="cpf_cnpj" required></label> -->
            <label>Endereço Comercial: <input type="text" name="endereco_comercial"></label>
            <label>Categoria do Anúncio:
                <select name="categoria_anuncio" required>
                    <option value="">Selecione</option>
                    <option value="História">História</option>
                    <option value="Educação">Educação</option>
                    <option value="Turismo">Turismo</option>
                    <option value="Curiosidades">Curiosidades</option>
                    <option value="Outro">Outro</option>
                </select>
            </label>
            <label>Descrição da Empresa:
                <textarea name="descricao_empresa" rows="3"></textarea>
            </label>
            <label>Senha de Acesso: <input type="password" name="senha" required></label>
            <button type="submit">Salvar</button>
        </form>
        <a href="../index.php">Voltar</a>
    </div>
</body>
</html>
