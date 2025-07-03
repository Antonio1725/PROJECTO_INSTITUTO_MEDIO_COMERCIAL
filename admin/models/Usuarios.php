<?php

class Usuarios
{
    private $conn;

    public function __construct($conexao)
    {
        $this->conn = $conexao;
        if ($this->conn->connect_error) {
            die("Erro de conexão: " . $this->conn->connect_error);
        }
    }

    public function create($nome_completo, $nome_usual, $email, $senha, $nivel_acesso, $imagem, $estado)
    {
        $senha_hash = $senha;

        // Prepara a consulta SQL
        $stmt = $this->conn->prepare("
        INSERT INTO usuarios (nome_completo, nome_usual, email, senha, nivel_acesso, imagem, estato)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

        if (!$stmt) {
            echo "Erro ao preparar a consulta: " . $this->conn->error;
            return false;
        }

        // Associa os parâmetros
        $stmt->bind_param("sssssss", $nome_completo, $nome_usual, $email, $senha_hash, $nivel_acesso, $imagem, $estado);

        // Executa a consulta
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            echo "Erro ao executar a consulta: " . $stmt->error;
            $stmt->close();
            return false;
        }
    }


    public function read($id = null)
    {
        if ($id) {
            $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE id = ?");
            $stmt->bind_param("i", $id);
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM usuarios");
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function update($id, $nome_completo, $nome_usual, $email, $senha = null, $nivel_acesso, $imagem)
    {
        if ($senha) {
            $senha_hash = password_hash($senha, PASSWORD_BCRYPT); // Atualiza a senha se fornecida
            $stmt = $this->conn->prepare("
                UPDATE usuarios 
                SET nome_completo = ?, nome_usual = ?, email = ?, senha = ?, nivel_acesso = ?, imagem = ? 
                WHERE id = ?
            ");
            $stmt->bind_param("ssssssi", $nome_completo, $nome_usual, $email, $senha_hash, $nivel_acesso, $imagem, $id);
        } else {
            $stmt = $this->conn->prepare("
                UPDATE usuarios 
                SET nome_completo = ?, nome_usual = ?, email = ?, nivel_acesso = ?, imagem = ? 
                WHERE id = ?
            ");
            $stmt->bind_param("sssssi", $nome_completo, $nome_usual, $email, $nivel_acesso, $imagem, $id);
        }

        return $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function login($nome_usual, $senha)
    {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE nome_usual = ?");
        $stmt->bind_param("s", $nome_usual);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        if (password_verify($senha, $usuario['senha'])) {

            $_SESSION["nome_completo"] = $usuario['nome_completo'];
            $_SESSION["id_usuario"] = $usuario['id'];
            $_SESSION["nivel_acesso"] = $usuario['nivel_acesso'];
            $_SESSION["imagem"] = $usuario['imagem'];
            return true; // Retorna os dados do usuário autenticado
        } else {

            return false; // Retorna falso se as credenciais forem inválidas
        }
    }
}

// Exemplo de uso
/*
$usuarios = new Usuarios('localhost', 'usuario', 'senha', 'banco_de_dados');

// Criar usuário
$usuarios->create(
    'Nome Completo',
    'Nome Usual',
    'email@example.com',
    'senha123',
    'admin',
    'caminho/para/imagem.jpg'
);

// Ler todos os usuários
print_r($usuarios->read());

// Atualizar usuário
$usuarios->update(1, 'Nome Atualizado', 'Nome Usual Atualizado', 'email@novo.com', null, 'usuario', 'caminho/para/nova_imagem.jpg');

// Deletar usuário
$usuarios->delete(1);
*/
