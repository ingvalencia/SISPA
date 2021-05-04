<?php
 require_once('clsServer.php');
  $debug=1;
  
  	
ob_clean();
 $server=new Soap_wrapper();
 /*********************************************************/ 
 $HTTP_RAW_POST_DATA=isset($HTTP_RAW_POST_DATA)?$HTTP_RAW_POST_DATA:'';
 $server->service_start($HTTP_RAW_POST_DATA);
 
 ?>