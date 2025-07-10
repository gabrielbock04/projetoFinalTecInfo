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
    $dados = [
        'nome' => htmlspecialchars(trim($_POST['nome'] ?? '')),
        'email' => htmlspecialchars($email),
        'telefone' => htmlspecialchars(trim($_POST['telefone'] ?? '')),
        'cpf_cnpj' => htmlspecialchars(trim($_POST['cpf_cnpj'] ?? '')),
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

        // Cadastro
        $anunciante = new Anunciante($db);
        if ($anunciante->cadastrar($dados)) {
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
