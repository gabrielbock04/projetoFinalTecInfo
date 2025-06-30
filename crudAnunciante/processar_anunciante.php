<?php
session_start();
include_once '../conexao/config.php';
include_once '../conexao/funcoes.php';

// Verifica se foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitização básica
    $dados = [
        'nome' => htmlspecialchars(trim($_POST['nome'] ?? '')),
        'email' => htmlspecialchars(trim($_POST['email'] ?? '')),
        'telefone' => htmlspecialchars(trim($_POST['telefone'] ?? '')),
        'cpf_cnpj' => htmlspecialchars(trim($_POST['cpf_cnpj'] ?? '')),
        'endereco_comercial' => htmlspecialchars(trim($_POST['endereco_comercial'] ?? '')),
        'categoria_anuncio' => htmlspecialchars(trim($_POST['categoria_anuncio'] ?? '')),
        'descricao_empresa' => htmlspecialchars(trim($_POST['descricao_empresa'] ?? '')),
        'senha' => password_hash($_POST['senha'] ?? '', PASSWORD_DEFAULT),
    ];

    // Validação básica obrigatória
    if (empty($dados['nome']) || empty($dados['email']) || empty($_POST['senha'])) {
        $_SESSION['erro'] = 'Preencha pelo menos nome, e-mail e senha.';
        header('Location: cadastrar_anunciante.php');
        exit();
    }

    try {
        $db = (new Database())->getConnection();
        $anunciante = new Anunciante($db);

        if ($anunciante->cadastrar($dados)) {
            $_SESSION['sucesso'] = 'Cadastro realizado com sucesso!';
        } else {
            $_SESSION['erro'] = 'Erro ao tentar cadastrar. Tente novamente.';
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
