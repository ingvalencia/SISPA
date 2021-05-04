<?php


require_once('nusoap.php');

$wsdl = "http://132.248.38.19/pagos_v2/pagosDgireUsuario/ws/server.php?wsdl";

$client = new nusoap_client($wsdl, 'wsdl');

$err = $client->getError();
if ($err) {
	die('<h2>Constructor error</h2>' . $err);
}

$user = 'ws_user';
$pwd = 'do_+3R,vi4X#';


$data = array(
'user' => $user
,'pwd' => $pwd
,'folios' => '20160810357'
);


//calling our first simple entry point
$resp=(object)$client->call('valid_pago', $data);

print_r($resp); exit;

echo $resp->error."<br>";
echo $resp->num_error."<br>";
echo $resp->msg."<br>";




?>