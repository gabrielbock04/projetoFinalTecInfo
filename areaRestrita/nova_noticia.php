<?php
session_start();
if (
    !isset($_SESSION['usuario_id']) ||
    (
        (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) &&
        (!isset($_SESSION['eh_funcionario']) || $_SESSION['eh_funcionario'] != true)
    )
) {
    header('Location: ../login.php');
    exit();
}


include_once '../conexao/config.php';
include_once '../conexao/funcoes.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit();
}

$db = (new Database())->getConnection();
$noticia = new Noticia($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $texto = $_POST['noticia']; // agora com HTML do TinyMCE
    $imagem = $_POST['imagem'] ?? null;
    $autor = $_SESSION['usuario_id'];
    $data = date('Y-m-d H:i:s');

    $noticia->criar($titulo, $texto, $imagem, $data, $autor);
    header('Location: ../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Nova Notícia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../styles/stylesNovaNoticia.css">
    <link rel="stylesheet" href="../styles/stylesResponsividade.css">
    <link rel="script" href="../script/scriptNovaNot.js">
    <script src="https://cdn.tiny.cloud/1/37ybikexkmn7wucbg1x3kgi89eul0az7uq9v07orofaq4hku/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

</head>

<body>
    <div class="container-nova-noticia">
        <h2>Publicar Nova Notícia</h2>
        <form method="POST">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" id="titulo" required>

            <label for="noticia">Conteúdo:</label>
            <textarea id="noticia" name="noticia" rows="10" required></textarea>

            <label for="imagem">Imagem principal (URL):</label>
            <input type="text" name="imagem" id="imagem">

            <input type="submit" value="Publicar" onclick="tinymce.get('noticia').save();">
        </form>
        <a href="../index.php" class="btn-voltar">Voltar</a>
    </div>
</body>

</html>