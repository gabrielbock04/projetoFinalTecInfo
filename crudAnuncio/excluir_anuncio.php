<?php
session_start();
include_once '../conexao/config.php';
include_once '../conexao/funcoes.php';

// Verifica permissão
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['tipo'], ['admin', 'anunciante'])) {
    header('Location: ../login.php');
    exit();
}

$db = (new Database())->getConnection();
$anuncio = new Anuncio($db);

// Verifica se o ID foi enviado
if (!isset($_GET['id'])) {
    $_SESSION['erro'] = "ID do anúncio não informado.";
    header('Location: ./');
    exit();
}

$id = intval($_GET['id']);

// Busca o anúncio antes de excluir (para remover imagem, se tiver)
$dados = $anuncio->buscarPorId($id);

if (!$dados) {
    $_SESSION['erro'] = "Anúncio não encontrado.";
    header('Location: ../painel_anunciante.php');
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

header('Location: ../painel_anunciante.php'); // Redireciona para listagem ou outra página
exit();
