$(function() {
	
	var dataSend = {}
		,timeout = 100000
		,solicitudes
		,id_solicitante = ""
		,folio_sol = ""
		,cve_edo_pago=""
		,fnombre=""
		,opt
		,lstConceptos
		,clave = 0
		,cantidad = 0
		,num_ext = 0
		,num_int = 0
		,id_cp = 0
		,conceptos=[]
		,xc=0
		,validRFC = false
		,id_concepto_pago
		,concepto
		,pesos
		,num_smdf
		,smdf
		,if_iva
		,precio_unitario
		,costo_variable
		,conceptos_solicitud = []
		,optionx
		,item_x
		,total_letras = 3
		;



	$("#clave").mask("9999");
	$("#cantidad").mask("9999");
	$("#id_cp").mask("99999");
	$('#precio_unitario').mask('0000');
	$("#folio_sol").mask("99999999999");
	

	function clearDataFacturacion(){
		
		$("#correo_usario").borrar();
		$("#frmfactura").reset();
	}
	
	function setIni(json){
		
		var item
			,x
			,y
			,z
			;
		
		lstConceptos = json.data.lstConceptos;
		
		$.each(json.estados, function(id, val){
			
			item = "<option value='"+val.cve_edo_pago+"'>"+val.nom_edo_pago+"</option>";
			$("#cve_edo_pago_s").append(item);
			
		});
		
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
		
		
		 
		//$('.chosen-select').chosen();

		iva = json.data.iva/100;
		
		smdf = json.data.smdf;
		//console.log(json.data);
		//console.log(smdf);

		$(".chosen-select").select2();
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

        var stotal = 0.00
			,ivat = 0.00
			,mtotal = 0.00
			,numero
			;

        $.each(conceptos, function(index, x_concepto) {
            mtotal += parseFloat(x_concepto.iva_total) + parseFloat(x_concepto.importe_sin_iva);
            ivat += parseFloat(x_concepto.iva_total);
            stotal += parseFloat(x_concepto.importe_sin_iva);
        });

        $("#stotal").html('$' + stotal.toLocaleString(undefined, { minimumFractionDigits: 2 }));
        $("#ivat").html('$' + ivat.toLocaleString(undefined, { minimumFractionDigits: 2 }));
        $("#mtotal").html('$' + mtotal.toLocaleString(undefined, { minimumFractionDigits: 2 }));

    }
	
	
	
	function delConcepto(xClave) {
        var xConceptos = [];

        $.each(conceptos, function(id, d) {
            if (d.clave != xClave) {
                xConceptos.push(d);
            }
        });
        conceptos = xConceptos;
    }
	
	
	
	
	 function clearForm() {

        $("#clave").borrar();
        $("#lst_conceptos").borrar();
        $("#cantidad").borrar();
        $("#precio_unitario").borrar();
        $("#tSolicitud").borrar();

    }
	
	
	
    function addRowTable(x_concepto) {

        var x = "";
        x += "<tr id='tr_" + clave + "'>";
        x += "<td class='text-center'>" + x_concepto.clave + "</td>";
        x += "<td>" + x_concepto.nom_concepto_pago + "</td>";
        x += "<td>" + '$' + parseFloat(x_concepto.importe).toFixed(2) + "</td>";
        x += "<td class='text-center'>" + x_concepto.cantidad + "</td>";
        x += "<td>" + '$' + x_concepto.importe_sin_iva + "</td>";
        x += "<td>" + '$' + x_concepto.iva_total + "</td>";
        x += "<td>" + '$' + x_concepto.monto_tot_conc + "</td>";
        x += "<td><button  clave='" + x_concepto.clave + "' class='btn btn-outline btn-danger center-block clsEliminaCon btn-xs'><span class='glyphicon glyphicon-trash'></span></button></td>";
        x += "</tr>";

        conceptos.push({
            "clave": x_concepto.clave,
            "cantidad": x_concepto.cantidad,
            "precio_variable": x_concepto.precio_variable,
            "iva_total": x_concepto.iva_total,
            "importe_sin_iva": x_concepto.importe_sin_iva
        });

        $("#tSolicitud").append(x);
        MuestraTotal();

    }

	
	function resetrfactura() {

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

        $("#frmfactura").data('formValidation').resetForm();
    }
	
	
		
	
	
	function enableRFC(val){
		$('#frmfactura').formValidation('enableFieldValidators', "rfc", val);
		$('#rfc').prop('disabled', !val);
		
		console.log("rfc: "+val);
	}
	
	function enabledRFCData(val){
		
		$(".vFac").each(function(){
			$('#frmfactura').formValidation('enableFieldValidators', $(this).attr("id"), val);
			$('#'+$(this).attr("id")).prop('disabled', !val);
		});
		
		$('#id_edo').prop('disabled', !val);
		$('#id_ciudad').prop('disabled', !val);
		$('#id_municipio').prop('disabled', !val);
		$('#id_colonia').prop('disabled', !val);
		$('#txtOtraCol').prop('disabled', !val);
		
	}
	
	
	jQuery.fn.borrar = function() {
        $(this).each(function() {
            $($(this)).val('');
        });
    };
	
	$(document).on('click', '.clsEliminaCon', function() {
		var xClave = $(this).attr("clave");
		$(this).parents("#tSolicitud tbody tr").remove();
		delConcepto(xClave);
		MuestraTotal();
	});

	
	$("#slcTipoUser").change(function(){

		var correo_usuario, slcTipoUser;
		
		$("#chFactura").prop("checked", false);
		
		enableRFC(false);
		enabledRFCData(false);
		
		$("#dvRFC").hide();
		$("#dvDataRFC").hide();
		$("#dvFactura").hide();
		$("#chDataRFC").prop("checked", false);
		correo_usuario = $("#correo_usuario").val();
		slcTipoUser = $("#slcTipoUser").val();
		
		$('#frmfactura').data('formValidation').resetForm(true);
		$("#correo_usuario").val(correo_usuario);
		$("#slcTipoUser").val(slcTipoUser);
		
		return true;
	});
	
	
	
/*
	$("#slcTipoUser").change(function(){
		
		var slc_opt = $(this).val();
		correo_usuario = $("#correo_usuario").val();
		
		$("#lstRFC").empty();
		$("#dvNombre").hide();
		$("#nombre_solicitante").val('');
		
		$('#frmfactura').data('formValidation').resetForm(true);
		$("#slcTipoUser").val(slc_opt);
		
		$("#dvSlcRFC").show();
		enableRFC(false);
		enabledRFCData(false);
		
		$("#dvRFC").hide();
		$("#chFactura").prop("checked", false);
		$("#dvFactura").hide();
		
		$("#correo_usuario").val(correo_usuario);
		$("#rfc").val("");
		$("#dvDataRFC").hide();
		$("#chDataRFC").prop("checked", false);
		
		if(slc_opt == "no_usr_reg"){
			$("#dvNombre").show();
		}
		
		return true;
	});
	*/
	
	 
	$("#btnSearchRFC").click(function(){
		
		var expreg = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		var x_correo = $("#correo_usuario").val();
		
		$("#dvDataRFC").empty();
		
		if(x_correo==""){
			dialog.alert("Debe ingresar un correo valido");
			return false;
		}
		
		x_correo = x_correo.replace(/\s/g, '');
		
		if(!expreg.test(x_correo)){
			dialog.alert("Debe ingresar un correo valido");
			return false;
		}
		
		dataSend = {};
		dataSend.opt = "searchRFCS";
		dataSend.correo_e = $("#correo_usuario").val();
		processData();
		

	});
	
	
	$("#chDataRFC").click(function(){
		
		$("#dvFactura").hide();
		
		if($(this).is(':checked')){
			$("#dvFactura").show();
			enabledRFCData(true);
			return true;
		}
		
		
		
		
		return true;

	});
	 

	$("#btnCreateSolicitud").click(function(){
		
		$("#dvGrid").hide();
		$("#dvFrmUsuario").show();
		$(".page-header").html("Registro de solicitud en caja");
		

	});
	
	
	$("#btnCloseUsuario").click(function(){
		
		window.location = "./";
			
	});
	
	/*
    $('#clave').keyup(function() {

        var longitud = $(this).val().length;
        var resto = total_letras - longitud;

		$("#lst_conceptos").borrar();
		$("#cantidad").borrar();
		$("#monto").borrar();
		$("#cantidad").prop("disabled", true);
		$("#monto").prop("disabled", true);
		$("#btnAddConcepto").prop("disabled", true);
		
		clave = $("#clave").val();
			
		if(longitud>3){
			dialog.alert("La clave del concepto no existe");
			return false;
		}
		
		if(longitud==3){
			
			if(lstConceptos[clave].id_concepto_pago === undefined){
				dialog.alert("La clave del concepto no existe");
				return false;
			}
			
			$("#lst_conceptos").val(clave);
			
			$("#precio_unitario").val(lstConceptos[clave].precio_variable);

			$("#cantidad").prop("disabled", false);
			$("#btnAddConcepto").prop("disabled", false);
	
			if(lstConceptos[clave].costo_variable==1){
				$("#precio_unitario").prop("disabled", false);
			}
			
			pesos = lstConceptos[clave].importe_pesos;
			num_smdf = lstConceptos[clave].importe_smdf;
	
			if (num_smdf != 0) {
				$("#precio_unitario").val(parseFloat(smdf * num_smdf).toFixed(2));
			} 
			else {
				$("#precio_unitario").val(parseFloat(pesos).toFixed(2));
			}
			
			$("#cantidad").focus();
			
		}
		
		

    });
*/

    $("#lst_conceptos").change(function() {
		
        clave = $("#lst_conceptos").val();
		$("#cantidad").prop("disabled", true);
		$("#cantidad").borrar();
		$("#precio_unitario").prop("disabled", true);
		$("#precio_unitario").borrar();

		if ((clave == null)||(clave == "")) {
            return false;
        }
		
     
		
		//$("#precio_unitario").val(lstConceptos[clave].precio_variable);

        $("#cantidad").prop("disabled", false);
        $("#btnAddConcepto").prop("disabled", false);

		if(lstConceptos[clave].costo_variable==1){
			$("#precio_unitario").prop("disabled", false);
		}
		
        pesos = lstConceptos[clave].importe_pesos;
        num_smdf = lstConceptos[clave].importe_smdf;

        if (num_smdf != 0) {
			console.log(num_smdf);
			console.log(smdf);
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
		
		
        if (conceptoRegistrado(clave)) {
			
            dialog.alert("La clave del concepto ya fue agregada");

            $("#clave").borrar();
			$("#lst_conceptos").val("").trigger("change");
			$("#cantidad").borrar();
            $("#precio_unitario").val("");

            $("#cantidad").prop("disabled", true);
            $("#precio_unitario").prop("disabled", true);
			$("#btnAddConcepto").prop("disabled", true);
			
            return false;
		}

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
		$("#lst_conceptos").borrar();
		$("#cantidad").borrar();
        $("#precio_unitario").val("");

        $("#cantidad").prop("disabled", true);
        $("#precio_unitario").prop("disabled", true);
		$("#btnAddConcepto").prop("disabled", true);
    			
    		dataSend = {};
        dataSend.opt = "getMontos";
        dataSend.clave = clave;
        dataSend.cantidad = cantidad;
        dataSend.precio_variable = precio_variable;
        processData();

        return true;

    });



    $("#btnRegistrar").click(function() {		
		
        if ($("#xbody tr").length == 0) {
			dialog.alert("Debe agregar un concepto para generar la solicitud");
			return false;
		}

		if(!$('#frmfactura').formValidation('validate')){
			dialog.alert("Debe de llenar todos los campos requeridos");
			return false;			
		}
		
        return false;

    });
	
	
	$("#btnFichad").click(function() {

        $.download("./processSolicitudes.php", "opt=getPDF&folio_sol=" + folio_sol, "POST");


    });
	

    $("#chFactura").click(function() {

		var correo_usuario, slcTipoUser;
		
		enableRFC(false);
		enabledRFCData(false);
		
		$("#dvRFC").hide();
		$("#dvDataRFC").hide();
		$("#dvFactura").hide();
		$("#chDataRFC").prop("checked", false);
		correo_usuario = $("#correo_usuario").val();
		slcTipoUser = $("#slcTipoUser").val();
		
		$('#frmfactura').data('formValidation').resetForm(true);
		$("#correo_usuario").val(correo_usuario);
		$("#slcTipoUser").val(slcTipoUser);
		
		if ($(this).is(':checked')) {		
			var expreg = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			var x_correo = $("#correo_usuario").val();
			
			if(x_correo==""){
				dialog.alert("Debe ingresar un correo valido");
				return false;
			}
			
			x_correo = x_correo.replace(/\s/g, '');
			
			if(!expreg.test(x_correo)){
				dialog.alert("Debe ingresar un correo valido");
				return false;
			}
			
			opt = "searchRFCS";
			dataSend = {};
			dataSend.opt = opt;
			dataSend.correo_usuario = $("#correo_usuario").val();
			dataSend.slcTipoUser = $("#slcTipoUser").val();
			processData();
		
			
			return true;
        } 
		
		
		
    });

	



    $('#tipo_persona').change(function() {

        $("#fnombre").val("");
        $("#fap_paterno").val("");
		$("#fap_materno").val("");
        $("#nombre_fisc").val("");

        if ($('#tipo_persona').val() == 1) {

            $("#cedulaMoral").hide();
            $("#cedulaPerfisica").show();
			$('#fmfactura').formValidation('enableFieldValidators', 'nombre_fisc', false)
			$('#frmfactura').formValidation('enableFieldValidators', 'fnombre', true);
			$('#frmfactura').formValidation('enableFieldValidators', 'fap_paterno', true);
            
        }

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
 
 
	$('#frmfactura').formValidation({
		framework: 'bootstrap'
		,message: 'Valor no es valido'
		,excluded: ':disabled'
        ,icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        }
        ,fields: {

			slcTipoUser: {
				validators: {
					notEmpty: {
						message: 'Debe seleccionar el tipo de usuario'
					}
				}
			}
			,nombre_solicitante:{
				validators: {
					stringLength: {
                        max: 80,
                        message: 'El nombre no debe ser mayor a 80 caracteres'
                    },
					regexp: {
						regexp: /^([A-Za-záéíóúüñÁÉÍÓÚÜÑ]{0,1}){1,}([\s]([A-Za-záéíóúüñÁÉÍÓÚÜÑ]{0,1}){1,})*$/
                        ,message: 'Debe indicar un nombre indicado'
                    }
				}
			}
			,correo_usuario: {
				validators: {
					notEmpty: {
						message: 'Debe seleccionar el tipo de usuario'
					}
					,emailAddress: {
						message: 'Debe de ser un correo valido'
					}
				}
			}
			,rfc:{
				validators: {
					notEmpty: {
                        message: 'Debe indicar un RFC'
                    },
					stringLength: {
                        min: 12,
                        max: 13,
                        message: 'El RFC se debe de componer de 12 caracteres'
                    },
					regexp: {
                        //regexp: '[A-Z,Ñ,&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?',
						regexp: /^([A-ZÑ\x26]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1]))((-)?([A-Z\d]{3}))?$/
                        ,message: 'El RFC no es valido'
                    }
				}
			}
            ,tipo_persona: {
				
                validators: {
                    notEmpty: {
                        message: 'Debe seleccionar el tipo de persona'
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
                        message: 'Debe indicar la colonia'
                    }
                }
            }

        }
    })
	.on('success.form.fv', function(e, data) {
        
		msg = "¿Esta seguro que desea agregar el Registro?";
		dialog.confirm(msg, function(dialogWin) {
            dialogWin.close();

			dataSend = {};
			dataSend = new Array();
			
			console.log($("#chFactura").val());
			
			
			var val_chFactura = 0;
			if ($("#chFactura").is(':checked')) {
				dataSend = $("#frmfactura").serializeArray();
				val_chFactura =  1;
			}
			else{
				val_chFactura =  0;
			}
			
	
			opt = "addSolUsu";
			dataSend.opt = opt;
			dataSend.push({ "name": "chFactura", "value": val_chFactura });
			dataSend.push({ "name": "chDataRFC", "value": $("#chDataRFC").is(':checked') ? 1 : 0});
			dataSend.push({ "name": "slcTipoUser", "value": $("#slcTipoUser").val() });
			dataSend.push({ "name": "correo_usuario", "value": $("#correo_usuario").val() });
			dataSend.push({ "name": "ptl_ptl", "value": $("#ptl_ptl").val() });
			dataSend.push({ "name": "nombre_solicitante", "value": $("#nombre_solicitante").val() });
			dataSend.push({ "name": "opt", "value": opt });
			dataSend.push({ "name": "conceptos", "value": JSON.stringify(conceptos) });
		
            processData();
			
        });
		
		
		
    })
	.on('err.form.fv', function(e, data) {

		msg = "Debe de llenar todos los campos requeridos";
		console.log(data);
		dialog.alert(msg, function(dialogWin) {
            dialogWin.close();			
        });
		
	})
	.on('err.field.fv', function(e, data) {

		//console.log("zzzzzzzzzzzzzzzz");
		console.log(data);
		console.log(e);
		
		
		
	});
	
	



    $("#id_colonia").change( function() {

        $("#check_colonia").prop("checked", false); 
		
        $("#datOtraColonia").hide();
        $('#frmfactura').formValidation('enableFieldValidators', 'id_colonia', true)
						.formValidation('enableFieldValidators', 'txtOtraCol', false);
		$("#txtOtraCol").borrar();
      
    });
	
	$("#check_colonia").click(function() {

        if ($(this).is(':checked')) {

            //muestro
            $("#datOtraColonia").show();
			$('#id_colonia').borrar();
            $('#frmfactura')
                .formValidation('enableFieldValidators', 'id_colonia', false)
                .formValidation('enableFieldValidators', 'txtOtraCol', true);
            $("#id_colonia").borrar();

        } else {

            //oculto
            $("#datOtraColonia").hide();
            $('#frmfactura')
                .formValidation('enableFieldValidators', 'id_colonia', true)
                .formValidation('enableFieldValidators', 'txtOtraCol', false);
            $("#txtOtraCol").borrar();

        }

    });




    $("#btnCerrar").click(function() {

        window.location = "./";

    });


    $("#id_cp").change(function() {

        dataSend = {};
        dataSend.opt = "getColonia";
        dataSend.id_cp = $("#id_cp").val();
        processData();


    });


	
	function processData() {

        $.ajax({
            type: "POST",
            url: 'processSolicitudes.php',
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
					alert("dwsd");
                    return false;
                }
				
				switch(dataSend.opt){
					case "getIni":
						setIni(json);
						
						break;
					
					case "searchRFCS":
					
						if(!json.exist){
							dialog.alert(json.msg);
							$("#chFactura").attr("checked", false);
							return false;
						}
						
						$("#lstRFC").empty();
						
						item_x = document.getElementById('lstRFC');
					
						$.each(json.datos.rfcs, function(id, xrfc){
							optionx = document.createElement('option');
							optionx.value = xrfc;
							item_x.appendChild(optionx);
						});
						
						$("#dvRFC").show();
						$("#dvDataRFC").show();
						$("#dvFactura").hide();
						
						$("#frmfactura").data('formValidation').resetForm();
						
						$("#chDataRFC").prop("checked", false);
						enableRFC(true);
						
						$("#chDataRFC").prop("checked", false);
						//$("#dvDataRFC").hide();
						
						if($("#slcTipoUser").val() == "no_usr_reg"){
							$("#dvDataRFC").show();
						}
					
						break;
						
					case "searchRFCPagos":
					
						item_x = document.getElementById('lstRFC');
					
						$.each(json.datos.rfcs, function(id, xrfc){
							optionx = document.createElement('option');
							optionx.value = xrfc;
							item_x.appendChild(optionx);
						});
					
						break;
						
					case "getMontos":

                        addRowTable(json.montos);
						$("#lst_conceptos").val("").trigger('change');

                        break;
					
					case "getDetalleSolicitud":

						setDetalle(json);
						
						break;
					
					case "saveComentario":
						
						dialog.message(json.msg);
						solicitudes.ajax.reload();
						break;
					
					case "addSolUsu":
						
						
						/*################################################*/
                        $("#dvFrmUsuario").hide();
                        $("#dvConfirm").show();
						
						folio_sol = json.folio_sol;

                        $("#folio_sol").html(json.folio_sol);
                        $("#monto_tot_conc").html("$"+json.monto_tot_conc);
						$("#referencia_ban").html(json.referencia_ban);
                        /*################################################*/
						
						break;

					 case "getColonia":

                        /*################################################*/
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
                        /*################################################*/

						break;
					
					case "validRFC":
						
						validRFC = !json.exist;
						console.log("proc:" +validRFC);
		
						$('#frmSolicitante').formValidation('revalidateField', 'rfc');
						
						break;
						
					case "getLstRFC":
					
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
	
	enableRFC(false);
	enabledRFCData(false);
	dataSend.opt="getIni";
	processData();
		    
});
