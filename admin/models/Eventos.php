<?php

class Eventos {
    private $conn;

    public function __construct($conexao) {
        $this->conn = $conexao;
        if ($this->conn->connect_error) {
            die("Erro de conexão: " . $this->conn->connect_error);
        }
    }

    public function create($titulo, $descricao, $data_evento, $local, $autor_id,$url_imagem,$stato) {
        $sql ="INSERT INTO eventos (titulo, descricao, data_evento, localizacao, autor_id,url_imagem,stato)
         VALUES ('$titulo', '$descricao', '$data_evento', '$local', '$autor_id','$url_imagem','$stato')";
    
         $r = $this->conn->query($sql);
        return $r;
    }

    public function read($id = null) {
        if ($id) {
            $stmt = $this->conn->prepare("SELECT * FROM eventos WHERE id = ?");
            $stmt->bind_param("i", $id);
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM eventos");
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function update($id, $titulo, $descricao, $data_evento, $local, $url_imagem,$stato) {
        $stmt = $this->conn->prepare("UPDATE eventos SET titulo = ?, descricao = ?, data_evento = ?, localizacao = ?, url_imagem=?,stato= ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $titulo, $descricao, $data_evento, $local, $url_imagem,$stato, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM eventos WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}





?>