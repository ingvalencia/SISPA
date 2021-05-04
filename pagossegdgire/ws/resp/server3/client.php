<?php
require_once('../nusoap.php');

$url = "http://132.248.38.19/~pruebas/aplicaciones/pagos_v2/pagosDgireUsuario/ws/server3/server.php";

$client = new nusoap_client("$url?wsdl", 'wsdl');
 
$error = $client->getError();

if ($error) {
	echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
}

//Use basic authentication method
$client->setCredentials("codezone4", "123", "basic");
$result = "";
 
if ($client) {
	$result = $client->call("get_zip_code", array("area" => "Cheltenham"));
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
		echo $result;
		echo "</pre>";
	}
}
 
echo "<h2>Request</h2>";
echo "<pre>" . htmlspecialchars($client->request, ENT_QUOTES) . "</pre>";
echo "<h2>Response</h2>";
echo "<pre>" . htmlspecialchars($client->response, ENT_QUOTES) . "</pre>"
?>