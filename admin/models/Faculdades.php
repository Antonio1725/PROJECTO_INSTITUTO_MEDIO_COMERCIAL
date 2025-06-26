<?php

class Faculdades {
    private $conn;

    // Construtor para inicializar a conexão com o banco de dados
    public function __construct($conexao) {
        $this->conn = $conexao;
        if ($this->conn->connect_error) {
            die("Erro de conexão: " . $this->conn->connect_error);
        }
    }

    // Criar uma nova faculdade
    public function create($nome, $descricao, $url_imagem) {
        $stmt = $this->conn->prepare("INSERT INTO membros_direccao (nome, descricao, url_imagem, dataCreate) VALUES (?, ?, ?, ?)");
        $dataCreate = date('Y-m-d H:i:s'); // Data de criação com a data atual
        $stmt->bind_param("ssss", $nome, $descricao, $url_imagem, $dataCreate);
        return $stmt->execute();
    }

    // Ler todas as faculdades ou uma específica por ID
    public function read($id = null) {
        if ($id) {
            $stmt = $this->conn->prepare("SELECT * FROM membros_direccao WHERE id = ?");
            $stmt->bind_param("i", $id);
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM membros_direccao");
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Atualizar uma faculdade existente
    public function update($id, $nome, $descricao, $url_imagem) {
        $stmt = $this->conn->prepare("UPDATE membros_direccao SET nome = ?, descricao = ?, url_imagem = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nome, $descricao, $url_imagem, $id);
        return $stmt->execute();
    }

    // Deletar uma faculdade
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM membros_direccao WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}

?>

