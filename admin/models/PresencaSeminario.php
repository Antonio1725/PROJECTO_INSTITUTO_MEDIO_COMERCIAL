<?php
class PresencaSeminario {
    private $conn;

    // Construtor para inicializar a conexão com o banco de dados
    public function __construct($conexao) {
        $this->conn = $conexao;
       
    }

    // Função para criar uma presença no seminário
    public function create($seminario_id, $nome, $estudante, $email, $telefone) {
        $stmt = $this->conn->prepare("INSERT INTO presenca_seminario (seminario_id, nome, estudante, email, telefone) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $seminario_id, $nome, $estudante, $email, $telefone);
        return $stmt->execute();
    }

    // Função para ler as presenças, opcionalmente por ID
    public function read($id = null) {
        if ($id) {
            $stmt = $this->conn->prepare("SELECT * FROM presenca_seminario WHERE id = ?");
            $stmt->bind_param("i", $id);
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM presenca_seminario");
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Função para atualizar uma presença no seminário
    public function update($id, $seminario_id, $nome, $estudante, $email, $telefone) {
        $stmt = $this->conn->prepare("UPDATE presenca_seminario SET seminario_id = ?, nome = ?, estudante = ?, email = ?, telefone = ? WHERE id = ?");
        $stmt->bind_param("issssi", $seminario_id, $nome, $estudante, $email, $telefone, $id);
        return $stmt->execute();
    }

    // Função para deletar uma presença no seminário
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM presenca_seminario WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }


    public function isPresencaRegistrada($seminario_id, $email) {


       $sql ="SELECT COUNT(*) FROM presenca_seminario WHERE seminario_id = '$seminario_id' AND email = '$email'";
        $numLinha = mysqli_query($this->conn,$sql);
        return $numLinha  > 0; // Retorna verdadeiro se já houver um registro
    }
}
?>
