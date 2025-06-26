<?php
include_once "../models/conexao.php";
include '../../impressao/mpdf/mpdf.php';

setlocale(LC_TIME, 'pt_PT.utf8', 'portuguese');



// Configurações da instituição
$nomeCompletoDoProgramaC = "INSTITUTO POLITÉCNICO";
$enderecoC = "Endereço: Rua Principal, 123";
$contactoC = "Telefone: +244 923 456 789";
$emailC = "Email: instituto@example.com";
$nifC = "NIF: 123456789";

// Cabeçalho
$header = "
<table border='0' style='width: 100%;font-family: Arial'>
    <tr>
        <td style='font-size: 8pt;text-align: center;width: 300px;'>
            <img alt='Logo' style='width: 100px;height: 50px' src='../../img/logotipo.png'>
            <br>
            <span>Instituto Médio Comercial de Luanda</span>
        </td>
        <td style='text-align: right;'>
            <h2>LISTA DE ESTUDANTES</h2>
           
        </td>
    </tr>
</table>
";


// Consulta SQL base
include "../models/conexao.php";
$curso1 = $_GET['curso1'];
$pesquisar = $_GET['pesquisar'];
$sql = "SELECT * FROM  aluno_inscricao WHERE  curso_1_opcao LIKE '$curso1' and nome LIKE  '%$pesquisar%'";
$resultado = $conexao->query($sql);

$nunLinha = mysqli_num_rows($resultado);


$html = "";



  $html .="<table border='1' style='border-spacing: 0'>
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>B.I</th>
                                        <th>Data Nascimento</th>
                                        <th>Gênero</th>
                                        <th>1ª Opção</th>
                                        <th>2ª Opção</th>
                                        <th>Nota</th>
                                        <th>Status</th>

                                    </tr>
                                </thead>
                                <tbody >";



if ($nunLinha > 0) {
    while ($aluno = $resultado->fetch_assoc()) {
        $nome = $aluno['nome'];
        $bi = $aluno['BI'];
        $data_nascimento = $aluno['data_nascimento'];
        $genero = $aluno['genero'];
        $curso_1_opcao = $aluno['curso_1_opcao'];
        $curso_2_opcao = $aluno['curso_2_opcao'];
        $nota = $aluno['nota'];


        $obs = $aluno['obs'];

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

    $html.="</tbody>
                            </table>";


} else {
    echo "<h3>Nenhuma informação encontrada...</h3>";
}


// Configuração do MPDF
$mpdf = new mpdf();
$mpdf->SetDisplayMode('fullpage', 'single');
$mpdf->_setPageSize('A4', 'P');
$mpdf->SetMargins(10, 5, 5);
$mpdf->_borderPadding(20, 20, 18, 18);
$mpdf->ResetMargins();

// Gerando o PDF
$mpdf->WriteHTML($header . $html);
$mpdf->Output('listaAlunos.pdf', 'I');
exit();
?>
