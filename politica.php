<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Políticas do Site</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/stylesPolitica.css">
    <link rel="stylesheet" href="./styles/stylesResponsividade.css">
    <link rel="stylesheet" href="./styles/stylesTemaEscuro.css">

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
            <a href="./cadastro_usuario.php" class="btn btnRegistrar registrar" style="background:#fff; color:#7a4a2e;border-radius:18px; padding:10px 25px; font-size:1.1rem; text-decoration:none;">Registrar-se</a>
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
            <h2>Política de Privacidade</h2>
            <p>Respeitamos a sua privacidade e estamos comprometidos em proteger seus dados pessoais. As informações coletadas são utilizadas apenas para melhorar sua experiência em nosso site e nunca são compartilhadas com terceiros sem o seu consentimento.</p>
            <ul>
                <li><strong>Coleta de Dados:</strong> Podemos coletar dados como nome, e-mail e preferências de navegação.</li>
                <li><strong>Uso das Informações:</strong> Os dados são usados para personalizar conteúdo, enviar comunicações relevantes e aprimorar nossos serviços.</li>
                <li><strong>Segurança:</strong> Adotamos medidas técnicas e administrativas para proteger suas informações.</li>
                <li><strong>Seus Direitos:</strong> Você pode solicitar a atualização, correção ou exclusão de seus dados a qualquer momento.</li>
            </ul>
            <h2>Política de Cookies</h2>
            <p>Nosso site utiliza cookies para garantir a melhor experiência ao usuário. Cookies são pequenos arquivos armazenados no seu navegador que ajudam na personalização de conteúdo e na análise de tráfego.</p>
            <ul>
                <li><strong>Cookies Essenciais:</strong> Necessários para o funcionamento do site.</li>
                <li><strong>Cookies de Desempenho:</strong> Ajudam a entender como os usuários interagem com o site.</li>
                <li><strong>Cookies de Preferência:</strong> Guardam suas escolhas, como idioma e região.</li>
            </ul>
            <p>Você pode gerenciar ou desativar os cookies nas configurações do seu navegador.</p>
            <a href="./index.php" class="btn-voltar">Voltar</a>

        </div>
    </main>
    <footer>
        <p>&copy; 2025 Almanaque do Tempo. Todos os direitos reservados.</p>
    </footer>
    <script src="./script/scriptTemaEscuro.js"></script>
</body>

</html>