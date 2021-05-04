$(function() {
	
	var dataSend = {};
	var timeout = 100000;
	var lst_descuentos;
	var lista_alumnos;
	var id_descuento = 0;
	var cod_descuento;
	var id_tipo_descuento;
	var porcentaje;
	var max_descuentos;
	var validCodigo = false;
    
	
	
	$('#cod_descuento').change(function(){
		$('#msg_cod_descuento').html("");
		cod_descuento = $("#cod_descuento").val();
		dataSend = {};
		dataSend.opt = "validCodigo";
		dataSend.cod_descuento = cod_descuento;
		processData();
	});
	
	$('#id_tipo_descuento').change(function(){
		if($('#id_tipo_descuento').val()==""){
			$('#msg_id_tipo_descuento').html("Debe de indicar un tipo de descuento");
			return false;
		}
		$('#msg_id_tipo_descuento').html("");
	});
	
	$("#porcentaje").change(function(){
		$("#msg_porcentaje").html("");
		//validPorcentaje($(this).val());
	});
	
	$("#max_descuentos").change(function(){
		$("#msg_max_descuentos").html("");
		validMaxDescuentos($(this).val());
	});


	lst_descuentos = $('#lst_descuentos').DataTable( {
		"processing": true
		,"bFilter": false
        ,"serverSide": true
		,"language": opLanguage
        ,"ajax": {
            "url": "processListaDescuentos.php"
            ,"type": "POST"
        }
    } );
	
	lst_descuentos.column( 0 ).visible(false);
	lst_descuentos.column( 1 ).visible(false);
	
	
	
	
	/*
	var lista_alumnos = $('#lista_alumnos').DataTable( {
        "processing": true
		,"bFilter": false
        ,"serverSide": true
		,"language": opLanguage
        ,"ajax": {
            "url": "processListaAlumnos.php"
            ,"type": "POST"
        }
    } );
	
	lista_alumnos.column( 0 ).visible(false);
	
	$('#lista_alumnos tbody').on( 'click', 'tr', function () {
            lista_alumnos.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
			$("#btnUpdateAlumno").show();
			id_alumno = lista_alumnos.row( this ).data()[0];
    } );
	*/
	
	
	
	
	
	$('#lst_descuentos tbody').on( 'click', 'tr', function () {
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
            lst_descuentos.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
			$("#btnUpDescuento").show();
			id_descuento = lst_descuentos.row( this ).data()[0];
			id_tipo_descuento = lst_descuentos.row( this ).data()[1];
			cod_descuento = lst_descuentos.row( this ).data()[2];
			porcentaje = lst_descuentos.row( this ).data()[4];
			max_descuento= lst_descuentos.row( this ).data()[5];
			
        }
    } );
	
	function validPorcentaje(d){
		var expr = /^\d{1,3}(\.\d{1,2})?$/;
		//var expr = /^\d$/;
    	if ( !expr.test(d) ){return false;}
		
		if(parseFloat(d)>100){ return false; }
		return true;
	}
	
	function validMaxDescuentos(d){
		var expr = /^\d*$/;
    	if ( !expr.test(d) ) return false;
		return true;
	}
	
	
	function validDescuento(){
	
		var cod_descuento = $("#cod_descuento").val();
		var id_tipo_descuento = $("#id_tipo_descuento").val();
		var porcentaje = $("#porcentaje").val();
		var max_descuentos = $("#max_descuentos").val();
		var flag = true;
		
		if(cod_descuento==""){
			$("#msg_cod_descuento").html("Debe ingresar un código de descuento");
			flag = false;
		}
		else if((cod_descuento.length<1)||(cod_descuento.length>7)){
			$("#msg_cod_descuento").html("Debe de ser menor a 7 caractereso");
			flag = false;
		}
		else if(!validCodigo){
			flag = false;
		}
		
		if(id_tipo_descuento==""){
			$("#msg_id_tipo_descuento").html("Debe indicar un tipo de descuento");
			flag = false;
		}
		
		if(porcentaje==""){
			$("#msg_porcentaje").html("Debe indicar un porcentaje");
			flag = false;
		}
		else if(!validPorcentaje(porcentaje)){
			$("#msg_porcentaje").html("Debe de ser un porcentaje valido");
			flag = false;
		}
		
		if($("#max_descuentos").val()==""){
			$("#msg_max_descuentos").html("");
		}
		else if(!validMaxDescuentos(max_descuentos)){
			$("#msg_max_descuentos").html("Debe de ser un descuento valido");
			flag = false;
		}
		
		return flag;
	}
	
	
	$("#btnUpDescuento").click(function(){
		validCodigo = true;
		$("#btnUpsDescuento").show();
		$("#btnSaveDescuento").hide();
		$("#cod_descuento").val(cod_descuento);
		$("#id_tipo_descuento").val(id_tipo_descuento);
		$("#porcentaje").val(porcentaje);
		$("#max_descuentos").val(max_descuentos);
		$("#modalDescuento").modal('show');
	});
	
	$("#btnUpsDescuento").click(function(){
		console.log("11");
		if(!validDescuento()){ return false; }
		console.log("12");
		//$('#frmDescuento').formVal||idation('validate');
		dialog.confirm("Esta seguro que desea actualizar el descuento", 
			function(dWin){
				console.log("13");
				dWin.close();
				dataSend.opt = "upDescuento";
				dataSend.id_descuento = id_descuento;
				dataSend.cod_descuento = $("#cod_descuento").val();
				dataSend.id_tipo_descuento = $("#id_tipo_descuento").val();
				dataSend.porcentaje = $("#porcentaje").val();
				dataSend.max_descuentos = $("#max_descuentos").val();
				$("#modalDescuento").modal('hide');
				processData();
			}
		);
		
	});
	
	
    $("#btnAddDescuento").click(function(){
		$("#btnUpsDescuento").hide();
		$("#btnSaveDescuento").show();
		$("#modalDescuento").modal('show');
	});
		
	$("#btnCancelDescuento").click(function(){
		$("#modalDescuento").modal('hide');
		clearForm();
	});
	
	$("#btnSaveDescuento").click(function(){
		
		if(!validDescuento()){ return false; }
		
		//$('#frmDescuento').formVal||idation('validate');
		dialog.confirm("Esta seguro que desea agregar el nuevo código", 
			function(dWin){
				dWin.close();
				dataSend.opt = "addDescuento";
				dataSend.cod_descuento = $("#cod_descuento").val();
				dataSend.id_tipo_descuento = $("#id_tipo_descuento").val();
				dataSend.porcentaje = $("#porcentaje").val();
				dataSend.max_descuentos = $("#max_descuentos").val();
				$("#modalDescuento").modal('hide');
				processData();
			}
		);
		
	});
	
	$('#xfrmDescuento').formValidation({
        message: 'Valor no es valido',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
		
            cod_descuento: {
                validators: {
                    notEmpty: {
                        message: 'Debes ingresar un código de descuento'
                    },
                    stringLength: {
                        min: 1,
                        max: 7,
                        message: 'Debe de ser menor a 7 caracteres'
                    },
                    callback: {
                        message: 'El código de escuento ya existe',
                        callback: function(value, validator) {
								return validCodigo;
                        }
                    }
					
                }
            },
			id_tipo_descuento: {
                
                validators: {
					notEmpty: {
                        message: 'Debe seleccionar un tipo de descuento'
                    }
                }
            },
			porcentaje: {
                
                validators: {
                    notEmpty: {
                        message: 'Debe indicar el porcentaje'
                    },
                    stringLength: {
                        min: 3,
                        max: 50,
                        message: 'Debe de ser mayor a 3 caracteres'
                    }
                }
            },
			max_descuentos: {
                
                validators: {
                    callback: {
                        message: 'Debe de ser un número entero',
                        callback: function(value, validator) {
								if($("#max_descuentos").val() == ""){ return  true; }
								return false;
                        }
                    }
                }
            }
			
			
        }
    }).on('success.form.fv', function(e) {
		
		var msg;
		
		switch(dataSend.opt){
			case "addDescuento":
				msg = "¿Esta seguro que desea agregar el descuento con código?: <br>"+$("#cod_descuento").val();
				break;
			case "updateDescuento":			
				msg = "¿Esta seguro que desea actualizar los datos del descuento?";
				break;
		}
		
		dialog.confirm(msg, function(dialogWin){dialogWin.close(); processData(); });
		
    });


	
	function setData(lstD){
		
		$.each(lstD, function(id, valor) {
			$("#id_tipo_descuento").append("<option value='"+id+"'>"+valor.descripcion_descuento+"</option>");
		});
	}

	function clearForm(){
		$("#cod_descuento").val("");
		$("#msg_cod_descuento").html("");
		$("#id_tipo_descuento").val("");
		$("#msg_id_tipo_descuento").html("");
		$("#porcentaje").val("");
		$("#msg_porcentaje").html("");
		$("#max_descuentos").val("");
		$("#msg_max_descuentos").html("");
	}
	
	function processData() {

        $.ajax({
            type: "POST",
            url: 'processDescuentos.php',
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
						setData(json.data.tipo_descuentos);
						break;
					case "validCodigo":
						$("msg_cod_descuento").html("");
						if(json.exist == "si"){ 
							$("#cod_descuento").val("");
							$("#msg_cod_descuento").html("El código ya existe");
							validCodigo=false; 
						}
						else{ validCodigo=true; }
						break;
					case "addDescuento":
						clearForm();
						dialog.message(json.msg);
						lst_descuentos.ajax.reload();
						break;
					case "upDescuento":
						clearForm();
						dialog.message(json.msg);
						lst_descuentos.ajax.reload();
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
				dialog.alert(msg)
                return false;
            }
        });
    }
	
	dataSend.opt="getIni";
	processData();
});
