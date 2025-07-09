<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit();
}

include_once '../conexao/config.php';
include_once '../conexao/funcoes.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$db = (new Database())->getConnection();
$noticia = new Noticia($db);

if (isset($_GET['id'])) {
    $n = $noticia->buscarPorId($_GET['id']);

    // Permite somente o autor da notícia OU o administrador
    if ($n['autor'] != $_SESSION['usuario_id'] && $_SESSION['is_admin'] != 1) {
        echo "Você não tem permissão para editar esta notícia.";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $texto = $_POST['noticia'];
    $imagem = $_POST['imagem'];

    $noticia->atualizar($id, $titulo, $texto, $imagem);
    header('Location: ../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Notícia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../styles/stylesEditNot.css">
    <link rel="stylesheet" href="../styles/stylesResponsividade.css">
    <link rel="script" href="../script/scriptEditNot.js">

    <!-- TinyMCE CDN -->
    <script src="https://cdn.tiny.cloud/1/37ybikexkmn7wucbg1x3kgi89eul0az7uq9v07orofaq4hku/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

</head>

<body>
    <div class="editar-container">
        <form method="POST">
            <h2>Editar Notícia</h2>
            <input type="hidden" name="id" value="<?php echo $n['id']; ?>">

            <label>Título:</label>
            <input type="text" name="titulo" value="<?php echo htmlspecialchars($n['titulo']); ?>" required>

            <label>Notícia:</label>
            <textarea name="noticia" rows="10" required><?php echo htmlspecialchars($n['noticia']); ?></textarea>

            <label>Imagem (URL):</label>
            <input type="text" name="imagem" value="<?php echo htmlspecialchars($n['imagem']); ?>">

            <input type="submit" value="Salvar alterações">
        </form>
    </div>
    <a href="javascript:history.back()" class="btn-voltar">Voltar</a>
</body>

</html>