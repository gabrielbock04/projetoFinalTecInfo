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

    <script src="../script/scriptCep.js"></script>
    <script src="../script/scriptCpfCnpj.js"></script>

</head>

<body>
    <div class="funcionario-container">
        <div class="container-cadastro">
            <h1>Cadastrar Anunciante</h1>
            <form action="processar_anunciante.php" method="POST">

                <label>Nome ou Empresa: <input type="text" name="nome" required></label>
                <label>Email: <input type="email" name="email" required></label>
                <label>Telefone: <input type="text" name="telefone"></label>

                <!-- Tipo de documento -->
                <label>
                    Tipo de Documento:
                    <select id="tipoDocumento" onchange="alternarCpfCnpj()" required>
                        <option value="">Selecione</option>
                        <option value="CPF">CPF</option>
                        <option value="CNPJ">CNPJ</option>
                    </select>
                </label>

                <!-- CPF -->
                <div id="campoCPF" style="display:none;">
                    <label>CPF:
                        <input type="text" name="cpf_cnpj" id="cpfInput" placeholder="Apenas números">
                    </label>
                </div>

                <!-- CNPJ -->
                <div id="campoCNPJ" style="display:none;">
                    <label>CNPJ:
                        <input type="text" name="cpf_cnpj" id="cnpjInput" pattern="\d{14}" placeholder="Apenas números">
                    </label>
                </div>

                <!-- CEP e Endereço Automático -->
                <label>CEP:
                    <input type="text" id="cep" name="cep" pattern="\d{8}" placeholder="Somente números" required>
                </label>

                <label>Rua:
                    <input type="text" id="rua" name="endereco_rua" readonly>
                </label>

                <label>Número:
                    <input type="text" name="endereco_numero" required>
                </label>

                <label>Bairro:
                    <input type="text" id="bairro" name="endereco_bairro" readonly>
                </label>

                <label>Cidade:
                    <input type="text" id="cidade" name="endereco_cidade" readonly>
                </label>

                <label>Estado:
                    <input type="text" id="estado" name="endereco_estado" readonly>
                </label>

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
            <a href="../index.php" class="btn-voltar">Voltar</a>
        </div>
</body>

</html>