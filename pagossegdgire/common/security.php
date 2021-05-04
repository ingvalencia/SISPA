<?php

session_start();


if(!isset($_SESSION["access"])){
	header('Location: ../acceso/index.php');
}

if($_SESSION["access"]!=1){
	header('Location: ../acceso/index.php');
}


?>