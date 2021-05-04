<?php

/**************************************
script adicionales
**************************************/
$ran = rand();
$extraLib = <<<"EOD"

<link rel='stylesheet' href='../js/select2/select2.css?$ran' />
<script src='../js/select2/select2.js'></script>

<script src='../js/download.jquery.js'></script>
<script src='../js/common_function.js'></script>
<script src='../js/solicitud_usr.js?$ran'></script>

EOD;

include_once("../templete/header.php");
require_once("../acceso/inactividad.php");


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
    <div class="container-fluid">

        <div class="row">

            <div class="col-lg-12">
                <h4 class="page-header" style="Padding-left:10px; color:#2e82c4;"><b>Registro de solicitud de pago</b></h4>

                <div id="dvGrid">

                    <div class="">

                        <div class="row">
                            <label class="col-md-7 control-label text-center">Concepto</label>
                            <label class="col-md-1 control-label text-center">Cantidad</label>
                            <label class="col-md-2 control-label text-center">Monto</label>
                            <label class="col-md-1 control-label text-center"></label>
                        </div>

                        <div class="row">

                            <div class="col-md-7">
                                <select id="lst_conceptos" name="lst_conceptos" data-placeholder="Seleccione un concepto..." style="width:550px;" class="chosen-select">
									<option value="">Seleccione un concepto...</option>
								</select>
                            </div>

                            <div class="col-md-1">
                                <input id="cantidad" class="form-control" disabled>
                            </div>

                            <div class="col-md-2">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control" id="precio_unitario" disabled>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <button id="btnAddConcepto" class="btn btn-outline btn-primary pull-right btn-sm" disabled><span class="glyphicon glyphicon-plus"></span> agregar</button>
                            </div>

                        </div>

                        <div class="table-responsive">
                            <table id="tSolicitud" name="tSolicitud" class="table table-striped table-bordered table-hover table-condensed customTables display nowrap" cellspacing="0" width="100%">
                                <thead>

                                    <tr>
                                        <td colspan="8" class="backgroundTitle">
                                            <div align="center" class="textWhite">Conceptos agregados
                                            </div>
                                        </td>
                                    </tr>

                                    <tr class="text-center backgroundMarinoUNAM textWhite">
                                        <td>Clave</td>
                                        <td>Concepto</td>
                                        <td>importe</td>
                                        <td>Cantidad</td>
                                        <td>Precio unit</td>
                                        <td>IVA</td>
                                        <td>Monto</td>
                                        <td></td>
                                    </tr>
                                </thead>

                                <tbody id="xbody">
                                </tbody>

                                <tfoot>
                                    <tr class="text-center">
                                        <td colspan="8">
                                            <table class="customTables display nowrap table-striped table-hover" align="right" cellpadding="0" width="200">
                                                <tfoot>
                                                    <tr class="backgroundMarinoUNAM textWhite">

                                                        <td width="800" colspan="5" align="right" class="ui-corner-all">Sub Total</td>
                                                        <td width="100" align="right"><span id="stotal">$0.00</span></td>

                                                    </tr>
                                                    <tr class="">
                                                        <td colspan="5" align="right" class="ui-corner-all">I.V.A</td>
                                                        <td align="right"><span id="iva_total">$0.00</span></td>

                                                    </tr>
                                                    <tr class="backgroundMarinoUNAM textWhite">
                                                        <td colspan="5" align="right" class="ui-corner-all">Monto Total</td>
                                                        <td align="right"><span id="monto_total">$0.00</span></td>

                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </td>
                                    </tr>
                                </tfoot>

                            </table>
                        </div>

                        <!-- -->
                        <div class="row">

                            <div class="panel panel-primary">

                                <div class="panel-heading">
                                    <label><b>Datos del solicitante</b></label>
                                </div>


                                <div class="panel-body">

                                    <!--Opción  plantel -->
                                    <div class="panel-heading">
                                        <label><b>Habilite la casilla de verificación si desea asociar su tramite a un plantel:</b></label>&nbsp;
                                        <input type="checkbox" id="chplantel" name="chplantel" value="1"><b>Si</b> </input>

                                        <select id="ptl_ptl" name="ptl_ptl" data-placeholder="Seleccione una opción..." style="display:none; width:450px;" class="chosen-select" disabled>
											<option value="">Seleccione un concepto...</option>
										</select>
                                    </div>

                                    <!--Opción factura -->
                                    <div class="panel-heading">
                                        <label><b>Marque la casilla de verificación si desea facturar</b></label>&nbsp;
                                        <input type="checkbox" id="chFactura" name="chFactura" value="1"><b>Si</b> </input>
                                    </div>

                                    <div id="dvFactura" class="panel-body">
                                        <?php 
											include_once("rfactura.php"); 
										?>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <!-- -->

                        <div class="col-lg-3 col-lg-offset-4">
                            <button id="btnRegistrar" name="btnRegistrar" class="btn btn-outline btn-primary center-block "><span class="glyphicon glyphicon-log-out"></span> Registrar</button>
                        </div>


                    </div>

                </div>
                <!-- Termina dvGrid -->


            </div>

            <!--Mensaje Registro exitoso -->
            <div id="dvConfirm" style="display:none;">

                <div class="alert alert-info">

                    <div class="row">
                        <div class="col-lg-12">
                            <h3 style="Padding-left:15px; color:#2e82c4;"> Registro Exitoso</h3>
                        </div>

                        <p align="justify" style="margin-left:30px;">
                            Le confirmamos que su solicitud se ha registrado satisfactoriamente.
                            <br><br> Con el número de folio: <label id="folio_sol" name="folio_sol"></label>
                            <br><br> Monto a pagar: <b>$</b><label id="monto_tot_conc"></label>
                            <br><br> La ficha de su depósito fue enviada por correo electrónico o si lo prefiere podrá descargarla dando click en el botón <b>"Ficha de depósito"</b>.
                            <br><br>
                        </p>


                        <div class="col-lg-12">
                            <h4 style="Padding-left:15px; color:#E46565;">Nota:</h4>
                        </div>

                        <p align="justify" style="margin-left:30px;">
                            Al realizar el depósito debera entrar al sistema y verificar que ha sisdo registrado su depósito.
                            <br>
                            <br>
                        </p>

                    </div>
                    <br>

                </div>

                <div class="col-lg-2 col-lg-offset-2">

                    <button class="btn btn-outline btn-primary center-block" name="btnfichad" id="btnfichad">
												<span class="glyphicon glyphicon-save-file"></span> Ficha de depósito
											</button>

                </div>

                <div class="col-lg-3 col-lg-offset-3">

                    <button id="btnNewSol" class="btn btn-outline btn-primary center-block ">
												<span class="glyphicon glyphicon-edit"></span> Nueva solicitud
											</button>

                </div>
                <br><br><br>

            </div>
            <!-- Termina row -->

        </div>
        <!-- Termina contenido -->


    </div>
    <!-- Termina body -->

</div>
	    
