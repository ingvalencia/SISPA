<?php

function getError($error, $xData = ''){
	
	if(is_null($msg)){
		return array(
			'error' => true
			,'num_error' => "E00X"
			,'message' => "ss"
		);
	}
	
	$msg = (object)$msg;

	$message = $msg->message;
	if($xData!=''){
		$message .= ": $xData";
	}
	
	return array(
		'error' => true
		,'num_error' => $msg->num_error
		,'message' => $message
	);

}


function getErrorSol($error, $xData = ''){
	global $message;
	
	if(!isset($message[$error])){
		
		return array(
			'error' => 1
			,'num_error' => "E000"
			,'existe_sol' => 0
			,'pagado_sol' => 0
			,'existe_concepto' => 0
			,'message' => "Error en el webservice comuniquese con el administrador"
		);
		
	}
	
	$msg = $message[$error];
	if($xData!=''){
		$msg .= ": $xData";
	}
	
	return array(
		'error' => 1
		,'num_error' => $error
		,'existe_sol' => 0
		,'pagado_sol' => 0
		,'existe_concepto' => 0
		,'message' => $msg
	);
}

function return_data($msg){
	
	return array(
		'error' => 0
		,'num_error' => 0
		,'message' => $msg
	);
}



$generic_error = "Error en el servidor comuniquese con el administrador";


$lst_message = (object)array(
	//crear solicitud
	"sc_valid_email" 			=> array("num_error" => "CS001" , "message" => "Debe de ingresar un email valido")
	,"sc_valid_conceptos"		=> array("num_error" => "CS002" , "message" => "Debe de ingresar una lista de conceptos validos")
	,"sc_valid_ptl"				=> array("num_error" => "CS003" , "message" => "El plantel no es valido")
	,"sc_valid_nom_solicitante"	=> array("num_error" => "CS004" , "message" => "El nombre del solicitante no es valido")
	,"sc_valid_rfc"				=> array("num_error" => "CS005" , "message" => "El nombre del solicitante no es valido")
	,"sc_transaction" 			=> array("num_error" => "CS006" , "message" => $generic_error)
	,"sc_query_id_solicitante"	=> array("num_error" => "CS007" , "message" => $generic_error)
	,"sc_id_solicitante"		=> array("num_error" => "CS008" , "message" => $generic_error)
	,"sc_exists_solicitante"	=> array("num_error" => "CS009" , "message" => $generic_error)
	,"sc_query_rfc"				=> array("num_error" => "CS010" , "message" => $generic_error)
	,"sc_exist_rfc"				=> array("num_error" => "CS011" , "message" => "no existe el rfc")
	,"sc_query_concepto"		=> array("num_error" => "CS012" , "message" => $generic_error)
	,"sc_exists_concepto"		=> array("num_error" => "CS013" , "message" => "El nombre del solicitante no es valido")
	,"sc_get_folio"				=> array("num_error" => "CS014" , "message" => "El nombre del solicitante no es valido")
	,"sc_save_solicitud"		=> array("num_error" => "CS015" , "message" => "El nombre del solicitante no es valido")
	,"sc_save_detalle"			=> array("num_error" => "CS016" , "message" => "El nombre del solicitante no es valido")
	,"sc_ws_ficha"				=> array("num_error" => "CS017" , "message" => "El nombre del solicitante no es valido")
	,"sc_save_referencia"		=> array("num_error" => "CS018" , "message" => "El nombre del solicitante no es valido")
	,"sc_send_email"			=> array("num_error" => "CS019" , "message" => "El nombre del solicitante no es valido")
	
	//existe_solicitud
	,"es_valid_folio"			=> array("num_error" => "ES001" , "message" => "El nombre del solicitante no es valido")
	,"es_query_folio"			=> array("num_error" => "ES002" , "message" => "El nombre del solicitante no es valido")
	
	//valid_concepto_solicitud
	,"vcs_valid_folio"			=> array("num_error" => "VCS01" , "message" => "El nombre del solicitante no es valido")
	,"vcs_valid_concepto"		=> array("num_error" => "VCS02" , "message" => "El nombre del solicitante no es valido")
	,"vcs_obj_solicitudes"		=> array("num_error" => "VCS03" , "message" => "El nombre del solicitante no es valido")
	,"vcs_query_folio"			=> array("num_error" => "VCS04" , "message" => "El nombre del solicitante no es valido")
	
	//existe_solicitante
	,"exs_valid_email"			=> array("num_error" => "EXS01" , "message" => "El nombre del solicitante no es valido")
	,"exs_obj_solicitantes"		=> array("num_error" => "EXS02" , "message" => "El nombre del solicitante no es valido")
	,"exs_id_solicitante"		=> array("num_error" => "EXS03" , "message" => "El nombre del solicitante no es valido")
	
	//getRFCS
	,"rfc_valid_email"			=> array("num_error" => "CS002" , "message" => "El nombre del solicitante no es valido")
	,"rfc_obj_solicitantes"		=> array("num_error" => "CS002" , "message" => "El nombre del solicitante no es valido")
	,"rfc_query_rfc"			=> array("num_error" => "CS002" , "message" => "El nombre del solicitante no es valido")
	
	//valid_pago
	,"vp_credenciales"			=> array("num_error" => "VP001" , "message" => "El nombre del solicitante no es valido")
	,"vp_valid_folio"			=> array("num_error" => "VP002" , "message" => "El nombre del solicitante no es valido")
	,"vp_query_folio"			=> array("num_error" => "VP003" , "message" => "El nombre del solicitante no es valido")
);

