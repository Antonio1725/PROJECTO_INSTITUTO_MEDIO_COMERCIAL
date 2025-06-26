<?php
class Exames {
    private $conn;

    // Construtor que recebe a conexão com o banco de dados
    public function __construct($conexao) {
        $this->conn = $conexao;
        if ($this->conn->connect_error) {
            die("Erro de conexão: " . $this->conn->connect_error);
        }
    }

    // Criar (Inserir novo exame)
    public function create($nome, $curso, $data, $hora, $duracao, $stato, $idUsuario) {
        $stmt = $this->conn->prepare("INSERT INTO exames (nome, curso, data, hora, duracao, stato, idUsuario) 
                                     VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $nome, $curso, $data, $hora, $duracao, $stato, $idUsuario);
        return $stmt->execute();
    }

    // Ler (Selecionar exames, pode ser com ID específico ou todos)
    public function read($id = null) {

        if ($id) {
            $stmt = $this->conn->prepare("SELECT * FROM exames WHERE id = ?");
            $stmt->bind_param("i", $id);

        } else {
            $stmt = $this->conn->prepare("SELECT * FROM exames");
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Atualizar (Editar exame existente)
    public function update($id, $nome, $curso, $data, $hora,  $stato) {
        $stmt = $this->conn->prepare("UPDATE exames SET nome = ?, curso = ?, data = ?, hora = ?, stato = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $nome, $curso, $data, $hora, $stato, $id);
        return $stmt->execute();
    }

    // Deletar (Remover exame)
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM exames WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>

