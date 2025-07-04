<?php
session_start();
if (!isset($_SESSION['anunciante_id'])) {
    header("Location: ../login.php");
    exit();
}

include_once '../conexao/config.php';
include_once '../conexao/funcoes.php';

$db = (new Database())->getConnection();
$anunciante = new Anunciante($db);
$anuncio = new Anuncio($db);

// Buscar dados do anunciante logado
$dados = $anunciante->buscarPorId($_SESSION['anunciante_id']);

// Buscar anúncios do anunciante
$anuncios = $anuncio->buscarPorAnunciante($_SESSION['anunciante_id']);

function saudacao() {
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
    <title>Painel do Anunciante</title>
    <link rel="stylesheet" href="../styles/stylesPainel.css">
    
</head>
<body>
    <div class="painel">
        <h1><?= saudacao() . ", " . htmlspecialchars($dados['nome']) ?>!</h1>

        <div class="info">
            <h3>Seus Dados</h3>
            <p><strong>Nome:</strong> <?= htmlspecialchars($dados['nome']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($dados['email']) ?></p>
            <p><strong>Telefone:</strong> <?= htmlspecialchars($dados['telefone']) ?></p>
            <p><strong>CPF/CNPJ:</strong> <?= htmlspecialchars($dados['cpf_cnpj']) ?></p>
            <p><strong>Endereço Comercial:</strong> <?= htmlspecialchars($dados['endereco_comercial']) ?></p>
            <p><strong>Categoria:</strong> <?= htmlspecialchars($dados['categoria_anuncio']) ?></p>
            <p><strong>Descrição:</strong> <?= htmlspecialchars($dados['descricao_empresa']) ?></p>
        </div>

        <div class="acoes">
            <a href="editar_anunciante.php" class="btn">Editar Dados</a>

            <form action="excluir_anunciante.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir sua conta? Esta ação é irreversível.');" style="display:inline;">
                <button type="submit" class="excluir-btn">Excluir Conta</button>
            </form>

            <a href="../logout.php" class="btn">Sair</a>
        </div>

        <div class="meus-anuncios">
            <h3>Meus Anúncios</h3>

            <?php if (empty($anuncios)): ?>
                <p>Você ainda não cadastrou nenhum anúncio.</p>
                <a href="../crudAnuncio/cadastrar_anuncio.php" class="btn">Cadastrar Anúncio</a>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Link</th>
                            <th>Valor</th>
                            <th>Ativo</th>
                            <th>Destaque</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($anuncios as $a): ?>
                            <tr>
                                <td><?= htmlspecialchars($a['nome']) ?></td>
                                <td><a href="<?= htmlspecialchars($a['link']) ?>" target="_blank" rel="noopener noreferrer">Ver Link</a></td>
                                <td>R$ <?= number_format($a['valorAnuncio'], 2, ',', '.') ?></td>
                                <td><?= $a['ativo'] ? 'Sim' : 'Não' ?></td>
                                <td><?= $a['destaque'] ? 'Sim' : 'Não' ?></td>
                                <td>
                                    <a href="../crudAnuncio/editar_anuncio.php?id=<?= $a['id'] ?>">Editar</a> | 
                                    <form action="../crudAnuncio/excluir_anuncio.php" method="POST" style="display:inline;" onsubmit="return confirm('Confirma exclusão deste anúncio?');">
                                        <input type="hidden" name="id" value="<?= $a['id'] ?>">
                                        <button type="submit" class="excluir-btn">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="../crudAnuncio/cadastrar_anuncio.php" class="btn">Cadastrar Novo Anúncio</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
