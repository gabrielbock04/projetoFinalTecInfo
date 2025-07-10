<?php
session_start();
include_once '../conexao/config.php';
include_once '../conexao/funcoes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Monta endereço completo
    $endereco_completo = $_POST['endereco_rua'] . ', ' .
        $_POST['endereco_numero'] . ', ' .
        $_POST['endereco_bairro'] . ', ' .
        $_POST['endereco_cidade'] . ' - ' .
        $_POST['endereco_estado'];

    // Sanitização
    $email = trim($_POST['email'] ?? '');
    $cpf_cnpj = trim($_POST['cpf_cnpj'] ?? '');
    $tipo_documento = $_POST['tipo_documento'] ?? '';

    // Remove caracteres não numéricos do CPF/CNPJ
    $cpf_cnpj = preg_replace('/[^0-9]/', '', $cpf_cnpj);

    $dados = [
        'nome' => htmlspecialchars(trim($_POST['nome'] ?? '')),
        'email' => htmlspecialchars($email),
        'telefone' => htmlspecialchars(trim($_POST['telefone'] ?? '')),
        'cpf_cnpj' => $cpf_cnpj,
        'endereco_comercial' => htmlspecialchars($endereco_completo),
        'categoria_anuncio' => htmlspecialchars(trim($_POST['categoria_anuncio'] ?? '')),
        'descricao_empresa' => htmlspecialchars(trim($_POST['descricao_empresa'] ?? '')),
        'senha' => password_hash($_POST['senha'] ?? '', PASSWORD_DEFAULT),
    ];

    // Validação básica
    if (empty($dados['nome']) || empty($dados['email']) || empty($_POST['senha'])) {
        $_SESSION['erro'] = 'Preencha nome, e-mail e senha.';
        header('Location: cadastrar_anunciante.php');
        exit();
    }

    // Validação de CPF/CNPJ obrigatório
    if (empty($cpf_cnpj)) {
        $_SESSION['erro'] = 'CPF/CNPJ é obrigatório.';
        header('Location: cadastrar_anunciante.php');
        exit();
    }

    // Validação de tipo de documento
    if (empty($tipo_documento)) {
        $_SESSION['erro'] = 'Selecione o tipo de documento.';
        header('Location: cadastrar_anunciante.php');
        exit();
    }

    // Validação de tamanho do documento
    if ($tipo_documento === 'CPF' && strlen($cpf_cnpj) !== 11) {
        $_SESSION['erro'] = 'CPF deve ter 11 dígitos.';
        header('Location: cadastrar_anunciante.php');
        exit();
    }

    if ($tipo_documento === 'CNPJ' && strlen($cpf_cnpj) !== 14) {
        $_SESSION['erro'] = 'CNPJ deve ter 14 dígitos.';
        header('Location: cadastrar_anunciante.php');
        exit();
    }

    try {
        $db = (new Database())->getConnection();

        // 🔒 Validação de e-mail duplicado
        $stmt = $db->prepare("SELECT COUNT(*) FROM anunciantes WHERE email = ?");
        $stmt->execute([$dados['email']]);
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['erro'] = 'E-mail já cadastrado!';
            header('Location: cadastrar_anunciante.php');
            exit();
        }

        // 🔒 Validação de CPF/CNPJ duplicado
        $stmt = $db->prepare("SELECT COUNT(*) FROM anunciantes WHERE cpf_cnpj = ?");
        $stmt->execute([$cpf_cnpj]);
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['erro'] = 'CPF/CNPJ já cadastrado!';
            header('Location: cadastrar_anunciante.php');
            exit();
        }

        // Cadastro usando prepared statement
        $query = "INSERT INTO anunciantes (nome, email, telefone, cpf_cnpj, endereco_comercial,
                  categoria_anuncio, descricao_empresa, senha)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);

        if ($stmt->execute([
            $dados['nome'],
            $dados['email'],
            $dados['telefone'],
            $dados['cpf_cnpj'],
            $dados['endereco_comercial'],
            $dados['categoria_anuncio'],
            $dados['descricao_empresa'],
            $dados['senha']
        ])) {
            $_SESSION['sucesso'] = 'Cadastro realizado com sucesso!';
        } else {
            $_SESSION['erro'] = 'Erro ao cadastrar. Tente novamente.';
        }
    } catch (PDOException $e) {
        $_SESSION['erro'] = 'Erro de banco de dados: ' . $e->getMessage();
    }

    header('Location: cadastrar_anunciante.php');
    exit();
} else {
    header('Location: cadastrar_anunciante.php');
    exit();
}
