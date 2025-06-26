<?php
include_once "../admin/models/conexao.php";
include 'mpdf/mpdf.php';

setlocale(LC_TIME, 'pt_PT.utf8', 'portuguese');

$meses = array(
    1 => 'Janeiro',
    2 => 'Fevereiro',
    3 => 'Março',
    4 => 'Abril',
    5 => 'Maio',
    6 => 'Junho',
    7 => 'Julho',
    8 => 'Agosto',
    9 => 'Setembro',
    10 => 'Outubro',
    11 => 'Novembro',
    12 => 'Dezembro'
);

if (!isset($_GET['id'])) {
    die('ID não fornecido');
}

$id = $_GET['id'];
$sql = "SELECT * FROM aluno_inscricao WHERE id = $id";
$resultado = $conexao->query($sql);
$inscricao = $resultado->fetch_assoc();

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
            <img alt='Logo' style='width: 100px;height: 50px' src='../img/logotipo.png'>
            <br>
            <span>Instituto Médio Comercial de Luanda</span>
        </td>
        <td style='text-align: right;'>
            <h2>COMPROVATIVO DE INSCRIÇÃO</h2>
            Nº: " . str_pad($id, 5, '0', STR_PAD_LEFT) . "
        </td>
    </tr>
</table>
";

// Conteúdo
$html = "
<div style='font-family: Arial; font-size: 12pt;'>
    <h3>DADOS DO CANDIDATO</h3>
    <table border='1' style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>
        <tr>
            <td style='padding: 5px;'><strong>Nome:</strong></td>
            <td style='padding: 5px;'>" . $inscricao['nome'] . "</td>
        </tr>
        <tr>
            <td style='padding: 5px;'><strong>B.I:</strong></td>
            <td style='padding: 5px;'>" . $inscricao['BI'] . "</td>
        </tr>
        <tr>
            <td style='padding: 5px;'><strong>Data de Nascimento:</strong></td>
            <td style='padding: 5px;'>" . date('d/m/Y', strtotime($inscricao['data_nascimento'])) . "</td>
        </tr>
        <tr>
            <td style='padding: 5px;'><strong>Província:</strong></td>
            <td style='padding: 5px;'>" . $inscricao['provincia'] . "</td>
        </tr>
        <tr>
            <td style='padding: 5px;'><strong>Município:</strong></td>
            <td style='padding: 5px;'>" . $inscricao['municipio'] . "</td>
        </tr>
        <tr>
            <td style='padding: 5px;'><strong>Natural de:</strong></td>
            <td style='padding: 5px;'>" . $inscricao['natural_de'] . "</td>
        </tr>
    </table>

    <h3>CURSOS ESCOLHIDOS</h3>
    <table border='1' style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>
        <tr>
            <td style='padding: 5px;'><strong>1ª Opção:</strong></td>
            <td style='padding: 5px;'>" . $inscricao['curso_1_opcao'] . "</td>
        </tr>
        <tr>
            <td style='padding: 5px;'><strong>2ª Opção:</strong></td>
            <td style='padding: 5px;'>" . $inscricao['curso_2_opcao'] . "</td>
        </tr>
    </table>
</div>

<div style='text-align: center; margin-top: 50px; font-family: Arial;'>
    <p>Luanda, " . date('d') . ' de ' . $meses[date('n')] . ' de ' . date('Y') . "</p>
    <br><br>
</div>
";

// Configuração do MPDF
$mpdf = new mpdf();
$mpdf->SetDisplayMode('fullpage', 'single');
$mpdf->_setPageSize('A4', 'P');
$mpdf->SetMargins(10, 5, 5);
$mpdf->_borderPadding(20, 20, 18, 18);
$mpdf->ResetMargins();

// Gerando o PDF
$mpdf->WriteHTML($header . $html);
$mpdf->Output('recibo_inscricao.pdf', 'I');
exit();
?> 