<?php
class Alunos
{
    private $conn;

    public function __construct($conexao) {
        $this->conn = $conexao;
        if ($this->conn->connect_error) {
            die("Erro de conexão: " . $this->conn->connect_error);
        }
    }

    // Método para criar um novo aluno
    public function create($nome, $email, $telefone, $dataInscricao)
    {
        $query = "INSERT INTO alunos (nome, email, telefone, data_inscricao) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssss", $nome, $email, $telefone, $dataInscricao);
        return $stmt->execute();
    }

    // Método para listar todos os alunos
    public function readAll()
    {
        $query = "SELECT * FROM alunos ORDER BY nome";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Método para buscar um aluno pelo ID
    public function readById($id)
    {
        $query = "SELECT * FROM alunos WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Método para atualizar um aluno
    public function update($id, $nome, $email, $telefone, $dataInscricao)
    {
        $query = "UPDATE alunos SET nome = ?, email = ?, telefone = ?, data_inscricao = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssi", $nome, $email, $telefone, $dataInscricao, $id);
        return $stmt->execute();
    }

    // Método para deletar um aluno
    public function delete($id)
    {
        $query = "DELETE FROM alunos WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
