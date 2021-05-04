<?php

require_once ("../common/config.php");
require_once ("../common/clsConceptosPago.php");
require_once ("../common/clsSolicitudes.php");
require_once ("../common/clsParametros.php");
include_once ("../common/clsSolicitantes.php");
require_once ("../common/clsDatosFacturacion.php");
require_once ("../common/class.WSPagos.php");
include_once ("../common/clsCorreo.php");

session_start();

include_once("validData.php");



$solicitudes = new clsSolicitudes();
$parametros = new clsParametros($solicitudes-> getLinkMysql());
$solicitantes = new clsSolicitantes();
$datosFacturacion = new clsDatosFacturacion($solicitudes-> getLinkMysql());
$enviacorreo = new clsCorreo();

$MyDebug->SetDebug(0);
$MyDebug->DebugError();
$ficha = new WSPagos();

$p = $parametros->search_parametros();
$sm = $p->parametros["smdf"]->valor;
$iva = $p->parametros["iva"]->valor/100;
$cveserie = $p->parametros["cveserie"]->valor;



$id_solicitante = $solicitantes->getSolicitantePagos();

if(!$solicitudes){

	$json = array(
		"error" => true
		,"msg" => "Error #C000: No se pudo conectarse con el servidor, si el probelma persiste comuniquese con el administrador"
		,"debug" => $solicitudes->getError()
	);
	die(json_encode($json));

}

function getIni(){
	
	global $solicitudes;
	
	$conceptos = $solicitudes->getTiposConceptos();
	
    $resp = $datosFacturacion->search_datos_facturacion(array("id_solicitante" => $id_solicitante));

	$json = array(
		"error" => false
		,"data" => array("conceptos" => $conceptos->tipos_conceptos)
	);
	
	return $json;
}

$json["error"] = "no";


if($opt=="getIni"){
	
	$est = $solicitudes->getEstadoPago();
	
	if(!$est){
		$json = array(
			"error" => true
			,"msg" => "sdsdfsd"
			,"debug" => $conceptosPago->getError
		);
		die(json_encode($json));
	}
	
	$conc = $solicitudes->search_conceptos(array("vigente"=>1));
	
	if(!$conc){
		$json = array(
			"error" => true
			,"msg" => "#1"
			,"debug" => $conceptosPago->getError()
		);
		die(json_encode($json));
	}
	
	$par = $parametros->search_parametros();
	
	if(!$par){
		$json = array(
			"error" => true
			,"msg" => "#2"
		);
		die(json_encode($json));
	}
	
  $rfcs = $datosFacturacion->search_datos_facturacion(array("id_solicitante" => $id_solicitante));
  
  
  if(!$rfcs){
    $json = array(
		"error" => true
		,"msg" => "#3"
	);
    die(json_encode($json));
  }

	$resp = $parametros->searchPlantelSybase();
	

	$json = array(
		"error" => false
		,"estados" => $est->estados
		,"data" => array( 
				"conceptos" => $conc->conceptos
				,"grupo_conceptos" => $conc->lstGrupos
				,"lstConceptos" => $conc->lstConceptos
				,"rfc" => $rfcs->rfc
				,"smdf" => $par->parametros["smdf"]->valor
				,"iva" => $par->parametros["iva"]->valor
				,"planteles" => $resp->lstPtl
			)
	);
	
	
	die(json_encode($json));
}


if($opt == "saveComentario"){
	
	
	$lstParam=array("comentario" => $comentario);
	
	$resp = $solicitudes->update_solicitud($id_solicitante, $folio_sol, $lstParam);
	
	if(!$resp){
		$json["error"]=true;
		$json["msg"]="No se pudo cuardar el comentario";
		//$json["debug"]=$conceptosPago->getError();
		die(json_encode($json));
	}
	
	$json["error"] = false;
	$json["query"] = $solicitudes->getLastQuery();
	$json["msg"] = "Se han actualizado los comentarios de la solicitud <strong>$folio_sol</strong>";
	die(json_encode($json));
	
}

if($opt == "searchRFCS"){

	if($slcTipoUser == "no_usr_reg"){
		$correo_usuario = "pagosdgire@dgire.unam.mx";
	}
	
	$resp = $solicitantes->searchRFCS($correo_usuario);
	
	if(!$resp){
		$json = array(
			"error" => true
			,"msg" => "Se presento un error en el servidor, comuniquese con el administrador"
		);
		die(json_encode($json));
	}
	
	$json = array();
	if($resp->count==0){
		$json["exist"] = false;
		$json["msg"] = "El usuario no tiene RFCs registrados";
	}
	else{
		$json["exist"] = true;
	}
	
	$json["error"] = false;
	$json["datos"] = $resp;
	die(json_encode($json));
	
}


