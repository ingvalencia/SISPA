<?php

	require_once("clsWSPagos.php");
	
	$wsP = new clsWSPagos();
	
	$lstConceptos=array();
	
	$opt="ficha";
	//$opt="insertCDFS";
	
	switch($opt){
	
		case "ficha":
		
			$c=array(
				"tipoPago" => "EF"
				,"importe" => "1.00"
				//,"concepto" => "INCORPORACI&oacute; DE PLANES DE ESTUDIO BACHILLERATO."
				,"concepto" => "INCORPORACI DE PLANES DE ESTUDIO BACHILLERATO."
			);
			$lstConceptos[] = $c;
		
	
			$resp = $wsP->getFichaDeposito($lstConceptos);
			//echo "ss";
			//print_r($resp);
			
			break;
	
		case "insertCDFS":
			
			
			$dRFC=array(
				"rfc" => "AAAA121231ZZZ"
				,"nombre_fisc"  => ""
				,"nombre"  => "NOMBRE"
				,"ap_paterno" => "PATERNO"
				,"ap_materno" => "MATERNO"
				,"estado" => "ESTADO"
				,"delMunicipio" => "DELEGACIÓN"
				,"id_cp" => "12345"
				,"colonia" => "COLONIA"
				,"calle" => "CALLE"
				,"num_ext" => "NÚM. EXT."
				,"num_int" => "NÚM. INT."
				,"email" => "correo@de.com"
			);
			
			$lstConceptos[]=array(
				"cuenta" => "202"
				,"cantidad" => 1
				,"concepto" => "PRODUCTO"
				,"importe" => 1
			);
			
			//cuentas en la tabla ct_concepto_pago => 11, 202, 421
			//Mensaje error 
			//Factura Electrónica Sección Compra |
			//Cuenta de Ingresos Extraordinarios no válido(a) en SG_SERVICIOS Y PRODUCTOS; 
			//valor(es) permitido(s) 001,201,202,203,204,205,209
			//La documentacion dice que para MXN estan las cuentas 202, 203, 204, 205
			//para USD 262
			$lstConceptos[]=array(
				"cuenta" => "202"
				,"cantidad" => 1
				,"concepto" => "INCORPORACIÓN DE PLANES DE ESTUDIO NIVEL LIC. POR CARRERA."
				,"importe" => 10
			);
			
			$resp = $wsP->insertCDFS($dRFC, $lstConceptos );

			//print_r($resp);
			break;
	
	}
	
?>
