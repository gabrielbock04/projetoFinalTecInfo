<?php
session_start();
include_once '../conexao/config.php';
include_once '../conexao/funcoes.php';

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../index.php');
    exit();
}

$db = (new Database())->getConnection();

$usuario = new Usuario($db);
$funcionario = new Funcionario($db);
$anunciante = new Anunciante($db);

$usuarios = $usuario->ler();
$funcionarios = $funcionario->listarTodos();
$anunciantes = $anunciante->listarTodos();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Painel do Administrador</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../styles/stylesPainelAdmin.css">

</head>

<body>
    <h1>Painel do Administrador</h1>

    <div class="painel-links">
        <a href="../crudUsuarios/cadastro_usuario.php">Cadastrar Novo Usuário</a>
        <a href="../crudFuncionario/cadastrar_funcionario.php">Cadastrar Novo Funcionário</a>
        <a href="../crudAnunciante/cadastrar_anunciante.php">Cadastrar Novo Anunciante</a>
        <a href="../logout.php">Logout</a>
    </div>

    <div class="painel-container">
        <h2>Usuários</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Sexo</th>
                <th>Fone</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
            <?php while ($row = $usuarios->fetch(PDO::FETCH_ASSOC)) : ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['nome']; ?></td>
                    <td><?php echo ($row['sexo'] === 'M') ? 'Masculino' : 'Feminino'; ?></td>
                    <td><?php echo $row['fone']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td>
                        <a class="acao" href="../crudUsuarios/editar_usuario.php?id=<?php echo $row['id']; ?>">Editar</a>
                        <a class="acao" href="../crudUsuarios/excluir_usuario.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Excluir este usuário?')">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <h2>Funcionários</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Sobrenome</th>
                <th>CPF/CNPJ</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($funcionarios as $f) : ?>
                <tr>
                    <td><?php echo $f['id']; ?></td>
                    <td><?php echo $f['nome']; ?></td>
                    <td><?php echo $f['sobrenome']; ?></td>
                    <td><?php echo $f['cpf_cnpj']; ?></td>
                    <td><?php echo $f['email']; ?></td>
                    <td><?php echo $f['telefone']; ?></td>
                    <td>
                        <a class="acao" href="../crudFuncionarios/editar_funcionario.php?id=<?php echo $f['id']; ?>">Editar</a>
                        <a class="acao" href="../crudFuncionarios/excluir_funcionario.php?id=<?php echo $f['id']; ?>" onclick="return confirm('Excluir este funcionário?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h2>Anunciantes</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>CPF/CNPJ</th>
                <th>Endereço</th>
                <th>Categoria</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($anunciantes as $a): ?>
                <tr>
                    <td><?php echo $a['id']; ?></td>
                    <td><?php echo $a['nome']; ?></td>
                    <td><?php echo $a['email']; ?></td>
                    <td><?php echo $a['telefone']; ?></td>
                    <td><?php echo $a['cpf_cnpj']; ?></td>
                    <td><?php echo $a['endereco_comercial']; ?></td>
                    <td><?php echo $a['categoria_anuncio']; ?></td>
                    <td>
                        <a href="../crudAnunciante/editar_anunciante.php?id=<?= $a['id'] ?>">Editar</a> |
                        <a href="../crudAnunciante/excluir_anunciante.php?id=<?= $a['id'] ?>" onclick="return confirm('Excluir este anunciante?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>