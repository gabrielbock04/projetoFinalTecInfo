<?php
session_start();
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
    header('Location: ../admin/painel_admin.php'); // ou a página correta do admin
    exit();
}

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
    <title>Painel do Anunciante</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/stylesPainelAnunciante.css">
    <link rel="stylesheet" href="../styles/stylesResponsividade.css">
    <link rel="stylesheet" href="../styles/stylesTemaEscuro.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Modern+Antiqua&display=swap" rel="stylesheet">
    <script src="../script/scriptMenu.js"></script>
    <script src="../script/scriptTemaEscuro.js"></script>
</head>

<body>
    <header class="header">
        <div style="display: flex; align-items: center; gap: 24px; margin-left: -25px;">
            <a href="../index.php">
                <img src="../img/logo.png" alt="logo" class="logo">
            </a>
            <button class="hamburguer" onclick="toggleMenu()" aria-label="Abrir menu" style="background:none; border:none; cursor:pointer; display:none;">
                <i class="fa fa-bars" style="font-size:2rem; color:#7a4a2e;"></i>
            </button>
        </div>
        <nav class="header-nav">
            <a href="../index.php#curiosidades">Curiosidades</a>
            <a href="../index.php#destaques">Destaques</a>
            <a href="../index.php#noticias">Notícias</a>
            <a href="../index.php#feedback">Feedback</a>
            <a href="../index.php#galeria">Galeria</a>
            <a href="../contato.php">Contato</a>
        </nav>
        <div class="header-actions-group" style="display: flex; align-items: center; gap: 12px;">
            <button id="toggle-dark" style="padding: 6px 12px; border-radius: 12px;">Alterar o Tema</button>
            <a href="painel_anunciante.php" class="btn btnAnunci" style="background:#7a4a2e; color:#fff; border-radius:18px; padding:10px 28px; font-size:1.1rem; text-decoration:none;">Painel do Anunciante</a>
            <a href="../crudAnuncio/cadastrar_anuncio.php" class="btn btnRegistrar registrar" style="background:#fff; color:#7a4a2e;border-radius:18px; padding:10px 25px; font-size:1.1rem; text-decoration:none;">Novo Anúncio</a>
            <a href="../logout.php" class="btn btnEntrar" style="background:#7a4a2e; color:#fff; border-radius:18px; padding:10px 28px; font-size:1.1rem; margin-right:10px; text-decoration:none;">Sair</a>
        </div>
    </header>
    <div class="painel" style="margin-top: 64px;">
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