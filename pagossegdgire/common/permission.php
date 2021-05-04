<?php 
session_start();
require_once("config.php");
require_once("../common/clsUsuario.php");
$usuario = new usuario();

if(!isset($_SESSION['userInfo'])){
	header( "Location: ../index.php");
	exit;
}else{

	//Se crea la lista de permisos de script
	$path_script = substr($_SERVER['PHP_SELF'], strlen($CONFIG['baseDir']));
	$PERMS = $usuario->getPermissionList($_SESSION['userInfo']['idRol'], $path_script);

	if(count($PERMS) == 0){

		header( "HTTP/1.1 401 Unauthorized");
		echo 'Permiso denegado';
		echo '<br />';
		echo $_SERVER['PHP_SELF'];
		echo $path_script;
		echo $PERMS;
		exit;

	}

}
?>