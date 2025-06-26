<?php
// Consulta SQL base
include "../models/conexao.php";
$curso1 = $_POST['curso1'];
$pesquisar = $_POST['pesquisar'];
$sql = "SELECT * FROM  aluno_inscricao WHERE  curso_1_opcao LIKE '$curso1' and nome LIKE  '%$pesquisar%'";
$resultado = $conexao->query($sql);

$sql_controlExame = "SELECT controlExame FROM  controlo_links WHERE  id='1'";
$query_controlExame = $conexao->query($sql_controlExame);

$resultado_controlExame = $query_controlExame->fetch_assoc();

$nunLinha = mysqli_num_rows($resultado);

$html = "";

if ($nunLinha > 0) {
    while ($aluno = $resultado->fetch_assoc()) {
        $id = $aluno['id'];
        $nome = $aluno['nome'];
        $bi = $aluno['BI'];
        $data_nascimento = $aluno['data_nascimento'];
        $genero = $aluno['genero'];
        $curso_1_opcao = $aluno['curso_1_opcao'];
        $curso_2_opcao = $aluno['curso_2_opcao'];
        $nota = $aluno['nota'];
        $obs = $aluno['obs'];
        $controlExame = $resultado_controlExame['controlExame'];
        $controlNota = $resultado_controlExame['controlNota'];

       

        if (empty($nota)) {
            $nota = "<button type='button' class='btn btn-primary btn-inserir-nota' onclick='buscarNota($id)' data-bs-toggle='modal' data-bs-target='#modalNota'>Inserir nota</button>";
        } else {
            if ($nota >= 10) {
                $nota = "<span style='color:blue;'>$nota</span>";
               // $obs = "Apto";
            } else {
                $nota = "<span style='color:red;'>$nota</span>";
              //  $obs = "Não Apto";
            }
        }




 // Se controlExame for igual a 'N', a variável $nota deve estar vazia
 if ($controlNota == 'N') {
   // Se controlExame for igual a 'N', a variável $nota deve estar vazia
 if ($controlExame == 'N') {
    $nota = '';
}
}
        $html .= "<tr>
            <td>$nome</td>
            <td>$bi</td>
            <td>$data_nascimento</td>
            <td>$genero</td>
            <td>$curso_1_opcao</td>
            <td>$curso_2_opcao</td>
            <td>$nota</td>
            <td>$obs</td>
        </tr>";
    }
    echo $html;
} else {
    echo "<h3>Nenhuma informação encontrada...</h3>";
}
?>
