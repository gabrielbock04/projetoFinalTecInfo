<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Políticas do Site</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/stylesTermos.css">
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
        <div class="container-politica" style="line-height: 1.5;">
            <h2>Termos de Uso</h2>
            <p>Bem-vindo ao nosso site. Ao acessar e utilizar nossos serviços, você concorda com os presentes Termos de Uso. Recomendamos a leitura cuidadosa de todos os pontos abaixo, pois eles regem o relacionamento entre o usuário e este site. Estes termos podem ser atualizados periodicamente, e o uso contínuo da plataforma após mudanças será considerado como aceitação das novas condições.</p>

            <ul>
                <li><strong>Acesso e Utilização:</strong> O site está disponível para uso pessoal e não comercial. Qualquer uso com fins ilegais, fraudulentos ou que infrinja direitos de terceiros é expressamente proibido.</li>

                <li><strong>Cadastro de Usuário:</strong> Algumas funcionalidades podem exigir cadastro. Ao se registrar, o usuário concorda em fornecer informações verdadeiras, completas e atualizadas. É de responsabilidade do usuário manter a confidencialidade de sua conta e senha.</li>

                <li><strong>Direitos Autorais e Propriedade Intelectual:</strong> Todo o conteúdo publicado, incluindo textos, imagens, vídeos, gráficos, logotipos e código-fonte, é protegido por direitos autorais. É proibida a reprodução, modificação ou distribuição sem autorização prévia por escrito.</li>

                <li><strong>Conduta do Usuário:</strong> Ao utilizar este site, o usuário compromete-se a:
                    <ul>
                        <li>Não enviar conteúdo ofensivo, discriminatório, ilegal ou difamatório;</li>
                        <li>Não violar a segurança da rede, como tentar acessar áreas restritas ou dados de terceiros;</li>
                        <li>Respeitar as leis locais, nacionais e internacionais aplicáveis.</li>
                    </ul>
                </li>

                <li><strong>Disponibilidade do Serviço:</strong> Nos reservamos o direito de modificar, suspender ou descontinuar o site, total ou parcialmente, sem aviso prévio. Também não garantimos que o site estará livre de erros, vírus ou interrupções.</li>

                <li><strong>Links Externos:</strong> Nosso site pode conter links para sites de terceiros. Não nos responsabilizamos pelo conteúdo, práticas de privacidade ou funcionamento desses sites externos.</li>

                <li><strong>Limitação de Responsabilidade:</strong> Não nos responsabilizamos por danos diretos, indiretos, incidentais ou consequenciais decorrentes do uso ou da impossibilidade de uso do site, incluindo perda de dados, lucros ou interrupção de negócios.</li>

                <li><strong>Modificações nos Termos:</strong> Estes Termos de Uso podem ser alterados a qualquer momento. As atualizações entrarão em vigor imediatamente após sua publicação nesta página.</li>

                <li><strong>Lei Aplicável:</strong> Estes termos são regidos pelas leis brasileiras. Quaisquer disputas decorrentes da utilização do site deverão ser resolvidas no foro da comarca do desenvolvedor ou responsável legal pela plataforma.</li>
            </ul>

            <p>Se você tiver dúvidas sobre estes termos, entre em contato conosco através de nossos canais de atendimento. Seu uso contínuo do site indica que você leu, compreendeu e concordou com todos os termos acima.</p>
            <a href="./index.php" class="btn-voltar">Voltar</a>
        </div>


    </main>
    <footer>
        <p>&copy; 2025 Almanaque do Tempo. Todos os direitos reservados.</p>
    </footer>
    <script src="./script/scriptTemaEscuro.js"></script>
</body>

</html>