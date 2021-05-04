$(function() {

    /*Variables a usar*/

    var conceptos = []
		,validRFC = false
		,clave
		,concepto
		,pesos
		,num_smdf
		,xclave
		,cad = ""
		,opt
		,chFactura
		,dvDataRFC
		,addRFC
		,chplantel
		,ptl_ptl
		;

	
    $("#clave").mask("9999");
    $("#cantidad").mask("9999");
    $("#id_cp").mask("99999");
    $('#precio_unitario').mask('0000');
	$("#ptl_ptl").hide();
	
    $("#dvFactura").hide();

    function delConcepto(xClave) {
        var xConceptos = [];

        $.each(conceptos, function(id, d) {
            if (d.clave != xClave) {
                xConceptos.push(d);
            }
        });
        conceptos = xConceptos;
		MuestraTotal();
    }

	jQuery.fn.borrar = function() {
        $(this).val('');
    };
 
    $("#lst_conceptos").change(function() {
		
        xclave = $("#lst_conceptos").val();
		$("#cantidad").prop("disabled", true);
		$("#cantidad").borrar();
		$("#precio_unitario").prop("disabled", true);
		$("#precio_unitario").borrar();

		if ((xclave == null)||(xclave == "")) {
            return false;
        }
		
        $("#cantidad").prop("disabled", false);
        $("#btnAddConcepto").prop("disabled", false);

		if(lstConceptos[xclave].costo_variable==1){
			$("#precio_unitario").prop("disabled", false);
		}
		
        pesos = lstConceptos[xclave].importe_pesos;
        num_smdf = lstConceptos[xclave].importe_smdf;

        if (num_smdf != 0) {
            $("#precio_unitario").val(parseFloat(smdf * num_smdf).toFixed(2));
        } 
		else {
            $("#precio_unitario").val(parseFloat(pesos).toFixed(2));
        }
		
		$("#cantidad").focus();
		
    });

    
   $("#btnAddConcepto").click(function() {

		clave = $("#lst_conceptos").val();
        cantidad = $("#cantidad").val();
        precio_variable = $("#precio_unitario").val();
		
		if (clave == "") {
            dialog.alert("Debe seleccionar un concepto");
            $("#clave").borrar();
			return false;
		}
		
		if (cantidad == 0) {
            dialog.alert("La cantidad debe ser mayor a 0");
            $("#cantidad").borrar();
			return false;

		}
		
		if(lstConceptos[clave].costo_variable==1){
			if(precio_variable==0){
				dialog.alert("El monto debe de ser mayor a $0.00 pesos");
				return false;
			}
		}
		
		$("#clave").borrar();
		$("#lst_conceptos").val("").trigger("change");
		$("#cantidad").borrar();
        $("#precio_unitario").val("");
		
        $("#cantidad").prop("disabled", true);
        $("#precio_unitario").prop("disabled", true);
		$("#btnAddConcepto").prop("disabled", true);
		
        if (conceptoRegistrado(clave)) {
            dialog.alert("El concepto ya fue agregada");
            return false;
		}

		dataSend = {};
        dataSend.opt = "getMontos";
        dataSend.clave = clave;
        dataSend.cantidad = cantidad;
        dataSend.precio_variable = precio_variable;
        processData();

        return true;

    });

    
    function addRowTable(x_concepto) {

        cad = "";
        cad += "<tr id='tr_" + clave + "'>";
        cad += "<td class='text-center'>" + x_concepto.clave + "</td>";
        cad += "<td>" + x_concepto.nom_concepto_pago + "</td>";
        cad += "<td>" + '$' + parseFloat(x_concepto.importe).toFixed(2) + "</td>";
        cad += "<td class='text-center'>" + x_concepto.cantidad + "</td>";
        cad += "<td>" + '$' + x_concepto.importe_sin_iva + "</td>";
        cad += "<td>" + '$' + x_concepto.iva_total + "</td>";
        cad += "<td>" + '$' + x_concepto.monto_tot_conc + "</td>";
        cad += "<td><button  clave='" + x_concepto.clave + "' class='btn btn-outline btn-danger center-block clsEliminaCon btn-xs'><span class='glyphicon glyphicon-trash'></span></button></td>";
        cad += "</tr>";

        conceptos.push({
            "clave": x_concepto.clave,
            "cantidad": x_concepto.cantidad,
            "precio_variable": x_concepto.precio_variable,
            "iva_total": x_concepto.iva_total,
            "importe_sin_iva": x_concepto.importe_sin_iva
        });

        $("#tSolicitud").append(cad);
        MuestraTotal();

    }
    
	function conceptoRegistrado(x_clave) {
        var x_flag = false;
		
		$.each(conceptos, function(index, x_concepto){
			if(x_concepto.clave == x_clave){
				x_flag = true;
			}
		});
		
        return x_flag;
    }
    
	
    function MuestraTotal() {

        var stotal = 0.00;
        var iva_total = 0.00;
        var monto_total = 0.00;
        var numero;

        $.each(conceptos, function(index, x_concepto) {
            monto_total += parseFloat(x_concepto.iva_total) + parseFloat(x_concepto.importe_sin_iva);
            iva_total += parseFloat(x_concepto.iva_total);
            stotal += parseFloat(x_concepto.importe_sin_iva);
        });

        $("#stotal").html('$' + stotal.toLocaleString(undefined, { minimumFractionDigits: 2 }));
        $("#iva_total").html('$' + iva_total.toLocaleString(undefined, { minimumFractionDigits: 2 }));
        $("#monto_total").html('$' + monto_total.toLocaleString(undefined, { minimumFractionDigits: 2 }));

    }
    
	
	function enableDataRFC(xFlag){
		
		$('#frmrfactura')
            .formValidation('enableFieldValidators', 'tipo_persona', xFlag)
            .formValidation('enableFieldValidators', 'rfc', xFlag)
            .formValidation('enableFieldValidators', 'nombre_fisc', xFlag)
            .formValidation('enableFieldValidators', 'fnombre', xFlag)
            .formValidation('enableFieldValidators', 'fap_paterno', xFlag)
            .formValidation('enableFieldValidators', 'calle', xFlag)
            .formValidation('enableFieldValidators', 'num_ext', xFlag)
            .formValidation('enableFieldValidators', 'id_cp', xFlag)
            .formValidation('enableFieldValidators', 'id_colonia', xFlag)
            .formValidation('enableFieldValidators', 'txtOtraCol', xFlag);

			
	}
	
	$(document).on('click', '.clsEliminaCon', function() {
        xclave = $(this).attr("clave");
        $(this).parents("#tSolicitud tbody tr").remove();
        delConcepto(xclave);
    });
    
	$("#btnRegistrar").click(function() {

		chFactura = $("#chFactura").is(':checked');
		dvDataRFC = $("#dvDataRFC").val();
		addRFC = $("#addRFC").is(':checked');
		chplantel = $("#chplantel").is(':checked');
		ptl_ptl = $("#ptl_ptl").val();
		
		
        if (conceptos.length == 0) {
            msg = "Debe agregar al menos un concepto para generar la solicitud";
            dialog.alert(msg)
            return false;
		}
		
		if (chplantel && ptl_ptl=="") {
            msg = "Si va asociar su trámite a un plantel debe seleccionar uno";
            dialog.alert(msg)
            return false;
		}
		
		if (chFactura && dvDataRFC == "" && !addRFC) {
			msg = "Debe seleccionar un RFC o agregar un RFC si desea solicitar factura";
            dialog.alert(msg)
            return false;
		}

		if(addRFC){
			$('#frmrfactura').formValidation('validate');
			return false;
		}

        agregar_solicitud();
		
        return false;

    });

    

    
    

    $("#chplantel").click(function() {

        if ($(this).is(':checked')) {
            $('#ptl_ptl').removeAttr('disabled');
        } else {
            $("#ptl_ptl").val("").trigger('change');
            $('#ptl_ptl').attr('disabled', 'disabled');
        }

    });

    $("#chFactura").click(function() {
		
		enableDataRFC(false);
        if ($(this).is(':checked')) {
            $("#dvFactura").show();
			
        } else {
            $("#dvFactura").hide();
            $("#dvDataRFC").val("");
            resetrfactura();
        }
		
    });

    $("#addRFC").click(function() {

        if ($(this).is(':checked')) {
			$("#dvDataRFC").val("").trigger("change");
			$(this).prop('checked', true);
			$("#dvregfactura").show();
			enableDataRFC(true);			
        } else {
            $("#dvregfactura").hide();
            enableDataRFC(false);
            $("#check_colonia").prop("checked", false);
            $("#datOtraColonia").hide();
            resetrfactura();
        }
		
    });

    $(document).on('change', '#dvDataRFC', function() {

        $("#addRFC").prop("checked", false);
        $("#dvregfactura").hide();
        enableDataRFC(false);
		resetrfactura();
    });

    $('#tipo_persona').change(function() {

        $("#fnombre").val("");
        $("#fap_paterno").val("");
		$("#fap_materno").val("");
        $("#nombre_fisc").val("");
        
        /*MORAL*/
        if ($('#tipo_persona').val() == 1) {

            $("#cedulaMoral").hide();
            $("#cedulaPerfisica").show();
			$('#fmfactura').formValidation('enableFieldValidators', 'nombre_fisc', false)
			$('#frmfactura').formValidation('enableFieldValidators', 'fnombre', true);
			$('#frmfactura').formValidation('enableFieldValidators', 'fap_paterno', true);
            
        }
        
          /*FISICA*/
        if ($('#tipo_persona').val() == 2) {

			$("#cedulaMoral").show();
			$("#cedulaPerfisica").hide();
            $('#frmfactura')
            $('#fmfactura').formValidation('enableFieldValidators', 'nombre_fisc', true)
			$('#frmfactura').formValidation('enableFieldValidators', 'fnombre', false);
			$('#frmfactura').formValidation('enableFieldValidators', 'fap_paterno', false);
        }

        if ($('#tipo_persona').val() == '') {
            $("#cedulaMoral").hide();
            $("#cedulaPerfisica").hide();
        }

    });

    $("#check_colonia").click(function() {

        if ($(this).is(':checked')) {

            //muestro
            $("#datOtraColonia").show();

            $('#frmrfactura')
                .formValidation('enableFieldValidators', 'id_colonia', false)
                .formValidation('enableFieldValidators', 'txtOtraCol', true);
            $("#id_colonia").borrar();

        } else {

            //oculto
            $("#datOtraColonia").hide();
            $('#frmrfactura')
                .formValidation('enableFieldValidators', 'id_colonia', true)
                .formValidation('enableFieldValidators', 'txtOtraCol', false);
            $("#txtOtraCol").borrar();

        }

    });

    $("#rfc").change(function() {

        if ($("#rfc").val() == "") {
            validRFC = false;
            return false;
        }

        dataSend = {};
        dataSend.opt = "existRFC";
        dataSend.rfc = $("#rfc").val();
        processData();

    });

    function resetrfactura() {

        $("#check_colonia").prop("checked", false);
        $("#datOtraColonia").hide();
        $("#dvregfactura").hide();
			
        $("#tipo_persona").borrar();
        $("#rfc").borrar();
        $("#nombre_fisc").borrar();
        $("#fnombre").borrar();
        $("#fap_paterno").borrar();
        $("#fap_materno").borrar();
        $("#calle").borrar();
        $("#num_ext").borrar();
        $("#num_int").borrar();
        $("#id_cp").borrar();
        $("#id_colonia").borrar();
        $("#nom_edo").borrar();
        $("#nom_ciudad").borrar();
        $("#nom_municipio").borrar();
        $("#txtOtraCol").borrar();

        $("#frmrfactura").data('formValidation').resetForm();
    }

    $('#frmrfactura').formValidation({
            framework: 'bootstrap',
            message: 'Valor no es valido',
            //excluded: ':disabled',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {


                tipo_persona: {
                    validators: {
                        notEmpty: {
                            message: 'Debe seleccionar el tipo de persona'
                        }
                    }
                },

                rfc: {
                    enabled: false,
                    validators: {
                        notEmpty: {
                            message: 'Debe indicar un RFC <br>'
                        },
                        stringLength: {
                            min: 12,
                            max: 13,
                            message: 'El RFC se debe de componer de 13 carácteres para persona física y de 12 para persona moral <br> '
                        },
                        regexp: {
                            regexp: /^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/,
                            message: 'El RFC no es valido <br>'
                        },
                        callback: {
                            message: 'No usar un RFC registrado',
                            callback: function(value, validator, $field) {
                                    console.log("fun:" +validRFC);
                                    return validRFC;      
                            }
                        }
                        
                    }
                },
                nombre_fisc: {
                    enabled: false,
                    validators: {
                        notEmpty: {
                            message: 'Ingrese Raz&oacute;n Social'
                        }
                    }
                },

                fnombre: {
                    enabled: false,
                    validators: {
                        notEmpty: {
                            message: 'Ingrese Nombre'
                        }
                    }
                },

                fap_paterno: {
                    enabled: false,
                    validators: {
                        notEmpty: {
                            message: 'Ingrese Apellido Paterno'
                        }
                    }
                },

                calle: {
                    enabled: false,
                    validators: {
                        notEmpty: {
                            message: 'Ingrese Calle'
                        }
                    }
                },

                num_ext: {
                    enabled: false,
                    validators: {
                        notEmpty: {
                            message: 'Ingrese Número Ext'
                        }
                    }
                },

                id_cp: {
                    enabled: false,
                    validators: {
                        notEmpty: {
                            message: 'Ingrese C&oacute;digo Postal'
                        }
                    }
                },

                id_colonia: {
                    enabled: false,
                    validators: {
                        notEmpty: {
                            message: 'Debe seleccionar una colonia'
                        }
                    }
                },

                txtOtraCol: {
                    enabled: false,
                    validators: {
                        notEmpty: {
                            message: 'Ingrese su colonia'
                        }
                    }
                }


            }
        })
        .on('success.form.fv', function(e) {
            agregar_solicitud();
        })
        .on('err.form.fv', function(e) {

		msg = "Debe de llenar todos los campos requeridos";
		//console.log(data);
		dialog.alert(msg, function(dialogWin) {
            dialogWin.close();			
        });
		
	})
	.on('err.field.fv', function(e) {

		console.log(e);

	});


    function agregar_solicitud() {
        var msg;
		
		opt = "addSolUsu";
        dataSend = new Array();
		
		dataSend = $("#frmrfactura").serializeArray();
        dataSend.opt = opt;
        dataSend.push({ "name": "opt", "value": opt });
        dataSend.push({ "name": "conceptos", "value": JSON.stringify(conceptos) });
		dataSend.push({ "name": "ptl_ptl", "value": $("#ptl_ptl").val() });
		dataSend.push({ "name": "chFactura", "value": $("#chFactura").is(':checked') });
		dataSend.push({ "name": "dvDataRFC", "value": $("#dvDataRFC").val() });
		dataSend.push({ "name": "addRFC", "value": $("#addRFC").is(':checked') });
		
        msg = "¿Está seguro que desea registrar la solicitud?";
        dialog.confirm(msg, function(dialogWin) {
            dialogWin.close();
            processData();
        });

    }


    $(document).on('change', '#id_colonia', function() {

        $("#check_colonia").prop("checked", false); 
        
		$("#datOtraColonia").hide();
		$('#frmrfactura')
			.formValidation('enableFieldValidators', 'id_colonia', true)
			.formValidation('enableFieldValidators', 'txtOtraCol', false);
		$("#txtOtraCol").borrar();
    });


    $("#btnfichad").click(function() {

        var data;
        data = "opt=getPDF&folio_sol=" + $("#folio_sol").val();

        $.download("./processSolusr.php", data, "POST");


    });


    $("#btnNewSol").click(function() {

        window.location = "../solicitud_usr/index.php";

    });


    $("#id_cp").change(function() {

        dataSend = {};
        dataSend.opt = "getColonia";
        dataSend.id_cp = $("#id_cp").val();
        processData();


    });

	//procesamiento

    function processData() {

        $.ajax({
            type: "POST",
            url: 'processSolusr.php',
            data: dataSend,
            dataType: "json",
            timeout: timeout,
            beforeSend: function() {
				dialog.show();
            },
            success: function(json) {

                dialog.close();

                if (json.error || json.error === undefined) {
					dialog.alert(json.msg);
					console.log(json.debug);
					return false;
                }

                switch (dataSend.opt) {
                    case "getIni":

                        var x, y, z;
                        
						lstConceptos = json.data.lstConceptos;
                        
                        $.each(json.data.grupo_conceptos, function(id, val) {
							x = "<optgroup label='" + val.nom_area + "' >";
							
							$("#lst_conceptos").append(x);
							
							$.each(val.conceptos, function(id, v) {
								x = "<option value='" + v.id_concepto_pago + "' >" + v.id_concepto_pago + " - " + v.nom_concepto_pago + "</option>";
								$("#lst_conceptos").append(x);
							});
							
							x = "</optgroup>";
							
							$("#lst_conceptos").append(x);                 
                        });
                        
						$.each(json.data.planteles, function(id, val) {
                            x = "<option value='" + val.ptl + "' >" + val.ptl + " - " + val.nombre + "</option>";
                            $("#ptl_ptl").append(x);
                        });
                        
						if (json.data.rfc != '') {
						
							$("#SiRFC").show();
						
                            $.each(json.data.rfc, function(id, val) {
								y = "<option value='" + val.rfc + "'>" + val.rfc + "</option>";
								$('#dvDataRFC').append(y);
                            });

                        } else {
                            $("#NoRFC").show();
                        }
                        
						$(".chosen-select").select2();
                        
						iva = json.data.iva / 100;
                        smdf = json.data.smdf;
                        
                        break;

                    case "getMontos":

						addRowTable(json.montos);

                        break;

                    case "addSolUsu":

                        $("#dvGrid").hide();
                        $("#dvConfirm").show();

                        $("#folio_sol").html(json.folio_sol);
                        $("#monto_tot_conc").html(json.monto_tot_conc);

                        break;

                    case "getColonia":

                        $("#id_colonia").empty();
                        $("#id_colonia").append("<option value=''>Seleccione...</option>");

                        $.each(json.colonias, function(id, col) {
                            $("#id_edo").val(col.id_edo);
                            $("#nom_edo").val(col.nom_edo);
                            $("#id_ciudad").val(col.id_ciudad);
                            $("#nom_ciudad").val(col.nom_ciudad);
                            $("#id_municipio").val(col.id_municipio);
                            $("#nom_municipio").val(col.nom_municipio);
                            $("#id_colonia").append("<option value='" + col.id_colonia + "'>" + col.nom_colonia + "</option>");
                        });
                        console.log(json);

                        break;

                    case "validRFC":

                        validRFC = !json.exist;
                        console.log("proc:" + validRFC);

                        $('#frmSolicitante').formValidation('revalidateField', 'rfc');

                        break;
						
					case "existRFC":

                        validRFC = !json.exist;
                        console.log("proc:" + validRFC);

                        $('#frmrfactura').formValidation('revalidateField', 'rfc');

                        break;

                }


                return false;
            },
            error: function(jqXHR, textStatus, errorThrown) {
                //console.log(textStatus, errorThrown);
                dialog.close();
                msg = "";
                if (textStatus == "timeout") {
                    msg = "tiempo agotado";
                } else {

                    msg = "Error en la conexion";
                }
                dialog.alert(msg)
                return false;
            }
        });
    }

    dataSend.opt = "getIni";
    processData();

});
