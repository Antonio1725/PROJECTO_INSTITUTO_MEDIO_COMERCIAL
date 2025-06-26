<?php

class Inscricoes {
    private $conn;
    public function __construct($conexao) {
        $this->conn = $conexao;
        if ($this->conn->connect_error) {
            die("Erro de conexão: " . $this->conn->connect_error);
        }
    }

    public function create($aluno_id, $evento_id, $data_inscricao) {
        $stmt = $this->conn->prepare("INSERT INTO inscricoes (aluno_id, evento_id, data_inscricao) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $aluno_id, $evento_id, $data_inscricao);
        return $stmt->execute();
    }

    public function read($id = null) {
        if ($id) {
            $stmt = $this->conn->prepare("SELECT * FROM inscricoes WHERE id = ?");
            $stmt->bind_param("i", $id);
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM inscricoes");
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function update($id, $aluno_id, $evento_id, $data_inscricao) {
        $stmt = $this->conn->prepare("UPDATE inscricoes SET aluno_id = ?, evento_id = ?, data_inscricao = ? WHERE id = ?");
        $stmt->bind_param("iisi", $aluno_id, $evento_id, $data_inscricao, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM inscricoes WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}


?>