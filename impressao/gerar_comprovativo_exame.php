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
// Buscar dados do aluno e do exame
$sql = "SELECT 
            ai.*, 
            e.nome AS nome_exame, 
            e.curso AS curso_exame, 
            e.dataInicio, 
            e.dataFim, 
            e.data AS data_exame, 
            e.sala
        FROM aluno_inscricao ai
        LEFT JOIN exames e ON ai.idExame = e.id
        WHERE ai.id = " . intval($id);
       
$resultado = $conexao->query($sql);
$dados = $resultado->fetch_assoc();

if (!$dados) {
    die('Dados não encontrados para o ID fornecido.');
}

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
            <h2>COMPROVATIVO DE EXAME</h2>
            Nº: " . str_pad($id, 5, '0', STR_PAD_LEFT) . "
        </td>
    </tr>
</table>
";

// Conteúdo do comprovativo
$html = "
<div style='font-family: Arial; font-size: 12pt;'>
    <h3>DADOS DO CANDIDATO</h3>
    <table border='1' style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>
        <tr>
            <td style='padding: 5px;'><strong>Nome:</strong></td>
            <td style='padding: 5px;'>" . $dados['nome'] . "</td>
        </tr>
        <tr>
            <td style='padding: 5px;'><strong>B.I:</strong></td>
            <td style='padding: 5px;'>" . $dados['BI'] . "</td>
        </tr>
        <tr>
            <td style='padding: 5px;'><strong>Curso:</strong></td>
            <td style='padding: 5px;'>" . $dados['curso_exame'] . "</td>
        </tr>
    </table>

    <h3>DADOS DO EXAME</h3>
    <table border='1' style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>
        <tr>
            <td style='padding: 5px;'><strong>Nome do Exame:</strong></td>
            <td style='padding: 5px;'>" . $dados['nome_exame'] . "</td>
        </tr>
        <tr>
            <td style='padding: 5px;'><strong>Data:</strong></td>
            <td style='padding: 5px;'>" . (!empty($dados['data_exame']) && $dados['data_exame'] !== '0000-00-00' ? date('d/m/Y', strtotime($dados['data_exame'])) : '-') . "</td>
        </tr>
        <tr>
            <td style='padding: 5px;'><strong>Início:</strong></td>
            <td style='padding: 5px;'>" . (!empty($dados['dataInicio']) ? date('H:i', strtotime($dados['dataInicio'])) : '-') . "</td>
        </tr>
        <tr>
            <td style='padding: 5px;'><strong>Fim:</strong></td>
            <td style='padding: 5px;'>" . (!empty($dados['dataFim']) ? date('H:i', strtotime($dados['dataFim'])) : '-') . "</td>
        </tr>
        <tr>
            <td style='padding: 5px;'><strong>Sala:</strong></td>
            <td style='padding: 5px;'>" . $dados['sala'] . "</td>
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
$mpdf->Output('comprovativo_exame.pdf', 'I');
exit(); 