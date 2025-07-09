<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}
include_once '../conexao/config.php';
include_once '../conexao/funcoes.php';


$usuario = new Usuario($db);
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $usuario->deletar($id);
    header('Location: ../admin/painel_admin.php');

    exit();
}
?>
<a href="javascript:history.back()" class="btn-voltar">Voltar</a>
