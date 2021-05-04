
<?php

require_once ("../common/config.php");
require_once ("../common/clsSolicitudes.php");
require_once ("../common/clsDatosFacturacion.php");
include_once ("../common/clsCorreo.php");
require_once ("../common/class.WSPagos.php");
include_once ("../common/clsLog.php");


session_start();

foreach($_POST as $k => $val){
	$var = "\$" . $k . "=0;";
	eval($var);
	$var = "\$ref=&$" . $k . ";";
	eval($var);
	$ref = addslashes($val);
}

$solicitudes = new clsSolicitudes();
$datosFacturacion = new clsDatosFacturacion($solicitudes-> getLinkMysql());
$enviacorreo = new clsCorreo();
$ficha = new WSPagos();

$MyDebug->SetDebug(0);
$MyDebug->DebugError();

$id_solicitante = $_SESSION["userData2"]->id_solicitante;
$nom_solicitante = $_SESSION["userData2"]->nombre_usr;
$usr_solicitante = $_SESSION["userData2"]->correo_e;


if(!$solicitudes){

    $json = array(
        			"error"  => true
    				,"msg"   => "Error C001: No se pudo conectarse con el servidor, si el probelma persiste comuniquese con el administrador"
    				,"debug" => $solicitudes->getError()
    );
    die(json_encode($json));

}

function getIni(){

    global $solicitudes;

    $conceptos = $solicitudes->getTiposConceptos();

    $json = array(
       				 "error" => false
    				,"data"  => array("conceptos" => $conceptos->tipos_conceptos)
    );

    return $json;
}

$json["error"] = "no";


if($opt=="getIni"){

		$resp = $solicitudes->getEstadoPago();


		if(!$resp){
					$json = array(
									"error"  => true
									,"msg"   => "Error C002: No se pudo conectarse con el servidor, si el probelma persiste comuniquese con el administrador"
									,"debug" => $conceptosPago->getError()
					);
					die(json_encode($json));
		}

		$json["error"]=false;
		$json["estados"] = $resp->estados;

		die(json_encode($json));
}

/*Valida el pago*/

if($opt=="validPago"){

		$resp_verificarPago=$solicitudes->verificarPago($folio_sol, $id_solicitante);

		//print_r($resp_verificarPago);exit();

		if(!$resp_verificarPago){

				$json = array(
								"error"  => true
								,"msg" 	 => "No se puede accesar a la informacion.<br>Error: verificarPago"
								,"debug" => $solicitudes->getError()
				);
				die(json_encode($json));
		}

		/*Respuesta Error WS*/
		
		$new_array = array();
		$array= get_object_vars($resp_verificarPago);

		foreach( $array as $indice=>$valor ) {
			$new_array[] = $valor;
		}

		$biterror_verificarPago=$new_array[1];
		$men_verificarPago=$new_array[5];

		if($biterror_verificarPago==1){

						$json = array(
										"error"  =>	true
										,"query" =>	$solicitudes->getLastQuery()
										,"valid" =>	true
										,"msg"   =>	$men_verificarPago
										,"debug" =>	$resp_verificarPago
						);
						die(json_encode($json));


		}else{
						$json = array(
										"error"  => false
										,"query" => $solicitudes->getLastQuery()
										,"valid" => true
										,"msg" 	 => $men_verificarPago
										,"debug" => $resp_verificarPago


						);
						die(json_encode($json));

		}

}

/*Genera Factura*/
if($opt=="genera_factura"){

	print_r('Mejorando el servico');exit();

}

/*Descarga Ticket*/

if($opt=="genera_ticket"){


		$lstParam = array(
							"id_solicitante" => $id_solicitante
							,"folio_sol" => $folio_sol
		);

		$resp = $solicitudes->search_detalle_solicitud($lstParam);

		print_r($resp);exit;
		
		if(!$resp){

				$json = array(
                    			"error"  =>	true
            					,"msg"   =>	"No se puede accesar a la información.<br>Error:search_detalle_solicitud"
            					,"debug" =>	$solicitudes->getError()
				);
				die(json_encode($json));
		}

		$num_folio_tic = $resp->detalles[0]->folio_ticket;
		$email_e = $resp->detalles[0]->correo_e;

		//print_r($num_folio_tic);exit;
    	//$email_e = $resp->detalles[0]->correo_e;

		$result = $solicitudes->GeneraTicket($id_solicitante, $folio_sol,$num_folio_tic,$email_e);

		print_r($email_e);exit;

		if(!$result){

				$json = array(
								"error"	 =>	true
            					,"msg"	 =>	"No se puede accesar a la información.<br>Error:GeneraTicket"
            					,"debug" =>	$solicitudes->getError()
				);


				die(json_encode($json));
		}

}


/*Detalle de la solicitud*/

if($opt=="getDetalleSolicitud"){

		$resp = $solicitudes->getDetalleSolicitud($folio_sol);

		if(!$resp){

				$json = array(
                    			"error"	 =>	true
            					,"msg"	 =>	"No se puede accesar a la informacion.<br>Error:getDetalleSolicitud"
            					,"debug" =>	$solicitudes->getError()
				);
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

		$resp = $solicitudes->search_solicitudes(array("folio_sol" => $folio_sol));

		if(!$resp){

				$json = array(
                    			"error"	 =>	true
            					,"msg"	 =>	"No se puede accesar a la informacion.<br>Error:search_solicitudes"
            					,"debug" =>	$solicitudes->getError()

				);
				die(json_encode($json));
		}


		$montos = array(
							"monto_total_iva" => '$'.number_format($resp->solicitudes[0]->monto_total_iva,2)
							,"monto_total_sin_iva" => '$'.number_format($resp->solicitudes[0]->monto_total_sin_iva,2)
							,"monto_total" => '$'.number_format($resp->solicitudes[0]->monto_total,2)
		);

		$json["data"]["montos"]=$montos;
		die(json_encode($json));
}



?>
