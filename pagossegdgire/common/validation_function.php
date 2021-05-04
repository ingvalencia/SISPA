<?php

function isJSON($string){
		return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}

function valid_conceptos($v){

	$flag = true;
	
	$exp = "/^\[(\{((\"[A-Za-z_]+\"\:\"\d*(\.)?\d*\")(\,)?)+\}(\,)?)+\]$/";
    
    if(preg_match($exp, $v)){
		if(isJSON($v)){
			$js = json_decode($v);
			$lst =array("clave" => array("type" => "int", "required" => true), "cantidad" => array("type" => "int", "required" => true), "precio_variable" => array("type" => "float", "required" => false));
			
			foreach($js as $j => $v){
				
				$v_array = (array)$v;
				
				foreach($lst as $t => $tv){
					
					if(isset($v_array[$t])){
						
						
						if($tv["type"]=="int"){
							
							$exp = "/^\d+$/";
							if(!preg_match($exp, $v_array[$t])){
								$flag = false;
								die("tt".$t);
							}
						}
						if($tv["type"]=="float"){
							
							
							$exp = "/^\d*(\.)?\d*$/";
							if(!preg_match($exp, $v_array[$t])){
								
								die($v_array[$t]);
								die("ee".$t."rr".$v_array[$t]);
								die("ee".$t);
							
							
								$flag = false;
							}
						}
						
					}
					else{
						if($tv["required"]){
							$flag = false;
							die("ww".$t);
						}
					}
					
				}
				
			}
		}
		
		return $flag;
	
	
	}
	
	
	return false;
 	
}



		
function validField($validName, $validData){
	global $rules;
	
	switch($rules[$validName]["type"]){
		
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
		
		case "passwordEmpty":
			if($validData == ""){
				return true;
			}
			$exp = "/^([A-Za-z]|\d|\@|\$|!|%|\*|\?|&|\_)*$/";
			return preg_match($exp, $validData);
			break;
		
		/* valida una fecha de tipo yyyy-mm-dd */
		case "date":
			$exp = "/^\d{4}-\d{1,2}-\d{1,2}$/";
			
			if(!preg_match($exp, $validData)){
				return false;
			}
			
			$x_format = 'Y-m-d';
			
			$d = DateTime::createFromFormat($x_format, $validData);
			return $d && $d->format($x_format) == $validData;
			
			break;
		
		/* permite los caracteres A-Za-záéíóúüñÁÉÍÓÚÜÑ el espacio y el punto . */
		case "text":
			$exp = "/^([A-Za-záéíóúüñÁÉÍÓÚÜÑ][\.]{0,1}){1,}([\s]([A-Za-záéíóúüñÁÉÍÓÚÜÑ][\.]{0,1}){1,})*$/";
			return preg_match($exp, $validData);
			break;
		
		/* permite los caracteres A-Za-záéíóúüñÁÉÍÓÚÜÑ el espacio y el punto */
		case "text_empty":
			if($validData==""){return true;}
			$exp = "/^([A-Za-záéíóúüñÁÉÍÓÚÜÑ]{0,1}){1,}([\s]([A-Za-záéíóúüñÁÉÍÓÚÜÑ]{0,1}){1,})*$/";
			return preg_match($exp, $validData);
			break;
			
		/* Clave */
		case "text_clave":
			$exp = "/^([A-Za-z]|\d|\_)*$/";
			return preg_match($exp, $validData);
			break;
		
		
		/* permite los caracteres A-Za-záéíóúüñÁÉÍÓÚÜÑ el espacio y los caracteres especiales .,_-[]() y numeros */
		case "textEsp":
			$exp = "/^([A-Za-záéíóúüñÁÉÍÓÚÜÑ]|\.|\,|\-|\_|\]|\[|\)|\(|\d){1,}([\s]{1,}([A-Za-záéíóúüñÁÉÍÓÚÜÑ]|\.|\,|\-|\_|\]|\[|\)|\(|\d){1,})*$/";
			return preg_match($exp, $validData);
			break;
		/* vlaida un email */	
		case "email":
			$exp = "/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/";
			return preg_match($exp, $validData);
			break;
		/* valida una estructura de email para buscar*/	
		case "email_like":
			$exp = "/^([a-zA-Z0-9_\.\-])+\@?(([a-zA-Z0-9\-])+\.)*([a-zA-Z0-9]{2,4})*$/";
			return preg_match($exp, $validData);
			break;
			
		/* valida un celular de 10 a 15 numeros */
		case "celular":
			$exp = "/^\d{10,15}$/";
			return preg_match($exp, $validData);
			break;
			
		/* valida el formato de un plantel de la forma B###, B###, b###, b#### */
		case "ptl":
			$exp = "/^(B|b)?\d{3,4}$/";			
			return preg_match($exp, $validData);
			break;
			
		/* valida un RFC */
		case "rfc":
			//$exp = "/^[a-zA-Z]{3,4}(\d{6})((\w){3})?$/";
			$exp = "/^([A-ZÑ\x26]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1]))((-)?([A-Z\d]{3}))?$/";
			return preg_match($exp, $validData);
			break;
			
		/* valida un booleano */
		case "boolean":
			$lstBoolean = array('true', 'false', 'TRUE', 'FALSE');
			foreach($lstBoolean as $id => $val){
				if($validData == $val){ return true;}
			}
			return false;
			break;
			
		/* valida un booleano entero solo puede ser 0 ó 1 */
		case "booleanInt":
			if(($validData!= '1')&&($validData!= '0')){
				return false;
			}
			return true;
			break;
		
		/* valida que solo sean numeros */
		case "int": 
            $exp = "/^\d+$/";
			return preg_match($exp, $validData);
			break;
			
		/* valida sean numeros enteros o cadena vacia*/
		case "intEmpty": 
			if($validData==""){
				return true;
			}
            $exp = "/^\d+$/";
			return preg_match($exp, $validData);
			break;
		
		case "maxInt": 
            $exp = "/^\d+$/";
			if(!preg_match($exp, $validData)){
				return false;
			}
			
			$max = $rules[$validName]["maxInt"];
			
			if((int)$validData >= $max){
				
				return false;
			}
			return true;
			break;
		
		case "minInt": 
            $exp = "/^\d+$/";
			if(!preg_match($exp, $validData)){
				return false;
			}
			
			$max = $rules[$validName]["minInt"];
			
			if((int)$validData < $max){
				return false;
			}
			return true;
			break;
		
		case "minMaxInt": 
            $exp = "/^\d+$/";
			if(!preg_match($exp, $validData)){
				return false;
			}
			
			$max = $rules[$validName]["maxInt"];
			$min = $rules[$validName]["minInt"];
			
			if(((int)$validData > $min)&&((int)$validData < $max)){
				return true;
			}
			return false;
			break;
		
		case "minMaxIntNot": 
            $exp = "/^\d+$/";
			if(!preg_match($exp, $validData)){
				return false;
			}
			
			$max = $rules[$validName]["maxInt"];
			$min = $rules[$validName]["minInt"];
			
			if(((int)$validData > $min)&&((int)$validData < $max)){
				return false;
			}
			return true;
			break;
		
		
		/* valida un entero negativo o positio de la forma -23423 ó +23423 */
		case "integer": 
            $exp = "/^[\-\+]?\d+$/";
			return preg_match($exp, $validData);
			break;
            
		/* valida un flotante negativo o positio de la forma -234.23 ó +234.23 */
		case "float":
            $exp = "/^[\-\+]?((([0-9]{1,3})([,][0-9]{3})*)|([0-9]+))?([\.]([0-9]+))?$/";
            return preg_match($exp, $validData);
			break;
			
		/* valida solo flotantes positivos sin signo de la forma 234.23 */
		case "float+":
            $exp = "/^((([0-9]{1,3})([,][0-9]{3})*)|([0-9]+))?([\.]([0-9]+))?$/";
            return preg_match($exp, $validData);
			break;
				
		/* valida que el dato este dentro de una lista de palabras */
		case "lstString":
			
			foreach($rules[$validName]["lst"] as $id => $val){
				if($validData == $val){ return true;}
			}
			return false;
			break;
			
		/* valida que el dato sea entero y este en una lista de enteros */
		case "lstInt":
		
			$exp = "/^\d+$/";
			if(!preg_match($exp, $validData)){
				return false;
			}
			
			foreach($rules[$validName]["lst"] as $id => $val){
				if($validData == $val){ return true;}
			}
			
			return false;
			break;
		
		/* valida que el dato sea entero positivo o negativo y este en una lista de enteros */
		case "lstInteger":
		
			$exp = "/^[\-\+]?\d+$/";
			if(!preg_match($exp, $validData)){
				return false;
			}
			
			foreach($rules[$validName]["lst"] as $id => $val){
				if($validData == $val){ return true;}
			}
			
			return false;
			break;
			
		/* llama a una funcion que este edfinada por el usaurio para validar el dato */
		case "function":
			
			return call_user_func($rules[$validName]["function"], $validData);
			
			break;
			
		/* valida el dato atraves de una expresion regular */
		case "regExp":
		
			return preg_match($rules[$validName]["regExp"], $validData);
			break;
		/* valida el dato atraves de una expresion regular */
		case "array_grid":
		
			if(!is_array($validData)){
				return false;
			}
			
			if(!isset($validData[0])){
				return false;
			}
			
			if(!isset($validData[0]['column'])){
				return false;
			}
			
			if(!isset($validData[0]['dir'])){
				return false;
			}
			
			if(!isset($validData[0]['column'])){
				return false;
			}
			
			$exp = "/^\d+$/";
			if(!preg_match($exp, $validData[0]['column'])){
				return false;
			}
			
			if(($validData[0]['dir']!="asc")&&($validData[0]['dir']!="desc")){
				return false;
			}
				
			return true;
			break;
		
		
		case "array_concepto":

			return valid_conceptos($validData);
			
			break;
			
		default:
			return false;
		
	}
	
	return false;
}




?>

