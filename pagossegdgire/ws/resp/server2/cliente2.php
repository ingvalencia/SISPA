<?php

require_once('nusoap.php'); 

$url = "http://132.248.38.19/~pruebas/aplicaciones/pagos_v2/pagosDgireUsuario/ws/server2.php";

$client=new SoapClient("$url?wsdl",array('trace'=>true));
$param= new SoapVar(array('username' => 'abdullah','password'=>'test'), SOAP_ENC_ARRAY); 
$header = new SoapHeader($url, 'AuthenticationInfo', $param,false);
$client->__setSoapHeaders($header);
try{
  $data=$client->__soapCall('getData',array('id'=>'1'));
print_r($data);
}
catch (SoapFault  $exception)
{
echo $exception->faultstring;
}

