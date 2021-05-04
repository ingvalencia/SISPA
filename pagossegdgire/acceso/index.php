<!DOCTYPE html>
<html lang="en">

<?php

/**************************************
script adicionales
**************************************/
$extraLib = <<<'EOD'
<script src="../js/login.js"></script>
EOD;



    /*Encabezado*/
    require_once("../templete/header2.php");
   
    ?>
<body>

    <div class="container">
        <div class="row">

		
                    <!--###########-->

                    <section class="container">
				            <section class="login-form">
				                <div class="panel panel-default">
                                    <div class="row backgroundTitle textWhite text-center">Inicio de sesi&oacute;n</div>

                                    <div class="center-block">
                                        <img class="profile-img"
                                             src="../image/icon-user-default.png">

                                    </div>


                                    <div class="panel-body">
				                         <form id="frmLogin" name="frmLogin">

						                        <fieldset>
						                                


						                                <div class="form-group has-feedback">
						                                       
						                                </div>

						                                <div style="margin-bottom: 25px" class="input-group">
															<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
														    <input class="form-control" placeholder="Usuario" name="usuario" id="usuario" autofocus >                                        
														</div>
													
														<div style="margin-bottom: 25px" class="input-group">
															<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
															<input class="form-control" placeholder="Contrase&ntilde;a" name="password" id="password" type="password" >
														</div>
													
						                                <!-- Change this to a button or input when using this as a form -->
														<div class="">
															<button id="btnLogin" name="btnLogin" class="btn btn-outline btn-primary center-block " ><span class="glyphicon glyphicon-log-out"></span> Ingresar</button>
                                       
														</div>
														
														<footer class="clearfix">
															<div class="form-group">
															   <div class="col-md-12 control">
																   <br>
																   <div style="border-top: 1px solid#2e82c4; padding-top:15px; font-size:85%" >
																	   <div class="form-links">
																		   <div id="dvMsg" class="text-center"></div>
																	   </div>
																   </div>
															   </div>
														   </div>   
					
														</footer>

		                           				</fieldset>
         
				                        </form>
				                        

				                    </div>
				                </div>



				            </section>
    				</section>


                    			

                    <!--###########-->


                </div>
            </div>
        </div>
    </div>

    
</body>

</html>
