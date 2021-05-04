<?php

//require_once('tcpdf_include.php');
require_once('lib/tcpdf_min/tcpdf.php');

function getFicha($fecha, $convenio, $referencia, $concepto, $importe){
	
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	
	// set document information
	$pdf->SetCreator("DGIRE");
	$pdf->SetAuthor('DGIRE');
	
	// remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	
	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	
	// set some language-dependent strings (optional)
	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		require_once(dirname(__FILE__).'/lang/eng.php');
		$pdf->setLanguageArray($l);
	}
	
	// ---------------------------------------------------------

	// add a page
	//$pdf->AddPage();
	$pdf->AddPage('P', 'A4');
	//$pdf->AddPage('L', 'A4');
	
	$pdf->SetXY(0, 0);
	$pdf->Image('img\ficha2.jpg', '', '', 210, 100, '', '', '', false, 300, '', false, false, 1, false, false, false);
	
	// set default font subsetting mode
	$pdf->setFontSubsetting(true);
	
	// print a block of text using Write()
	$pdf->SetFont('helvetica', '', 8, '', true);
	$pdf->SetXY(80, 27.8);
	$pdf->Write(0, $fecha, '', 0, '', true, 0, false, false, 0);
	
	$pdf->SetFont('helvetica', '', 15, '', true);
	$pdf->SetXY(23.2, 60);
	$pdf->Write(0, $convenio, '', 0, '', true, 0, false, false, 0);
	
	
	$pdf->SetFont('helvetica', '', 15, '', true);
	$pdf->SetXY(71.5, 60);
	$pdf->Write(0, $referencia, '', 0, '', true, 0, false, false, 0);
	
	$pdf->SetFont('helvetica', '', 8, '', true);
	$pdf->SetXY(35, 75.9);
	$pdf->Write(0, $concepto, '', 0, '', true, 0, false, false, 0);
	
	
	$pdf->SetFont('helvetica', '', 10, '', true);
	$pdf->SetXY(173, 81.5);
	$pdf->Write(0, $importe, '', 0, '', true, 0, false, false, 0);
	
	
	
	$pdf->SetXY(12, 90);
	
	// define barcode style
	$style = array(
		'position' => '',
		'align' => 'C',
		'stretch' => false,
		'fitwidth' => true,
		'cellfitalign' => '',
		'border' => false,
		'hpadding' => 'auto',
		'vpadding' => 'auto',
		'fgcolor' => array(0,0,0),
		'bgcolor' => false, //array(255,255,255),
		'text' => true,
		'font' => 'helvetica',
		'fontsize' => 6,
		'stretchtext' => 4
	);
	
	$pdf->write1DBarcode($referencia, 'C128A', '', '', '', 10, 0.3, $style, 'N');
	
	$pdf->Output('example_002.pdf', 'I');
}


function getFicha2($fecha, $convenio, $referencia, $concepto, $importe){
	
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	
	// set document information
	$pdf->SetCreator("DGIRE");
	$pdf->SetAuthor('DGIRE');
	
	// remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	
	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	
	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	
	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	
	// set some language-dependent strings (optional)
	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		require_once(dirname(__FILE__).'/lang/eng.php');
		$pdf->setLanguageArray($l);
	}
	
	// ---------------------------------------------------------

	// add a page
	//$pdf->AddPage();
	//$pdf->AddPage('P', 'A4');
	$pdf->AddPage('L', 'A4');
	
	$pdf->SetXY(30, 20);
	$pdf->Image('img/ficha.png', '', '', 210, 100, '', '', '', false, 300, '', false, false, 1, false, false, false);
	
	// set default font subsetting mode
	$pdf->setFontSubsetting(true);
	
	// print a block of text using Write()
	$pdf->SetFont('helvetica', '', 8, '', true);
	$pdf->SetXY(110, 47.8);
	$pdf->Write(0, $fecha, '', 0, '', true, 0, false, false, 0);
	
	$pdf->SetFont('helvetica', '', 15, '', true);
	$pdf->SetXY(53.2, 80);
	$pdf->Write(0, $convenio, '', 0, '', true, 0, false, false, 0);
	
	
	$pdf->SetFont('helvetica', '', 15, '', true);
	$pdf->SetXY(101.5, 80);
	$pdf->Write(0, $referencia, '', 0, '', true, 0, false, false, 0);
	
	$pdf->SetFont('helvetica', '', 8, '', true);
	$pdf->SetXY(65, 95.9);
	$pdf->Write(0, $concepto, '', 0, '', true, 0, false, false, 0);
	
	
	$pdf->SetFont('helvetica', '', 10, '', true);
	$pdf->SetXY(203, 101.5);
	$pdf->Write(0, $importe, '', 0, '', true, 0, false, false, 0);
	
	
	
	$pdf->SetXY(42, 110);
	
	// define barcode style
	$style = array(
		'position' => '',
		'align' => 'C',
		'stretch' => false,
		'fitwidth' => true,
		'cellfitalign' => '',
		'border' => false,
		'hpadding' => 'auto',
		'vpadding' => 'auto',
		'fgcolor' => array(0,0,0),
		'bgcolor' => false, //array(255,255,255),
		'text' => true,
		'font' => 'helvetica',
		'fontsize' => 6,
		'stretchtext' => 4
	);
	
	$pdf->write1DBarcode($referencia, 'C128A', '', '', '', 10, 0.3, $style, 'N');
	
	$pdf->Output($referencia.'.pdf', 'I');
}

$fecha = "08/08/2016";
$convenio = "1136135";
$referencia = "16000255101008490248";
$concepto = "Impresión de historial academico";
$importe = "$35,00";

getFicha2($fecha, $convenio, $referencia, $concepto, $importe);