<!DOCTYPE html>

<html lang="en">
    <?php
    /*Encabezado*/
    require_once("../templete/header2.php");
    require_once ("../common/clsSuspende.php");


    $Suspende = new clsSuspende();

    $message=$Suspende->mensajeSuspende();
    $message1=$Suspende->mensajeSuspende();


    if ($Suspende->aperturaSistema() == 1)
    {

    ?>


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

                                    <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                       <input class="form-control" placeholder="Correo electr&oacute;nico: correo@correo.com" name="usuario" id="usuario" autofocus >
                                    </div>

                                    <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input class="form-control" placeholder="Contrase&ntilde;a" name="password" id="password" type="password" autofocus >
                                    </div>
                                    
                                     <div id="dvMsg" class="text-center" ></div>
                                    <br>
                                   
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
                                                       <span class="glyphicon glyphicon-question-sign text-primary"></span> <a href="../recovery_pwd">&iquest;Olvido su contrase&ntilde;a?</a><br />
                                                       <span class="glyphicon glyphicon-user text-primary"></span> <a href="../registro_solicitante">&iquest;A&uacute;n no esta registrado? haga click aqu&iacute;</a>
                                                   </div>
                                               </div>
                                           </div>
                                       </div>

                                    </footer>


                                </fieldset>
                            </form>
                        

                         <br>
                        <?php
                        if(!empty($message['success'])=='true')

                            echo $message['message'];
                        ?>
                        <br>
                    </div>

        <?php
    }else{
                echo '<br><br><br><br><br><br><br><br><br>'.$message['message'];
         }
        ?>
                </div>

            </section>
    </section>


<script>
        $(document).ready(parpadear);
        function parpadear(){ $('#parp').fadeIn(500).delay(2750).fadeOut(500, parpadear) }
        
</script>





<?php
/*footer*/
require_once("../templete/footer.php");
?>
