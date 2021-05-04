var dataSend = {};
var timeout = 100000;
	
$(function() {
		
	var id_instructor = 0;
	var lista_instructores;
	var nombre_instructor;
    
	$("#btnUpdateInstructor").hide();
	$("#btnEnableInstructor").hide();
	$("#btnDisableInstructor").hide();
	
	function cancelOption(dialogWin){
		dialogWin.close();
	}
	
	function aceptOption(dialogWin){
		dialogWin.close();
		processData();
	}
	
	$("#btnUpdateInstructor").click(function(){
		window.location="./instructor.php?action=update&id_instructor="+id_instructor;	
	});
	
	$("#btnDisableInstructor").click(function(){
		msg  = "Esta seguro que desea deshabilitar al instructor: <br><br>"+nombre_instructor;
		msg += "<br><br><strong>Nota: Al deshabilitar al instructor ya no lo podra seleccionar para nuevos grupos</strong>";
		dialog.confirm(msg, aceptOption, cancelOption);
		dataSend.opt="disableInstructor";
		dataSend.id_instructor = id_instructor;
	});
	
	$("#btnEnableInstructor").click(function(){
		msg  = "Esta seguro que desea habilitar al instructor: <br><br>"+nombre_instructor;
		msg += "<br><br><strong>Nota: Al habilitar al instructor podra selccionarlo para nuevos grupos</strong>";
		dialog.confirm(msg, aceptOption, cancelOption);
		dataSend.opt="enableInstructor";
		dataSend.id_instructor = id_instructor;
	});
	
	lista_instructores = $('#lista_instructores').DataTable( {
        "processing": true
		,"bFilter": false
        ,"serverSide": true
		,"language": opLanguage
        ,"ajax": {
            "url": "processListaInstructores.php"
            ,"type": "POST"
        }
    } );
	
	lista_instructores.column( 0 ).visible(false);
	
	$('#lista_instructores tbody').dblclick(function() {
		window.location="./instructor.php?action=update&id_instructor="+id_instructor;
	});
	
	$('#lista_instructores tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
			/*
            $(this).removeClass('selected');
			$("#btnUpdateInstructor").hide();
			$("#btnEnableInstructor").hide();
			$("#btnDisableInstructor").hide();
			*/
        }
        else {
            lista_instructores.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
			$("#btnUpdateInstructor").show();
			if(lista_instructores.row( this ).data()[9]=="Si"){
				$("#btnEnableInstructor").hide();
				$("#btnDisableInstructor").show();
			}
			else{
				$("#btnEnableInstructor").show();
				$("#btnDisableInstructor").hide();
			}
			id_instructor = lista_instructores.row( this ).data()[0];
			nombre_instructor  = lista_instructores.row( this ).data()[1];
			nombre_instructor += lista_instructores.row( this ).data()[2];
			nombre_instructor += lista_instructores.row( this ).data()[3];
			//console.log( lista_instructores.row( this ).data()[0] );
        }
    } );
	
	
	
	
	
	
	
	

	function processData() {

        $.ajax({
            type: "POST",
            url: 'processInstructores.php',
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
				
				lista_instructores.ajax.reload();
				$("#btnUpdateInstructor").hide();
				$("#btnEnableInstructor").hide();
				$("#btnDisableInstructor").hide();
				
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
		    
});
