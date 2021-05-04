<?php

require_once('lib/tcpdf_min/tcpdf.php');
require_once("xmlrpc.inc");


class clsWSPagos{
	var $user;
	var $password;
	var $dependencia;
	var $subDep;
	var $origen;
	var $tipo_moneda;
	var $cve_moneda;
	
	public function __construct($option=false){
	
		$this->user = "JLCHIQUE";
		$this->password = "JLCHIQUE";
	
		$this->dependencia = "551";
		$this->subDep = "01";
		$this->origen = "DE";
		$this->tipo_moneda = "1";
		$this->cve_moneda = "MXN";
		
	}
	
	function getFichaDeposito($lstConceptos = array()){
	
		$resp = $this->getReferencia($lstConceptos);
		
		//print_r($resp); exit;
		
		if(!$resp){
			//echo "aa";
			return false;
		}
		
		if($resp->error){
			//echo "aaasdas";
			return false;
		}
		
		$lstConceptos = (object)$lstConceptos[0];
		//print_r($lstConceptos); exit;
		//echo $lstConceptos->concepto; exit;
		//echo $lstConceptos->importe; exit;
		$d = $lstConceptos->concepto;
		
		die("sasa");
		
		print_r($resp); exit;
		
		
		//$this->createFichaDeposito2($resp->data->fecha, $resp->data->convenio, $resp->data->referencia, $lstConceptos->concepto, $lstConceptos->importe);
		$this->createFichaDeposito2(
			$resp->data->fecha
			,$resp->data->convenio
			,$resp->data->referencia
			//,$lstConceptos->concepto
			,$d
			//,"INCORPORACIÓN DE PLANES DE ESTUDIO BACHILLERATO."
			,$lstConceptos->importe
			
		);
	
	}
	
	
	function createFichaDeposito($fecha, $convenio, $referencia, $concepto, $importe){
		
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
		$pdf->Image('img\ficha.png', '', '', 210, 100, '', '', '', false, 300, '', false, false, 1, false, false, false);
		
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
		
		$pdf->Output('example_002.pdf', 'D');
	}
	
	
	function createFichaDeposito2($fecha, $convenio, $referencia, $concepto, $importe){
		
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
		$pdf->Image('img/ficha2.jpg', '', '', 210, 100, '', '', '', false, 300, '', false, false, 1, false, false, false);
		
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
		$pdf->Write(0, "$".$importe, '', 0, '', true, 0, false, false, 0);
		
		
		
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
		
		ob_end_clean();
		$pdf->Output($referencia.'.pdf', 'D');
	}

	public function getReferencia($lstConceptos = array()){
		$param = array();
		
		/*
		$parametro0 = array(
			'Dependencia' => "551",
			'Subdep' => "01",
			'Origen' => "DE",
			'Cve_moneda' => "MXN",
			'Tipo_pago' => "EF",
			'Importe' => "1.00",
			'Concepto' => "Ficha de Deposito prueba servicio."
		);
		*/
		
		//print_r($lstConceptos); exit;
	
		
		foreach($lstConceptos as $c => $d){
			
				$d = (object)$d;
		
			//print_r($d); exit;
			
			/*
				'Dependencia' => $this->dependencia,
				'Subdep' => $this->subDep,
				'Origen' => $this->origen,
				'Cve_moneda' => $this->moneda,
				'Tipo_pago' => $d->tipoPago,
				'Importe' => $d->importe,
				'Concepto' => $d->concepto
				
				
				'Dependencia' => "551",
			'Subdep' => "01",
			'Origen' => "DE",
			'Cve_moneda' => "MXN",
			'Tipo_pago' => "EF",
			'Importe' => "1.00",
			'Concepto' => "Ficha de Deposito prueba servicio."
			);
				*/
			$data = array(
			
				'Dependencia' => $this->dependencia
				,'Subdep' => $this->subDep
				,'Origen' => $this->origen
				,'Cve_moneda' => $this->cve_moneda
				,'Tipo_pago' => $d->tipoPago
				,'Importe' => $d->importe
				,'Concepto' => $d->concepto
				
				/*
				'Dependencia' => "551",
			'Subdep' => "01",
			'Origen' => "DE",
			'Cve_moneda' => "MXN",
			'Tipo_pago' => "EF",
			'Importe' => "1.00",
			'Concepto' => "Ficha de Deposito prueba servicio."}
			*/
			);
			
			//print_r($data); exit;
		
			$param[] = php_xmlrpc_encode($data);
		}
	
		$msg = new xmlrpcmsg("fichadeposito.datos", $param);
		unset($param);
	
		//$client = new xmlrpc_client("https://132.248.40.26:443/wscfdspruebas/fichadepositoN.php");
		$client = new xmlrpc_client("https://wscfdspruebas.patronato.unam.mx:443/wscfdspruebas/fichadepositoN.php");
		$client->setCredentials($this->user, $this->password);
		$resp = $client->send($msg);
	
	print_r($resp); exit;
	
		unset($client);
		unset($msg);
		
		if(!$resp->val){
			//echo "sadas"; exit;
			return (object)array("error" => $resp->errno, "msg" => $resp->errstr, "ficha_deposito" => (object)array());
		}
		
		$error = $resp->val->me["array"][0]->me["boolean"];
		
		
		
		if(!$error){
			return false;
		}
		
		$fecha = $resp->val->me["array"][1]->me["struct"]["Fecha"]->me["string"];
		$convenio = $resp->val->me["array"][1]->me["struct"]["Convenio"]->me["string"];
		$referencia = $resp->val->me["array"][1]->me["struct"]["Referencia"]->me["string"];
		//print_r($resp); exit;
		
		return (object)array("error" => 0, "msg" => 0, "data" => (object)array("fecha" => $fecha, "convenio" => $convenio, "referencia" => $referencia));
	}
	
