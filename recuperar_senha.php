<?php
session_start();
include_once './conexao/config.php';
include_once './conexao/funcoes.php';

$db = (new Database())->getConnection();
$usuario = new Usuario($db);

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $codigo = $usuario->gerarCodigoVerificacao($email);

        if ($codigo) {
            $link = "http://localhost/portalNoticias/verificar_codigo.php?codigo=$codigo";
            $mensagem = "Um e-mail foi enviado com instruções para redefinir sua senha.";


            echo "<p>Clique no link para redefinir sua senha: <a href='$link'>$link</a></p>";
        } else {
            $mensagem = "Erro ao gerar código de verificação.";
        }
    } else {
        $mensagem = "E-mail não encontrado.";
    }
}
?>

<!<!DOCTYPE html>
    <html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <title>Recuperar Senha</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="./styles/stylesRecSenha.css">
        <link rel="stylesheet" href="./styles/stylesResponsividade.css">
        <link rel="stylesheet" href="./styles/stylesTemaEscuro.css">

    </head>

    <body>
        <div class="recuperar-container">
            <h2>Recuperar Senha</h2>
            <form method="POST">
                <label for="email">Digite seu e-mail cadastrado:</label>
                <input type="email" name="email" required>
                <input type="submit" value="Enviar código">
            </form>
            <?php if (!empty($mensagem)) echo '<p>' . $mensagem . '</p>'; ?>
        </div>
        <script src="./script/scriptTemaEscuro.js"></script>
    </body>

    </html>