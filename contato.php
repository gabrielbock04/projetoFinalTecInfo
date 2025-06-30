<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato - Almanaque do Tempo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/stylesContato.css">

</head>

<body>
    <?php session_start(); ?>
    <header class="header">
        <div style="display: flex; align-items: center; gap: 24px;">
            <a href="./index.php">
                <img src="./img/logo.png" alt="logo" class="logo">
            </a>
        </div>
        <nav class="header-nav">
            <a href="./index.php#home">Home</a>
            <a href="./index.php#curiosidades">Curiosidades</a>
            <a href="./index.php#destaques">Destaques</a>
            <a href="./index.php#feedback">Feedback</a>
            <a href="./index.php#galeria">Galeria</a>
            <a href="./index.php#contato">Contato</a>
        </nav>
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
            <a href="./areaRestrita/nova_noticia.php" class="btn" style="background:#7a4a2e; color:#fff; border-radius:18px; padding:8px 24px; text-decoration:none;">+ Nova Notícia</a>
        <?php endif; ?>
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <a href="perfil.php" class="btn" style="background:#7a4a2e; color:#fff; border-radius:18px; padding:8px 24px; text-decoration:none;">Meu Perfil</a>
        <?php endif; ?>

        <?php if (isset($_SESSION['usuario_id'])): ?>
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                <a href="painel_admin.php" style="background:#7a4a2e; color:#fff; border-radius:18px; padding:8px 24px; text-decoration:none; font-weight:bold;">Painel do Admin</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="./login.php" class="btn btnEntrar" style="background:#7a4a2e; color:#fff; border-radius:18px; padding:10px 28px; font-size:1.1rem; margin-right:10px; text-decoration:none;">Entrar</a>
            <a href="./crudUsuarios/cadastro_usuario.php" class="btn btnRegistrar registrar" style="background:#fff; color:#7a4a2e;border-radius:18px; padding:10px 25px; font-size:1.1rem; text-decoration:none;">Registrar-se</a>
        <?php endif; ?>
        <div>
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <form action="./logout.php" method="post" style="display:inline;">
                    <button class="btnEntrar" type="submit" style="background:#7a4a2e; color:#fff; border:none; border-radius:18px; padding:10px 28px; font-size:1.1rem;">Sair</button>
                </form>
            <?php endif; ?>
        </div>
    </header>
    <main>
        <div class="container-politica">
            <h2>Fale Conosco</h2>
            <p>Tem dúvidas, sugestões ou deseja entrar em contato com a equipe do <strong>Almanaque do Tempo</strong>? Preencha o formulário abaixo e responderemos o mais breve possível.</p>
            <form action="https://formsubmit.co/priscila.schloten@gmail.com" method="POST">
                <div>
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" required placeholder="Seu nome completo">
                </div>
                <div>
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required placeholder="seuemail@exemplo.com">
                </div>
                <div>
                    <label for="assunto">Assunto</label>
                    <input type="text" id="assunto" name="assunto" required placeholder="Motivo do contato">
                </div>
                <div>
                    <label for="mensagem">Mensagem</label>
                    <textarea id="mensagem" name="mensagem" rows="6" required placeholder="Escreva sua mensagem aqui..."></textarea>
                </div>
                <button type="submit">Enviar Mensagem</button>
            </form>
        </div>
    </main>
</body>

</html>