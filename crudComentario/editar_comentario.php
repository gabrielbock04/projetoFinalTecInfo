<?php
session_start();
include_once '../conexao/config.php';

if (!isset($_SESSION['usuario_id']) || !isset($_POST['id'], $_POST['comentario'])) {
    exit('erro');
}

$db = (new Database())->getConnection();
$id = (int)$_POST['id'];
$comentario = trim($_POST['comentario']);

// Verifica se o comentário pertence ao usuário
$stmt = $db->prepare("SELECT * FROM comentarios WHERE id = ?");
$stmt->execute([$id]);
$c = $stmt->fetch(PDO::FETCH_ASSOC);

if ($c && $c['usuario_id'] == $_SESSION['usuario_id']) {
    $stmt = $db->prepare("UPDATE comentarios SET comentario = ?, data = NOW() WHERE id = ?");
    $stmt->execute([$comentario, $id]);
    echo 'ok';
} else {
    echo 'erro';
}