	public function insertCDFS($dRFC=array(), $lstConceptos = array()){
	
		$productos = array();
		$pagos = array();
		
		$parametro0 = array(
			'Tpocfd' => "FD",
			'Cvepago' => "SG"
		);
		
		foreach($lstConceptos as $c => $d){
		
			$d = (object)$d;
	
			$productos[] = array(
				'Cta_ie' => $d->cuenta
				,'Cantidad' => $d->cantidad
				,'Unidad' => "NO APLICA"
				,'Descripcion' => $d->concepto
				,'Precio_unit' => $d->importe
				//,'Iva' => "16"
				,'Iva' => "0"
				//,'Iva_impte' => 0.14
				,'Iva_impte' => 0
				,'Importe_total' => $d->importe
			);
			
			$pagos[] = array(
				//'Pago' => "EFECTIVO",
				'Cve_pago' => "EF"
				,'Importe' => $d->importe
			);
			

		}

		$dRFC = (object)$dRFC;
		$datos = array(
			'Procedencia' => "OT"
			,'Pais' => "MX"
			,'Rfc' => $dRFC->rfc
			,'Razon_soc' => $dRFC->nombre_fisc
			,'Nombre' => $dRFC->nombre
			,'A_paterno' => $dRFC->ap_paterno
			,'A_materno' => $dRFC->ap_materno
			,'Estado' => $dRFC->estado
			,'DelOMunicipio' => $dRFC->delMunicipio
			,'Cp' => $dRFC->id_cp
			,'Colonia' => $dRFC->colonia
			,'Calle' => $dRFC->calle
			,'No_ext' => $dRFC->num_ext
			,'No_int' => $dRFC->num_int
			,'Email' => $dRFC->email
		);

	
		$dogDig = array(
			'Dependencia' => $this->dependencia
			,'Subdep' => $this->subDep
			,'Origen' => $this->origen
			,'Tipo_moneda' => $this->tipo_moneda
			,'Cve_moneda' => $this->cve_moneda
			,'Carta_certif' => 0
			,'Forma_liq' => "E"
			,'Status_pago' => "I"
			,'Importe_factura' => 1
			,'Observaciones' => "OBSERVACIONES"
		);


		
		

		$param = array();
		$param[] = php_xmlrpc_encode($parametro0);
		$param[] = php_xmlrpc_encode($productos);
		$param[] = php_xmlrpc_encode($pagos);
		$param[] = php_xmlrpc_encode($datos);
		$param[] = php_xmlrpc_encode($dogDig);

		$msg = new xmlrpcmsg("insertcfds.datos", $param);
		unset($param);
		
		$client = new xmlrpc_client("https://132.248.40.26:443/wscfdspruebas/siiewebN.php");
		//$client = new xmlrpc_client("https://wscfdspruebas.patronato.unam.mx:443/wscfdspruebas/fichadepositoN.php");
		$client->setCredentials($this->user, $this->password);
		$resp = $client->send($msg);
		unset($client);
		unset($msg);
	
		return $resp;
	}
}


?>
