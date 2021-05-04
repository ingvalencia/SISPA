<?php
 
require_once '../nusoap.php';

function doAuthenticate() {
	if (isset($_SERVER['PHP_AUTH_USER']) and isset($_SERVER['PHP_AUTH_PW'])) {
		if ($_SERVER['PHP_AUTH_USER'] == "codezone4" && $_SERVER['PHP_AUTH_PW'] == "123")
			return true;
		else
			return false;
	}
}
 
function get_zip_code($area) {
	if (!doAuthenticate())
		return "Invalid username or password";
	
	if ($area == "Temple Hills") {
		return 20757;
	} else if ($area == "Cheltenham") {
		return 20623;
	} else {
		return "Can not find zip code for " . $area;
	}
}
 
$server = new soap_server();
$server->configureWSDL("zipcodelist", "urn:zipcodelist");
 
//Register web service function so that clients can access
$server->register("
	get_zip_code"
	,array("area" => "xsd:string")
	,array("return" => "xsd:int")
	,"urn:zipcodelist"
	,"urn:zipcodelist#get_zip_code"
	,"rpc"
	,"encoded"
	,"Retrieve zip code for a given area"
);
 
ob_clean();
$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA'])? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
$server->service($POST_DATA);
?>