<?php



// Reutilizar a estrutura para as demais tabelas

class Galeria {
    private $conn;

    public function __construct($conexao) {
        $this->conn = $conexao;
        if ($this->conn->connect_error) {
            die("Erro de conexão: " . $this->conn->connect_error);
        }
    }

    public function create($url_imagem, $descricao, $estado, $usuario_id) {
        $stmt = $this->conn->prepare("INSERT INTO galeria (url_imagem, descricao, estado, usuario_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $url_imagem, $descricao, $estado, $usuario_id);
        return $stmt->execute();
    }

    public function read($id = null) {
        if ($id) {
            $stmt = $this->conn->prepare("SELECT * FROM galeria WHERE id = ?");
            $stmt->bind_param("i", $id);
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM galeria");
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function update($id, $url_imagem, $descricao, $estado, $usuario_id) {
        $stmt = $this->conn->prepare("UPDATE galeria SET url_imagem = ?, descricao = ?, estado = ?, usuario_id = ? WHERE id = ?");
        $stmt->bind_param("sssii", $url_imagem, $descricao, $estado, $usuario_id, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM galeria WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}


?>