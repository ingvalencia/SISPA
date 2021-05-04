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
		
		case "ptl":
			$exp = "/^(B|b)?\d{3,4}$/";			
			return preg_match($exp, $validData);
			break;
		
		case "text":
			$exp = "/^([A-Za-záéíóúüñÁÉÍÓÚÜÑ]{0,1}){1,}([\s]([A-Za-záéíóúüñÁÉÍÓÚÜÑ]{0,1}){1,})*$/";
			return preg_match($exp, $validData);
			break;
			
		/* valida que solo sean numeros */
		case "int": 
            $exp = "/^\d+$/";
			return preg_match($exp, $validData);
			break;
			
		/* valida que solo sean numeros */
		case "folio": 
            $exp = "/^\d+$/";
			return preg_match($exp, $validData);
			break;
		
		case "rfc":
			//$exp = "/^[a-zA-Z]{3,4}(\d{6})((\w){3})?$/";
			$exp = "/^([A-ZÑ\x26]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1]))((-)?([A-Z\d]{3}))?$/";
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
			
			
		/* valida que el dato sea un concepto entero y almenos tres caracteres */
		case "concepto":
		
			$exp = "/^\d+$/";
			
			if(!preg_match($exp, $validData)){ 
				return false; 
			}
			
			if(strlen($validData)<3){
				return false;
			}
			
			return true;
			break;
		
		/* valida que el dato sea entero y este en una lista de enteros */
		case "lstConceptos":
		
			$l=0;
			$exp = "/^\d+$/";
            $exp_float = "/^((([0-9]{1,3})([,][0-9]{3})*)|([0-9]+))?([\.]([0-9]+))?$/";
            
			if(!is_array($validData)){
				return "D102";
			}
			
			foreach($validData as $id => $val){
				
				if(!is_array($val)){
					return "D103";;
				}
				
				if(!isset($val["clave"])){
					return "D104";
				}
				
				$l = strlen($val["clave"]);
				if($l < 3){
					return "D105";;
				}
				
				if(!preg_match($exp, $val["clave"])){ 
					return "D106";
				}
				
				if(!isset($val["cantidad"])){
					return "D107";
				}
				
				if(!preg_match($exp, $val["cantidad"])){ 
					return "D108";
				}
				
				if(isset($val["precio_variable"])){
					if(!preg_match($exp_float, $val["precio_variable"])){ 
						return "D009";
					}
				}
				
				
			}
			
			return 0;
			break;
			
		default:
			return false;
		
	}
	
	return false;
}




?>

