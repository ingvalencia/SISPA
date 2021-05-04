<?php

$ran = rand();

/**************************************
script adicionales
**************************************/
$extraLib = <<<"EOD"
<script src="../js/download.jquery.js"></script>
<script src="../js/common_function.js"></script>
<script src="../js/administrador_pagos.js?$ran"></script>

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

        <h3 class="page-header" style="Padding-left:15px; color:#2e82c4;">Seguimiento administrador pagos</h3>

        <?php
			include_once("frmUsuario.php");
		?>

        <div id="dvGrid">
            <div class=" well col-lg-12">
                <label class="col-md-1 control-label">Folio:</label>
                <div class="col-md-2">
                    <input id="folio_sol_s" name="folio_sol_s" class="form-control" type="text">
                </div>
                <label class="col-md-1 control-label">Nombre:</label>
                <div class="col-md-2">
                    <input id="fnombre_s" name="fnombre_s" class="form-control" type="text">
                </div>
                <label class="col-md-2 control-label">Estado de pago:</label>
                <div class="col-md-2">
                    <select id="cve_edo_pago_s" name="cve_edo_pago_s" class="form-control">
							<option value="">Seleccione un estado</option>
						</select>
                </div>
                <div class="col-md-2">
                    <button type="button" id="btnSearch" name="btnSearch" class="btn btn-outline btn-primary">Buscar</button>
                </div>
            </div>

            <div align="right">

                <button type="button" id="btnCreateSolicitud" name="btnCreateSolicitud" class="btn btn-outline btn-primary"><span class="glyphicon glyphicon-list-alt"></span> Generar Solicitud</button>
            </div>



            <table id="solicitudes" name="solicitudes" class="table table-striped  table-hover table-condensed customTables display nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr class="text-center backgroundMarinoUNAM textWhite">
                        <th>id_solicitante</th>
                        <th>Folio</th>
                        <th>Fc actualización</th>
                        <th>Estado pago</th>
                        <th>Estado sol</th>
                        <th>Nombre</th>
                        <th>Correo electrónico</th>
                        <th>Teléfono</th>
                        <th>Factura</th>
                        <th>Monto</th>
                        <th>IVA</th>
                        <th>Precio unitatio</th>
                        <th># Serie</th>
                        <th>RFC</th>
                        <th># Factura</th>
                        <th># Ticket</th>
                        <th>Aclaración</th>
                        <th>Comentario</th>
                        <th>Ref Bancaria</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr class="text-center backgroundMarinoUNAM textWhite">
                        <th>id_solicitante</th>
                        <th>Folio</th>
                        <th>Fc actualización</th>
                        <th>Estado pago</th>
                        <th>Estado sol</th>
                        <th>Nombre</th>
                        <th>Correo electrónico</th>
                        <th>Teléfono</th>
                        <th>Factura</th>
                        <th>Monto</th>
                        <th>IVA</th>
                        <th>Precio unitatio</th>
                        <th># Serie</th>
                        <th>RFC</th>
                        <th># Factura</th>
                        <th># Ticket</th>
                        <th>Aclaración</th>
                        <th>Comentario</th>
                        <th>Ref Bancaria</th>
                    </tr>
                </tfoot>
                <tbody>
                </tbody>
            </table>


        </div>



    </div>





    <!-- fin contenido -->
</div>
<!-- fin body -->
 
