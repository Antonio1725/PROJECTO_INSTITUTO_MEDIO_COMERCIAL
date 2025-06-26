<?php
include "models/conexao.php";

if (isset($_POST['aluno_id']) && isset($_POST['nota'])) {
    $alunoId = $_POST['aluno_id'];
    $nota = $_POST['nota'];

    $sql = "UPDATE aluno_inscricao SET nota ='$nota' WHERE id = '$alunoId'";
    $stmt = $conexao->query($sql);


    if ($stmt) {
        echo "Nota salva com sucesso!";
    } else {
        http_response_code(500);
        echo "Erro ao salvar nota.";
    }
} else {
    http_response_code(400);
    echo "Dados incompletos.";
}
?>

