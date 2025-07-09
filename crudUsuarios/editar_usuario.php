<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}
include_once '../conexao/config.php';
include_once '../conexao/funcoes.php';


$usuario = new Usuario($db);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $sexo = $_POST['sexo'];
    $fone = $_POST['fone'];
    $email = $_POST['email'];
    $usuario->atualizar($id, $nome, $sexo, $fone, $email);
    header('Location: ../conexao/verifica_login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $row = $usuario->lerPorId($id);
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../styles/stylesCadUsu.css">
    <link rel="stylesheet" href="../styles/stylesResponsividade.css">
    <link rel="stylesheet" href="../styles/stylesTemaEscuro.css">
</head>

<body>
    <h1>Editar Usuário</h1>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" value="<?php echo $row['nome']; ?>" required>
        <br><br>
        <label>Sexo:</label>
        <label for="masculino_editar">
            <input type="radio" id="masculino_editar" name="sexo" value="M" <?php echo ($row['sexo'] === 'M') ? 'checked' : ''; ?> required> Masculino
        </label>
        <label for="feminino_editar">
            <input type="radio" id="feminino_editar" name="sexo" value="F" <?php echo ($row['sexo'] === 'F') ? 'checked' : ''; ?> required> Feminino
        </label>
        <br><br>
        <label for="fone">Fone:</label>
        <input type="text" name="fone" value="<?php echo $row['fone']; ?>" required>
        <br><br>
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $row['email']; ?>" required>
        <br><br>
        <input type="submit" value="Atualizar">
    </form>
    <a href="javascript:history.back()" class="btn-voltar">Voltar</a>
    <script src="../script/scriptTemaEscuro.js"></script>
</body>

</html>