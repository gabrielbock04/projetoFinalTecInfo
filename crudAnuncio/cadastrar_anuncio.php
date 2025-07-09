<?php
session_start();
include_once '../conexao/config.php';
include_once '../conexao/funcoes.php';

// Verifica se é admin ou anunciante logado
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
$anuncianteId = null;

if ($isAdmin && isset($_SESSION['usuario_id'])) {
    $anuncianteId = $_SESSION['usuario_id']; // Admin usa seu próprio ID
} elseif (isset($_SESSION['anunciante_id'])) {
    $anuncianteId = $_SESSION['anunciante_id']; // Anunciante comum
} else {
    header('Location: ../login.php');
    exit();
}

$db = (new Database())->getConnection();
$anuncio = new Anuncio($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dados recebidos
    $nome = trim($_POST['nome'] ?? '');
    $link = trim($_POST['link'] ?? '');
    $texto = trim($_POST['texto'] ?? '');
    $valor = $_POST['valorAnuncio'] ?? '';
    $destaque = isset($_POST['destaque']) ? 1 : 0;

    // Inicializa variáveis de erro
    $erros = [];

    // Validações
    if (empty($nome)) $erros[] = "O nome do anúncio é obrigatório.";
    if (!empty($link) && !filter_var($link, FILTER_VALIDATE_URL)) $erros[] = "O link informado é inválido.";
    if (strlen($texto) > 75) $erros[] = "O texto do anúncio deve ter no máximo 75 caracteres.";
    if (!is_numeric($valor) || floatval($valor) < 0) $erros[] = "Valor do anúncio inválido.";

    // Processa a imagem
    $imagemPath = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($ext, $permitidas)) {
            $erros[] = "Formato de imagem não permitido. Use: jpg, jpeg, png, gif ou webp.";
        } else {
            $nomeImagem = uniqid('anuncio_') . '.' . $ext;
            $caminhoImagem = 'uploads/' . $nomeImagem;
            if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoImagem)) {
                $erros[] = "Erro ao enviar a imagem.";
            } else {
                $imagemPath = $caminhoImagem;
            }
        }
    }

    // Se tiver erros, retorna
    if (!empty($erros)) {
        $_SESSION['erro'] = implode('<br>', $erros);
        header('Location: cadastrar_anuncio.php');
        exit();
    }

    // Prepara dados para inserir
    $dados = [
        ':nome' => htmlspecialchars($nome),
        ':link' => htmlspecialchars($link),
        ':texto' => htmlspecialchars($texto),
        ':ativo' => 0, // Admin que decide depois
        ':destaque' => $destaque,
        ':valorAnuncio' => floatval($valor),
        ':imagem' => $imagemPath,
        ':anunciante_id' => $anuncianteId
    ];

    $ok = $anuncio->criar($dados);

    if ($ok) {
        $_SESSION['sucesso'] = "Anúncio cadastrado com sucesso! Aguarde aprovação do administrador.";
    } else {
        $_SESSION['erro'] = "Erro ao cadastrar o anúncio.";
    }
    header('Location: cadastrar_anuncio.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastrar Anúncio</title>
    <link rel="stylesheet" href="../styles/stylesAnuncio.css">
    <link rel="stylesheet" href="../styles/stylesResponsividade.css">
    <link rel="stylesheet" href="../styles/stylesTemaEscuro.css">
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

    <div class="anuncio-card">
        <form method="POST" enctype="multipart/form-data">
            <label>Nome do Anúncio:</label>
            <input type="text" name="nome" required class="anuncio-nome"><br>

            <label>Imagem:</label>
            <input type="file" name="imagem" accept="image/*"><br>

            <label>Link do Anúncio:</label>
            <input class="anuncio-link" type="url" name="link"><br>

            <label>Texto (máx. 75 caracteres):</label>
            <input type="text" class="anuncio-texto" name="texto" maxlength="75"><br>

            <label>Valor do Anúncio (R$):</label>
            <input type="number" name="valorAnuncio" step="0.01"><br>

            <label><input type="checkbox" name="ativo" checked> Ativo</label><br>
            <label><input type="checkbox" name="destaque"> Destaque</label><br>

            <input type="submit" value="Cadastrar Anúncio">

        </form>
    </div>
    <a href="javascript:history.back()" class="btn-voltar">Voltar</a>
    <script src="../script/scriptTemaEscuro.js"></script>
</body>

</html>