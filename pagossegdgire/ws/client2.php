<?php
require_once('nusoap.php');

$url = "http://132.248.38.19/~pruebas/aplicaciones/pagos_v2/pagossegdgire/ws/server.php";

$client = new nusoap_client("$url?wsdl", 'wsdl');
 
$error = $client->getError();

if ($error) {
	echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
}

//Use basic authentication method
$client->setCredentials("codezone4", "123", "basic");
$result = "";
 
if ($client) {
	
	$data = array('folio' => "20170512016");
	
	$result = $client->call("existe_solicitud", $data);
	
}
if ($client->fault) {
	echo "<h2>Fault</h2><pre>";
	print_r($result);
	echo "</pre>";
} 
else {
	$error = $client->getError();
	if ($error) {
		echo "<h2>Error</h2><pre>" . $error . "</pre>";
	} else {
		echo "<h2>zip code</h2><pre>";
		$resp = (object)$result;
		echo "<p>Error: $resp->error</p>";
		echo "<p>#Error: $resp->num_error</p>";
		echo "<p>Mensaje: $resp->message</p>";
		echo "</pre>";
	}
}
 
echo "<h2>Request</h2>";
echo "<pre>" . htmlspecialchars($client->request, ENT_QUOTES) . "</pre>";
echo "<h2>Response</h2>";
echo "<pre>" . htmlspecialchars($client->response, ENT_QUOTES) . "</pre>"
?>