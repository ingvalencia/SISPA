<?php

		
function validField($type, $validData){
	
	switch($type){
		
		/* permite los caracteres A-Za-z y el _ */
		case "login":
			$exp = "/^(([A-Za-z][\_]{0,1}){1,})*$/";
			return preg_match($exp, $validData);
			break;
			
		/* permite los caracteres A-Za-z y el $@$!%*?& */
		case "password":
			$exp = "/^([A-Za-z]|\d|\@|\$|!|%|\*|\?|&|\_)*$/";
			return preg_match($exp, $validData);
			break;

		/* vlaida un email */	
		case "email":
			$exp = "/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/";
			return preg_match($exp, $validData);
			break;

		/* valida que solo sean numeros */
		case "int": 
            $exp = "/^\d+$/";
			return preg_match($exp, $validData);
			break;
			
		/* valida que el dato sea entero y este en una lista de enteros */
		case "lstInt":
		
			$exp = "/^\d+$/";
			
			if(!is_array($validData)){
				return false;
			}
			
			foreach($validData as $id => $val){
				if(!preg_match($exp, $val)){ 
					return false; 
				}
			}
			
			return true;
			break;
		
		/* valida que el dato sea entero y este en una lista de enteros */
		case "lstConceptos":
		
			$exp = "/^\d+$/";
			
			if(!is_array($validData)){
				return false;
			}
			
			foreach($validData as $id => $val){
				
				if(!is_array($val)){
					return false;
				}
				
				if(!isset($val["clave"])){
					return false;
				}
				
				if(!preg_match($exp, $val["clave"])){ 
					return false; 
				}
				
				if(!isset($val["cantidad"])){
					return false;
				}
				
				if(!preg_match($exp, $val["cantidad"])){ 
					return false; 
				}
			}
			
			return true;
			break;
			
		default:
			return false;
		
	}
	
	return false;
}




?>

