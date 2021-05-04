<?php

$ran = rand();

/**************************************
script adicionales
**************************************/
$extraLib = <<<"EOD"

<link rel="stylesheet" href="../js/select2/select2.css" />
<script src="../js/select2/select2.full.js"></script>


<script src="../js/download.jquery.js"></script>
<script src="../js/common_function.js"></script>
<script src="../js/generacion_solicitudes.js?$ran"></script>


EOD;

include_once("../templete/header.php");
?>

    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">

        <?php
	include_once("../templete/navigator.php");
	include_once("../templete/menu.php");
?>

    </nav>


   <!-- inicia body -->
<div id="page-wrapper">
    <!-- contenido -->

    <div class="row">

        <h3 class="page-header" style="Padding-left:15px; color:#2e82c4;">Generación de solicitudes</h3>

        <?php
			include_once("frmUsuario.php");
		?>



    </div>





    <!-- fin contenido -->
</div>
<!-- fin body -->
 
