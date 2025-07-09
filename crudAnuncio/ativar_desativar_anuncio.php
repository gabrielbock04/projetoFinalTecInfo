<?php
session_start();
include_once '../conexao/config.php';
include_once '../conexao/funcoes.php';

// Somente administrador pode aprovar/desativar anúncios
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['ativo'])) {
    $db = (new Database())->getConnection();
    $stmt = $db->prepare("UPDATE anuncio SET ativo = :ativo WHERE id = :id");
    $stmt->execute([
        ':ativo' => $_POST['ativo'],
        ':id' => $_POST['id']
    ]);

    $_SESSION['sucesso'] = "Status do anúncio atualizado!";
}

// Redireciona de volta à página anterior
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
