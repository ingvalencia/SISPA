var dataSend = {};
$(function() {
	

var timeout = 100000;
var id_curso = 0;
var nombre_curso = 0;
var rowSelect;
var lista_cursos;
var lista_grupos;
var lista_instructores;
var lista_alumnos;
var dataSerialize = "";
var opt;
var clave;
var hideItem;

	//$("#btnSaveCurso").hide();
	$("#btnUpdateCurso").hide();
	$("#btnCancelCurso").hide();
	
	$('#id_concepto_pago').mask('9999');
	$('#duracion_hrs').mask('9999');
	
	hideItem = {
		"insert_link":false
		,"unlink":false
		,"insert_img":false
		,"print":false
		,"source":false
		};
	
	$("#objetivo").Editor(hideItem);
	$("#contenido").Editor(hideItem);
	
	function setData(data){
		id_curso = data.id_curso;
		$.each(data, function(id, valor){
			if((id=="objetivo")||(id=="contenido")){
				$("#"+id).Editor("setText", valor);	
				//$("#objetivo").Editor("setText", "asdas");	
				//$("#contenido").Editor("setText", "dddd");	
				//next;
			}
			else{
				$("#"+id).val(valor);
				$("#"+id).text(valor);
			}
		});
   }
   
   
   $("#btnCancelCurso").click(function(){
	   window.location = "./lista_cursos.php";
   });
   
   $("#btnSaveCurso").click(function(){
	  
	   opt="saveCurso";
	    //dataSend=$('#frmCurso').serializeArray();
		dataSend = {};
		dataSend.nombre_curso = $("#nombre_curso").val();
		dataSend.id_concepto_pago = $("#id_concepto_pago").val();
		dataSend.opt = opt;   
		dataSend.objetivo = $("#objetivo").Editor("getText");
		dataSend.contenido = $("#contenido").Editor("getText");
		dataSend.duracion_hrs = $("#duracion_hrs").val();
		
		//console.log($("#objetivo").Editor("getText"));
		//return false;
		$('#frmCurso').formValidation('validate');
		
		return false;
   });

	$("#btnUpdateCurso").click(function(){
		
		var obj = $("#objetivo").Editor("getText");
		var con = $("#contenido").Editor("getText");
		opt="updateCurso";
		dataSend=$('#frmCurso').serializeArray();
		dataSend.opt = opt;   
		dataSend.push({"name" : "opt", "value" : opt});
		dataSend.push({"name" : "id_curso", "value" : id_curso});
		dataSend.push({"name" : "objetivo", "value" : obj});
		dataSend.push({"name" : "contenido", "value" : con});
		$('#frmCurso').formValidation('validate');
		
		return false;
   });

	$("#id_concepto_pago").change(function(){
	
		clave = $("#id_concepto_pago").val();
		dataSend = {};
		dataSend.opt = "checkClave";
		dataSend.id_concepto_pago = clave;
		processData();
		
	});
	
	
	
	function afterSaveCurso(xMsg){
		
		msg = xMsg+"<br><br>¿Quiere agregar otro curso?";
		dialog.confirm(msg
		,function(dialogWin){ 
			dialogWin.close();
			$('#frmCurso').formValidation('resetForm');
			$('#frmCurso')[0].reset();
			$('#monto_curso').text("");
			$("#objetivo").Editor("setText", "");
			$("#contenido").Editor("setText", "");
		}
		,function(dialogWin){
			dialogWin.close();
			window.location = "lista_cursos.php";	
		});
	}
	
	function afterUpdateCurso(msg){
		dialog.message(msg, function(dialogWin){ window.location = "./lista_cursos.php";});
	}
	
	function afterGetCurso(xCurso){
		console.log(xCurso);
		setData(xCurso);
	}
	
	function afterCheckClave(json){
		
		console.log(1);
		if(json.exist=="no"){
			console.log(2);
			msg="Clave <strong>"+clave+"</strong> no existe, intente con otra";
			dialog.message(msg);
			$("#id_concepto_pago").val("");
			$("#monto_curso").html("");
			return false;
		}
		console.log(3);
		$("#monto_curso").html(json.monto_curso);
		console.log(4);
	}
	

$('#frmCurso').formValidation({
        message: 'Valor no es valido',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
		
            nombre_curso: {
                
                validators: {
                    notEmpty: {
                        message: 'Debe indicar el nombre del curso'
                    },
                    stringLength: {
                        min: 10,
                        max: 250,
                        message: 'Debe de ser mayor a 10 caracteres'
                    }
					
                }
            },
			contenido: {
                
                validators: {
                    notEmpty: {
                        message: 'Debe indicar el contenido'
                    },
                    stringLength: {
                        min: 50,
                        max: 2000,
                        message: 'Debe de ser mayor a 50 caracteres'
                    }
                }
            },
			objetivo: {
                
                validators: {
                    notEmpty: {
                        message: 'Debe indicar el objetivo'
                    },
                    stringLength: {
                        min: 50,
                        max: 2000,
                        message: 'Debe de ser mayor a 50 caracteres'
                    }
                }
            },
			duracion_hrs: {
                
                validators: {
                    notEmpty: {
                        message: 'Debe indicar la duración'
                    },
					digits: {
                        message: 'Solo pueden ser números'
                    },
                    stringLength: {
                        min: 1,
                        max: 4,
                        message: 'Debe de ser mayor a 1 digito'
                    }
                }
            }			
        }
    }).on('success.form.fv', function(e) {
	
		var xCurso = $("#nombre_curso").val();
			
		switch(opt){
			case "saveCurso":
				msg = "Esa seguro que desea crear el nuevo curso: <br><br>"+xCurso;
				break;
			case "updateCurso":
				msg = "Esa seguro que desea actualizar los datos del curso: <br><br>"+xCurso;
				break;
		}
		
		dialog.confirm(msg, function(dialogWin){dialogWin.close(); processData(); });
		
    });
	
	function processData(){
		
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
				
				switch(dataSend.opt){
					case "saveCurso":
						afterSaveCurso(json.msg);
						break;
					case "updateCurso":
						afterUpdateCurso(json.msg);
						break;
					case "getCurso":
						afterGetCurso(json.curso);
						break;
					case "checkClave":
						afterCheckClave(json);
						break;
					default:
					
				}
				
				
				return false;

			},
			error: function(jqXHR, textStatus, errorThrown) {
				dialog.close();
				msg = "";
				if (textStatus == "timeout") {
					msg = "El tiempo de espera agotado: intentelo de nuevo";
				} else {
					msg = "Error en la conexión: intentelo de nuevo";
				}
				dialog.alert(msg);
				return false;
			}
		});
	}
	
	//console.log("action: "+action);
	if(dataSend.opt != undefined){
		console.log(dataSend);
		if(dataSend.opt=="getCurso"){
			opt="updateCurso";
			processData();
			$("#btnSaveCurso").hide();
			$("#btnUpdateCurso").show();
			$("#btnCancelCurso").show();
			
			$("#titlePage").text("Actualizar curso");
		}
		else{
			opt="saveCurso";
			$("#btnSaveCurso").show();
			$("#btnUpdateCurso").hide();
		}
	}
	else{
		opt="saveCurso";
	}
	
});
