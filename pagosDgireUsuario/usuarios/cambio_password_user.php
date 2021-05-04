<!DOCTYPE html>
<html lang="en">

<?php 

$extraLib = <<<'EOD'
<script src="../js/cambio_password_user.js"></script>
EOD;

require_once("../header.php"); 
require_once("../common/permisos.php"); 

?>

    <body>
        <?php require_once("../imageHeader.php"); ?>
            <div id="wrapper">
                <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">

                    <?php	
						require_once("../navigator.php");
						require_once("../menu.php");
					?>
                </nav>

                <!-- Page Content -->
                <div id="page-wrapper">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <h1 id="hTitle" class="page-header">Cambio de contraseña</h1>
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>
                        
                        <div class="input-group col-lg-6">
                        	<form id="frmPassword">
							<div>
								<label>Nueva contraseña</label>
								<input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" />
							</div>
                            <label id="lblPassword"></label>
							<br/><br/>
							<div>
								<label>Confirmar contraseña</label>
								<input type="password" class="form-control" id="confirm_password" name="confirm_password"  placeholder="Confirmar contraseña" />
							</div>
                            <label id="lblConfirm"></label>
                            </form>
						</div>
                        <br>
                        <button id="btnSave" class="btn btn-primary">Cambiar</button>
						<button id="btnCancelar" class="btn btn-danger">Cancelar</button>
                        <!-- /.row -->
                    </div>
                    <!-- /.container-fluid -->
                </div>
                <!-- /#page-wrapper -->

            </div>
            <!-- /#wrapper -->

            <?php require_once("../footer.php"); ?>

    </body>

</html>