<?php
require('fpdf/fpdf.php');
include_once "admin/models/conexao.php";

if (!isset($_GET['id'])) {
    die('ID não fornecido');
}

$id = $_GET['id'];
$sql = "SELECT * FROM aluno_inscricao WHERE id = $id";
$resultado = $conexao->query($sql);
$inscricao = $resultado->fetch_assoc();

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, 'RECIBO DE INSCRIÇÃO', 0, 1, 'C');
        $this->Ln(10);
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Dados do aluno
$pdf->Cell(0, 10, 'DADOS DO CANDIDATO', 0, 1, 'L');
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(5);

$pdf->Cell(0, 8, 'Nome: ' . utf8_decode($inscricao['nome']), 0, 1);
$pdf->Cell(0, 8, 'B.I: ' . $inscricao['BI'], 0, 1);
$pdf->Cell(0, 8, 'Data de Nascimento: ' . date('d/m/Y', strtotime($inscricao['data_nascimento'])), 0, 1);
$pdf->Cell(0, 8, utf8_decode('Província: ') . utf8_decode($inscricao['provincia']), 0, 1);
$pdf->Cell(0, 8, utf8_decode('Município: ') . utf8_decode($inscricao['municipio']), 0, 1);
$pdf->Cell(0, 8, 'Natural de: ' . utf8_decode($inscricao['natural_de']), 0, 1);

$pdf->Ln(10);
$pdf->Cell(0, 10, 'CURSOS ESCOLHIDOS', 0, 1, 'L');
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(5);

$pdf->Cell(0, 8, utf8_decode('1ª Opção: ') . utf8_decode($inscricao['curso_1_opcao']), 0, 1);
$pdf->Cell(0, 8, utf8_decode('2ª Opção: ') . utf8_decode($inscricao['curso_2_opcao']), 0, 1);

$pdf->Ln(20);
$pdf->Cell(0, 10, '_____________________________________', 0, 1, 'C');
$pdf->Cell(0, 5, 'Assinatura do Responsável', 0, 1, 'C');

$pdf->Output('I', 'recibo_inscricao.pdf');
?> 