<?php
class Carrossel
{
    private $conn;

    public function __construct($conexao) {
        $this->conn = $conexao;
        if ($this->conn->connect_error) {
            die("Erro de conexão: " . $this->conn->connect_error);
        }
    }
    // Método para criar um novo banner
    public function create($urlBanner, $descricao, $estado,$titulo,$sub_titulo)
    {
        $query = "INSERT INTO carrossel (titulo,sub_titulo,url_banner, descricao,  estado) VALUES (?,?, ?, ?,?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssss",$titulo,$sub_titulo, $urlBanner, $descricao, $estado);
        return $stmt->execute();
    }

    // Método para listar todos os banners
    public function readAll()
    {
        $query = "SELECT * FROM carrossel ORDER BY id DESC";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Método para buscar um banner pelo ID
    public function readById($id)
    {
        $query = "SELECT * FROM carrossel WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Método para atualizar um banner
    public function update($id, $urlBanner, $descricao,  $estado, $titulo,$sub_titulo)
    {
        $query = "UPDATE carrossel SET titulo = ?, sub_titulo=?,url_banner = ?, descricao = ?,  estado = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssssi",$titulo,$sub_titulo, $urlBanner, $descricao,  $estado, $id);
        return $stmt->execute();
    }

    // Método para deletar um banner
    public function delete($id)
    {
        $query = "DELETE FROM carrossel WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}


?>