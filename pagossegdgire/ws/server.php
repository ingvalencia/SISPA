<?php

require_once('nusoap.php');
require_once('../common/config.php');
require_once('../common/clsConceptosPago.php');
require_once('../common/clsSolicitudes.php');
require_once('../common/clsParametros.php');
include_once('../common/clsSolicitantes.php');
require_once('../common/clsDatosFacturacion.php');
require_once('../common/class.WSPagos.php');
include_once('../common/clsCorreo.php');
require_once('validData.php');
require_once('messages.php');


$server = new nusoap_server;
$server->configureWSDL('ws_pagos', 'urn:ws_pagos');
$server->wsdl->schemaTargetNamespace = 'urn:ws_pagos';
$server->wsdl->addComplexType(
    'data_response',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'error' => array('name' => 'error', 'type' => 'xsd:int', 'description' => '1 indica que se presento un error, 0 un error en caso contrario')
		,'num_error' => array('name' => 'num_error', 'type' => 'xsd:string', 'description' => 'Identificador del error')
        ,'message' => array('name' => 'message', 'type' => 'xsd:string', 'description' => 'Mensaje de la respuesta')
    )
);


$server->wsdl->addComplexType(
    'data_response_sol',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'error' => array('name' => 'error', 'type' => 'xsd:int', 'description' => '1 indica que se presento un error, 0 un error en caso contrario')
		,'num_error' => array('name' => 'num_error', 'type' => 'xsd:string', 'description' => 'Identificador del error')
		,'existe_sol' => array('name' => 'existe_sol', 'type' => 'xsd:int', 'description' => 'Si existe la solicitud')
        ,'pagada_sol' => array('name' => 'pagada_sol', 'type' => 'xsd:int', 'description' => 'Si esta pagada la solicitud')
		,'existe_concepto' => array('name' => 'existe_concepto', 'type' => 'xsd:int', 'description' => 'Si existe el concepto dentro de la solicitud')
		,'message' => array('name' => 'message', 'type' => 'xsd:string', 'description' => 'Mensaje de la respuesta')
    )
);


$server->wsdl->addComplexType(
    'concepto',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'clave' => array('name' => 'clave', 'type' => 'xsd:string', 'description' => 'Clave del concepto de pago')
		,'cantidad' => array('name' => 'cantidad', 'type' => 'xsd:int', 'description' => 'Cantidad requerida del concepto')
		,'precio_variable' => array('name' => 'precio_variable', 'type' => 'xsd:float', 'description' => 'Indica el monto del concepto')
    )
);

$server->wsdl->addComplexType(
    'conceptosArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array(
            'ref' => 'SOAP-ENC:arrayType',
            'wsdl:arrayType' => 'tns:concepto[]'
        )
    )
);


$server->wsdl->addComplexType(
    'intArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array(
            'ref' => 'SOAP-ENC:arrayType',
            'wsdl:arrayType' => 'xsd:integer[]'
        )
    )
);



function check_credenciales() {

	if (isset($_SERVER['PHP_AUTH_USER']) and isset($_SERVER['PHP_AUTH_PW'])) {
		if ($_SERVER['PHP_AUTH_USER'] == "codezone4" && $_SERVER['PHP_AUTH_PW'] == "123")
			return true;
		else
			return false;
	}
}











