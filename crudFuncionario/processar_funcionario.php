<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../index.php");
    exit();
}

include_once '../conexao/config.php';
include_once '../conexao/funcoes.php';

$db = (new Database())->getConnection();

// Verifica se o e-mail já existe
$verifica = $db->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
$verifica->execute([$_POST['email']]);
if ($verifica->fetchColumn() > 0) {
    $_SESSION['erro'] = "E-mail já cadastrado.";
    header("Location: cadastrar_funcionario.php");
    exit();
}


// Usa o campo oculto que consolida CPF ou CNPJ
$cpf_cnpj = $_POST['cpf_cnpj'] ?? null;


// Upload da foto
$foto = null;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $foto = uniqid('func_') . "." . $ext;
    move_uploaded_file($_FILES['foto']['tmp_name'], '../uploads/' . $foto);
}

// 1. Cadastrar usuário
$senha_hash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
$stmt = $db->prepare("INSERT INTO usuarios (nome, email, senha, is_admin) VALUES (?, ?, ?, 0)");
$stmt->execute([$_POST['nome'], $_POST['email'], $senha_hash]);
$usuario_id = $db->lastInsertId();

// 2. Cadastrar funcionário
$stmt2 = $db->prepare("INSERT INTO funcionarios
    (usuario_id, nome, sobrenome, data_nascimento, cpf_cnpj, sexo, telefone, email, endereco, estado_civil, raca_cor, escolaridade, nacionalidade, rg, foto)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt2->execute([
    $usuario_id,
    $_POST['nome'],
    $_POST['sobrenome'],
    $_POST['data_nascimento'],
    $cpf_cnpj,
    $_POST['sexo'],
    $_POST['telefone'],
    $_POST['email'],
    $_POST['endereco'],
    $_POST['estado_civil'],
    $_POST['raca_cor'],
    $_POST['escolaridade'],
    $_POST['nacionalidade'],
    $_POST['rg'],
    $foto
]);

$_SESSION['sucesso'] = "Funcionário cadastrado com sucesso!";
header("Location: ../admin/painel_admin.php");
exit();
