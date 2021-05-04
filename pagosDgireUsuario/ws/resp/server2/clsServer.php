<?php
require_once ('../nusoap.php');

class Soap_wrapper extends soap_server{
	var $script_uri;
	public function __construct(){
		$page = $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

		// $page = substr($page,0,strrpos($page,'/'));

		$this->script_uri = 'http://' . $page;
		parent::nusoap_server();
		$this->configureWSDL('testserver', 'urn:' . $this->script_uri);
		$this->wsdl->addComplexType(
			"ArrayOfString"
			, "complexType"
			, "array"
			, ""
			, "SOAP-ENC:Array"
			, array()
			, array(array(
				"ref" => "SOAP-ENC:arrayType",
				"wsdl:arrayType" => "xsd:string[]"
				)
			)
			, "xsd:string"
		);
		
		$this->wsdl->schemaTargetNamespace = $this->script_uri;
		$this->register(
			'authenticate'
			, array('client' => 'xsd:string','key' => 'xsd:string')
			, array('return' => 'xsd:string')
			, 'urn:' . $this->script_uri, 'urn:' . $this->script_uri . '#authenticate'
		);
		
	}
	
	public	function service_start($data){
		if (!empty($data)){
			/***********parsing header for authentication********/
			//parse header for username and password
			/*********************************************************/
				$this->validateUser($authHeaders); // function to validate the user
				$this->service($data);
		}
		else 
			$this->service($data);
	}
	
	

	public function validateUser($auth)	{
			
		if (!empty($auth[username]) && !empty($auth[password])) 
			authenticate($auth[username], $auth[password]); //this function check the authentication from db
		else 
			$server->fault(401, 'Authentication failed!');
		
		
	}
		
}