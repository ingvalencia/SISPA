
<?php
include("xmlrpc.inc");


$usuario = "JLCHIQUE";
$password = "JLCHIQUE";


$parametros = array();

/*
//Emisión de fichas de depósito

$parametro0 = array(
	'Dependencia' => "551",
	'Subdep' => "01",
	'Origen' => "DE",
	'Cve_moneda' => "MXN",
	'Tipo_pago' => "EF",
	'Importe' => "1.00",
	'Concepto' => "Ficha de Deposito prueba servicio."
);

$parametros = array();
$parametros[] = php_xmlrpc_encode($parametro0);

$mensaje = new xmlrpcmsg("fichadeposito.datos", $parametros);
unset($parametros);

//$cliente = new xmlrpc_client("http://132.248.40.26:80/wscfdspruebas/fichadepositoN.php");
$cliente = new xmlrpc_client("http://wscfdspruebas.patronato.unam.mx/wscfdspruebas/fichadepositoN3.php");


$cliente->setCredentials($usuario, $password);
$respuesta = $cliente->send($mensaje);
print_r($respuesta);
unset($cliente);
unset($mensaje);
exit;
*/



//Consulta de movimientos bancarios por referencia

$parametros = array();
$referencia= '5510121000051DD20254';

$parametros[]= new xmlrpcval(['referencia' => new xmlrpcval($referencia, 'string')], 'struct');

$mensaje = new xmlrpcmsg("consultabancoreferencia.srv", $parametros);
unset($parametros);

$cliente = new xmlrpc_client("http://wscfdspruebas.patronato.unam.mx/wstiendapruebas/srv.php");


$cliente->setCredentials($usuario, $password);
$respuesta = $cliente->send($mensaje);
print_r($respuesta);
unset($cliente);
unset($mensaje);
exit;










?>
