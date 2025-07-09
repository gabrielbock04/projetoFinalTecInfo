<?php
session_start();
include_once './conexao/config.php';
include_once './conexao/funcoes.php';

$db = (new Database())->getConnection();
$usuario = new Usuario($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        // 1. Tenta logar como usuário (admin ou comum)
        if ($dados_usuario = $usuario->login($email, $senha)) {
            $_SESSION['usuario_id'] = $dados_usuario['id'];
            $_SESSION['is_admin'] = $dados_usuario['is_admin'];
            $_SESSION['cargo'] = $dados_usuario['cargo'] ?? null;
            setcookie("nome_usuario", $dados_usuario['nome'], time() + (86400 * 30), "/");

            // Verifica se é funcionário
            $stmt = $db->prepare("SELECT * FROM funcionarios WHERE usuario_id = ?");
            $stmt->execute([$dados_usuario['id']]);
            $func = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['eh_funcionario'] = $func ? true : false;

            // Redireciona conforme tipo
            if ($_SESSION['is_admin'] == 1) {
                header('Location: index.php');
            } else {
                header('Location: index.php');
            }
            exit();
        } else {
            // 2. Tenta logar como anunciante
            $anunciante = new Anunciante($db);
            $dados_anunciante = $anunciante->buscarPorEmail($email);

            if ($dados_anunciante && password_verify($senha, $dados_anunciante['senha'])) {
                $_SESSION['usuario_id'] = $dados_anunciante['id']; 
                $_SESSION['tipo'] = 'anunciante';
                $_SESSION['anunciante_id'] = $dados_anunciante['id'];
                $_SESSION['anunciante_nome'] = $dados_anunciante['nome'];
                setcookie("nome_anunciante", $dados_anunciante['nome'], time() + (86400 * 30), "/");

                header('Location: ./crudAnunciante/painel_anunciante.php');
                exit();
            } else {
                $mensagem_erro = "Credenciais inválidas!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Realize o Login!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./styles/login.css">
    <link rel="stylesheet" href="./styles/stylesLogin.css">
    <link rel="stylesheet" href="./styles/stylesResponsividade.css">
    <link rel="stylesheet" href="./styles/stylesTemaEscuro.css">
</head>

<body>
    <div class="container">
        <div class="box">
            <h1>Realize seu Login</h1>
            <form method="POST">
                <label for="email">Email:</label>
                <input type="email" name="email" required>
                <label for="senha">Senha:</label>
                <input type="password" name="senha" required>
                <input type="submit" name="login" value="Login">
            </form>

            <div class="titulo-senha">
                <p>Esqueceu a senha? <a href="./recuperar_senha.php">Recuperar senha</a></p>
            </div>


            <div class="titulo-links">
                <p>Não possui uma conta? Cadastre-se agora!</p>
            </div>

            <ul>
                <div class="cadastro-links">
                    <a href="./crudUsuarios/cadastro_usuario.php">Para usuários</a> <a>|</a>
                    <a href="./crudAnunciante/cadastrar_anunciante.php">Para anunciantes</a>
                </div>
            </ul>


            <a href="index.php" class="btn-voltar">Voltar</a>

            <div class="mensagem">
                <?php if (isset($mensagem_erro)) echo '<p>' . $mensagem_erro . '</p>'; ?>
            </div>
        </div>
    </div>
    <script src="./script/scriptTemaEscuro.js"></script>
</body>

</html>