/*****************************************************
NAME: crear_solicitud
DATA:
	string 	$email				Email del usuario no registrado
	array 	$conceptos			Array de conceptos de pago de la forma 
								{(clave,cantidad),(clave,cantidad),...}
DESC:
	Registra una solicitud a un usuario no registrado, recibe un email y una lista de conceptos

*****************************************************/
$doc_crear_solicitud ="
Registra una solicitud a un usuario no registrado, recibe un email y una lista de conceptos
";
function crear_solicitud($email, $conceptos, $ptl_ptl, $nombre_solicitante, $rfc){
	global $CONFIG, $lst_message;
	
	if(!validField("email", $email)){
		return getError($lst_message->sc_valid_email);
	}
	
	$error = validField("lstConceptos", $conceptos);
	if($error){
		return getError($lst_message->sc_valid_conceptos);
	}
	
	if($ptl_ptl != ""){
		if(!validField("ptl", $ptl_ptl)){
			return getError($lst_message->sc_valid_ptl);
		}
	}
	
	if($nombre_solicitante != ""){
		if(!validField("text", $nombre_solicitante)){
			return getError($lst_message->sc_valid_nom_solicitante);
		}
	}
	
	if($rfc != ""){
		if(!validField("rfc", $rfc)){
			return getError($lst_message->sc_valid_rfc);
		}
	}

	$solicitudes = new clsSolicitudes();
	$parametros = new clsParametros($solicitudes-> getLinkMysql());
	$solicitantes = new clsSolicitantes();
	$datosFacturacion = new clsDatosFacturacion($solicitudes-> getLinkMysql());
	$enviacorreo = new clsCorreo();
	$ficha = new WSPagos();

	$p = $parametros->search_parametros();
	$sm = $p->parametros["smdf"]->valor;
	$iva = $p->parametros["iva"]->valor/100;
	$cveserie = $p->parametros["cveserie"]->valor;
	
		
	if(!$solicitudes->startTransactionMysql()){
		return getError($lst_message->sc_transaction);
	}
	
	
	$id_solicitante = 0;
	
	/* obtenemos el id del usuario */
	$user_reg = true;
	$resp = $solicitantes->getIdSolicitantePagos($email);
	
	if(!$resp){
		return getError($lst_message->sc_query_id_solicitante);
	}
	
	if($resp->count==0){
		
		$resp = $solicitantes->getIdSolicitantePagos($CONFIG->email_pagos);
	
		if(!$resp){
			return getError($lst_message->sc_id_solicitante);
		}
		
		if($resp->count==0){
			return getError($lst_message->sc_exists_solicitante);
		}
		
		$user_reg = false;
	}
	
	
	$id_solicitante = $resp->id_solicitante;
	
	
	$monto_total = 0;
	$monto_tot_conc = 0;
	$monto_total_iva = 0;
	$monto_total_sin_iva = 0;
	
	$detalles = array();
	
	$claves= array();

	
	/* verificamos si existe el rfc y si pertenecec al usuaario */
	if($rfc != null){
		
		$resp = $solicitantes->existe_rfc($id_solicitante, $rfc);
		if(!$resp){
			return getError($lst_message->sc_query_rfc);
		}
		
		if($resp->count){
			return getError($lst_message->sc_exists_rfc);
		}
		
	}
	
	
	/**/
	
	
	
	
	
	
	
	/* Procesamos los conceptos */
	foreach($conceptos as $i => $x_concepto){
		
		$x_concepto = (object)$x_concepto;
		$claves[] = $x_concepto->clave;
		
		$resp_concepto = $solicitudes->getConcepto($x_concepto->clave);
			
		if(!$resp_concepto){
			return getError($lst_message->sc_query_concepto, $x_concepto->clave);
		}
		
		if(!$resp_concepto->count){
			return getError($lst_message->sc_exists_concepto);
		}
			
		$concepto = $resp_concepto->concepto;
		
		$id_concepto_pago= $concepto->id_concepto_pago;
		$calcula_iva= $concepto->calcula_iva;
		$importe_smdf=$concepto->importe_smdf;
		$importe_pesos=$concepto->importe_pesos;
		$costo_variable=$concepto->costo_variable;

		if(isset($x_concepto->precio_variable)){
			
			$precio_variable = $x_concepto->precio_variable;
		}
		else{
			if($importe_smdf!=0){
				$precio_variable = $importe_smdf*$sm;
			}
			else{
				$precio_variable = $importe_pesos;
			}
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

		$monto_total += $MontosConcepto->monto_tot_conc;

	}
	
	/* Obtenemos el folio de la solicitu */
	$folio = $solicitudes->getFolio($id_solicitante);
	
	if(!$folio){
		$solicitudes->rollbackMysql();
		return getError($lst_message->sc_get_folio);
	}
	
	$monto_total = number_format($monto_total, 2, '.', '');
	
	//echo $monto_total; exit;
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
		,"rfc" => ''
		,"ptl_ptl" => $ptl_ptl
		,"nombre_solicitante" => $nombre_solicitante
		,"correo_rfc" => $user_reg ? '' : $email
		,"factura"=> 0
	);
	
	$resp = $solicitudes->agregar_solicitud($lstParam);
	
	if(!$resp){  
		$solicitudes->rollbackMysql();
		return getError($lst_message->sc_save_solicitud);
	}
	
	foreach($detalles as $clave => $det){
	
	
		$resp = $solicitudes->agregar_detalle($id_solicitante, $folio, $det );
				
		if(!$resp){
			$solicitudes->rollbackMysql();
			return getError($lst_message->sc_save_detalle);
		}
	}
	
	$result = $ficha->getFichaDeposito($info);
	
	if($result == WSP_SUCCESS) {
		$fecha = $ficha->getResponse()->fecha;
		$referencia = $ficha->getResponse()->referencia;
	}
	else{
		$solicitudes->rollbackMysql();
		return getError($lst_message->sc_ws_ficha);
	
	}
	
	
	$resp = $solicitudes->update_solicitud(
		$id_solicitante
		, $folio
		, array("referencia_ban" => $referencia )
	);
	
	if(!$resp){  
		$solicitudes->rollbackMysql();
		return getError($lst_message->sc_save_referencia);
	}
	
	$lstParam = array(
		"folio" => $folio
		,"fec_sol" => date('d/m/Y')
		,"monto_total" => $monto_total
		,"conceptos" => $detalles
	);
	
	$contenido=$enviacorreo->imprimeFormularioHtml2($lstParam);

		
	$datEmail=array(
		'addAddress'	=> $email
		,'from' 		=> $CONFIG->from_email
		,'subject' 		=> 'Registro de solicitud de pago'
		,'fromName'	 	=> 'Sistemas'
		//,'addBcc' 		=> $CONFIG->addBcc
		,'addBcc' 		=> "ammoises@yahoo.com.mx"
		,'addBccName' 	=> 'Sistemas DGIRE'
		,'addCC' 		=> $CONFIG->addCC
		,'addCCName' 	=> 'Pagos DGIRE'
		,'contenido' 	=> $contenido
	);
	
	
	
	$resp_envia_correo= $enviacorreo->enviaCorreoSolicitante($contenido, $datEmail,$referencia);
	
	if(!$resp_envia_correo){
		$solicitudes->rollbackMysql();
		return getError($lst_message->sc_send_email);
	}
	
	/*Insertamos registro*/
	$solicitudes->commitMysql();
	
	/*Borra la ficha de la carpeta doc*/

	if($resp_envia_correo != 0){
	 
		$carpeta_folio="../fichas/doc/" . $referencia . '.pdf';
	    
		unlink($carpeta_folio);
	  
	}else{
		
		
		
	}

	
	return return_data($folio);
	
}


