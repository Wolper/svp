<?php

//require './index.php';

define('FPDF', 'FONTPATH', 'font/');
require '../_app/config.php';
require '../pdf/fpdf.php';
header("Content-type: text/html; charset=utf-8");

$pdf = new FPDF('P', 'cm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTitle('Relatório Consolidado de Pontos', $isUTF8 = TRUE);
//$pdf->SetTextColor(0, 0, 150);
date_default_timezone_set('America/Sao_Paulo');
$mes = date('m');
$me = "SELECT *, SUM(pt.total_de_pontos) AS total FROM (militar_estadual AS me JOIN pontos_acumulados AS pt ON me.id_militar_estadual = pt.id_militar_estadual) WHERE pt.mes_vigencia = {$mes} GROUP BY pt.mes_vigencia, me.subunidade, me.unidade ORDER BY me.subunidade, me.unidade";
$me = $pdo->query($me);

$pdf->Cell(19, 1, utf8_decode('Relatório Consolidado de Pontos'), 0, 1, 'C');
$pdf->Cell(19, 1, '', 0, 1, 'C');

$pdf->Cell(19, 1, utf8_decode('Lista de Militares Estaduais que pontuaram no mês ') . date('m / Y'), 0, 1, 'C');
$pdf->Cell(19, 1, '', 0, 1, 'C');
$pdf->Cell(3, 1, utf8_decode('POSTO'), 0, 0, 'C', '');
$pdf->Cell(8, 1, utf8_decode('NOME'), 0, 0, 'C', '');
$pdf->Cell(4, 1, utf8_decode('LOTAÇÃO'), 0, 0, 'C', '');
$pdf->Cell(4, 1, utf8_decode('TOTAL DE PONTOS'), 0, 1, 'C', '');

//$pdf->SetTextColor(0, 0, 0);

if ($me->rowCount() > 0):
    foreach ($me->fetchAll() as $mil):
        extract($mil);
        $pdf->Cell(3, 1, utf8_decode($posto), 'T', 0, 'C', '');
        $pdf->Cell(8, 1, utf8_decode($nome . ' ' . $sobrenome), 'T', 0, 'C', '');
        $pdf->Cell(4, 1, utf8_decode($subunidade . ' / ' . $unidade), 'T', 0, 'C', '');
        $pdf->Cell(4, 1, utf8_decode($total), 'T', 1, 'C', '');

    endforeach;
endif;

$pdf->Cell(19, 1, utf8_decode(''), 0, 1, 'C');
$pdf->Cell(19, 1, utf8_decode(''), 0, 1, 'C');
$pdf->Cell(19, 1, utf8_decode('Data/Hora de Emissão: ' . date('d / m / Y - H:i').'h'), 0, 1, 'R');
$pdf->Cell(19, 1, utf8_decode(''), 0, 1, 'C');
$pdf->Cell(19, 1, utf8_decode('Comandante de Cia/Chefe de Seção'), 0, 1, 'C');
$pdf->Output();




