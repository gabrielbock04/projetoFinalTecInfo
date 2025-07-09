<?php
class Usuario
{
    private $conn;
    private $table_name = "usuarios";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function registrar($nome, $sexo, $fone, $email, $senha)
    {
        $query = "INSERT INTO " . $this->table_name . " (nome, sexo, fone, email, senha) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $hashed_password = password_hash($senha, PASSWORD_BCRYPT);
        $stmt->execute([$nome, $sexo, $fone, $email, $hashed_password]);
        return $stmt;
    }

    public function login($email, $senha)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario;
        }
        return false;
    }

    public function criar($nome, $sexo, $fone, $email, $senha, $confirmar_senha)
    {
        return $this->registrar($nome, $sexo, $fone, $email, $senha, $confirmar_senha);
    }

    public function ler()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function lerPorId($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $nome, $sexo, $fone, $email, $foto = null, $senha = null)
    {
        if ($senha !== null && $senha !== '') {
            $hashed_password = password_hash($senha, PASSWORD_BCRYPT);
            $query = "UPDATE " . $this->table_name . " 
                  SET nome = ?, sexo = ?, fone = ?, email = ?, foto = ?, senha = ? 
                  WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$nome, $sexo, $fone, $email, $foto, $hashed_password, $id]);
        } else {
            $query = "UPDATE " . $this->table_name . " 
                  SET nome = ?, sexo = ?, fone = ?, email = ?, foto = ? 
                  WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$nome, $sexo, $fone, $email, $foto, $id]);
        }
        return $stmt;
    }


    public function deletar($id)
    {
        // Deleta notícias vinculadas a esse autor
        $stmtNoticia = $this->conn->prepare("DELETE FROM noticias WHERE autor = ?");
        $stmtNoticia->execute([$id]);

        // Agora deleta o usuário
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt;
    }

    // Gera um código de verificação e o salva no banco de dados
    public function gerarCodigoVerificacao($email)
    {
        $codigo = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, 10);
        $query = "UPDATE " . $this->table_name . " SET codigo_autenticacao = ? WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$codigo, $email]);
        return ($stmt->rowCount() > 0) ? $codigo : false;
    }

    // Verifica se o código de verificação é válido
    public function verificarCodigo($codigo)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE codigo_autenticacao = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$codigo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Redefine a senha do usuário e remove o código de verificação
    public function redefinirSenha($codigo, $novaSenha)
    {
        $hashed_password = password_hash($novaSenha, PASSWORD_BCRYPT);
        $query = "UPDATE " . $this->table_name . " SET senha = ?, codigo_autenticacao= NULL WHERE codigo_autenticacao = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$hashed_password, $codigo]);
        return $stmt->rowCount() > 0;
    }
}
?>
<?php
class Noticia
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function listarTodas()
    {
        $sql = "SELECT * FROM noticias ORDER BY data DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT n.*, u.nome AS autor_nome, u.foto AS autor_foto
            FROM noticias n
            LEFT JOIN usuarios u ON n.autor = u.id
            WHERE n.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }




    public function criar($titulo, $noticia, $imagem, $data, $autor)
    {
        $stmt = $this->conn->prepare("INSERT INTO noticias (titulo, noticia, imagem, data, autor) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$titulo, $noticia, $imagem, $data, $autor]);
    }

    public function atualizar($id, $titulo, $noticia, $imagem)
    {
        $stmt = $this->conn->prepare("UPDATE noticias SET titulo = ?, noticia = ?, imagem = ? WHERE id = ?");
        $stmt->execute([$titulo, $noticia, $imagem, $id]);
    }

    public function excluir($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM noticias WHERE id = ?");
        $stmt->execute([$id]);
    }
    public function buscarPorTitulo($termo)
    {
        $stmt = $this->conn->prepare("SELECT * FROM noticias WHERE titulo LIKE ? ORDER BY data DESC");
        $stmt->execute(["%" . $termo . "%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
<?php
class Funcionario
{
    private $conn;
    private $table = "funcionarios";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function listarTodos()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarPorUsuarioId($usuario_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE usuario_id = ?");
        $stmt->execute([$usuario_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function excluir($id) {
    $stmt = $this->conn->prepare("DELETE FROM funcionarios WHERE id = ?");
    return $stmt->execute([$id]);
}


    public function atualizar($id, $dados)
    {
        $sql = "UPDATE {$this->table} SET 
            nome = ?, sobrenome = ?, data_nascimento = ?, cpf_cnpj = ?, sexo = ?, telefone = ?, 
            email = ?, endereco = ?, estado_civil = ?, raca_cor = ?, escolaridade = ?, 
            nacionalidade = ?, rg = ?
            WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $dados['nome'],
            $dados['sobrenome'],
            $dados['data_nascimento'],
            $dados['cpf_cnpj'],
            $dados['sexo'],
            $dados['telefone'],
            $dados['email'],
            $dados['endereco'],
            $dados['estado_civil'],
            $dados['raca_cor'],
            $dados['escolaridade'],
            $dados['nacionalidade'],
            $dados['rg'],
            $id
        ]);
    }
}
?>
<?php

class Comentario
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function listarPorNoticia($noticia_id)
    {
        $sql = "SELECT c.*, u.nome AS nome, u.foto AS foto
            FROM comentarios c
            JOIN usuarios u ON c.usuario_id = u.id
            WHERE c.noticia_id = ?
            ORDER BY c.data DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$noticia_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function adicionar($noticia_id, $usuario_id, $comentario)
    {
        $stmt = $this->conn->prepare("INSERT INTO comentarios (noticia_id, usuario_id, comentario) VALUES (?, ?, ?)");
        $stmt->execute([$noticia_id, $usuario_id, $comentario]);
    }
}
?>

<?php
class Anuncio
{
    private $conn;
    private $table = "anuncio";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function criar($dados)
    {
        $query = "INSERT INTO $this->table (nome, imagem, link, texto, ativo, destaque, data_cadastro, valorAnuncio, anunciante_id)
              VALUES (:nome, :imagem, :link, :texto, :ativo, :destaque, NOW(), :valorAnuncio, :anunciante_id)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($dados);
    }

    public function listarTodos()
    {
        $query = "SELECT * FROM $this->table ORDER BY data_cadastro DESC";
        return $this->conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $dados)
    {
        $query = "UPDATE $this->table SET nome = :nome, imagem = :imagem, link = :link, texto = :texto,
                  ativo = :ativo, destaque = :destaque, valorAnuncio = :valorAnuncio WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $dados[':id'] = $id;
        return $stmt->execute($dados);
    }

    public function excluir($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function buscarPorAnunciante($anunciante_id)
    {
        $query = "SELECT * FROM anuncio WHERE anunciante_id = :anunciante_id ORDER BY data_cadastro DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':anunciante_id' => $anunciante_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function listarAtivos()
    {
        $sql = "SELECT * FROM anuncio WHERE ativo = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<?php
class Anunciante
{
    private $conn;
    private $table = "anunciantes";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function cadastrar($dados)
    {
        $query = "INSERT INTO $this->table (nome, email, telefone, cpf_cnpj, endereco_comercial,
                  categoria_anuncio, descricao_empresa, senha)
                  VALUES (:nome, :email, :telefone, :cpf_cnpj, :endereco_comercial, :categoria_anuncio, :descricao_empresa, :senha)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($dados);
    }

    public function listarTodos()
    {
        $query = "SELECT id, nome, email, telefone, cpf_cnpj, endereco_comercial, categoria_anuncio FROM $this->table";
        return $this->conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarPorEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $dados)
    {
        $query = "UPDATE $this->table SET nome = :nome, email = :email, telefone = :telefone,
                  endereco_comercial = :endereco_comercial, categoria_anuncio = :categoria_anuncio,
                  descricao_empresa = :descricao_empresa WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $dados[':id'] = $id;
        return $stmt->execute($dados);
    }

    public function excluir($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
