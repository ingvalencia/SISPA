
<?php
include("xmlrpc.inc");





$usuario = "JLCHIQUE";
$password = "JLCHIQUE";
/*
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
*/

$sprecio = 1.72413793;
$siva = 0.275862069;

$productos = array();
$pagos = array();

/*
$productos[0] = array(
	'Cta_ie' => "202",
	'Cantidad' => 1,
	'Unidad' => "NO APLICA",
	'Descripcion' => "PRODUCTO",
	'Precio_unit' => 1,
	'Iva' => "16",
	'Iva_impte' => 0.14,
	'Importe_total' => 1
);
*/

$productos[0] = array(
	'Cta_ie' => "202",
	'Cantidad' => 2,
	'Unidad' => "NO APLICA",
	'Descripcion' => "PRODUCTO",
	'Precio_unit' => 1,
	'Iva' => "0",
	'Iva_impte' => 0,
	'Importe_total' => 2
);


$pagos[0] = array(
	//'Pago' => "EFECTIVO",
	'Cve_pago' => "EF",
	'Importe' => "2"
);


/*
$productos[1] = array(
	'Cta_ie' => "202",
	'Cantidad' => 1,
	'Unidad' => "NO APLICA",
	'Descripcion' => "PRODUCTO",
	'Precio_unit' => 2,
	'Iva' => "16",
	'Iva_impte' => 0.16,
	'Importe_total' => 2
);


$pagos[1] = array(
	//'Pago' => "EFECTIVO",
	'Cve_pago' => "EF",
	'Importe' => "2.00"
);
*/


$datos = array(
	'Procedencia' => "OT",
	'Pais' => "MX",
	'Rfc' => "AAAA121231ZZZ",
	'Razon_soc' => "",
	'Nombre' => "NOMBRE",
	'A_paterno' => "PATERNO",
	'A_materno' => "MATERNO",
	'Estado' => "ESTADO",
	'DelOMunicipio' => "DELEGACIÓN",
	'Cp' => "12345",
	'Colonia' => "COLONIA",
	'Calle' => "CALLE",
	'No_ext' => "NÚM. EXT.",
	'No_int' => "NÚM. INT.",
	'Email' => "correo@dominio.com"
);

$dogDig = array(
	'Dependencia' => "551",
	'Subdep' => "01",
	'Origen' => "DE",
	'Tipo_moneda' => "1",
	'Cve_moneda' => "MXN",
	'Carta_certif' => 0,
	'Forma_liq' => "E",
	'Status_pago' => "I",
	'Importe_factura' => 1,
	'Observaciones' => "OBSERVACIONES"
);

$parametro0 = array(
	'Tpocfd' => "FD",
	'Cvepago' => "SG"
);

$parametros = array();
$parametros[] = php_xmlrpc_encode($parametro0);
$parametros[] = php_xmlrpc_encode($productos);
$parametros[] = php_xmlrpc_encode($pagos);
$parametros[] = php_xmlrpc_encode($datos);
$parametros[] = php_xmlrpc_encode($dogDig);

$mensaje = new xmlrpcmsg("insertcfds.datos", $parametros);
unset($parametros);

$cliente = new xmlrpc_client("https://132.248.40.26:443/wscfdspruebas/siiewebN.php");
$cliente->setCredentials($usuario, $password);
$respuesta = $cliente->send($mensaje);
print_r($respuesta);
//header("Content-Disposition: attachment; filename = documento_solicitud.pdf");
//echo $respuesta->raw_data;
unset($cliente);
unset($mensaje);

?>
