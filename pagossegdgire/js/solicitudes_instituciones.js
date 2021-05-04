var dataSend = {};
$(function() {
    
	
var dataSend = {};
var timeout = 100000;
var id_solicitud_institucion = 0;
var rowSelect;
var lst_sol_instituciones;
var dataSerialize = "";

	$("#btnUpdateCurso").hide();
	$("#btnEnableCurso").hide();
	$("#btnDisableCurso").hide();
	
	function cancelOption(dialogWin){
		dialogWin.close();
	}
	
	function aceptOption(dialogWin){
		dialogWin.close();
		processData();
	}
	
	$("#btnShowSolicitud").click(function(){
		window.location="./solicitud_institucion.php?action=show&id_solicitud_institucion="+id_solicitud_institucion;	
	});
	
	
	lst_sol_instituciones = $('#lst_sol_instituciones').DataTable({
        "processing": true
		,"bFilter": false
        ,"serverSide": true
		,"language": opLanguage
        ,"ajax": {
            "url": "processListaSolInstituciones.php",
            "type": "POST"
        }
	});
	
	//lista_cursos.column( 0 ).visible(false);
	//lst_sol_instituciones.column( 5 ).visible(false);
	

	$('#lst_sol_instituciones tbody').dblclick(function() {
		//window.location="./cursos.php?action=update&id_curso="+id_curso;
	});

	$('#lst_sol_instituciones tbody').on( 'click', 'tr', function () {
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
            lst_sol_instituciones.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
			$("#btnShowSolicitud").show();
			if(lst_sol_instituciones.row( this ).data()[4]=="Si"){
				$("#btnEnableCurso").hide();
				$("#btnDisableCurso").show();
			}
			else{
				$("#btnEnableCurso").show();
				$("#btnDisableCurso").hide();
			}
			id_solicitud_institucion =lst_sol_instituciones.row( this ).data()[0];
			//nombre_curso = lst_sol_instituciones.row( this ).data()[1];
			//console.log( lista_cursos.row( this ).data()[0] );
        }
    } );	
	
	 function processData() {

        $.ajax({
            type: "POST",
            url: 'processListaSolInstituciones.php',
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
				
				dialog.alert(json.msg);
				
				lst_sol_instituciones.ajax.reload();
				$("#btnUpdateCurso").hide();
				$("#btnEnableCurso").hide();
				$("#btnDisableCurso").hide();
				
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
