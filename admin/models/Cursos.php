<?php

class Cursos
{
    private $conn;

    public function __construct($conexao) {
        $this->conn = $conexao;
        if ($this->conn->connect_error) {
            die("Erro de conexão: " . $this->conn->connect_error);
        }
    }
    // Método para criar um novo curso
    public function create($nome, $descricao, $estatus, $descricaoRequisitos, $taxa1Termo, $taxa2Termo, $taxa3Termo, $descricaoPrecos, $descricaoPerfilAluno, $aplicacaoEtapa1, $aplicacaoEtapa2, $aplicacaoEtapa3, $aplicacaoEtapa4, $aplicacaoEtapa5, $imgBanner, $primeiroSemestreTitulo, $primeiroSemestreDesc, $segundoSemestreTitulo, $segundoSemestreDesc, $faculdade, $inicio, $fim, $duracao, $totalVaga)
    {
        $query = "INSERT INTO cursos (nome, descricao, estatus, descricao_requisitos, taxa_1termo, taxa_2termo, taxa_3termo, descricao_precos, descricao_do_perfil_aluno, aplicacao_desc_etapa1, aplicacao_desc_etapa2, aplicacao_desc_etapa3, aplicacao_desc_etapa4, aplicacao_desc_etapa5, img_banner, primeiro_semestre_titulo, primeiro_semestre_desc, segundo_semestre_titulo, segundo_semestre_desc, faculdade, inicio, fim, duracao, total_vaga) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssssddsddssssssssssssii", $nome, $descricao, $estatus, $descricaoRequisitos, $taxa1Termo, $taxa2Termo, $taxa3Termo, $descricaoPrecos, $descricaoPerfilAluno, $aplicacaoEtapa1, $aplicacaoEtapa2, $aplicacaoEtapa3, $aplicacaoEtapa4, $aplicacaoEtapa5, $imgBanner, $primeiroSemestreTitulo, $primeiroSemestreDesc, $segundoSemestreTitulo, $segundoSemestreDesc, $faculdade, $inicio, $fim, $duracao, $totalVaga);
        return $stmt->execute();
    }

    // Método para listar todos os cursos
    public function readAll()
    {
        $query = "SELECT * FROM cursos ORDER BY id DESC";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Método para buscar um curso pelo ID
    public function readById($id)
    {
        $query = "SELECT * FROM cursos WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Método para atualizar um curso
    public function update($id, $nome, $descricao, $estatus, $descricaoRequisitos, $taxa1Termo, $taxa2Termo, $taxa3Termo, $descricaoPrecos, $descricaoPerfilAluno, $aplicacaoEtapa1, $aplicacaoEtapa2, $aplicacaoEtapa3, $aplicacaoEtapa4, $aplicacaoEtapa5, $imgBanner, $primeiroSemestreTitulo, $primeiroSemestreDesc, $segundoSemestreTitulo, $segundoSemestreDesc, $faculdade, $inicio, $fim, $duracao, $totalVaga)
    {
        $query = "UPDATE cursos SET nome = ?, descricao = ?, estatus = ?, descricao_requisitos = ?, taxa_1termo = ?, taxa_2termo = ?, taxa_3termo = ?, descricao_precos = ?, descricao_do_perfil_aluno = ?, aplicacao_desc_etapa1 = ?, aplicacao_desc_etapa2 = ?, aplicacao_desc_etapa3 = ?, aplicacao_desc_etapa4 = ?, aplicacao_desc_etapa5 = ?, img_banner = ?, primeiro_semestre_titulo = ?, primeiro_semestre_desc = ?, segundo_semestre_titulo = ?, segundo_semestre_desc = ?, faculdade = ?, inicio = ?, fim = ?, duracao = ?, total_vaga = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssssddsddssssssssssssiii", $nome, $descricao, $estatus, $descricaoRequisitos, $taxa1Termo, $taxa2Termo, $taxa3Termo, $descricaoPrecos, $descricaoPerfilAluno, $aplicacaoEtapa1, $aplicacaoEtapa2, $aplicacaoEtapa3, $aplicacaoEtapa4, $aplicacaoEtapa5, $imgBanner, $primeiroSemestreTitulo, $primeiroSemestreDesc, $segundoSemestreTitulo, $segundoSemestreDesc, $faculdade, $inicio, $fim, $duracao, $totalVaga, $id);
        return $stmt->execute();
    }
    // Método para atualizar os dados do curso
    public function updateCourse($id, $nome, $faculdade, $duracao, $inicio, $fim, $total_vaga, $estado, $img_banner) {
        $sql = "UPDATE cursos SET nome = ?, faculdade = ?, duracao = ?, inicio = ?, fim = ?, total_vaga = ?, estado = ?, img_banner = ? WHERE id = ?";

        // Prepara a query
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssssss", $nome, $faculdade, $duracao, $inicio, $fim, $total_vaga, $estado, $img_banner, $id);

        // Executa a query e retorna o resultado
        return $stmt->execute();
    }

    // Método para deletar um curso
    public function delete($id)
    {
        $query = "DELETE FROM cursos WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}


?>