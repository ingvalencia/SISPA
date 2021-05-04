<?php

require_once('nusoap.php');
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
    'concepto',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'clave' => array('name' => 'clave', 'type' => 'xsd:int', 'description' => 'Clave del concepto de pago')
		,'cantidad' => array('name' => 'cantidad', 'type' => 'xsd:int', 'description' => 'Cantidad requerida del concepto')
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
function crear_solicitud($email, $conceptos){
	
	if(!check_credenciales()){
		return getMessage("A001");
	}
	
	if(!validField("email", $email)){
		return getMessage("D001");
	}
	
	if(!validField("lstConceptos", $conceptos)){
		return getMessage("D002");
	}
	
	
	
	foreach($conceptos as $id => $c){
		
	}
	
	$folio= 23423423;
	return return_data($folio);
	
}


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
		return getMessage("D003");
	}
	
	$folio= 23423423;
	return return_data($folio);
	
}



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
existen los conceptos dentro de la solicitud
";
function valid_concepto_solicitud($folio, $conceptos){
	
	foreach($conceptos as $id => $c){
		
	}
	
	$folio= 23423423;
	return return_data($folio);
	
}

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
function validar_pago($folios) {	

	if(!check_credenciales()){
		return getMessage("A001");
	}

	return return_data(23433323);
	
}			















/**************************************************
Registra funcion:   crear_solicitud
**************************************************/
$server->register(
		'crear_solicitud'
		,array('email' => 'xsd:string', 'conceptos' => 'tns:conceptosArray') 	//parametros
		,array('return' => 'tns:data_response')  								//output
		,'urn:ws_pagos'   														//namespace
		,'urn:ws_pagos#crear_solicitud'  										//soapaction
		,'rpc' 																	// style
		,'encoded' 																// use
		,$doc_crear_solicitud
);



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



/**************************************************
Registra funcion:   valid_concepto_solicitud
**************************************************/
$server->register(
		'valid_concepto_solicitud'
		,array('folio' => 'xsd:int', 'conceptos' => 'xsd: int[]') 	//parametros
		,array('return' => 'tns:data_response')  					//output
		,'urn:ws_pagos'   											//namespace
		,'urn:ws_pagos#valid_concepto_solicitud'					//soapaction
		,'rpc' 														// style
		,'encoded' 													// use
		,$doc_valid_concepto_solicitud
);



/**************************************************
Registra funcion:   valid_pago
**************************************************/
$server->register(
		'valid_pago'
		,array('folios' => 'xsd:string') 				//parametros
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
