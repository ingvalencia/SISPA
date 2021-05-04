
<?php
include("xmlrpc.inc");

$usuario = "JLCHIQUE";
$password = "JLCHIQUE";

$parametros = array();

$parametro0 = array(
	'Dependencia' => "551",
	'Subdep' => "01",
	'Origen' => "DE",
	'Cve_moneda' => "MXN",
	'Tipo_pago' => "EF",
	'Importe' => "1.00",
	'Concepto' => "Ficha de Deposito prueba servicio."
);

$parametros[] = php_xmlrpc_encode($parametro0);

$parametro0 = array(
	'Dependencia' => "551",
	'Subdep' => "01",
	'Origen' => "DE",
	'Cve_moneda' => "MXN",
	'Tipo_pago' => "EF",
	'Importe' => "1.00",
	'Concepto' => "Ficha de Deposito prueba servicio."
);

$parametros[] = php_xmlrpc_encode($parametro0);




$mensaje = new xmlrpcmsg("fichadeposito.datos", $parametros);
unset($parametros);

$cliente = new xmlrpc_client("https://132.248.40.26:443/wscfdspruebas/fichadepositoN.php");
$cliente->setCredentials($usuario, $password);
$respuesta = $cliente->send($mensaje);
//$respuesta->raw_data="";
print_r($respuesta);

//header("Content-Disposition: attachment; filename = documento_solicitud.pdf");
//echo $respuesta->raw_data;
unset($cliente);
unset($mensaje);


exit;




?>
