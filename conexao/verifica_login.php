<?php
session_start();
include_once '../conexao/config.php';
include_once '../conexao/funcoes.php';


// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}
$usuario = new Usuario($db);


// Processar exclusão de usuário
if (isset($_GET['deletar'])) {
    $id = $_GET['deletar'];
    $usuario->deletar($id);
    header('Location: ../conexao/verifica_login.php');
    exit();
}
// Obter dados do usuário logado
$dados_usuario = $usuario->lerPorId($_SESSION['usuario_id']);
$nome_usuario = $dados_usuario['nome'];
$_SESSION['is_admin'] = $dados_usuario['is_admin'];
// echo "Valor de is_admin: " . $_SESSION['is_admin'];

// Obter dados dos usuários
$dados = $usuario->ler();
// Função para determinar a saudação
function saudacao()
{
    $hora = date('H');
    if ($hora >= 6 && $hora < 12) {
        return "Bom dia";
    } elseif ($hora >= 12 && $hora < 18) {
        return "Boa tarde";
    } else {
        return "Boa noite";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Portal</title>
</head>

<body>
    <h1><?php echo saudacao() . ", " . $nome_usuario; ?>!</h1>
    <a href="../cadastro_usuario.php">Adicionar Usuário</a>
    <a href="../logout.php">Logout</a>
    <br>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Sexo</th>
            <th>Fone</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $dados->fetch(PDO::FETCH_ASSOC)) : ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nome']; ?></td>
                <td><?php echo ($row['sexo'] === 'M') ? 'Masculino' : 'Feminino'; ?></td>
                <td><?php echo $row['fone']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td>
                    <a href="../crudUsuarios/editar_usuario.php?id=<?php echo $row['id']; ?>">Editar</a>
                    <a href="../crudUsuarios/excluir_usuario.php?id=<?php echo $row['id']; ?>">Deletar</a>

                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>