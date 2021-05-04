var dataSend = {};

$(function() {
	
	var id_usuario = 0;
	var nombre_usuario;

	
	$("#btnSave").click(function(){
		$('#frmPassword').formValidation('validate');
	});
	
	$('#btnCancelar').click(function(){
		window.location = "./lista_usuarios.php";
	});

	
	$('#frmPassword').formValidation({
         message: 'Valor no es valido',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
			password: {
                validators: {
                    notEmpty: {
                        message: 'Debe indicar un password'
                    }
                }
            },
            confirm_password: {
                validators: {
                    notEmpty: {
                        message: 'Debe confirmar el password'
                    },
                    identical: {
                        field: 'password',
                        message: 'Debe de ser igual al password'
                    }
                }
            }
			
        }
    }).on('success.form.fv', function(e) {
		
		dataSend = {};
		opt= "updatePassword";
		dataSend.opt = opt;
		dataSend.id_usuario = id_usuario;
		dataSend.password = $("#password").val();
		
		msg = "¿Está seguro que desea cambiar el password de <strong>"+nombre_usuario+"</strong>?";
				
		dialog.confirm(msg, function(dialogWin){dialogWin.close(); processData(); });
		
    });
	
	
	function processData() {

        $.ajax({
            type: "POST",
            url: 'processUsuarios.php',
            data: dataSend,
            dataType: "json",
            timeout: timeout,
            beforeSend: function() {
                dialog.show();
            },
            success: function(json) {

                dialog.close();

                if (json.error == "si") {
                    dialog.alert(json.msg);
					console.log(json.debug);
                    return false;
                }

				switch(dataSend.opt){
					case "updatePassword":
						dialog.message(json.msg, function(dWin){ dWin.close(); window.location = "../usuarios/lista_usuarios.php";});
						break;
				}

                return false;

            },
            error: function(jqXHR, textStatus, errorThrown) {
                dialog.close();
                msg = "";
                if (textStatus == "timeout") {
                    msg = "tiempo agotado";
                } else {
                    //msg = messageCommon.errorSend;
                    msg = "Error en la conexion";
                }
                console.log(msg);
                dialog.alert(msg);//showMessage(msg);
                return false;
            }
        });
    }
	
	
	/*
	 if (dataSend.opt != undefined) {
		 if (dataSend.opt == "getUsuario") {
			 dataSend.id_usuario = id_usuario;
			 processData();
		 }
	 }
	 
	 */
	 
	id_usuario = localStorage.getItem("id_usuario");
	nombre_usuario = localStorage.getItem("nombre_usuario");
	 
	$("#hTitle").html("Cambio de contraseña de: "+nombre_usuario);
	 

});