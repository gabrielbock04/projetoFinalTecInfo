<?php
session_start();
include_once '../conexao/config.php';

if (!isset($_SESSION['usuario_id']) || !isset($_POST['id'])) {
    exit('erro');
}

$db = (new Database())->getConnection();
$id = (int)$_POST['id'];

// Verifica se o comentário pertence ao usuário
$stmt = $db->prepare("SELECT * FROM comentarios WHERE id = ?");
$stmt->execute([$id]);
$c = $stmt->fetch(PDO::FETCH_ASSOC);

if ($c && $c['usuario_id'] == $_SESSION['usuario_id']) {
    $stmt = $db->prepare("DELETE FROM comentarios WHERE id = ?");
    $stmt->execute([$id]);
    echo 'ok';
} else {
    echo 'erro';
}
