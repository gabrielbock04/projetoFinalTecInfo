<?php
session_start();
include_once '../conexao/config.php';
include_once '../conexao/funcoes.php';

// Verifica se o anunciante está logado
if (!isset($_SESSION['anunciante_id'])) {
    header('Location: ../login.php');
    exit();
}

$id = $_SESSION['anunciante_id'];

$db = (new Database())->getConnection();
$anunciante = new Anunciante($db);

// Executa a exclusão
$excluido = $anunciante->excluir($id);

// Desloga e redireciona
if ($excluido) {
    session_destroy();
    header('Location: ../index.php'); // Ou para uma página de confirmação
    exit();
} else {
    $_SESSION['erro'] = "Erro ao excluir conta.";
    header('Location: editar_anunciante.php');
    exit();
}
