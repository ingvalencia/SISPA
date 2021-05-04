<?php

/**************************************
script adicionales
**************************************/
$extraLib = <<<'EOD'
<script src="../js/bootstrap-dialog.min.js"></script>
<script src="../js/common_function.js"></script>
<script src="../js/usuario.js"></script>
EOD;


include_once("../templete/header.php");


?>

<script>
	<?php
	//print_r($_GET);
		$cod = "";
		if(isset($_GET["action"])){
			if($_GET["action"]=="update"){
				echo "var id_usuario = ".$_GET["id_usuario"].";";
				$cod  = "dataSend.opt = 'getUsuario';";
				$cod .= "dataSend.id_usuario = ".$_GET["id_usuario"].";";
				echo $cod;
			}
		}
	?>
</script>

<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">

<?php
	include_once("../templete/navigator.php");
    include_once("../templete/menu.php");
?>
			        
</nav>

<!-- inicia body -->
<div id="page-wrapper">
    <div class="container-fluid">
	<!-- contenido -->
	
	
	
	
	
	
    
    					<div class="row">
                            <div class="col-lg-12">
                                <h1 id="titlePage" class="page-header">Agregar Usuario</h1>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <form id="frmUsuario" name="frmUsuario" data-toggle="validator" >
                               
								<div class="form-group">
                                    <label class="control-label">Nombre completo del usuario</label>
                                    <input type="text" name="nombre_usr" id="nombre_usr" class="form-control">
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label">Login</label>
                                    <input type="text" name="login" id="login" class="form-control">
                                </div>

								 <div class="form-group">
                                   <label class="control-label">Tipo de usuario</label>
                                    <select name="idRol" id="idRol" class="form-control">
                                    </select>
                                </div>

                                <div id="dvPassword" class="form-group" >
                                    <label class="control-label">Password</label>
                                    <input type="password" name="password" id="password" class="form-control"/>
                                </div>
                                
                                <div id="dvConfirmPassword" class="form-group">
                                    <label class="control-label">Confirmar Password</label>
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control"/>
                                </div>
                                
                            </form>
							
                            <div align="right">
        						<button type="button" id="btnAddUsuario" name="btnAddUsuario" class="btn btn-outline btn-primary" style="display:none;">Agregar</button>
            					<button type="button" id="btnUpdateUsuario" name="btnUpdateUsuario" class="btn btn-outline btn-primary" style="display:none;">Actualizar</button>
                                <button type="button" id="btnCancelUsuario" name="btnCancelUsuario" class="btn btn-outline btn-danger" style="display:none;">Cancelar</button>
        					</div>
                        </div>
    
    
    
    
    
    
    
    
    
	
	
	<!-- fin contenido -->
	</div>
	<br>
	<br>
</div>
<!-- fin body -->
        	
<?php include_once("../footer.php"); ?>