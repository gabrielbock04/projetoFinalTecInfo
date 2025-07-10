<?php
session_start();

include_once './conexao/config.php';
include_once './conexao/funcoes.php';

$db = (new Database())->getConnection();
$noticia = new Noticia($db);
$anuncio = new Anuncio($db);

// Buscar apenas anúncios ativos
$anuncios = $anuncio->listarAtivos(); // você criará essa função



if (isset($_GET['busca']) && !empty(trim($_GET['busca']))) {
    $termo = trim($_GET['busca']);
    $noticias = $noticia->buscarPorTitulo($termo); // função personalizada
} else {
    $noticias = $noticia->listarTodas();
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Portal de Notícias</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="./styles/styles.css">
    <link rel="stylesheet" href="./styles/stylesIndex.css">
    <link rel="stylesheet" href="./styles/stylesMenu.css">
    <link rel="stylesheet" href="./styles/stylesTemaEscuro.css">
    <link rel="stylesheet" href="./styles/stylesPrevisaoTempo.css">


    <script src="./script/scriptMenu.js"></script>
    <script src="./script/scriptIndex.js"></script>
    <script src="./script/scriptTemaEscuro.js"></script>


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Modern+Antiqua&display=swap" rel="stylesheet">
</head>

<body>

    <header class="header">
        <div style="display: flex; align-items: center; gap: 24px; margin-left: -25px;">
            <a href="./index.php">
                <img src="./img/logo.png" alt="logo" class="logo">
            </a>
            <!-- Botão hamburguer para mobile -->
            <button class="hamburguer" onclick="toggleMenu()" aria-label="Abrir menu" style="background:none; border:none; cursor:pointer; display:none;">
                <i class="fa fa-bars" style="font-size:2rem; color:#7a4a2e;"></i>
            </button>
        </div>
        <nav class="header-nav">
            <a href="#curiosidades">Curiosidades</a>
            <a href="#destaques">Destaques</a>
            <a href="#noticias">Notícias</a>
            <a href="#feedback">Feedback</a>
            <a href="#galeria">Galeria</a>
            <a href="./contato.php">Contato</a>
        </nav>

        <div class="header-actions-group" style="display: flex; align-items: center; gap: 12px;">
            <button id="toggle-dark" style="padding: 6px 12px; border-radius: 12px;">Alterar o Tema</button>
            <?php if ((isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) || (isset($_SESSION['eh_funcionario']) && $_SESSION['eh_funcionario'] == true)): ?>
                <a href="./areaRestrita/nova_noticia.php" class="btn" style="background:#7a4a2e; color:#fff; border-radius:18px; padding:8px 24px; text-decoration:none;">+ Nova Notícia</a>
            <?php endif; ?>
            <?php
            $destinoAnuncie = './crudAnunciante/cadastrar_anunciante.php';
            if (isset($_SESSION['anunciante_id'])) {
                $destinoAnuncie = './crudAnuncio/cadastrar_anuncio.php';
            } elseif (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
                $destinoAnuncie = './crudAnuncio/cadastrar_anuncio.php';
            }
            ?>
            <a href="<?= $destinoAnuncie ?>" class="btn btnAnunci" style="background:#7a4a2e; color:#fff; border-radius:18px; padding:10px 28px; font-size:1.1rem; text-decoration:none;">Anuncie</a>
            <?php if (!isset($_SESSION['usuario_id']) && !isset($_SESSION['anunciante_id'])): ?>
                <a href="./login.php" class="btn btnEntrar" style="background:#7a4a2e; color:#fff; border-radius:18px; padding:10px 28px; font-size:1.1rem; margin-right:10px; text-decoration:none;">Entrar</a>
                <a href="./crudUsuarios/cadastro_usuario.php" class="btn btnRegistrar registrar" style="background:#fff; color:#7a4a2e;border-radius:18px; padding:10px 25px; font-size:1.1rem; text-decoration:none;">Registrar-se</a>
            <?php endif; ?>

            <!-- Menu Hambúrguer - sempre visível -->
            <button class="hamburguer-user" type="button" tabindex="0" aria-label="Abrir menu do usuário" onclick="toggleUserMenu(event)" style="background:none; border:none; cursor:pointer; margin-left:8px; padding:8px; display:flex !important; align-items:center; justify-content:center; visibility:visible !important; opacity:1 !important; position:relative !important; z-index:9999 !important;">
                <i class="fa fa-bars" style="font-size:2.2rem; color:#7a4a2e;"></i>
            </button>
            <div id="user-dropdown" class="user-dropdown-menu">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <a href="./areaRestrita/perfil.php" class="perfil-link">Meu Perfil</a>
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                        <a href="./admin/painel_admin.php" class="admin-link">Painel do Admin</a>
                    <?php endif; ?>
                    <form action="./logout.php" method="post" style="margin:0;">
                        <button type="submit" class="sair-link">Sair</button>
                    </form>
                <?php elseif (isset($_SESSION['anunciante_id'])): ?>
                    <a href="crudAnunciante/painel_anunciante.php" class="perfil-link">Meu Perfil</a>
                    <form action="./logout.php" method="post" style="margin:0;">
                        <button type="submit" class="sair-link">Sair</button>
                    </form>
                <?php else: ?>
                    <!-- Menu para usuários não logados -->
                    <a href="./login.php" class="perfil-link">Entrar</a>
                    <a href="./crudUsuarios/cadastro_usuario.php" class="admin-link">Registrar-se</a>
                    <a href="./crudAnunciante/cadastrar_anunciante.php" class="admin-link">Cadastrar Anunciante</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <section id="home" class="hero-section" style="display: flex; background:rgb(128, 75, 48); color: #fff; min-height: 300px; width: 100%; font-family: 'Modern Antiqua', serif;">
        <div class="hero-images"
            style="display: flex; flex-direction: column; gap: 50px; padding: 65px 0 80px 80px;
        position: relative;
        background: url('./img/papiroHome.png') center center no-repeat;
        background-size: cover; 
        width: 490px;
        height: 489px;
        margin-left: 20px;">
            <div class="carrossel-jornal-container" style="width: 420px; height: 510px; overflow: hidden; border-radius: 24px; position: relative;">
                <div id="carrossel-jornal-slides" style="display: flex; flex-direction: column; transition: transform 0.7s cubic-bezier(.22,1,.36,1); height: 1010px;">
                    <img src="./img/jornal1.jpeg" alt="Jornal antigo" style="background: #D6B889; width: 400px; height: 495px; object-fit: contain;">
                    <img src="./img/jornal2.jpeg" alt="Jornal antigo 2" style="background: #D6B889; width: 400px; height: 495px; object-fit: contain;">
                </div>
            </div>

        </div>
        <div class="hero-content" ...>
            <?php if (isset($_COOKIE['nome_usuario'])): ?>
                <div class="bem-vindo-cookies">
                    Bem-vindo de volta, <strong><?= htmlspecialchars($_COOKIE['nome_usuario']) ?></strong>!
                </div>
            <?php endif; ?>
            <h1 style="font-size: 3.4rem; font-weight: 400; margin-bottom: 32px; color: #fff; line-height: 1.1;">
                Fatos Históricos e Curiosidades <br> que atravessam o tempo
            </h1>
            <p style="font-size: 1.28rem; margin-top: -5px ;color: #f3e6e0; line-height: 1.5; max-width: 750px;">
                Descubra curiosidades, fatos marcantes e relatos fascinantes do passado.<br>
                Navegue por memórias, mapas antigos e imagens raras, como se folheasse um almanaque repleto de descobertas. Sinta-se parte dessa viagem nostálgica e envolvente.
            </p>
            <a href="#curiosidades" class="btn-ver-historias">LEIA AGORA</a>
        </div>
    </section>
    <!-- Fim Hero Section -->
    <!-- Nova Section inspirada nas imagens enviadas -->
    <section id="curiosidades" class="hero-section" style="background: #f9f6f0; padding: 64px 0 32px 0;">
        <div style="max-width: 1280px; margin: 0 auto; padding: 0 40px;">
            <h2 style="font-family: 'Modern Antiqua', serif; font-size: 3rem; color: #4b2a17; margin-bottom: 18px; font-weight: 400;">
                O que você pode descobrir aqui?
            </h2>
            <p style="font-size: 1.3rem; color: #6d3c24; margin-bottom: 48px; max-width: 900px;">
                Viaje por séculos de descobertas, mistérios e relatos que atravessam gerações. Cada história é um convite para explorar o passado com olhos curiosos e mente aberta.
            </p>
            <div style="display: flex; gap: 32px; flex-wrap: wrap; justify-content: center;">
                <!-- Card 1 -->
                <div style="background: #fff; border-radius: 24px; box-shadow: 0 2px 16px rgba(0,0,0,0.06); width: 400px; padding-bottom: 32px; display: flex; flex-direction: column; align-items: flex-start;">
                    <img src="./img/marcosHistoricos.png" alt="Linha do tempo" style="width: 100%; height: 220px; object-fit: cover; border-radius: 24px 24px 0 0;">
                    <div style="padding: 24px;">
                        <span style="color: #a78a6d; font-size: 1rem;">Curiosidades</span>
                        <h3 style="font-family: 'Modern Antiqua', serif; font-size: 1.6rem; color: #4b2a17; margin: 10px 0 12px 0; font-weight: 400;">
                            Marcos que moldaram o mundo
                        </h3>
                        <p style="color: #6d3c24; font-size: 1.08rem; margin-bottom: 24px;">
                            Percorra datas marcantes, eventos curiosos e transformações históricas. Descubra como cada época deixou sua marca e inspire-se com as jornadas do tempo.
                        </p>
                        <a href="#noticias" style="background: #6d3c24; color: #fff; padding: 12px 32px; border-radius: 20px; font-size: 1.1rem; text-decoration: none;">Explorar</a>
                    </div>
                </div>
                <!-- Card 2 -->
                <div style="background: #fff; border-radius: 24px; box-shadow: 0 2px 16px rgba(0,0,0,0.06); width: 400px; padding-bottom: 32px; display: flex; flex-direction: column; align-items: flex-start;">
                    <img src="./img/narrativas.png" alt="Artigos" style="width: 100%; height: 220px; object-fit: cover; border-radius: 24px 24px 0 0;">
                    <div style="padding: 24px;">
                        <span style="color: #a78a6d; font-size: 1rem;">Curiosidades</span>
                        <h3 style="font-family: 'Modern Antiqua', serif; font-size: 1.6rem; color: #4b2a17; margin: 10px 0 12px 0; font-weight: 400;">
                            Narrativas que atravessam gerações
                        </h3>
                        <p style="color: #6d3c24; font-size: 1.08rem; margin-bottom: 24px;">
                            Mergulhe em reportagens sobre personagens, culturas e fatos pouco conhecidos. Conhecimento contado de forma envolvente, como nas páginas de um diário antigo.
                        </p>
                        <a href="#noticias" style="background: #6d3c24; color: #fff; padding: 12px 32px; border-radius: 20px; font-size: 1.1rem; text-decoration: none;">Ler mais</a>
                    </div>
                </div>
                <!-- Card 3 -->
                <div style="background: #fff; border-radius: 24px; box-shadow: 0 2px 16px rgba(0,0,0,0.06); width: 400px; padding-bottom: 32px; display: flex; flex-direction: column; align-items: flex-start;">
                    <img src="./img/mapa.png" alt="Curiosidades" style="width: 100%; height: 220px; object-fit: cover; border-radius: 24px 24px 0 0;">
                    <div style="padding: 24px;">
                        <span style="color: #a78a6d; font-size: 1rem;">Curiosidades</span>
                        <h3 style="font-family: 'Modern Antiqua', serif; font-size: 1.6rem; color: #4b2a17; margin: 10px 0 12px 0; font-weight: 400;">
                            Você sabia? Detalhes do passado
                        </h3>
                        <p style="color: #6d3c24; font-size: 1.08rem; margin-bottom: 24px;">
                            Encontre invenções, segredos e pequenas pérolas históricas que surpreendem e encantam. O passado guarda histórias fascinantes esperando por você.
                        </p>
                        <a href="#galeria" style="background: #6d3c24; color: #fff; padding: 12px 32px; border-radius: 20px; font-size: 1.1rem; text-decoration: none;">Descobrir</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Destaques em carrossel com efeito opaco ao passar o mouse -->
    <section id="destaques" class="hero-section" style="background: #f9f6f0; padding: 56px 0;">
        <div style="max-width: 1320px; margin: 0 auto; padding: 0 24px;">
            <h2 style="font-family: 'Modern Antiqua', serif; font-size: 2.4rem; color: #4b2a17; margin-bottom: 32px; font-weight: 400;">
                Notícias que estão sendo produzidas. Não perca!!
            </h2>
            <div class="carrossel-destaques" style="display: flex; gap: 32px; overflow-x: auto; scroll-snap-type: x mandatory; padding-bottom: 16px;">
                <!-- Card 1 -->
                <div class="destaque-card" style="background: #fff; border-radius: 24px; min-width: 900px; max-width: 900px; display: flex; overflow: hidden; box-shadow: 0 2px 16px rgba(0,0,0,0.06); scroll-snap-align: start; transition: opacity 0.3s;">
                    <img src="./img/annefrank.png" alt="Globo" style="width: 50%; object-fit: cover; height: 340px;">
                    <div style="flex: 1; padding: 40px 36px 36px 36px; display: flex; flex-direction: column; justify-content: center;">
                        <span style="display:inline-block; background:#ede7df; color:#7a4a2e; font-size:0.95rem; border-radius:12px; padding:6px 18px 4px 18px; margin-bottom:18px;">
                            30 de junho de 2025
                        </span>
                        <h3 style="font-family: 'Modern Antiqua', serif; font-size:2.1rem; color:#4b2a17; margin:0 0 18px 0; font-weight:400;">
                            O diário que mudou o mundo
                        </h3>
                        <p style="color:#6d3c24; font-size:1.13rem; margin-bottom:28px;">
                            Descubra como as palavras de Anne Frank atravessaram décadas, inspirando gerações e revelando detalhes do cotidiano em tempos de guerra. Uma viagem íntima pelas páginas da história.
                        </p>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <img src="./img/autor1.png" alt="Autor" style="width:48px; height:48px; border-radius:50%; object-fit:cover;">
                            <div>
                                <div style="color:#4b2a17; font-size:1.05rem;">Taylor Cardoso</div>
                                <div style="color:#a78a6d; font-size:0.98rem;">Data de publicação será em 28/06/2025</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="destaque-card" style="background: #fff; border-radius: 24px; min-width: 900px; max-width: 900px; display: flex; overflow: hidden; box-shadow: 0 2px 16px rgba(0,0,0,0.06); scroll-snap-align: start; transition: opacity 0.3s;">
                    <img src="./img/construcaoMundial.png" style="width: 50%; object-fit: cover; height: 340px;">
                    <div style="flex: 1; padding: 40px 36px 36px 36px; display: flex; flex-direction: column; justify-content: center;">
                        <span style="display:inline-block; background:#ede7df; color:#7a4a2e; font-size:0.95rem; border-radius:12px; padding:6px 18px 4px 18px; margin-bottom:18px;">
                            8 de julho de 2025
                        </span>
                        <h3 style="font-family: 'Modern Antiqua', serif; font-size:2.1rem; color:#4b2a17; margin:0 0 18px 0; font-weight:400;">
                            O templo que revelou novos mundos
                        </h3>
                        <p style="color:#6d3c24; font-size:1.13rem; margin-bottom:28px;">
                            Conheça a história do mapa que mudou rotas e conectou culturas. Uma peça rara que narra aventuras, descobertas e o fascínio pelo desconhecido.
                        </p>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <img src="./img/cafe.png" alt="Autor" style="width:48px; height:48px; border-radius:50%; object-fit:cover;">
                            <div>
                                <div style="color:#4b2a17; font-size:1.05rem;">Mario Antunes</div>
                                <div style="color:#a78a6d; font-size:0.98rem;">Data de publicação será em 15/07/2025</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="destaque-card" style="background: #fff; border-radius: 24px; min-width: 900px; max-width: 900px; display: flex; overflow: hidden; box-shadow: 0 2px 16px rgba(0,0,0,0.06); scroll-snap-align: start; transition: opacity 0.3s;">
                    <img src="./img/cafe.png" alt="Café antigo" style="width: 50%; object-fit: cover; height: 340px;">
                    <div style="flex: 1; padding: 40px 36px 36px 36px; display: flex; flex-direction: column; justify-content: center;">
                        <span style="display:inline-block; background:#ede7df; color:#7a4a2e; font-size:0.95rem; border-radius:12px; padding:6px 18px 4px 18px; margin-bottom:18px;">
                            20 de setembro de 1921
                        </span>
                        <h3 style="font-family: 'Modern Antiqua', serif; font-size:2.1rem; color:#4b2a17; margin:0 0 18px 0; font-weight:400;">
                            O café onde histórias se encontram
                        </h3>
                        <p style="color:#6d3c24; font-size:1.13rem; margin-bottom:28px;">
                            Descubra o charme dos cafés antigos, onde escritores, artistas e pensadores se reuniam para trocar ideias e criar movimentos culturais inesquecíveis.
                        </p>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <img src="./img/autor3.png" alt="Autor" style="width:48px; height:48px; border-radius:50%; object-fit:cover;">
                            <div>
                                <div style="color:#4b2a17; font-size:1.05rem;">Joana Ribeirão</div>
                                <div style="color:#a78a6d; font-size:0.98rem;">Data de publicação será em 20/07/2025</div>
                            </div>
                        </div>
                    </div>
    </section>
    <!-- Carrossel de Anúncios 3x1 horizontal, troca linhas de baixo para cima -->
    <div class="anuncios-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; margin-top: 40px;">
        <?php foreach ($anuncios as $a): ?>
            <div class="card" style="background:#fff; border-radius:18px; box-shadow:0 2px 12px rgba(0,0,0,0.07); padding:18px; display:flex; flex-direction:column;">
                <h3><?= htmlspecialchars($a['nome']) ?></h3>
                <?php
                $imgRelativa = $a['imagem'];
                $urlImagem = 'crudAnuncio/' . htmlspecialchars($imgRelativa);
                $caminhoFisico = './crudAnuncio/' . $imgRelativa;
                ?>
                <?php if (!empty($imgRelativa) && file_exists($caminhoFisico)): ?>
                    <div class="card-img" style="width:100%;height:180px;overflow:hidden; border-radius:12px; margin-bottom:12px;">
                        <img src="<?= $urlImagem ?>" style="width:100%;height:100%;object-fit:cover;">
                    </div>
                <?php else: ?>
                    <div class="card-img" style="width:100%;height:180px;display:flex;align-items:center;justify-content:center;font-size:3rem;color:#ccc; background:#f3e6e0; border-radius:12px; margin-bottom:12px;">📷</div>
                <?php endif; ?>
                <div style="margin-bottom:10px; color:#444;">
                    <?= htmlspecialchars($a['texto']) ?>
                </div>
                <a href="<?= htmlspecialchars($a['link']) ?>" target="_blank" style="color:#7a4a2e; text-decoration:underline;">Acessar</a>
            </div>
        <?php endforeach; ?>
    </div>
    <!-- Nova seção de cards históricos -->
    <section id="noticias" class="noticia" style="background: #F9F6F0; padding: 56px 0;">
        <div style="max-width: 1320px; margin: 0 auto; padding: 0 24px;">
            <h2 style="font-family: 'Modern Antiqua', serif; font-size: 2.8rem; color: #4b2a17; margin-bottom: 32px; font-weight: 400; margin: 25px">
                Últimas Notícias
            </h2>
            <!-- Previsão do tempo -->
            <div class="previsaoTempo" style="margin-bottom: 32px;">
                <div class="tempo-header">
                    <iframe src="https://www.meteoblue.com/pt/tempo/widget/daily/sapucaia-do-sul_brasil_3448031?geoloc=fixed&days=7&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&precipunit=MILLIMETER&coloured=coloured&pictoicon=0&maxtemperature=1&mintemperature=1&windspeed=0&windgust=0&winddirection=0&uv=0&humidity=0&precipitation=1&precipitationprobability=1&spot=0&pressure=0&layout=light" frameborder="0" scrolling="NO" allowtransparency="true" sandbox="allow-same-origin allow-scripts allow-popups allow-popups-to-escape-sandbox" style="margin-top: -55px; width: 408px; height: 200px; vertical-align: top;"></iframe>
                    <?php include './conexao/previsaoTempo.php'; ?>
                    <div><!-- DO NOT REMOVE THIS LINK --><a href="https://www.meteoblue.com/pt/tempo/semana/sapucaia-do-sul_brasil_3448031" target="_blank" rel="noopener"></a></div>
                </div>
            </div>

            <form method="GET" class="pesquisa-bar-container pesquisa-bar-direcao" action="#noticias">
                <input class="pesquisa-bar" type="text" name="busca" placeholder="Pesquise por autor, título..." value="<?= isset($_GET['busca']) ? htmlspecialchars($_GET['busca']) : '' ?>">
                <button type="submit" class="btn-lupa">
                    <svg width="32" height="32" fill="none" stroke="#7a4a2e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="15" cy="15" r="10" />
                        <line x1="28" y1="28" x2="21.5" y2="21.5" />
                    </svg>
                </button>
            </form>

            <div class="cards-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; margin-top: 80px;">
                <?php foreach ($noticias as $n): ?>
                    <div class="card" style="background:#fff; border-radius:18px; box-shadow:0 2px 12px rgba(0,0,0,0.07); padding:18px; display:flex; flex-direction:column;">
                        <a href="./areaRestrita/noticia.php?id=<?php echo $n['id']; ?>" style="text-decoration: none; color: inherit; display: block;">
                            <?php if ($n['imagem']): ?>
                                <div class="card-img" style="width:100%;height:180px;overflow:hidden; border-radius:12px; margin-bottom:12px;">
                                    <img src="<?php echo htmlspecialchars($n['imagem']); ?>" alt="Imagem" style="width:100%;height:100%;object-fit:cover;">
                                </div>
                            <?php else: ?>
                                <div class="card-img" style="width:100%;height:180px;display:flex;align-items:center;justify-content:center;font-size:3rem;color:#ccc; background:#f3e6e0; border-radius:12px; margin-bottom:12px;">📷</div>
                            <?php endif; ?>

                            <div class="card-title" style="font-size: 1.6rem; font-weight: bold; margin-bottom: 6px;">
                                <?php echo htmlspecialchars($n['titulo']); ?>
                            </div>

                            <div style="font-size:1.1rem;color:#666;margin-bottom:8px;">
                                <strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($n['data'])); ?>
                            </div>

                            <div style="margin-bottom:10px; color:#444;">
                                <?= mb_strimwidth(strip_tags($n['noticia']), 0, 200, '...') ?>
                            </div>
                        </a>

                        <?php if (
                            (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) ||
                            (isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] == $n['autor'])
                        ): ?>
                            <div style="margin-top:10px;">
                                <a href="./areaRestrita/editar_noticia.php?id=<?= $n['id'] ?>" class="btn btnEntrar" style="background:#7a4a2e; color:#fff; border-radius:14px; padding:6px 18px; text-decoration:none; margin-right:6px;">Editar</a>
                                <a href="./areaRestrita/excluir_noticia.php?id=<?= $n['id'] ?>" class="btn btnEntrar" style="background:#e57373; color:#fff; border-radius:14px; padding:6px 18px; text-decoration:none;" onclick="return confirm('Excluir esta notícia?')">Excluir</a>
                            </div>
                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>
    </section>


    <section id="feedback" class="feedback" style="background: #804B30; padding: 64px 0;">
        <h2 style="color: #fff; text-align: center; font-family: 'Modern Antiqua', serif; font-size: 3.9rem; margin-bottom: 38px; font-weight: 400;">
            Feedback
        </h2>
        <div style="max-width: 900px; margin: 0 auto; position: relative;">
            <div class="depoimentos-carrossel">
                <div class="depoimento-slide ativo">
                    <div class="comentario-borda">
                        <img src="./img/profPerfil1.jpg" alt="Professor" class="prof-img">
                        <p class="comentario-texto">
                            Descobrir este portal é como abrir um velho almanaque: cada página traz uma surpresa, um detalhe esquecido, uma história que ganha vida diante dos olhos.
                        </p>
                        <div>
                            <div class="comentario-nome">Alex Duarte</div>
                            <div class="comentario-cargo">Professor de história</div>
                        </div>
                    </div>
                </div>
                <div class="depoimento-slide">
                    <div class="comentario-borda">
                        <img src="./img/perfilProf2.png" alt="Professor" class="prof-img">
                        <p class="comentario-texto">
                            Utilizo o portal em minhas aulas para os alunos se envolverem mais e aprenderem com prazer. É uma ferramenta indispensável para quem ama ensinar história!
                        </p>
                        <div>
                            <div class="comentario-nome">Marina Lopes</div>
                            <div class="comentario-cargo">Professora de história</div>
                        </div>
                    </div>
                </div>
                <div class="depoimento-slide">
                    <div class="comentario-borda">
                        <img src="./img/perfilProf3.png" alt="Professor" class="prof-img">
                        <p class="comentario-texto">
                            O conteúdo do portal é confiável e muito bem apresentado. Recomendo para professores, estudantes e todos que têm curiosidade sobre o passado.
                        </p>
                        <div>
                            <div class="comentario-nome">Carlos Henrique</div>
                            <div class="comentario-cargo">Professor de filosofia e sociologia</div>
                        </div>
                    </div>
                </div>

                <div class="depoimento-slide">
                    <div class="comentario-borda">
                        <img src="./img/perfilAluno.png" alt="Aluna" class="prof-img">
                        <p class="comentario-texto">
                            Sempre tive dificuldade em gostar de história, mas com o portal ficou tudo mais interessante e fácil de entender. As curiosidades e imagens ajudam muito no aprendizado!
                        </p>
                        <div>
                            <div class="comentario-nome">Juliana Souza</div>
                            <div class="comentario-cargo">Estudante do ensino médio</div>
                        </div>
                    </div>
                </div>
                <div class="depoimento-slide">
                    <div class="comentario-borda">
                        <img src="./img/perfilIdosa.png" alt="Idoso" class="prof-img">
                        <p class="comentario-texto">
                            Fico emocionada ao ver tantas histórias e memórias reunidas em um só lugar. O portal me faz relembrar momentos importantes da minha juventude e aprender ainda mais sobre o passado.
                        </p>
                        <div>
                            <div class="comentario-nome">Neusa Silva</div>
                            <div class="comentario-cargo">Aposentada e entusiasta de história</div>
                        </div>

    </section>

    <!-- Galeria de Memórias Visuais com efeito de slide lateral e zoom ao passar o mouse -->
    <section id="galeria" class="galeria" style=" background: #F9F6F0; padding: 56px 0;">
        <h2 style="font-family: 'Modern Antiqua', serif; font-size: 2.6rem; color: #4b2a17; text-align: center; margin-bottom: 38px; font-weight: 400;">
            Galeria de Construções Históricas
        </h2>
        <div class="galeria-carrossel" style="display: flex; gap: 50px; margin-left: 50px; overflow-x: auto; scroll-snap-type: x mandatory; padding: 24px 80px 32px 80px;">

            <!-- Stonehenge (~3000 a.C.) -->
            <div class="galeria-card">
                <div class="galeria-img-container">
                    <img src="./img/strohegne.png" alt="Stonehenge" class="galeria-img">
                    <div class="galeria-desc">
                        Stonehenge (aproximadamente 3000 a.C.) — Inglaterra.<br>
                        Monumento pré-histórico misterioso, construído há cerca de 5.000 anos.
                    </div>
                </div>
            </div>
            <!-- Ponte da Caravana (850 a.C.) -->
            <div class="galeria-card">
                <div class="galeria-img-container">
                    <img src="./img/ponteCaravana.png" alt="Ponte da Caravana" class="galeria-img">
                    <div class="galeria-desc">
                        Ponte da Caravana (850 a.C.) — Turquia.<br>
                        Com quase três mil anos, a ponte foi rota essencial para mercadores e viajantes em antigas rotas comerciais.
                    </div>
                </div>
            </div>
            <!-- Coliseu (80 d.C.) -->
            <div class="galeria-card">
                <div class="galeria-img-container">
                    <img src="./img/coliseu.png" alt="Coliseu" class="galeria-img">
                    <div class="galeria-desc">
                        Coliseu (80 d.C.) — Roma, Itália.<br>
                        Um dos maiores símbolos da Roma Antiga, palco de peças, batalhas de gladiadores e jogos.
                    </div>
                </div>
            </div>
            <!-- Mausoléu de Adriano (139 d.C.) -->
            <div class="galeria-card">
                <div class="galeria-img-container">
                    <img src="./img/mausoleu.png" alt="O Mausoléu de Adriano" class="galeria-img">
                    <div class="galeria-desc">
                        Mausoléu de Adriano (139 d.C.) — Roma, Itália.<br>
                        Também chamado de Castelo de Santo Ângelo, foi construído em 139 para ser o túmulo do imperador Adriano e seus sucessores. Hoje é museu e forte.
                    </div>
                </div>
            </div>
            <!-- Torre de Hércules (~ século II d.C.) -->
            <div class="galeria-card">
                <div class="galeria-img-container">
                    <img src="./img/torreHercules.png" alt="A Torre de Hércules" class="galeria-img">
                    <div class="galeria-desc">
                        Torre de Hércules (século II d.C.) — Espanha.<br>
                        Único farol romano do mundo ainda em funcionamento, com cerca de 1.900 anos.
                    </div>
                </div>
            </div>
            <!-- Basílica de Santa Sofia (532-537 d.C.) -->
            <div class="galeria-card">
                <div class="galeria-img-container">
                    <img src="./img/basilicaSantaSofia.png" alt="Basílica de Santa Sofia" class="galeria-img">
                    <div class="galeria-desc">
                        Basílica de Santa Sofia (532-537 d.C.) — Istambul, Turquia.<br>
                        Famosa por sua cúpula e pelas artes bizantinas. É um museu natural que já foi usado como igreja e mosteiro e que até hoje ainda abriga celebrações.
                    </div>
                </div>
            </div>
            <!-- Mesquita de Uqba (670 d.C.) -->
            <div class="galeria-card">
                <div class="galeria-img-container">
                    <img src="./img/mesquistaUqba.png" alt="A Mesquita de Uqba" class="galeria-img">
                    <div class="galeria-desc">
                        Mesquita de Uqba (670 d.C.) — Tunísia.<br>
                        Criada em 670, é uma das mais antigas do mundo islâmico.
                    </div>
                </div>
            </div>
            <!-- Templo Nashan (782 d.C.) -->
            <div class="galeria-card">
                <div class="galeria-img-container">
                    <img src="./img/templo.png" alt="Templo Nashan" class="galeria-img">
                    <div class="galeria-desc">
                        Templo Nashan (782 d.C.) — China.<br>
                        Templo budista construído em 782 d.C., feito de madeira e ainda muito bem conservado.
                    </div>
                </div>
            </div>
        </div>


        <div class="galeria-timeline">
            <div class="timeline-bar"></div>
            <div class="timeline-points">
                <div class="timeline-point ativo" style="left: 2%;">~3000 a.C.</div>
                <div class="timeline-point" style="left: 16%;">850 a.C.</div>
                <div class="timeline-point" style="left: 30%;">80 d.C.</div>
                <div class="timeline-point" style="left: 44%;">139 d.C.</div>
                <div class="timeline-point" style="left: 58%;">Séc. II</div>
                <div class="timeline-point" style="left: 72%;">532 d.C.</div>
                <div class="timeline-point" style="left: 86%;">670 d.C.</div>
                <div class="timeline-point" style="left: 98%;">782 d.C.</div>
            </div>
        </div>
    </section>


    <footer style="background: #583721; color: #fff; font-family: 'Modern Antiqua', serif; padding: 48px 0 0;">

        <div style="max-width: 1320px; margin: 0 auto; padding: 0 32px; display: flex; flex-wrap: wrap; gap: 32px; justify-content: space-between;">

            <!-- Logo e título -->
            <div style="flex: 1 1 220px; min-width: 220px;">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 18px;">
                    <a href="./index.php">
                        <img src="./img/logo.png" alt="logo" class="logo" style="width: 270px; height: auto;">
                    </a>
                </div>
                <button id="btn-hamburguer" class="hamburguer" onclick="toggleMenu()" aria-label="Abrir menu" style="background:none; border:none; cursor:pointer; display:none;">
                    <i class="fa fa-bars" style="font-size:2rem; color:#7a4a2e;"></i>
                </button>
            </div>

            <!-- Menus -->

                            


            <div style="flex: 1 1 160px; min-width: 160px; display: flex; flex-direction: column; gap: 4px;">
                <div style="font-size: 1rem; color: rgb(199, 158, 134); margin-bottom: 10px; letter-spacing: 1px;">Descubra</div>
                <div><a href="#home" style="color: #fff; text-decoration: none;">Home</a></div>
                <div><a href="#curiosidades" style="color: #fff; text-decoration: none;">Curiosidades da Semana</a></div>
                <div><a href="#destaques" style="color: #fff; text-decoration: none;">Destaques</a></div>
                <div><a href="#noticias" style="color: #fff; text-decoration: none;">Últimas Notícias</a></div>
                <div><a href="#feedback" style="color: #fff; text-decoration: none;">Feedback</a></div>
                <div><a href="#galeria" style="color: #fff; text-decoration: none;">Galeria</a></div>
            </div>

            <div style="flex: 1 1 160px; min-width: 160px; display: flex; flex-direction: column; gap: 4px;">
                <div style="font-size: 1rem; color: rgb(199, 158, 134); margin-bottom: 10px; letter-spacing: 1px;">Contato</div>
                <div><a href="./contato.php" style="color: #fff; text-decoration: none;">Mande uma mensagem para nós!</a></div>
                <div><a href="./crudAnunciante/cadastrar_anunciante.php" style="color: #fff; text-decoration: none;">Anuncie agora mesmo clicando aqui!</a></div>
            </div>

            <div style="flex: 1 1 160px; min-width: 160px; display: flex; flex-direction: column; gap: 4px;">
                <div style="font-size: 1rem; color: rgb(199, 158, 134); margin-bottom: 10px; letter-spacing: 1px;">Legal</div>
                <div><a href="./politica.php" style="color: #fff; text-decoration: none;">Política</a></div>
                <div><a href="./termos.php" style="color: #fff; text-decoration: none;">Termos</a></div>
            </div>

            <!-- botao anuncie aqui -->
            <div style="flex: 1 1 120px; min-width: 120px; display: flex; flex-direction: column;">
                <div style="width: 100%; display: flex; justify-content: flex-end; margin-top: 35px;">
                    <a href="./crudAnunciante/cadastrar_anunciante.php"
                        class="btnAnunciFooter"
                        style="background:#7a4a2e; color:#fff; border-radius:18px; padding:10px 18px; font-size:1.1rem; text-align:center; min-width:110px; box-sizing:border-box; margin-bottom:28px;">
                        Anuncie aqui!
                    </a>
                </div>
                <!-- Espaçador flexível -->
                <div style="flex:1"></div>
                <!-- Logos das redes sociais -->
                <div style="display: flex; gap: 14px; justify-content: flex-end;">
                    <a href="index.php" style="color: #fff; font-size: 1.6rem; transition: 0.3s;" title="Facebook">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="index.php" style="color: #fff; font-size: 1.6rem; transition: 0.3s;" title="Instagram">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="index.php" style="color: #fff; font-size: 1.6rem; transition: 0.3s;" title="Twitter">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a>
                    <a href="index.php" style="color: #fff; font-size: 1.6rem; transition: 0.3s;" title="LinkedIn">
                        <i class="fa-brands fa-linkedin-in"></i>
                    </a>
                    <a href="index.php" style="color: #fff; font-size: 1.6rem; transition: 0.3s;" title="YouTube">
                        <i class="fa-brands fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Linha divisória -->
        <hr style="border: none; border-top: 2px solid #5b3323; margin: 32px 0 0 0;">

        <!-- Rodapé final -->
        <div style="max-width: 1320px; margin: 0 auto; padding: 18px 32px 24px 32px; display: flex; flex-wrap: wrap; justify-content: flex-end; align-items: center; font-size: 1.05rem; margin-top: -40px;">
            <div style="color:rgb(221, 188, 166); text-align: right;">
                Todos os direitos reservados © 2025
            </div>
        </div>
    </footer>
    <script src="./script/scriptMenu.js"></script>
</body>

</html>
<script>
    const anuncios = <?php echo json_encode(array_filter($anuncios, fn($a) => $a['ativo'])); ?>;

    window.onload = function() {
        if (anuncios.length > 0) {
            setTimeout(() => {
                const sorteado = anuncios[Math.floor(Math.random() * anuncios.length)];

                let html = '';
                if (sorteado.imagem) {
                    html += `<img src="crudAnuncio/${sorteado.imagem}" style="max-width:100%"><br>`;
                }

                html += `<strong>${sorteado.nome}</strong><br>`;
                html += `<p>${sorteado.texto}</p>`;
                html += `<a href="${sorteado.link}" target="_blank">Ver mais</a>`;

                document.getElementById('conteudo-modal').innerHTML = html;
                document.getElementById('anuncio-modal').style.display = 'block';
            }, 1000); // exibe após 1 segundo
        }
    };
</script>