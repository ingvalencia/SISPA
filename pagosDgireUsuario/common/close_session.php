<?php

session_start();
$_SESSION = array();
session_destroy( );

header('Refresh: 3; URL = ../acceso/index.php');
//header('Location: ../acceso/index.php');

?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<title>Saliendo...</title>
        
        
          <link href="../bower_components/css/open-sans.css" rel="stylesheet" type="text/css">
          <link href="../bower_components/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
          <link rel="stylesheet" type="text/css" href="../style/pagos.css"/>
           
	</head>
    
	<body class="text--steelblue">
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<main>
			<center style="color:#2e82c4;">
				<h1>Saliendo...</h1>
				<h2>Estas regresando al sistema de pagos de la DGIRE</h2>
				<h3>Vuelve pronto</h3>
				<p>&nbsp;</p>
				<i class="fa fa-spinner fa-spin fa-3x"></i>
			</center>
	</main>
	</body>
</html>


