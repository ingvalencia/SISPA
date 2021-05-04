var dataSend = {};

$(function() {
	
	var usuario = "";
	var validUser = true;
	
	$("#btnAddUsuario").hide();
    $("#btnUpdateUsuario").hide();
    $("#btnCancelUsuario").hide();
	$("#dvPassword").hide();
	$("#dvConfirmPassword").hide();
		
	function setDataUsuario(dUser){
		$.each(dUser, function(id, valor) {
			$("#"+id).val(valor);
		});
	}
	
	function setCatalogo(dCat){
		$("#idRol").append("<option value>Seleccione una opción</option>");
		$.each(dCat, function(id, valor) {
			$("#idRol").append("<option value='"+id+"'>"+valor["nomRol"]+"</option>");
		});
	}
	
	$("#btnAddUsuario").click(function(){
		dataSend = {};
		opt= "addUsuario";
		dataSend = $("#frmUsuario").serializeArray();
		dataSend.opt = opt;
		dataSend.push({"name" : "opt", "value" : opt });
		$('#frmUsuario').formValidation('validate');
	});
	
	$("#btnUpdateUsuario").click(function(){
		dataSend = {};
		opt= "updateUsuario";
		dataSend = $("#frmUsuario").serializeArray();
		dataSend.opt = opt;
		dataSend.push({"name" : "id_usuario", "value" : id_usuario });
		dataSend.push({"name" : "opt", "value" : opt });
		$('#frmUsuario').formValidation('validate');
	});
	
	$('#btnCancelUsuario').click(function(){
		window.location = "../usuarios/lista_usuarios.php";
	});
	
	$('#login').change(function(){
		login = $("#login").val();
		dataSend = {};
		dataSend.opt = "existLogin";
		dataSend.login = login;
		processData();
	});
	
	
	$('#frmUsuario').formValidation({
        message: 'Valor no es valido',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
			nombre_usr: {
                
                validators: {
                    notEmpty: {
                        message: 'Debe indicar el nombre del usuario'
                    },
                    stringLength: {
                        min: 3,
                        max: 50,
                        message: 'Debe de ser mayor a 3 caracteres'
                    }
                }
            },
			login: {
                
                validators: {
                    notEmpty: {
                        message: 'Debe indicar el login del usuario'
                    },
                    stringLength: {
                        min: 3,
                        max: 50,
                        message: 'Debe de ser mayor a 3 caracteres'
                    },
					callback: {
                            message: 'Ya existe un usuario con este login',
                            callback: function(value, validator, $field) {
								return validUser;		
                            }
                        }
                }
            },
			idRol: {
                validators: {
                    notEmpty: {
                        message: 'Debe seleccionar el tipo de usuario'
                    }
                }
            },
			password: {
                enabled: false,
                validators: {
                    notEmpty: {
                        message: 'Debe indicar un password'
                    }
                }
            },
            confirm_password: {
                enabled: false,
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
	
		login = $("#login").val();
			
		switch(opt){
			case "addUsuario":			
				msg = "Esta seguro que desea agregar el usuario?: <br>"+login;
				break;
			case "updateUsuario":
				msg = "Esta seguro que desea actualizar los datos del usuario?: <br>"+login;
				break;
		}
		
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
					case "getIni":
						setCatalogo(json.roles);
						break;
					case "getUsuario":
						setCatalogo(json.roles);
						setDataUsuario(json.usuario);
						break;
					case "updateUsuario":
					case "addUsuario":
						dialog.message(json.msg, function(dialogWin){ dialogWin.close(); window.location = "../usuarios/lista_usuarios.php";});
						break;
					case "existLogin":
						if(json.exist=="si"){
							validUser = false;
						}
						else{
							validUser = true;
						}
						$('#frmUsuario').formValidation('revalidateField', 'login');
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
	
	
	 if (dataSend.opt != undefined) {
		 if (dataSend.opt == "getUsuario") {
			 dataSend.id_usuario = id_usuario;
			 //$("#nombre_usr").attr("disabled", true);
			 $("#login").attr("disabled", true);
			 $("#btnAddUsuario").hide();
			 $("#btnUpdateUsuario").show();
			 $("#btnCancelUsuario").show();
			 $("#dvPassword").hide();
			 $("#dvConfirmPassword").hide();
			 $("#titlePage").html("Actualizar datos de Usuario");
		 }
	 }
	 else{
		 
		 $("#btnAddUsuario").show();
		 $("#btnUpdateUsuario").hide();
		 $("#btnCancelUsuario").show();
		 $("#dvPassword").show();
		 $("#dvConfirmPassword").show();
		 $('#frmUsuario').formValidation('enableFieldValidators', 'password', true)
                        .formValidation('enableFieldValidators', 'confirm_password', true);
		 dataSend.opt = "getIni";
		 

	 }
	 
	 processData();

});