if($opt == "searchRFCPagos"){

	$correo_usuario = "pagosdgire@dgire.unam.mx";
	
	$resp = $solicitantes->searchRFCS($correo_usuario);
	
	if(!$resp){
		$json["error"]=true;
		$json["msg"]="No se pudo cuardar el comentario";
		die(json_encode($json));
	}
	
	if($resp->count==0){
		$json["msg"] = "El usuario no tiene RFCs registrados";
	}
	$json["error"] = false;
	$json["datos"] = $resp;
	die(json_encode($json));
	
}


if($opt=="getDetalleSolicitud"){
	
	$resp = $solicitudes->search_solicitudes(array("folio_sol" => $folio_sol));
	if(!$resp){
		$json["error"]=true;
		$json["msg"]="sdsdfsd";
		$json["debug"]=$solicitudes->getError();
		die(json_encode($json));
	}
	
	if(!$resp->count){
		$json["error"]=false;
		$json["exist"]=false;
		$json["msg"]="No existe la solicitud";
		die(json_encode($json));
	}
	
	if($resp->solicitudes[0]->comentario==""){
		$resp->solicitudes[0]->comentario="";
	}
	
	$json["exist"]=true;
	$json["data"]["solicitud"]=$resp->solicitudes[0];
	
	
	$resp = $solicitudes->getDetalleSolicitud($folio_sol);
	
	if(!$resp){
		$json["error"]=true;
		$json["msg"]="sdsdfsd";
		$json["debug"]=$solicitudes->getError();
		die(json_encode($json));
	}
	
	$detalles = $resp->detalles;
	foreach($detalles as $id => $d){
		$detalles[$id]->importe = '$'.number_format($detalles[$id]->importe,2);
		$detalles[$id]->precio_unitario = '$'.number_format($detalles[$id]->precio_unitario,2);
		$detalles[$id]->iva = '$'.number_format($detalles[$id]->iva,2);
		$detalles[$id]->monto_tot_conc = '$'.number_format($detalles[$id]->monto_tot_conc,2);
	}
	
	$json["error"]=false;
	$json["data"]["detalles"]=$detalles;
	
	$montos = array(
		"monto_total_iva" => '$'.number_format($resp->solicitudes[0]->monto_total_iva,2)
        ,"monto_total_sin_iva" => '$'.number_format($resp->solicitudes[0]->monto_total_sin_iva,2)
        ,"monto_total" => '$'.number_format($resp->solicitudes[0]->monto_total,2)
	);
	
	$json["data"]["montos"]=$montos;
	
	die(json_encode($json));
}

if($opt == "getColonia"){
  
  $resp = $solicitantes->getColonia($id_cp);
  if(!$resp){
    $json["error"] = true;
    $json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
    $json["debug"] = $solicitantes->getError(); 
    die(json_encode($json));
  }
  
  $json["error"] = false;
  $json["colonias"] = $resp->colonias;
  die(json_encode($json));
}

if($opt == "validRFC"){
    
  $resp = $solicitantes->exist_rfc($rfc);
  
  if(!$resp){
    $json["error"] = true;
    $json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
    $json["debug"] = $solicitantes->getError(); 
    die(json_encode($json));
  }
  
  
  $json["error"] = false;
  $json["exist"] = $resp->exist;
  die(json_encode($json));
}


if($opt=="getMontos"){
	
	$resp_concepto = $solicitudes->getConcepto($clave);
	
	
	if(!$resp_concepto){
		$json["error"] = true;
		$json["msg"] = "Error-getMontos#1: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
		die(json_encode($json));
	}
	
	if(!$resp_concepto->count){
		$json["error"] = true;
		$json["msg"] = "Error-getMontos#2: El concepto no existe";
		die(json_encode($json));
	}
		
	$concepto = $resp_concepto->concepto;
	
	
	if(!$concepto){
		$json["error"] = true;
		$json["msg"] = "Error-getMontos#3: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
		die(json_encode($json));
	}

	$id_concepto_pago= $concepto->id_concepto_pago;
	$calcula_iva= $concepto->calcula_iva;
	$importe_smdf=$concepto->importe_smdf;
	$importe_pesos=$concepto->importe_pesos;
	$costo_variable=$concepto->costo_variable;
	
	$MontosConcepto = $solicitudes->getMontosConcepto($id_concepto_pago
													  ,$cantidad
													  ,$sm
													  ,$calcula_iva
													  ,$iva
													  ,$importe_smdf
													  ,$importe_pesos
													  ,$costo_variable
													  ,$precio_variable
													  );
	
	$MontosConcepto->clave = $concepto->id_concepto_pago;								
	$MontosConcepto->nom_concepto_pago = $concepto->nom_concepto_pago;
	$MontosConcepto->precio_variable = $precio_variable;
	$MontosConcepto->costo_variable = $concepto->costo_variable;
	
	$json["error"] = false;
	$json["msg"] = "Los montos y conceptos son: ";
	$json["montos"] = $MontosConcepto;
	die(json_encode($json));
	
}


