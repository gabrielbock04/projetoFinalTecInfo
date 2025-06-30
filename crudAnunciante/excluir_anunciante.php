<?php
session_start();


if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../index.php");
    exit();
}

include_once './conexao/config.php';
include_once './conexao/funcoes.php';

$db = (new Database())->getConnection();
$anunciante = new Anunciante($db);


if (!isset($_GET['id'])) {
    echo "ID do anunciante não fornecido.";
    exit();
}

$id = $_GET['id'];


$anunciante->excluir($id);

header("Location: ../index.php");
exit();
?>
