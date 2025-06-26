<?php
class Noticias {
    private $conn;

    public function __construct($conexao) {
        $this->conn = $conexao;
        if ($this->conn->connect_error) {
            die("Erro de conexão: " . $this->conn->connect_error);
        }
    }

    public function create($titulo, $conteudo, $url_imagem, $autor_id,$stato) {
        $stmt = $this->conn->prepare("INSERT INTO noticias (titulo, conteudo, url_imagem, autor_id,stato) VALUES (?, ?, ?, ?,?)");
        $stmt->bind_param("sssis", $titulo, $conteudo, $url_imagem, $autor_id,$stato);
        return $stmt->execute();
    }

    public function read($id = null) {
        if ($id) {
            $stmt = $this->conn->prepare("SELECT * FROM noticias WHERE id = ?");
            $stmt->bind_param("i", $id);
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM noticias");
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function update($id, $titulo, $conteudo, $url_imagem, $stato) {
        $stmt = $this->conn->prepare("UPDATE noticias SET titulo = ?, conteudo = ?, url_imagem = ?, stato = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $titulo, $conteudo, $url_imagem, $stato, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM noticias WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}


?>