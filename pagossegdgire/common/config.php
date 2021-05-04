<?php

global $CONFIG;

$CONFIG = (object)array (
	'mysql' => (object)array(
		//'host' => "localhost"
		'host' => "100.10.16.17" #Host de pruebas
		,'user' => "usr_pagos"
		,'pass' => "Rm45L+1Z"
		,'db' => "Mercurioz_pagos_new" #BD de pruebas
		,'port' => 7408
	)
	
	,'sybase' => (object)array(
		'host' => "132.248.38.8:4101",
		'user' => "usr_con",
		'pass' => "Xn2snc15",
		'db' => "unamsi",
	)
	,'captcha' => (object)array(

		//clave solo para el servidor19
		 'public_key'  => "6LecURITAAAAAIE3WAk-i939KrX1EPbU3K_cC2iZ"
		,'private_key' => "6LecURITAAAAAJwCoxi6N1WmtZFR1FRrnThs6-bg"
		
		//clave solo para el servidor24
		//'recaptchaPublic' => "6Ldo-xcTAAAAAO1zfGW_wqpHjjipcXPQbQNWhcwx"
		//,'recaptchaPrivate' => "6Ldo-xcTAAAAAN2dnec6b98bFfe_M3Uf7McQhflT"

	)
	
	,'email_pagos' => "pagosdgire@dgire.unam.mx"	#Correo del adminsitrador de caja 
	,'from_email' => "sistemas@dgire.unam.mx"		#Correo del administrador de pagos pagos@dgire.unam.mx
	,'addBcc' => "gvalenci@dgire.unam.mx"			#Correo del administrador de pagos pagos@dgire.unam.mx
	,'addCC' => " "									#Correo del administrador de pagos pagos@dgire.unam.mx
	,'baseDir' => "http://132.247.147.15/~gvalenci/pagos_v2/pagosDgireUsuario/"#ruta base del proyecto  en prueba

);

?>
