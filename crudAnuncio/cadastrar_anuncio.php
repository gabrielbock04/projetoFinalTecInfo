<?php
session_start();
include_once '../conexao/config.php';
include_once '../conexao/funcoes.php';

if (!(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) &&  // admin
    !isset($_SESSION['anunciante_id'])) {
    header('Location: ../login.php');
    exit();
}


$db = (new Database())->getConnection();
$anuncio = new Anuncio($db);

// PROCESSA O FORMULÁRIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        ':nome' => htmlspecialchars(trim($_POST['nome'] ?? '')),
        ':link' => htmlspecialchars(trim($_POST['link'] ?? '')),
        ':texto' => htmlspecialchars(trim($_POST['texto'] ?? '')),
        ':ativo' => isset($_POST['ativo']) ? 1 : 0,
        ':destaque' => isset($_POST['destaque']) ? 1 : 0,
        ':valorAnuncio' => floatval($_POST['valorAnuncio'] ?? 0),
        ':imagem' => null,
        ':anunciante_id' => $_SESSION['anunciante_id'] ?? null
    ];

    // Upload de imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $nomeImagem = uniqid('anuncio_') . '.' . $ext;
        $caminhoImagem = 'uploads/' . $nomeImagem;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoImagem)) {
            $dados[':imagem'] = $nomeImagem;
        } else {
            $_SESSION['erro'] = "Erro ao enviar a imagem.";
            header('Location: cadastrar_anuncio.php');
            exit();
        }
    }

    // Usa a função da sua classe
    $ok = $anuncio->criar($dados);

    if ($ok) {
        $_SESSION['sucesso'] = "Anúncio cadastrado com sucesso!";
        header('Location: cadastrar_anuncio.php');
        exit();
    } else {
        $_SESSION['erro'] = "Erro ao cadastrar o anúncio.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastrar Anúncio</title>
</head>

<body>

    <h1>Cadastrar Anúncio</h1>

    <?php if (isset($_SESSION['sucesso'])): ?>
        <p style="color: green;"><?php echo $_SESSION['sucesso'];
                                    unset($_SESSION['sucesso']); ?></p>
    <?php elseif (isset($_SESSION['erro'])): ?>
        <p style="color: red;"><?php echo $_SESSION['erro'];
                                unset($_SESSION['erro']); ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Nome do Anúncio:</label>
        <input type="text" name="nome" required><br>

        <label>Imagem:</label>
        <input type="file" name="imagem" accept="image/*"><br>

        <label>Link do Anúncio:</label>
        <input type="url" name="link"><br>

        <label>Texto (máx. 75 caracteres):</label>
        <input type="text" name="texto" maxlength="75"><br>

        <label>Valor do Anúncio (R$):</label>
        <input type="number" name="valorAnuncio" step="0.01"><br>

        <label><input type="checkbox" name="ativo" checked> Ativo</label><br>
        <label><input type="checkbox" name="destaque"> Destaque</label><br>

        <input type="submit" value="Cadastrar Anúncio">
    </form>

</body>

</html>