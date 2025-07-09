<?php
session_start();
include_once '../conexao/config.php';
include_once '../conexao/funcoes.php';

$db = (new Database())->getConnection();
$anunciante = new Anunciante($db);

// Determina se é admin ou anunciante comum
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

// Se for admin e passou ID pela URL, edita outro anunciante
if ($isAdmin && isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    // Se for anunciante, edita a si mesmo
    if (!isset($_SESSION['anunciante_id'])) {
        header('Location: ../login.php');
        exit();
    }
    $id = $_SESSION['anunciante_id'];
}

// PROCESSAMENTO DO FORMULÁRIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        ':nome' => htmlspecialchars(trim($_POST['nome'] ?? '')),
        ':email' => htmlspecialchars(trim($_POST['email'] ?? '')),
        ':telefone' => htmlspecialchars(trim($_POST['telefone'] ?? '')),
        ':endereco_comercial' => htmlspecialchars(trim($_POST['endereco_comercial'] ?? '')),
        ':categoria_anuncio' => htmlspecialchars(trim($_POST['categoria_anuncio'] ?? '')),
        ':descricao_empresa' => htmlspecialchars(trim($_POST['descricao_empresa'] ?? '')),
    ];

    $atualizou = $anunciante->atualizar($id, $dados);

    if ($atualizou) {
        if (!empty($_POST['senha'])) {
            $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE anunciantes SET senha = :senha WHERE id = :id");
            $stmt->execute([':senha' => $senhaHash, ':id' => $id]);
        }

        $_SESSION['sucesso'] = "Perfil atualizado com sucesso!";
    } else {
        $_SESSION['erro'] = "Erro ao atualizar o perfil.";
    }

    // Redireciona de volta à mesma página com o ID (caso admin)
    $redirect = $isAdmin ? 'editar_anunciante.php?id=' . $id : 'editar_anunciante.php';
    header('Location: ' . $redirect);
    exit();
}

// CARREGA DADOS
$dados = $anunciante->buscarPorId($id);
if (!$dados) {
    $_SESSION['erro'] = "Anunciante não encontrado.";
    header('Location: ../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Perfil do Anunciante</title>
    <link rel="stylesheet" href="../styles/stylesCadAnunciante.css">
    <link rel="stylesheet" href="../styles/stylesResponsividade.css">
    <link rel="stylesheet" href="../styles/stylesTemaEscuro.css">
</head>

<body>

    <h1>Editar Perfil</h1>

    <?php if (isset($_SESSION['sucesso'])): ?>
        <p style="color: green;"><?php echo $_SESSION['sucesso'];
                                    unset($_SESSION['sucesso']); ?></p>
    <?php elseif (isset($_SESSION['erro'])): ?>
        <p style="color: red;"><?php echo $_SESSION['erro'];
                                unset($_SESSION['erro']); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Nome:</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($dados['nome']) ?>" required><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($dados['email']) ?>" required><br>

        <label>Telefone:</label>
        <input type="text" name="telefone" value="<?= htmlspecialchars($dados['telefone']) ?>"><br>

        <label>Endereço Comercial:</label>
        <input type="text" name="endereco_comercial" value="<?= htmlspecialchars($dados['endereco_comercial']) ?>"><br>

        <label>Categoria do Anúncio:</label>
        <input type="text" name="categoria_anuncio" value="<?= htmlspecialchars($dados['categoria_anuncio']) ?>"><br>

        <label>Descrição da Empresa:</label>
        <textarea name="descricao_empresa"><?= htmlspecialchars($dados['descricao_empresa']) ?></textarea><br>

        <label>Nova Senha (opcional):</label>
        <input type="password" name="senha"><br>

        <input type="submit" value="Salvar Alterações">

        <a href="./painel_anunciante.php">← Voltar ao Painel</a>

    </form>

</body>

</html>