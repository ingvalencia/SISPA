<?php


?>
<!DOCTYPE html>
<html lang="en">
<head>
<!--<meta charset="ISO-8859-1" />-->
<meta charset="UTF-8" />


    <link rel="icon" type="image/x-icon" href="../image/favicon.ico" />
    <title>..:: SISPA-DGIRE ::..</title>

<!--#...**************************************************************************************************...#-->
<!-- Bootstrap Core CSS -->
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

   <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <script src="../js/login.js"></script>

<!--#...**************************************************************************************************...#-->

<!--#...**************************************************************************************************...#-->
<!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
<!--#...**************************************************************************************************...#-->

<!--#...**************************************************************************************************...#-->

    <link href="http://fonts.googleapis.com/css?family=Lato:100italic,100,300italic,300,400italic,400,700italic,700,900italic,900" rel="stylesheet" type="text/css">
    <!--<link rel="stylesheet" type="text/css" href="../bower_components/assets/bootstrap/css/bootstrap.min.css" />-->
    <link rel="stylesheet" type="text/css" href="../bower_components/assets/css/styles.css" />

    <script src="../bower_components/assets/bootstrap/js/bootstrap.min.js"></script>

<!--#...**************************************************************************************************...#-->


<!--#...**************************************************************************************************...#-->
	<!-- Bootstrap Core CSS -->
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

	    <!-- DataTables CSS -->
    <link href="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css">

    <!-- Timeline CSS -->
    <link href="../dist/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">


    <!-- Custom Fonts -->
    <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">


	<!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>


    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- bvalidato JavaScript -->
    <link rel="stylesheet" href="../bower_components/bvalidator/bvalidator.css">

    <!-- DataTables JavaScript -->
    <script src="../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.5/css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.5/js/bootstrap-dialog.min.js"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="../js/jquery.mask.js"></script>
    <script src="../js/common_function.js"></script>
    <script type="text/javascript" src="../libs/blockUI/jquery.blockUI.js" ></script>


    <!--- bootstrap validaor -->
    <link rel="stylesheet" href="../bower_components/bootstrap-validator/dist/css/formValidation.css"/>
    <script type="text/javascript" src="../bower_components/bootstrap-validator/dist/js/formValidation.js"></script>
    <script type="text/javascript" src="../bower_components/bootstrap-validator/dist/js/reCaptcha2.min.js"></script>
    <script type="text/javascript" src="../bower_components/bootstrap-validator/dist/js/framework/bootstrap.js"></script>

    <!---PrintArea-->
    <script type="text/javascript" src="../bower_components/printThis-master/printThis.js"></script>
	
	 <!---font-awesome-->
	  <link href="../bower_components/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	 

    

<!--#...**************************************************************************************************...#-->



<!--#...**************************************************************************************************...#-->
<link rel="stylesheet" type="text/css" href="../style/pagos.css"/>


<?php if(isset($extraLib)){ echo $extraLib; } ?>
</head>
<style>
.customTables {
    font-size: 12px;
    font-family: Arial;
}
</style>
<!--#...**************************************************************************************************...#-->

<!------------------------------------Contenedor-Inicio--------------------------------------------->

<?php require_once("preloader.php"); ?>


<body class="hold-transition skin-blue layout-top-nav" >

<!--<body class="hold-transition skin-blue layout-top-nav"  onload="setTimeout('dialog.alert(\'Su sesi?n expira en 30 segundos\')', 270000)"> -->

	<div id="documentBody" class="wrapper document__body container-fluid">
    
        <header class="main-header">

            <div class="row backgroundMarinoUNAM">
				  <div class="col-xs-1">
				  	<a href="http://www.unam.mx" target="_blank">
							<img id="imgUNAM" class="img-responsive center-block" src="../image/logo_unam.png">
						</a>
				  </div>

				  <div class="col-xs-5 hidden-xs text-left" id="titleUNAM">
				  	<h5 class="textWhite">Universidad Nacional Aut&oacute;noma de M&eacute;xico</h5>
				  </div>

				  <div class="col-xs-5 hidden-xs text-right" id="titleDGIRE">
				  	<h5 class="textOroDGIRE">Direcci&oacute;n General de Incorporaci&oacute;n
				  	y Revalidaci&oacute;n de Estudios</h5>
				  </div>

				  <div class="col-xs-1">
				  	<a href="http://www.dgire.unam.mx/contenido/home.htm" target="_blank">
							<img id="imgDGIRE" class="img-responsive center-block" src="../image/logo_dgire.png">
						</a>
				  </div>
			</div>

            <div class="row backgroundTitle textWhite">
                <div class="col-xs-12">
                    <h4 class="text-center"></i> Sistema de pagos de la DGIRE</h4>
                </div>
            </div>
            

        </header>


        <!------------------------------------Contenedor--------------------------------------------->
