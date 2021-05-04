<?php


$generic_error = "Error en el servidor comuniquese con el administrador";

$lst_message = (object)array(

        //LOGUEO:ERROR

            "lg_user"               =>  array("error" => "si","num_error" => "LG001","msg" => $generic_error)
            ,"lg_user_valid"        =>  array("error" => "si","sucess"=>"no","num_error" => "LG002","msg" => '<span class="alert alert-danger">Usuario o contraseña incorrectos</span>')
            ,"lg_user_vigente"      =>  array("error" => "si","sucess"=>"no","num_error" => "LG003","msg" => '<span class="alert alert-danger">Usuario no vigente en el sistema</span>')

        //LOGUEO:EXITO

            ,"lg_sucess_user"   => array("sucess"=>"si","msg"=>'<span class="alert alert-success">Usuario y contraseña correctos.</span>')

);