/**************************************************
Registra funcion:   crear_solicitud
**************************************************/
$server->register(
		'crear_solicitud'
		,array(													//parametros
			'email' => 'xsd:string'
			,'conceptos' => 'tns:conceptosArray'
			,'ptl_ptl' => 'xsd:string'
			,'nombre_solicitante' => 'xsd:string'
			,'rfc' => 'xsd:string'
			) 	
		,array('return' => 'tns:data_response')  				//output
		,'urn:ws_pagos'   										//namespace
		,'urn:ws_pagos#crear_solicitud'  						//soapaction
		,'rpc' 													// style
		,'encoded' 												// use
		,$doc_crear_solicitud
);









/*****************************************************
NAME: existe_solicitud
DATA:
	int 	$folio			Folio de pago 
	
DESC:
	Verifica si existe la solicitud con el folio
	
*****************************************************/
$doc_existe_solicitud = "
Verifica si existe la solicitud con el folio
";
function existe_solicitud($folio){
	
	if(!validField("int", $folio)){
		return getError($lst_message->es_valid_folio);
	}
	
	$solicitudes = new clsSolicitudes();
	
	$resp = $solicitudes->exist_solicitud($folio);
	if(!$resp){
		return getError($lst_message->es_query_folio);
	}
	
	//$solicitudes->close();
	
	return return_data($resp->count);
	
}


/**************************************************
Registra funcion:   existe_solicitud
**************************************************/
$server->register(
		'existe_solicitud'
		,array('folio' => 'xsd:int') 				//parametros
		,array('return' => 'tns:data_response')  	//output
		,'urn:ws_pagos'   							//namespace
		,'urn:ws_pagos#existe_solicitud'  			//soapaction
		,'rpc' 										// style
		,'encoded' 									// use
		,$doc_existe_solicitud
);








/*****************************************************
NAME: valid_conceptos_solicitud
DATA:
	int 	$folio			Folio de pago 
	array 	$conceptos		lista de conceptos
	
DESC:
	Verifica si existe la solicitud con el folio y si 
	existen los conceptos dentro de la solicitud
	
*****************************************************/
$doc_valid_concepto_solicitud = "
Verifica si existe la solicitud con el folio y si 
existe el concepto dentro de la solicitud
";
function valid_concepto_solicitud($folio, $concepto){
	
	if(!validField("int", $folio)){
		return getError($lst_message->vcs_valid_folio);
	}
	
	if(!validField("concepto", $concepto)){
		return getError($lst_message->vcs_valid_concepto);
	}

	$solicitudes = new clsSolicitudes();
	if(!$solicitudes){
		return getError($lst_message->vcs_obj_solicitudes);
	}
	
	$resp = $solicitudes->getSolicitud($folio);
	if(!$resp){
		return getError($lst_message->vcs_query_folio);
	}
	
	if(!$resp->count){
		return array(
			'error' => 0
			,'num_error' => 0
			,'existe_sol' => 0
			,'pagado_sol' => 0
			,'existe_concepto' => 0
			,'message' => "No existe el concepto"
		);
	}
	
	$sol = $resp->solicitud;
	foreach($sol->detalle as $id => $det){
		if($det->id_concepto_pago == $concepto){
			return array(
				'error' => 0
				,'num_error' => 0
				,'existe_sol' => 1
				,'pagado_sol' => 0
				,'existe_concepto' => 0
				,'message' => "Existe el concepto"
			);
		}
	}
	
	return return_data(0);
		
}

