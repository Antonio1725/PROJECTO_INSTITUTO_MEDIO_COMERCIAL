
<?php
class Videos {
    private $conn;

    public function __construct($conexao) {
        $this->conn = $conexao;
        if ($this->conn->connect_error) {
            die("Erro de conexão: " . $this->conn->connect_error);
        }
    }

    public function create($url_video,$titulo, $descricao, $estado, $usuario_id) {
        $stmt = $this->conn->prepare("INSERT INTO videos (url_video,titulo, descricao, estado, usuario_id) VALUES (?,?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $url_video,$titulo, $descricao, $estado, $usuario_id);
        return $stmt->execute();
    }

    public function read($id = null) {
        if ($id) {
            $stmt = $this->conn->prepare("SELECT * FROM videos WHERE id = ?");
            $stmt->bind_param("i", $id);
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM videos");
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function update($id, $url_video, $descricao,  $estado) {
        $stmt = $this->conn->prepare("UPDATE videos SET url_video = ?, descricao = ?, estado= ? WHERE id = ?");
        $stmt->bind_param("sssi", $url_video, $descricao, $estado, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM videos WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}

?>