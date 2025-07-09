<?php
session_start();
include_once '../conexao/config.php';
include_once '../conexao/funcoes.php';

// Verifica se é admin ou anunciante
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['tipo'], ['admin', 'anunciante'])) {
    header('Location: ../login.php');
    exit();
}

$db = (new Database())->getConnection();
$anuncio = new Anuncio($db);

// Pega o ID do anúncio a ser editado
if (!isset($_GET['id'])) {
    $_SESSION['erro'] = "ID do anúncio não informado.";
    header('Location: ./');
    exit();
}

$id = intval($_GET['id']);
$dados = $anuncio->buscarPorId($id);

if (!$dados) {
    $_SESSION['erro'] = "Anúncio não encontrado.";
    header('Location: ./');
    exit();
}

// PROCESSA A EDIÇÃO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novosDados = [
        ':nome' => htmlspecialchars(trim($_POST['nome'] ?? '')),
        ':link' => htmlspecialchars(trim($_POST['link'] ?? '')),
        ':texto' => htmlspecialchars(trim($_POST['texto'] ?? '')),
        ':ativo' => isset($_POST['ativo']) ? 1 : 0,
        ':destaque' => isset($_POST['destaque']) ? 1 : 0,
        ':valorAnuncio' => floatval($_POST['valorAnuncio'] ?? 0),
        ':imagem' => $dados['imagem'] // valor antigo, caso não envie novo
    ];

    // Se nova imagem for enviada, substitui
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $nomeImagem = uniqid('anuncio_') . '.' . $ext;
        $caminho = '../uploads/' . $nomeImagem;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho)) {
            $novosDados[':imagem'] = $nomeImagem;
        } else {
            $_SESSION['erro'] = "Erro ao enviar nova imagem.";
            header("Location: editar_anuncio.php?id=$id");
            exit();
        }
    }

    $ok = $anuncio->atualizar($id, $novosDados);

    if ($ok) {
        $_SESSION['sucesso'] = "Anúncio atualizado com sucesso!";
        header("Location: editar_anuncio.php?id=$id");
        exit();
    } else {
        $_SESSION['erro'] = "Erro ao atualizar anúncio.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Anúncio</title>
    <link rel="stylesheet" href="../styles/stylesAnuncio.css">
    <link rel="stylesheet" href="../styles/stylesResponsividade.css">
    <link rel="stylesheet" href="../styles/stylesTemaEscuro.css">
</head>

<body>

    <h1>Editar Anúncio</h1>

    <?php
    $mensagem = '';
    if (isset($_SESSION['sucesso'])) {
        $mensagem = '<p style="color: green;">' . $_SESSION['sucesso'] . '</p>';
        unset($_SESSION['sucesso']);
    } elseif (isset($_SESSION['erro'])) {
        $mensagem = '<p style="color: red;">' . $_SESSION['erro'] . '</p>';
        unset($_SESSION['erro']);
    }
    ?>

    <?= $mensagem ?>


    <form method="POST" enctype="multipart/form-data">
        <label>Nome:</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($dados['nome']) ?>" required><br>

        <label>Link:</label>
        <input type="url" name="link" value="<?= htmlspecialchars($dados['link']) ?>"><br>

        <label>Texto:</label>
        <input type="text" name="texto" maxlength="75" value="<?= htmlspecialchars($dados['texto']) ?>"><br>

        <label>Valor:</label>
        <input type="number" step="0.01" name="valorAnuncio" value="<?= $dados['valorAnuncio'] ?>"><br>

        <label><input type="checkbox" name="ativo" <?= $dados['ativo'] ? 'checked' : '' ?>> Ativo</label><br>
        <label><input type="checkbox" name="destaque" <?= $dados['destaque'] ? 'checked' : '' ?>> Destaque</label><br>

        <label>Imagem Atual:</label><br>
        <?php if ($dados['imagem']): ?>
            <img src="../uploads/<?= $dados['imagem'] ?>" alt="Imagem do anúncio" width="150"><br>
        <?php else: ?>
            <em>Nenhuma imagem cadastrada.</em><br>
        <?php endif; ?>

        <label>Nova Imagem (opcional):</label>
        <input type="file" name="imagem" accept="image/*"><br>

        <input type="submit" value="Salvar Alterações">
    </form>
    <a href="javascript:history.back()" class="btn-voltar">Voltar</a>
    <script src="../script/scriptTemaEscuro.js"></script>
</body>

</html>