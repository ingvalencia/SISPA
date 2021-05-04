var dataSend = {};
$(function() {
    
	
var dataSend = {};
var timeout = 100000;
var id_curso = 0;
var nombre_curso = 0;
var rowSelect;
var lista_cursos;
var lista_grupos;
var lista_instructores;
var lista_alumnos;
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
	
	$("#btnUpdateCurso").click(function(){
		window.location="./cursos.php?action=update&id_curso="+id_curso;	
	});
	
	$("#btnDisableCurso").click(function(){
		msg  = "Esta seguro que desea deshabilitar el curso: <br><br>"+nombre_curso;
		msg += "<br><br><strong>Nota: Al deshabilitar el curso ya no lo podra seleccionar para nuevos grupos</strong>";
		dialog.confirm(msg, aceptOption, cancelOption);
		dataSend.opt="disableCurso";
		dataSend.id_curso = id_curso;
	});
	
	$("#btnEnableCurso").click(function(){
		msg  = "Esta seguro que desea habilitar el curso: <br><br>"+nombre_curso;
		msg += "<br><br><strong>Nota: Al habilitar el curso podra selccionarlo para nuevos grupos</strong>";
		dialog.confirm(msg, aceptOption, cancelOption);
		dataSend.opt="enableCurso";
		dataSend.id_curso = id_curso;
	});
	
	lista_cursos = $('#lista_cursos').DataTable({
        "processing": true
		,"bFilter": false
        ,"serverSide": true
		,"language": opLanguage
        ,"ajax": {
            "url": "processListaCursos.php",
            "type": "POST"
        }
	});
	
	lista_cursos.column( 0 ).visible(false);
	lista_cursos.column( 5 ).visible(false);
	lista_cursos.column( 6 ).visible(false);

	$('#lista_cursos tbody').dblclick(function() {
		window.location="./cursos.php?action=update&id_curso="+id_curso;
	});

	$('#lista_cursos tbody').on( 'click', 'tr', function () {
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
            lista_cursos.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
			$("#btnUpdateCurso").show();
			if(lista_cursos.row( this ).data()[4]=="Si"){
				$("#btnEnableCurso").hide();
				$("#btnDisableCurso").show();
			}
			else{
				$("#btnEnableCurso").show();
				$("#btnDisableCurso").hide();
			}
			id_curso = lista_cursos.row( this ).data()[0];
			nombre_curso = lista_cursos.row( this ).data()[1];
			//console.log( lista_cursos.row( this ).data()[0] );
        }
    } );	
	
	 function processData() {

        $.ajax({
            type: "POST",
            url: 'processCursos.php',
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
				
				lista_cursos.ajax.reload();
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
