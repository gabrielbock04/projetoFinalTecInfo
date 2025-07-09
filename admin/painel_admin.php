<?php
session_start();
include_once '../conexao/config.php';
include_once '../conexao/funcoes.php';

// Verifica se é administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../index.php');
    exit();
}

$db = (new Database())->getConnection();

// Instanciando classes
$usuario = new Usuario($db);
$funcionario = new Funcionario($db);
$anunciante = new Anunciante($db);
$anuncioObj = new Anuncio($db);

// Dados
$usuarios = $usuario->ler();
$funcionarios = $funcionario->listarTodos();
$anunciantes = $anunciante->listarTodos();
$anuncios = $anuncioObj->listarTodos(); // Todos, incluindo não aprovados
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Painel do Administrador</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../styles/stylesPainelAdmin.css">
    <link rel="stylesheet" href="../styles/stylesResponsividade.css">

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
                        <a class="acao" href="../crudFuncionario/editar_funcionario.php?id=<?php echo $f['id']; ?>">Editar</a>
                        <a class="acao" href="../crudFuncionario/excluir_funcionario.php?id=<?php echo $f['id']; ?>" onclick="return confirm('Excluir este funcionário?')">Excluir</a>
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

        <h2>Anúncios</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Link</th>
                <th>Custo</th>
                <th>Status</th>
                <th>Destaque</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($anuncios as $a): ?>
                <tr>
                    <td><?= $a['id'] ?></td>
                    <td><?= htmlspecialchars($a['nome']) ?></td>
                    <td><a href="<?= htmlspecialchars($a['link']) ?>" target="_blank">Ver link</a></td>
                    <td>R$ <?= number_format($a['valorAnuncio'], 2, ',', '.') ?></td>
                    <td>
                        <?= $a['ativo'] ? '<span style="color:green;">Publicado</span>' : '<span style="color:red;">Pendente</span>' ?>
                    </td>
                    <td><?= $a['destaque'] ? 'Sim' : 'Não' ?></td>
                    <td>
                        <?php if (!$a['ativo']): ?>
                            <!-- Aprovar anúncio -->
                            <form action="../crudAnuncio/ativar_desativar_anuncio.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $a['id'] ?>">
                                <input type="hidden" name="ativo" value="1">
                                <button type="submit">Aprovar</button>
                            </form>
                        <?php else: ?>
                            <!-- Desativar anúncio -->
                            <form action="../crudAnuncio/ativar_desativar_anuncio.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $a['id'] ?>">
                                <input type="hidden" name="ativo" value="0">
                                <button type="submit">Desativar</button>
                            </form>
                        <?php endif; ?>

                        <!-- Excluir anúncio -->
                        <form action="../crudAnuncio/excluir_anuncio.php" method="POST" style="display:inline;" onsubmit="return confirm('Deseja excluir este anúncio?');">
                            <input type="hidden" name="id" value="<?= $a['id'] ?>">
                            <button type="submit">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>


        <a href="javascript:history.back()" class="btn-voltar">Voltar</a>



    </div>
</body>

</html>