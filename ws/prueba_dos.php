
<?php
include("xmlrpc.inc");


$usuario = "JLCHIQUE";
$password = "JLCHIQUE";


$sprecio = 1.72413793;
$siva = 0.275862069;



/*
* Si coloco 
* 'Iva' => "16",
* 'Iva_impte' => 0.275862069,
*
* me sigue mandando el mismo error
* Factura Electrónica Sección Información General | Importe Total no coincide con el 
* Importe Total de los productos.
*
*/


$productos = array();
$productos[0] = array(
	'Cta_ie' => "202",
	'Cantidad' => 1,
	'Unidad' => "NO APLICA",
	'Descripcion' => "PRODUCTO",
	'Precio_unit' => 2,
	'Iva' => "0",
	'Iva_impte' => 0,
	'Importe_total' => 2
);

$pagos = array();
$pagos[0] = array(
	'Pago' => "EFECTIVO",
	'Cve_pago' => "EF",
	'Importe' => "2.00"
);

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
	'Importe_factura' => 2,
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

//print_r($parametros);exit;
unset($parametros);

$cliente = new xmlrpc_client("http://wscfdspruebas.patronato.unam.mx/wscfdspruebas/siiewebNV3.php");
http://wscfdspruebas.patronato.unam.mx/wscfdspruebas/siiewebNV3.php

$cliente->setCredentials($usuario, $password);
$respuesta = $cliente->send($mensaje);
print_r($respuesta);
unset($cliente);
unset($mensaje);


	

?>
