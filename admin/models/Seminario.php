<?php

class Seminario {
    private $conn;

    // Construtor para inicializar a conexão com o banco de dados
    public function __construct($conexao) {
        $this->conn = $conexao;
        if ($this->conn->connect_error) {
            die("Erro de conexão: " . $this->conn->connect_error);
        }
    }

    // Função para criar um seminário
    public function create($nome, $descricao, $dataEvento, $localizacao, $url_imagem,$stato,$id_usuario) {
        $stmt = $this->conn->prepare("INSERT INTO seminario (nome, descricao, dataEvento, localizacao, url_imagem,estado,
idUsuario) VALUES (?, ?, ?, ?, ?,?,?)");
        $stmt->bind_param("ssssssi", $nome, $descricao, $dataEvento, $localizacao, $url_imagem,$stato,$id_usuario);
        return $stmt->execute();
    }

    // Função para ler os seminários, opcionalmente por ID
    public function read($id = null) {
        if ($id) {
            $stmt = $this->conn->prepare("SELECT * FROM seminario WHERE id = ?");
            $stmt->bind_param("i", $id);
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM seminario");
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Função para atualizar um seminário
    public function update($id, $nome, $descricao, $dataEvento, $localizacao, $url_imagem) {
        $stmt = $this->conn->prepare("UPDATE seminario SET nome = ?, descricao = ?, dataEvento = ?, localizacao = ?, url_imagem = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $nome, $descricao, $dataEvento, $localizacao, $url_imagem, $id);
        return $stmt->execute();
    }

    // Função para deletar um seminário
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM seminario WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}

?>