/**************************************************
Registra funcion:   valid_concepto_solicitud
**************************************************/
$server->register(
		'valid_concepto_solicitud'
		,array('folio' => 'xsd:int', 'concepto' => 'xsd:string') 	//parametros
		,array('return' => 'tns:data_response_sol')					//output
		,'urn:ws_pagos'   											//namespace
		,'urn:ws_pagos#valid_concepto_solicitud'					//soapaction
		,'rpc' 														// style
		,'encoded' 													// use
		,$doc_valid_concepto_solicitud
);







/*****************************************************
NAME: exist_solicitante
DATA:
	string 	$email			correo del solicitante
	
DESC:
	Verifica si existe un solicitante 
	
*****************************************************/
$doc_existe_solicitante = "
Verifica si existe un solicitante 
";
function existe_solicitante($email){
	
	if(!validField("email", $email)){
		return getError($lst_message->exs_valid_email);
	}
	
	$solicitantes = new clsSolicitantes();
	if(!$solicitantes){
		return getError($lst_message->exs_obj_solicitantes);
	}
	
	$resp = $solicitantes->getIdSolicitantePagos($email);
	if(!$resp){
		return getError($lst_message->exs_id_solicitante);
	}
	
	if(!$resp->count){
		return return_data(0);
	}
	
	return return_data(1);
}


/**************************************************
Registra funcion:   exist_solicitante
**************************************************/
$server->register(
		'exist_solicitante'
		,array('email' => 'xsd:string') 				//parametros
		,array('return' => 'tns:data_response')  		//output
		,'urn:ws_pagos'   								//namespace
		,'urn:ws_pagos#existe_solicitante'  						//soapaction
		,'rpc' 											// style
		,'encoded' 										// use
		,$doc_existe_solicitante
);






/*****************************************************
NAME: getRFCS
DATA:
	string 	$email			correo del solicitante
	
DESC:
	Obtiene la lista de RFC asociados a un usuario registrado
	
*****************************************************/
$doc_getRFCS = "
Obtiene la lista de RFC asociados a un usuario registrado
";
function getRFCS($email){
	
	if(!validField("email", $email)){
		return getError($lst_message->rfc_valid_email);
	}
	
	$solicitantes = new clsSolicitantes();
	if(!$solicitantes){
		return getError($lst_message->rfc_obj_solicitantes);
	}
	
	$resp = $solicitantes->searchRFCS($email);
	if(!$resp){
		return getError($lst_message->rfc_query_rfc);
	}
	
	if(!$resp->count){
		return return_data(0);
	}
	
	return array(
			'error' => 0
			,'num_error' => 0
			,'exist' => 1
			,'rfcs' => $resp->rfcs
		);
}


/**************************************************
Registra funcion:   getRFCS
**************************************************/
$server->register(
		'getRFCS'
		,array('email' => 'xsd:string') 				//parametros
		,array('return' => 'tns:data_response')  		//output
		,'urn:ws_pagos'   								//namespace
		,'urn:ws_pagos#getRFCS'  						//soapaction
		,'rpc' 											// style
		,'encoded' 										// use
		,$doc_getRFCS
);





/*****************************************************
NAME: validar_pago
INPUT:
	int 	folio			Folio de pago 
	
OUTPUT:
	array(
		int			error			Indica si se presenta error: 0 si no existe error, 1 en caso contrario
		string		num_error		Numero de error que presento
		string		message			
	)
DESC:
	Verifica si una solicitud con el folio indicado esta pagada, si no es asi
	verifica el pago a traves del web service de patronato y genera el ticket
	o factura segun corresponda

*****************************************************/
$doc_validar_pago = "
Verifica si una solicitud con el folio indicado esta pagada, si no es asi
verifica el pago a traves del web service de patronato y genera el ticket
o factura segun corresponda";
function validar_pago($folio) {	

	if(!check_credenciales()){
		return getError($lst_message->vp_credenciales);
	}
	
	if(!validField("folio", $folio)){
		return getError($lst_message->vp_valid_folio);
	}

	$resp = $solicitudes->validPago($folio);
	if(!$resp){
		return getError($lst_message->vp_query_folio);
	}
	
	
	return return_data(23433323);
	
}			



/**************************************************
Registra funcion:   valid_pago
**************************************************/
$server->register(
		'valid_pago'
		,array('folio' => 'xsd:string') 				//parametros
		,array('return' => 'tns:data_response')  		//output
		,'urn:ws_pagos'   								//namespace
		,'urn:ws_pagos#valid_pago'  						//soapaction
		,'rpc' 											// style
		,'encoded' 										// use
		,$doc_validar_pago
);














 








	
ob_clean();
$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA'])? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
$server->service($POST_DATA);
