<?php
session_start();
include_once '../conexao/config.php';
include_once '../conexao/funcoes.php';

// Verifica permissão
if (!isset($_SESSION['usuario_id']) && !isset($_SESSION['anunciante_id']) && !isset($_SESSION['is_admin'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    $_SESSION['erro'] = "Requisição inválida ou ID não informado.";
    header('Location: painel_anunciante.php');
    exit();
}

$id = intval($_POST['id']);

$db = (new Database())->getConnection();
$anuncio = new Anuncio($db);

// Verifica se o anúncio existe
$dados = $anuncio->buscarPorId($id);
if (!$dados) {
    $_SESSION['erro'] = "Anúncio não encontrado.";
    header('Location: painel_anunciante.php');
    exit();
}

// Remove imagem associada, se houver
if (!empty($dados['imagem']) && file_exists("../uploads/{$dados['imagem']}")) {
    unlink("../uploads/{$dados['imagem']}");
}

// Exclui do banco
$ok = $anuncio->excluir($id);

if ($ok) {
    $_SESSION['sucesso'] = "Anúncio excluído com sucesso.";
} else {
    $_SESSION['erro'] = "Erro ao excluir o anúncio.";
}

header('Location:../crudAnunciante/painel_anunciante.php');
exit();
