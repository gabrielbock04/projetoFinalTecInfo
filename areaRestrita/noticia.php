<?php
session_start();
include_once '../conexao/config.php';
include_once '../conexao/funcoes.php';

if (!isset($_GET['id'])) {
    echo "Notícia não encontrada.";
    exit();
}

$db = (new Database())->getConnection();
$noticiaObj = new Noticia($db);
$comentarioObj = new Comentario($db);

$noticia = $noticiaObj->buscarPorId($_GET['id']);

if (!$noticia) {
    echo "Notícia não encontrada.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentar']) && isset($_SESSION['usuario_id'])) {
    $comentario = trim($_POST['comentario']);
    if (!empty($comentario)) {
        $comentarioObj->adicionar($_GET['id'], $_SESSION['usuario_id'], $comentario);
        header("Location: noticia.php?id=" . $_GET['id']);
        exit();
    }
}

$comentarios = $comentarioObj->listarPorNoticia($noticia['id']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($noticia['titulo']) ?></title>
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/stylesResponsividade.css">
    <link rel="stylesheet" href="../styles/stylesTemaEscuro.css">

    <script src="../script/scriptComentario.js"></script>
    <script src="../script/scriptExcluirComentario.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="../script/scriptGeraPdf.js"></script>
    <script src="../script/scriptTemaEscuro.js"></script>

</head>

<body>
    <header class="header">
        <a href="../index.php">
            <img src="../img/logo.png" alt="logo" class="logo">
        </a>
        <nav class="header-nav">
            <a href="index.php" class="active">Notícias</a>
            <a href="#">Fatos Históricos</a>
            <a href="#">Curiosidades</a>
            <a href="#">Comunidade</a>
            <a href="#">Contato</a>
        </nav>
        <div class="header-actions">
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <form action="./logout.php" method="post" style="display:inline;">
                    <button class="btnEntrar" type="submit">Sair</button>
                </form>
            <?php else: ?>
                <a href="../login.php"><button class="btn btnEntrar" type="button">Entrar</button></a>
                <a href="../crudUsuarios/cadastro_usuario.php"><button class="btn btnRegistrar registrar" type="button">Registrar-se</button></a>
            <?php endif; ?>
        </div>
    </header>


    <main class="main-content" style="max-width: 800px; margin: auto; background: white;">
        <button id="toggle-dark" style="margin: 16px 0 0 16px; padding: 6px 12px; border-radius: 12px;">Alterar o Tema</button>
        <div id="conteudo-pdf">
            <h1><?= htmlspecialchars($noticia['titulo']) ?></h1>
            <p>
                <strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($noticia['data'])) ?>
                <?php if (!empty($noticia['autor_nome'])): ?>
                    — <strong>Autor:</strong> <?= htmlspecialchars($noticia['autor_nome']) ?>
                <?php endif; ?>

                <?php
                $caminho_foto = __DIR__ . '/' . $noticia['autor_foto'];
                $url_foto = './' . htmlspecialchars($noticia['autor_foto']);
                if (!empty($noticia['autor_foto']) && file_exists($caminho_foto)) {
                    echo "<img src=\"$url_foto\" alt=\"Foto do autor\" style=\"width:40px;height:40px;border-radius:50%;object-fit:cover;margin-left:10px;\">";
                }
                ?>

                <?php if ($noticia['imagem']): ?>
                    <img src="<?= htmlspecialchars($noticia['imagem']) ?>" alt="Imagem" style="max-width:100%; margin: 20px 0;">
                <?php endif; ?>
            </p>

            <div class="conteudo-noticia" style="font-size: 1.2rem;">
                <?= $noticia['noticia'] ?>
            </div>
        </div>


        <button id="gerar-pdf" style="padding: 10px 18px; border-radius: 12px; background: #7a4a2e; color: white; border: none;">
            Gerar PDF
        </button>

        <hr>
        <h3>Comentários:</h3>

        <?php if ($comentarios): ?>
            <?php foreach ($comentarios as $c): ?>
                <div style="background:#f2f2f2;padding:10px;margin-bottom:8px;border-radius:6px;" id="comentario-<?= $c['id'] ?>">

                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 5px;">
                        <?php
                        $foto_url = $c['foto']; // já inclui 'uploads/'
                        $foto_fisico = __DIR__ . '/' . $c['foto'];
                        ?>

                        <?php if (!empty($c['foto']) && file_exists($foto_fisico)): ?>
                            <img src="<?= htmlspecialchars($foto_url) ?>"
                                alt="Foto do autor"
                                style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
                        <?php else: ?>
                            <div style="width:40px; height:40px; border-radius:50%; background:#ccc;"></div>
                        <?php endif; ?>

                        <strong><?= htmlspecialchars($c['nome']) ?></strong>
                        <small style="color:gray; margin-left:auto;"><?= date('d/m/Y H:i', strtotime($c['data'])) ?></small>
                    </div>


                    <p class="texto-comentario"><?= nl2br(htmlspecialchars($c['comentario'])) ?></p>

                    <?php if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] == $c['usuario_id']): ?>
                        <button onclick="mostrarEdicao(<?= $c['id'] ?>)">Editar</button>
                        <button onclick="excluirComentario(<?= $c['id'] ?>)">Excluir</button>

                        <div id="edicao-<?= $c['id'] ?>" style="display:none; margin-top:8px;">
                            <textarea id="texto-<?= $c['id'] ?>" rows="2" style="width:100%;"><?= htmlspecialchars($c['comentario']) ?></textarea>
                            <button onclick="salvarComentario(<?= $c['id'] ?>)">Salvar</button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color:gray;">Nenhum comentário ainda.</p>
        <?php endif; ?>


        <?php if (isset($_SESSION['usuario_id'])): ?>
            <form method="post" style="margin-top:10px;">
                <textarea name="comentario" rows="3" required placeholder="Escreva um comentário..." style="width:100%;padding:8px;"></textarea><br>
                <button type="submit" name="comentar" class="btn btnEntrar">Comentar</button>
            </form>
        <?php else: ?>
            <p><a href="login.php">Faça login</a> para comentar.</p>
        <?php endif; ?>
    </main>
    <a href="javascript:history.back()" class="btn-voltar">Voltar</a>
</body>