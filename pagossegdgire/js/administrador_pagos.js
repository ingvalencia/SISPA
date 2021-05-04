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
		,if_iva
		,precio_unitario
		,costo_variable
		,conceptos_solicitud = []
		;


	
	
	//solo numeros
	$("#clave").mask("9999");
	$("#cantidad").mask("9999");
	$("#num_ext").mask("9999");
	$("#num_int").mask("9999");
	$("#id_cp").mask("99999");
	$('#precio_unitario').mask('0000');
	$("#folio_sol").mask("99999999999");
	

	$("#btnSearch").click(function(){
		
		$("#dvDetalle").hide();
		createGrid();
		
	});
	
	$("#btnClear").click(function(){
		
		$("#folio_sol").val("");
		$("#cve_edo_pago").val("");
		$("#dvDetalle").hide();
		createGrid();
		
	});
	
	$("#cve_edo_pago").change(function(){
		$("#dvDetalle").hide();
		createGrid();
	});
	
	$("#btnExcel").click(function(){
		folio_sol = $("#folio_sol_s").val();
		fnombre = $("#fnombre_s").val();
		cve_edo_pago = $("#cve_edo_pago_s").val();
		$.download("excel.php", "folio_sol="+folio_sol+"&fnombre="+fnombre+"&cve_edo_pago="+cve_edo_pago, "POST");
	});
	
	function createGrid(){
		
		solicitudes.destroy();
		
		$('#solicitudes tbody').empty();
		
		folio_sol = $("#folio_sol_s").val();
		cve_edo_pago = $("#cve_edo_pago_s").val();
		fnombre = $("#fnombre_s").val();
		
		
		solicitudes = $('#solicitudes').DataTable( {
			"processing": true
			,"bFilter": false
			,"serverSide": true
			,"language": opLanguage
			,"scrollX": true
			,"order": [[ 0, "asc" ]]
			,"ajax": {
				"url": "processListSolicitudes.php"
				,"type": "POST"
				,"data": function ( d ) {
						d.folio_sol = folio_sol;
						d.cve_edo_pago = cve_edo_pago;
						d.fnombre = fnombre;
					}
			}
		} );

		solicitudes.column( 0 ).visible(false);
	}
	
	//$('#tooltipContainerForm').data('formValidation').resetForm(true);
	
	solicitudes = $('#solicitudes').DataTable( {
		"processing": true
		,"bFilter": false
        ,"serverSide": true
		,"language": opLanguage
		,"scrollX": true
		,"order": [[ 0, "asc" ]]
        ,"ajax": {
            "url": "processListSolicitudes.php"
            ,"type": "POST"
        }
    } );
	
	solicitudes.column( 0 ).visible(false);
	
	
	//createGrid();
	
	$('#solicitudes tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
	    }
        else {
            solicitudes.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');

			id_solicitante = solicitudes.row( this ).data()[0];
			folio_sol = solicitudes.row( this ).data()[1];
        }
    });
	
	
	$('#solicitudes tbody').dblclick(function() {
	
		dataSend = {};
		dataSend.opt="getDetalleSolicitud";
		dataSend.folio_sol = folio_sol;
		processData();		
		
	});
	

	
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

		iva = json.data.iva/100;
		smdf = json.data.smdf;
		
	}
	
	
	function confirmComentario(dWin){
		
		dWin.close();
	}
	
	function setDetalle(json){
		
		var msg;
		var comentario;
		
		comentario = json.data.solicitud.comentario;
		console.log(comentario);
		if(comentario=="null"){
			comentario="";
		}
		
		
		msg = "Folio solicitud: "+folio_sol;
		msg+= "<br><br><textarea class='form-control' cols='8' style='resize:vertical'>"+comentario+"</textarea>";
		BootstrapDialog.show({
			title: 'Agregar comentario',
            message: msg,
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: false,
            buttons: [{
                label: 'Guardar',
				cssClass: 'btn-success',
                action: function(dWin) {
					var xmsg = "¿Está seguro que desea guardar los cambios?";
                    dialog.confirm(xmsg, function(xdWin){
						var com = dWin.getModalBody().find('textarea').val();
						dWin.close();
						xdWin.close();
						dataSend = {};
						dataSend.id_solicitante = id_solicitante;
						dataSend.folio_sol = folio_sol;
						dataSend.opt = "saveComentario";
						dataSend.comentario = com;
						processData();
					});
                }
            }, {
                label: 'Cancelar',
				cssClass: 'btn-danger',
                action: function(dWin) {
                    dWin.close();
                }
            }]
        });
		
	}


	
	function clearDataFacturacion(){
		
		$("#correo_usario").borrar();
		$("#cslcOrigenRFC").empty();
		$("#dvfactura").reset();
	}
	
	
	$("#slcTipoUser").change(function(){
		
		var slc_opt = $(this).val();
		
		$("#dvSlcRFC").hide();
		$("#dvfactura").hide();
		
		$('#frmfactura').data('formValidation').resetForm(true);
		$(this).val(slc_opt);
		
		switch(slc_opt){
			case "usr_reg":
			
				$("#dvSlcRFC").show();
				enabledUserReg(true);
				$("#dvfactura").hide();
				enabledRFCData(false);
				
				break;
			
			case "no_usr_reg":
			
				$("#dvSlcRFC").hide()
				enabledUserReg(false);;
				$("#dvfactura").show();
				enabledRFCData(true);
				
				break;
				
		}
		
	});
	 
	$("#btnSearchRFC").click(function(){
		
		var expreg = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		var x_correo = $("#correo_usuario").val();
		
		$("#slcOrigenRFC").empty();
		
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
	 

	/*###############################################################*/
	$("#btnCreateSolicitud").click(function(){
		
		$("#dvGrid").hide();
		$("#dvFrmUsuario").show();
		$(".page-header").html("Registro de solicitud en caja");
		

	});
	
	
	$("#btnCloseUsuario").click(function(){
		
		$("#dvGrid").show();
		$("#dvFrmUsuario").hide();
		$(".page-header").html("Seguimiento administrador pagos");
		
		//limpiar los campos
		$("#cantidad").borrar();
		$("#lst_conceptos").borrar();
		$("#clave").borrar();
		$("#precio_unitario").html("");
		
		$("#tSolicitud  tbody tr ").detach();
		$("#stotal").html("");
		$("#ivat").html("");
		$("#mtotal").html("");
			
	});
	
	
	/*###############################################################*/
	
	/*###############################################################*/
	//Registra Solicitud
	
	
	//######################    Procesa conceptos  ###########################//
    
	//Elimina conceptos del arreglo
    function delConcepto(xClave) {
        var xConceptos = [];

        $.each(conceptos, function(id, d) {
            if (d.clave != xClave) {
                xConceptos.push(d);
            }
        });
        conceptos = xConceptos;
    }
	
	function conceptoRegistrado() {
        var x_flag = false;

        $('#tSolicitud tbody tr').each(function() {

            if ($(this).find("td").eq(0).html() == $('#clave').val()) {
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
    //#################################################//
	
	//#################################################//
    //Boton elimina concepto

	$(document).on('click', '.clsEliminaCon', function() {
		var xClave = $(this).attr("clave");
		$(this).parents("#tSolicitud tbody tr").remove();
		delConcepto(xClave);
		MuestraTotal();
	});
	
	
	
	
	
	
	
	
    //#################################################//


	//#################################################//
    //Funcion borrar
    jQuery.fn.borrar = function() {
        $(this).each(function() {
            $($(this)).val('');
        });
    };
    //#################################################//


	//#################################################//
    //Limpia Formulario
    function clearForm() {

        $("#clave").borrar();
        $("#lst_conceptos").borrar();
        $("#cantidad").borrar();
        $("#precio_unitario").borrar();
        $("#tSolicitud").borrar();

    }
    //#################################################//
	
	//#################################################//
    /*Busca concepto por clave*/

    var total_letras = 3;

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

    //#################################################//

	//#################################################//
    /*Buscar concepto por lista*/
	

    $("#lst_conceptos").change(function() {
		
        clave = $("#lst_conceptos").val();
		$("#cantidad").prop("disabled", true);
		$("#cantidad").borrar();
		$("#precio_unitario").prop("disabled", true);
		$("#precio_unitario").borrar();

		if (clave == null) {
            return false;
        }
		
        $("#clave").val(clave);
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
		

    });
    //#################################################//



	//#################################################//
    //Verifica el concepto
    


    $("#btnAddConcepto").click(function() {

		clave = $("#clave").val();
        cantidad = $("#cantidad").val();
        precio_variable = $("#precio_unitario").val();
		
		console.log(precio_variable);
		
        if (conceptoRegistrado()) {
			
            dialog.alert("La clave del concepto ya fue agregada");

            $("#clave").borrar();
			$("#lst_conceptos").borrar();
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


    //#################################################//
	
	//#################################################//
    //Agrega registro del concepto

    

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

	
	//#################################################//
	

	
	

	
	
    //Boton registra solicitud
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
		//dataSend = {};
		//dataSend.opt = "getPDF";
		//dataSend.folio_sol = folio_sol;
		//processData();
        
		

    });
	
    //#################################################//

	//#################################################//
    //Marca casilla si desea facturar

    $("#chFactura").click(function() {

        enabledTipoUser(false);
		enabledUserReg(false);
		enabledRFCData(false);
		$('#frmfactura').data('formValidation').resetForm(true);
		
		if ($(this).is(':checked')) {
            $("#dvFactura").show();
			enabledTipoUser(true);			
			return true;
        } 
		
		$("#dvFactura").hide();
		$("#dvSlcRFC").hide();
		$("#dvfactura").hide();

    });

	



    $('#tipo_persona').change(function() {

        $("#fnombre").val("");
        $("#fap_paterno").val("");
		$("#fap_materno").val("");
        $("#nombre_fisc").val("");

        if ($('#tipo_persona').val() == 1) {

            $("#cedulaMoral").hide();
            $("#cedulaPerfisica").show();
            //$('#fmfactura')
            //    .formValidation('enableFieldValidators', 'nombre_fisc', false)
            //    .formValidation('enableFieldValidators', 'fnombre', true)
            //    .formValidation('enableFieldValidators', 'fap_paterno', true);

        }

        if ($('#tipo_persona').val() == 2) {

			$("#cedulaMoral").show();
			$("#cedulaPerfisica").hide();
            //$('#frmfactura')
            //    .formValidation('enableFieldValidators', 'nombre_fisc', true)
            //    .formValidation('enableFieldValidators', 'fnombre', false)
            //    .formValidation('enableFieldValidators', 'fap_paterno', false);

        }

        if ($('#tipo_persona').val() == '') {
            $("#cedulaMoral").hide();
            $("#cedulaPerfisica").hide();
        }

    });
    //});

    //#################################################//

    //#################################################//
    //No colonia

    

    //################################################//

    $("#rfc").change(function() {

        if ($("#rfc").val() == "") {
            validRFC = false;
            return false;
        }

        dataSend = {};
        dataSend.opt = "validRFC";
        dataSend.rfc = $("#rfc").val();
        processData();

    });

    //###############################################//


	//#################################################//
    //borra inputs del formulario

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

        $("#dvfactura").data('formValidation').resetForm();
    }
    //#################################################//


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
			,slcOrigenRFC:{
				validators: {
					notEmpty: {
						message: 'Debe seleccionar un RFC'
					}
				}
			}
			,correo_rfc: {
				validators: {
					notEmpty: {
						message: 'Debe indicar un correo valido'
					}
					,emailAddress: {
						message: 'Debe de ser un correo valido'
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
			rfc: {
				enabled: false,
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
			
			if ($("#chFactura").is(':checked')) {
				dataSend = $("#frmfactura").serializeArray();
				dataSend.push({ "name": "chFactura", "value": 1 });
			}
			else{
				dataSend.push({ "name": "chFactura", "value": 0 });
			}
			
	
			opt = "addSolUsu";
			dataSend.opt = opt;
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
		
	});
	
	
	
	function enabledTipoUser(val){
		$('#frmfactura').formValidation('enableFieldValidators', "slcTipoUser", val);
		$('#slcTipoUser').prop('disabled', !val);
	}
	
	function enabledUserReg(val){
		$('#frmfactura').formValidation('enableFieldValidators', "correo_usuario", val);
		$('#frmfactura').formValidation('enableFieldValidators', "slcOrigenRFC", val);
		$('#correo_usuario').prop('disabled', !val);
		$('#slcOrigenRFC').prop('disabled', !val);
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
		$('#frmfactura').formValidation('enableFieldValidators', "id_edo", val);
		$('#frmfactura').formValidation('enableFieldValidators', "id_ciudad", val);
		$('#frmfactura').formValidation('enableFieldValidators', "id_municipio", val);
		
		
	}
	
	


    //#################################################//
    //limpia casilla: ¿No aparece su colonia?

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


    //#################################################//

	
	//#################################################//

    //boton cerrar

    $("#btnCerrar").click(function() {

        window.location = "../solicitud/index.php";

    });

    //#################################################//
	

	//#################################################//

    //Busca localidad por CP

    $("#id_cp").change(function() {

        dataSend = {};
        dataSend.opt = "getColonia";
        dataSend.id_cp = $("#id_cp").val();
        processData();


    });


    //#################################################//

	//#################################################//

    //BUSCAR RFC

    //Select2
    $.getScript('../bower_components/select2/select2.min.js', function() {

        /* dropdown and filter select */
        var select = $('#dvDataRFC').select2();

        /* Select2 plugin as tagpicker */
        $("#tagPicker").select2({
            closeOnSelect: false
        });

    }); //script   		 



    //#################################################//
	

	/*###############################################################*/


	
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
                    return false;
                }
				
				switch(dataSend.opt){
					case "getIni":
						setIni(json);
						
						break;
					
					case "searchRFCS":
					
						
						if(json.datos.count==0){
							dialog.message(json.msg);
							return false;
						}
						
						$("#dvLstRFC").show();
						$("#slcOrigenRFC").empty();
						$("#slcOrigenRFC").append("<option value=''>Seleccione el RFC</option>");
						
						$.each(json.datos.rfcs, function(id, xrfc){
							$("#slcOrigenRFC").append("<option value='"+xrfc+"'>"+xrfc+"</option>");
						})
						
						
						
						break;
						
					case "getMontos":

                        addRowTable(json.montos);

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
