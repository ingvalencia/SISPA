var dataSend = {};
$(function() {
    
	
	var dataSend = {};
	var timeout = 100000;
	var id_usuario = 0;
	var nombre_usuario = 0;
	var rowSelect;
	var lista_usuarios;
	var dataSerialize = "";
	var nombre;

	$("#btnUpdatePassword").hide();
	$("#btnUpdateUsuario").hide();
	$("#btnEnableUsuario").hide();
	$("#btnDisableUsuario").hide();
	$("#btnDeleteUsuario").hide();
	
	
	$("#btnUpdatePassword").click(function(){
		localStorage.setItem('id_usuario', id_usuario);
		localStorage.setItem('nombre_usuario', nombre_usuario);
		window.location="./cambio_password.php?action=update&id_usuario="+id_usuario;	
	});
	
	
	$("#btnUpdateUsuario").click(function(){
		window.location="./usuario.php?action=update&id_usuario="+id_usuario;	
	});
	
	$("#btnDisableUsuario").click(function(){
		msg  = "Esta seguro que desea deshabilitar el usuario: <br><br>"+nombre_usuario;
		msg += "<br><br><strong>Nota: Al deshabilitar el usuario ya no podra iniciar sesión</strong>";
		dataSend.opt="disableUsuario";
		dataSend.id_usuario = id_usuario;
		dialog.confirm(msg, 
			function(dWin){
					dWin.close(); 
					processData();
			},
			function(dWin){
				dWin.close();
			}
			);
	});
	
	$("#btnDeleteUsuario").click(function(){
		msg  = "Esta seguro que desea eliminar el usuario: <br><br>"+nombre_usuario;
		msg += "<br><br><strong>Nota: Al eliminar el usuario ya no podra iniciar sesión</strong>";
		dataSend.opt="deleteUsuario";
		dataSend.id_usuario = id_usuario;
		dialog.confirm(msg, 
			function(dWin){
					dWin.close(); 
					processData();
			},
			function(dWin){
				dWin.close();
			}
			);
	});
	
	$("#btnEnableUsuario").click(function(){
		msg  = "Esta seguro que desea habilitar el usuario: <br><br>"+nombre_usuario;
		msg += "<br><br><strong>Nota: Al habilitar el usuario podra iniciar sesión</strong>";
		dataSend.opt="enableUsuario";
		dataSend.id_usuario = id_usuario;
		dialog.confirm(msg, 
			function(dWin){
					dWin.close(); 
					processData();
			},
			function(dWin){
				dWin.close();
			}
			);
	});
	
	lista_usuarios = $('#lista_usuarios').DataTable({
        "processing": true
		,"bFilter": false
        ,"serverSide": true
		,"language": opLanguage
        ,"ajax": {
            "url": "processListaUsuarios.php",
            "type": "POST"
        }
	});
	
	//lista_cursos.column( 0 ).visible(false);
	//lista_usuarios.column( 5 ).visible(false);
	//lista_usuarios.column( 6 ).visible(false);

	$('#lista_usuarios tbody').dblclick(function() {
		window.location="./usuario.php?action=update&id_usuario="+id_curso;
	});

	$('#lista_usuarios tbody').on( 'click', 'tr', function () {
		rowSelect = this;
        if ( $(this).hasClass('selected') ) {
			/*
            $(this).removeClass('selected');
			$("#btnUpdateCurso").hide();
			$("#btnEnableCurso").hide();
			$("#btnDisableCurso").hide();
			*/
        }
        else {
            lista_usuarios.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
			$("#btnUpdatePassword").show();
			$("#btnUpdateUsuario").show();
			$("#btnDeleteUsuario").show();
			if(lista_usuarios.row( this ).data()[4]=="Si"){
				$("#btnEnableUsuario").hide();
				$("#btnDisableUsuario").show();
			}
			else{
				$("#btnEnableUsuario").show();
				$("#btnDisableUsuario").hide();
			}
			id_usuario = lista_usuarios.row( this ).data()[0];
			nombre_usuario = lista_usuarios.row( this ).data()[1];
			//console.log( lista_cursos.row( this ).data()[0] );
        }
    } );	
	
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
				
				dialog.message(json.msg);
				lista_usuarios.ajax.reload();
				$("#btnUpdateUsuario").hide();
				$("#btnEnableUsuario").hide();
				$("#btnDisableUsuario").hide();
				$("#btnDeleteUsuario").hide();
				$("#btnUpdatePassword").hide();
				
						/*
				switch(dataSend.opt){
					case "enableUsuario":
						dialog.alert(json.msg);
						lista_cursos.ajax.reload();
						$("#btnUpdateUsuario").hide();
						$("#btnEnableUsuario").hide();
						$("#btnDisableUsuario").hide();
				
						break;
					case "disableUsuario":
						break;
				}
				*/
				
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
				dialog.alert(msg)
                return false;
            }
        });
    }

	//processData();
		    
});
