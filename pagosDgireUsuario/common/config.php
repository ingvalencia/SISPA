<?php

# information de errores del sistema.

ini_set('display_errors', 0);
ini_set('error_reporting', E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', '../../logs_pagosDgire/bitacora_user/error_log_system/errors.log');
    
global $CONFIG;

$CONFIG = (object)array (
	
	'mysql' => (object)array(
								//'host' => "localhost" #Host de producción
								//'host' => "132.248.38.24" #Host de pruebas
								 'host' => "100.10.16.17" #Host de pruebas
								,'user' => "dXNyX3BhZ29z"
								,'pass' => "Um00NUwrMVo="
								//,'db' => "dgire_pagos2016IVA" #BD de producci?n
								,'db' => "Mercurioz_pagos_new" #BD de pruebas
								,'port' => 7408
	)
    ,
	'sybase' => (object)array(
								'host' => "132.248.38.8:4101"
								,'user' => "dXNyX2Nvbg=="
								,'pass' => "WG4yc25jMTU="
								,'db' => "unamsi"
	),
    'patronato' => (object)array(

								 'user' => "SkxDSElRVUU="
								,'password' => "SkxDSElRVUU="
								,'dependencia' => "551"
								,'ejercicio' => "2017"
								,'subDep' => "01"
								,'origen' => "DE"
								,'tipo_moneda' => 1
								,'cve_moneda' => "MXN"
								,'urlBase' =>"http://wscfdspruebas.patronato.unam.mx/" #URL de pruebas
								//,'urlBase' =>"" #URL de producción
    )

    /*
	,'captcha' => (object)array(

		//clave solo para el servidor19
		'public_key'  => "6LecURITAAAAAIE3WAk-i939KrX1EPbU3K_cC2iZ"
		,'private_key' => "6LecURITAAAAAJwCoxi6N1WmtZFR1FRrnThs6-bg"
		
		//clave solo para el servidor24
		//'recaptchaPublic' => "6Ldo-xcTAAAAAO1zfGW_wqpHjjipcXPQbQNWhcwx"
		//,'recaptchaPrivate' => "6Ldo-xcTAAAAAN2dnec6b98bFfe_M3Uf7McQhflT"

	)*/
	,
	"mail" => (object)array(

								 'Host' => "132.248.38.11"
								,'Port' => 25
								,'user' => "c2lzdGVtYXM="
								,'password' => "WjE4NmhNQ2U="
								,'pagos' => "pagosdgire@dgire.unam.mx"	#Correo del adminsitrador de caja
								,'from' => "sistemas@dgire.unam.mx"		#Correo del administrador de pagos pagos@dgire.unam.mx
								,'addBcc' => "gvalenci@dgire.unam.mx"	#Correo copia
								,'addCC' => "rejinderiog7@gmail.com"	#Correo copia
	)
	,
    "rutas" => (object)array(
								//'baseDir' => ""#ruta base del proyecto en produccion
        						'baseDir' => "http://132.247.147.15/~gvalenci/pagos_v2/pagosDgireUsuario/"#ruta base del proyecto  en prueba

        						//'baseDir' => ""#ruta de alojamiento de los logs en produccion
								,'DirLog' => "../../logs_pagosDgire/bitacora_user/logs/"#ruta de alojamiento de los logs en prueba
    )


	
);


?>