if($opt == "addSolUsu"){  
	
	$conceptos = json_decode(stripslashes($conceptos));
		
	if(!$solicitudes->startTransactionMysql()){
		$json = array(
			"error" => true
			,"msg" => "Error #C001: comuniquese con el administrador"
		);
		die(json_encode($json));
	}
	
	
	$id_solicitante = 0;
	
	/*verificamos que no existe el usuario*/
	if($slcTipoUser == "no_usr_reg"){
		$resp = $solicitantes->getIdSolicitantePagos($correo_usuario);
		
		if(!$resp){
			$json = array(
				"error" => true
				,"msg" => "No se pudo comunicar con el servidor"
			);
			die(json_encode($json));
		}
		
		if($resp->count>0){
			
			$json = array(
				"error" => true
				,"msg" => "El correo <strong>$correo_usuario</strong> pertenece a un usuario registrado"
			);
			die(json_encode($json));
		}
		
		$resp = $solicitantes->getIdSolicitantePagos("pagosdgire@dgire.unam.mx");
	
		if(!$resp){
			$json = array(
				"error" => true
				,"msg" => "No se pudo comunicar con el servidor"
			);
			die(json_encode($json));
		}
		
		if($resp->count==0){
			
			$json = array(
				"error" => true
				,"msg" => "No se pudo encontrar el usuario de pagos, comuniquese con el administrador"
			);
			die(json_encode($json));
		}
	
	}
	
	if($slcTipoUser == "usr_reg"){
	
		$resp = $solicitantes->getIdSolicitantePagos($correo_usuario);
	
		if(!$resp){
			$json = array(
				"error" => true
				,"msg" => "No se pudo comunicar con el servidor"
			);
			die(json_encode($json));
		}
		
		if($resp->count==0){
			
			$json = array(
				"error" => true
				,"msg" => "El correo <strong>$correo_usuario</strong> no pertenece a ningun usuario registrado"
			);
			die(json_encode($json));
		}
	}
	
	$id_solicitante = $resp->id_solicitante;
	

	/*verificamos que exista el rfc*/
	if($chFactura && !$chDataRFC){
		
		if($slcTipoUser == "usr_reg" ){
			$resp = $solicitantes->searchRFCS($correo_usuario, $rfc);
			//die("aaa");
		}
		else{
			$resp = $solicitantes->searchRFCS("pagosdgire@dgire.unam.mx", $rfc);
			//die("bbb");
		}
		
		if(!$resp){
			$json = array(
				"error" => true
				,"msg" => "No se pudo comunicar con el servidor"
			);
			die(json_encode($json));
		}
		
		if($resp->count==0){
			$json = array(
				"error" => true
				,"msg" => "El RFC no esta registrado, debe registrar los datos del RFC"
			);
			die(json_encode($json));
		}
		
		//print_r($resp); exit;
	
	}
	
	
	/* registrar datos de facturacion para usuarios no registrados */
	if($chFactura && $chDataRFC && $slcTipoUser == "no_usr_reg" ){
	
		/*verificar si existe el rfc */
		$resp = $solicitantes->searchRFCS("pagosdgire@dgire.unam.mx", $rfc);
		
		if(!$resp){
			$json = array(
				"error" => true
				,"msg" => "No se pudo comunicar con el servidor"
			);
			die(json_encode($json));
		}
		
		if($resp->count>0){
			$json = array(
				"error" => true
				,"msg" => "El RFC ya ha sido registrado"
			);
			die(json_encode($json));
		}
		
		
		$dFac = array(
		"id_solicitante"=> $id_solicitante
		,"rfc" => $rfc
		,"tipo_persona" => $tipo_persona
		,"calle" => $calle
		,"id_ciudad" => $id_ciudad
		,"id_municipio" => $id_municipio
		,"id_edo" => $id_edo
		,"id_cp" => $id_cp
		,"num_int" => $num_int
		,"nombre_fisc"=>$nombre_fisc
		,"nombre"=>$fnombre
		,"ap_paterno"=>$fap_paterno
		,"ap_materno"=>$fap_materno
		,"id_colonia"=>$id_colonia
		,"colonia_otra"=>$txtOtraCol
		,"num_ext" => $num_ext  
		);
		
		
		$resp = $solicitantes->agregar_datos_facturacion($dFac);
		
		if(!$resp){
			$json = array(
				"error" => true
				,"msg" => "zError #C000GGGGGG:"
				,"query" => $solicitantes->getLastQuery()
				,"debug" => $solicitantes->getError()
			);
			$solicitantes->rollbackMysql();
			die(json_encode($json));
		}
	}

	
	
	$monto_total = 0;
	$monto_tot_conc = 0;
	$monto_total_iva = 0;
	$monto_total_sin_iva = 0;
	
	$detalles = array();
	
	$claves= array();

	/* Procesamos los conceptos */
	foreach($conceptos as $i => $x_concepto){
		
		$claves[] = $x_concepto->clave;
		
		$resp_concepto = $solicitudes->getConcepto($x_concepto->clave);
			
		if(!$resp_concepto){
			$json = array(
				"error" => true
				,"msg" => "Error-getMontos#1: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador"
			);
			die(json_encode($json));
		}
		
		if(!$resp_concepto->count){
			$json = array(
				"error" => true
				,"msg" => "Error-getMontos#2: El concepto no existe"
			);
			die(json_encode($json));
		}
			
		$concepto = $resp_concepto->concepto;
		
		
		if(!$concepto){
			$json["error"] = true;
			$json["msg"] = "Error-getMontos#3: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
			die(json_encode($json));
		}
		
		$id_concepto_pago= $concepto->id_concepto_pago;
		$calcula_iva= $concepto->calcula_iva;
		$importe_smdf=$concepto->importe_smdf;
		$importe_pesos=$concepto->importe_pesos;
		$costo_variable=$concepto->costo_variable;
		
		$precio_variable = 0;
		
		if(isset($x_concepto->precio_variable)){
			$precio_variable = $x_concepto->precio_variable;
		}
		
		$MontosConcepto = $solicitudes->getMontosConcepto($id_concepto_pago
														,$x_concepto->cantidad
														,$sm
														,$calcula_iva
														,$iva
														,$importe_smdf
														,$importe_pesos
														,$costo_variable
														,$precio_variable
														);
		
		
		$MontosConcepto->clave = $concepto->id_concepto_pago;
		$MontosConcepto->id_concepto_pago = $concepto->id_concepto_pago;
		$MontosConcepto->importe_pesos = $MontosConcepto->importe_sin_iva;
		$MontosConcepto->nom_concepto_pago = $concepto->nom_concepto_pago;
		$MontosConcepto->precio_variable = $precio_variable;
		$MontosConcepto->costo_variable = $concepto->costo_variable;
			
		$detalles[$x_concepto->clave] = $MontosConcepto;
		
		$monto_total+= $MontosConcepto->monto_tot_conc;
	}
	
	//print_r($detalles); exit;
	
	/* Obtenemos el folio de la solicitu */
	$folio = $solicitudes->getFolio($id_solicitante);
	
	if(!$folio){
		$resp = array(
			"error" => true
			,"msg" => " No folio"
			//"debug" = $solicitudes->getError()
			//"query" = $solicitudes->getLastQuery()
		);
		$solicitudes->rollbackMysql();
		die(json_encode($json));
	}
	
	$monto_total = number_format($monto_total, 2, '.', '');
	
	$info = array(
		'tipoPago' => "EF"
		,'importe' => $monto_total
		,'concepto' => implode(",", $claves)
		
	);
	
	

	$lstParam = array(
		"id_solicitante" => $id_solicitante
		,"folio_sol" => $folio
		,"serie_fac" => $cveserie
		,"monto_total_iva" => number_format($monto_total_iva, 2, '.', '')
		,"monto_total_sin_iva" => number_format($monto_total_sin_iva, 2, '.', '')
		,"monto_tot_conc" => $monto_total
		,"referencia_ban" => ""
		,"ptl_ptl" => $ptl_ptl
		,"nombre_solicitante" => $nombre_solicitante
		,"rfc"=> isset($rfc) ? $rfc : ""
		,"correo_rfc" => $slcTipoUser == "no_usr_reg" ? $correo_usuario : ""
		,"factura"=> $chFactura
		
	);
	
	$resp = $solicitudes->agregar_solicitud($lstParam);
	
	if(!$resp){  
		$json = array(
			"error" => true
			,"msg" => "Error #003 No se pudo agregar la solicirud"
			,"query" => $solicitudes->getLastQuery()
			,"debug" => $solicitudes->getError()
		);
		$solicitudes->rollbackMysql();
		die(json_encode($json));
	}
	
	foreach($detalles as $clave => $det){
	
		$resp = $solicitudes->agregar_detalle($id_solicitante, $folio, $det );
				
		if(!$resp){
			$json = array(
				"error" => true
				,"msg" => "Error #004 No coinciden las claves con las cantidades"
				,"query" => $solicitudes->getLastQuery()
				,"debug" => $solicitudes->getError()
			);
			$solicitudes->rollbackMysql();
			die(json_encode($json));
		}
	}
	
	
	$result = $ficha->getFichaDeposito($info);
	
	if($result == WSP_SUCCESS) {
		$fecha = $ficha->getResponse()->fecha;
		$referencia = $ficha->getResponse()->referencia;
	}
	else{
		$error_result= utf8_encode($ficha->getResponse()->error);
		$json = array(
			"error" => true
			,"msg" => "Error: No se pudo generar su ficha de referencia, intentelo mas tarde ".$error_result
			,"debug" => $result
		);
		$solicitudes->rollbackMysql();
		die(json_encode($json));
	
	}
	
	
	$resp = $solicitudes->update_solicitud(
	$id_solicitante
	, $folio
	, array("referencia_ban" => $referencia ));
	
	/*Insertamos registro*/
	$solicitudes->commitMysql();
	
	
	
	$lstParam = array(
		"folio" => $folio
		,"fec_sol" => date('d/m/Y')
		,"monto_total" => $monto_total
		,"conceptos" => $detalles
	);
				
	$contenido=$enviacorreo->imprimeFormularioHtml2($lstParam);
	
	//print_r($contenido); exit;
	$config = (object)$CONFIG;

	
	
	$datEmail=array(
		'addAddress'=>$correo_usuario
		,'from'=>'sistemas@dgire.unam.mx'
		,'subject'=>'Registro de solicitud de pago'
		,'fromName'=>'Sistemas'
		,'addBcc'=>$config->addBcc
		,'addBccName'=>'Sistemas DGIRE'
		,'addCC'=>$config->addCC
		,'addCCName'=>'Pagos DGIRE'
		,'contenido'=>$contenido
	);
	
	
	
	$datEmail=array(
		'addAddress'=>$correo_usuario
		,'from'=>'sistemas@dgire.unam.mx'
		,'subject'=>'Registro de solicitud de pago'
		,'fromName'=>'Sistemas'
		,'addCC'=>"ammoises@yahoo.com.mx"
		,'addBcc'=>"ammoises@yahoo.com.mx"
		,'addBccName'=>'Sistemas DGIRE'
		,'addCCName'=>'Pagos DGIRE'
		,'contenido'=>$contenido
	);
	
	
	
	
	
	
	
	$resp_envia_correo= $enviacorreo->enviaCorreoSolicitante($contenido, $datEmail,$referencia);
	
	if(!$resp_envia_correo){
		$json = array(
			"error" => true
			,"msg" => "Error #005 Problema enviar correo"
		);
		$solicitantes->rollbackMysql();
		die(json_encode($json));
	}
	
	
	/*Borra la ficha de la carpeta doc*/

	if($resp_envia_correo != 0){
	 
		$carpeta_folio="../fichas/doc/" . $referencia . '.pdf';
	    
		unlink($carpeta_folio);
	  
	}else{
		
		$json = array(
			"error" => true
			,"msg" => "No se pudo borrar la ficha dentro de la carpeta doc"
		);
		
	}

	
	$json = array(
		"error" => true
		,"msg" => "Se agrego el Registro"
		,"folio_sol" => $folio
		,"referencia_ban" => $referencia
		,"monto_tot_conc" =>  $monto_total
		,"id_concepto_pago" => $detalles
	);
	
	die(json_encode($json));
	

		
}



if($opt=="getPDF"){
	
	$sol = $solicitudes->getSolicitud($folio_sol);
	
	$fecha = explode(' ', $sol->solicitud->fec_sol)[0];
	$referencia = $sol->solicitud->referencia_ban;
	$importe = $sol->solicitud->monto_total;
	$convenio ='1136135';
	$concepto = "";
	
	foreach($sol->detalle as $id => $det){
		$concepto .= ",".$det->id_concepto_pago;
	}
	
	$concepto = substr($concepto, 1);
	
	$result = $ficha->DescargarFichaDeposito($fecha, $convenio, $referencia, $concepto, $importe);
	
	exit();		
}





?>
