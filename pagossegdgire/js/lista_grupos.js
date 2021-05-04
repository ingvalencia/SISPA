$(function() {
	
	var dataSend = {};
	var timeout = 100000;
	var lista_grupos = 0;
	var id_grupo = 0;
	var nombre_grupo;
    
	$("#btnUpdateGrupo").hide();
    $("#btnEnableGrupo").hide();
    $("#btnListaAlumnos").hide();
	$("#btnDisableGrupo").hide();
			
	$("#btnUpdateGrupo").click(function(){
		window.location="./grupo.php?actionGrupo=update&id_grupo="+id_grupo;	
	});
	
	$("#btnDisableGrupo").click(function(){
		msg  = "Esta seguro que desea deshabilitar el grupo: <br><br>"+nombre_grupo;
		msg += "<br><br><strong>Nota: Al deshabilitar el grupo ya no se mostrara en el calendario </strong>";
		dataSend.opt="disableGrupo";
		dataSend.id_grupo = id_grupo;
		dialog.confirm(
			msg
			,function(dialogWin){
				dialogWin.close();
				processData();
			}
			,function(dialogWin){
				dialogWin.close();
			
			}
		);
	});
	
	$("#btnEnableGrupo").click(function(){
		msg  = "Esta seguro que desea habilitar el grupo: <br><br>"+nombre_grupo;
		msg += "<br><br><strong>Nota: Al habilitar el grupo se mostrara en el calendario </strong>";
		dataSend.opt="enableGrupo";
		dataSend.id_grupo = id_grupo;
		dialog.confirm(
			msg
			,function(dialogWin){
				dialogWin.close();
				processData();
			}
			,function(dialogWin){
				dialogWin.close();
			
			}
		);
	});
	

	
	
	lista_grupos = $('#lista_grupos').DataTable( {
		"processing": true
		,"bFilter": false
        ,"serverSide": true
		,"language": opLanguage
        ,"ajax": {
            "url": "processListaGrupos.php"
            ,"type": "POST"
        }
    } );
	
	lista_grupos.column( 0 ).visible(false);
	
	

	
	$('#lista_grupos tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
			/*
            $(this).removeClass('selected');
			$("#btnUpdateGrupo").hide();
    		$("#btnEnableGrupo").hide();
		    $("#btnDisableGrupo").hide();
		    $("#btnListaAlumnos").hide();
			*/
        }
        else {
            lista_grupos.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
			$("#btnUpdateGrupo").show();
		    $("#btnListaAlumnos").show();
			if(lista_grupos.row( this ).data()[11]=="Si"){
				$("#btnEnableGrupo").hide();
		    	$("#btnDisableGrupo").show();
			}
			else{
				$("#btnEnableGrupo").show();
		    	$("#btnDisableGrupo").hide();
			}
			id_grupo = lista_grupos.row( this ).data()[0];
			nombre_grupo = lista_grupos.row( this ).data()[1];
			//console.log( lista_cursos.row( this ).data()[0] );
        }
    } );


	function processData() {

        $.ajax({
            type: "POST",
            url: 'processGrupos.php',
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
				
				lista_grupos.ajax.reload();
				$("#btnUpdateGrupo").hide();
				$("#btnEnableGrupo").hide();
				$("#btnDisableGrupo").hide();
				$("#btnListaAlumnos").hide();
				
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
