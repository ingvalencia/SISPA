$(function() {
	
	var dataSend = {}
		,timeout = 100000
		,ct_conceptos_pago = null
		,id_concepto_pago = 0
		,id_area = 0
		,sid_concepto_pago = 0
		,sid_area = 0
		,nom_concepto_pago=""
		,isValid = true
		,iva = 0.16
		,smdf=73
		,opt;
    
	$("#btnUpdateGrupo").hide();
    $("#btnEnableGrupo").hide();
    $("#btnEnableGrupo").hide();
    $("#btnListaAlumnos").hide();
	$("#btnDisableGrupo").hide();
	
	$("#id_concepto_pago").mask("9999");
	$("#importe_smdf").mask("9999");
	$("#cuenta").mask("9999");
	$("#precio_unitario").mask("9999999.99");
	$("#importe_pesos").mask("9999999.99");
	$("#uimporte_smdf").mask("9999");
	$("#ucuenta").mask("9999");
	$("#uprecio_unitario").mask("9999999.99");
	
	$("#sid_concepto_pago").mask("9999");
	
	function setIni(xData){
		
		var item;
		$.each(xData.areas, function(id, val){
			
			item = "<option value='"+val.id_area+"'>"+val.nom_area+"</option>";
			$("#id_area").append(item);
			$("#uid_area").append(item);
			$("#sid_area").append(item);
			
		});
		
		$.each(xData.conceptos, function(id, val){
			item = "<option value='"+val.cve_tipo_con+"'>"+val.nombre+"</option>";
			$("#cve_tipo_con").append(item);
			$("#ucve_tipo_con").append(item);
		});
		
		smdf = xData.smdf;
		iva = xData.iva/100;
		
		$("#th_iva").html(xData.iva+"%");
		
		$("#lblIVA").html("IVA "+xData.iva+"%");
		$("#ulblIVA").html("IVA "+xData.iva+"%");
		
		
	}	
	
	function calcula_importe(pesos, num_smdf, calcula_iva){
		
		var precio_unitario = 0
		var precio_smdf = 0
		var monto_iva = 0;
		var importe = 0;
		
		//alert("pesos: "+pesos+"  num_smdf:"+num_smdf+"  clacula_iva:"+calcula_iva);
		if(num_smdf!=0){
			precio_unitario =parseFloat(num_smdf)*parseFloat(smdf);
			precio_smdf = precio_unitario;
			
		}
		else{
			precio_unitario = pesos;
		}
				
		if(calcula_iva==1){
			monto_iva = parseFloat(precio_unitario)*parseFloat(iva);
			monto_iva = monto_iva.toFixed(2);
		}
		
		
		importe= parseFloat(precio_unitario) + parseFloat(monto_iva);
		
		//alert("precio_unitario: "+precio_unitario+"  monto_iva: "+monto_iva);
		
		return {"precio_smdf": precio_smdf, "importe": importe, "monto_iva": monto_iva };
		
	}
	
	
	function calcula_precio_unitario(importe, num_smdf, calcula_iva){
		
		var precio_unitario = 0
		var precio_smdf = 0
		var monto_iva = 0;
		var xIva = iva;
			
		if(calcula_iva!=1){
			xIva = 0;
		}
	
		if(num_smdf!=0){
			precio_unitario =parseFloat(num_smdf)*parseFloat(smdf);
			precio_unitario = precio_unitario.toFixed(2);
			precio_smdf = precio_unitario;
		}
		else{
			precio_unitario = parseFloat(importe)/(1+xIva);
			precio_unitario = precio_unitario.toFixed(2);
		}
		
		monto_iva = parseFloat(precio_unitario)*parseFloat(xIva);
		monto_iva = monto_iva.toFixed(2);
		//if(monto_iva==0){ monto_iva = ""; }
		
		return {"precio_smdf": precio_smdf, "precio_unitario": precio_unitario, "monto_iva": monto_iva };
		
	}
	
	function setPreciosUpdate(){
		var montos;
		var pesos;
		var num_smdf;
		var calcula_iva;
		
		montos = calcula_importe($("#precio_unitario").val(), $("#importe_smdf").val(), $("#calcula_iva").val());
				
		$("#importe_pesos").val(montos.importe);
		$("#monto_iva").val(montos.monto_iva);
		$("#precio_smdf").val(montos.precio_smdf);
	}
	
	
	function setPreciosConcepto(){
		var montos;
		var pesos;
		var num_smdf;
		var calcula_iva;
		var importe;
		
		importe = $("#importe_pesos").val();
		num_smdf = $("#importe_smdf").val();
		calcula_iva = $("#calcula_iva").val();
		
		if(importe ==""){ importe = 0;}
		if(num_smdf ==""){ num_smdf = 0;}
		
		montos = calcula_precio_unitario(importe, num_smdf, calcula_iva);
		
		$("#precio_unitario").val(montos.precio_unitario);		
		$("#monto_iva").val(montos.monto_iva);
		$("#precio_smdf").val(montos.precio_smdf);
		
	}
	
	function resetFrmConcepto(){
		$("#id_concepto_pago").val("");
		$("#nom_concepto_pago").val("");
		$("#id_area").val("");
		$("#cve_tipo_con").val("");
		$("#importe_smdf").val("0");
		$("#precio_smdf").val("0");
		$("#precio_unitario").val("0");
		$("#monto_iva").val("0");
		$("#importe_pesos").val("0");
		$("#calcula_iva").val("0");
		$("#costo_variable").val("0");
		$("#cuenta").val("");
		$("#vigente").val("1");
		$("#frmConcepto").data('formValidation').resetForm();
	}

	
	
	
	function createGrid(){
		
		var area;
		var clave;
		
		if(ct_conceptos_pago != null){
			ct_conceptos_pago.destroy();
		}
		
		$('#ct_conceptos_pago tbody').empty();
		
		area = $("#sid_area").val();
		clave = $("#sid_concepto_pago").val();
		console.log(clave);
		
		ct_conceptos_pago = $('#ct_conceptos_pago').DataTable( {
			"processing": true
			,"bFilter": false
			,"serverSide": true
			,"language": opLanguage
			,"scrollX": true
			,"order": [[ 0, "asc" ]]
			,"ajax": {
				"url": "processListaCatalogos.php"
				,"type": "POST"
				,"data": function ( d ) {
					//d.id_concepto_pago = sid_concepto_pago;
					//d.id_area = sid_area;
					d.id_concepto_pago = clave;
					d.id_area = area;
				}
			}
		} );
		
		
	}
	
	
	$('#frmConcepto').formValidation({
		framework: 'bootstrap',
        message: 'Valor no es valido',
		excluded: ':disabled',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
		
            id_concepto_pago: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese clave'
                    },
					digits: {
                        message: 'Solo pueden ser números'
                    },
                    stringLength: {
                        min: 3,
                        max: 4,
                        message: 'Clave no mayor a 9999'
                    },
                    callback: {
                        message: 'Ya existe la clave',
                        callback: function(value, validator, $field) {
							console.log(Math.random()+ " => "+isValid);
							return !isValid;		
                        }
                    }
					
                }
            },
			nom_concepto_pago: {
                validators: {
                    notEmpty: {
                        message: 'Debe indicar el nombre del concepto'
                    },
                    stringLength: {
                        min: 3,
                        max: 210,
                        message: 'Debe de ser mayor a 10 '
                    }
                }
            },
			id_area: {
                validators: {
                    notEmpty: {
                        message: 'Debe seleccionar un área'
                    }
                }
            },
			cve_tipo_con: {
                validators: {
                    notEmpty: {
                        message: 'Debe seleccionar una clave'
                    }
                }
            },
			costo_variable: {
                validators: {
                    notEmpty: {
                        message: 'Selecciona una opción'
                    }
                }
            },
			precio_unitario: {
                validators: {
                    notEmpty: {
                        message: 'Debe de ser un monto'
                    },
					numeric: {
                        message: 'Solo pueden ser números'
						,decimalSeparator: '.'
                    }
                }
            },
			importe_pesos: {
                validators: {
                    notEmpty: {
                        message: 'Debe de ser un monto'
                    },
					numeric: {
                        message: 'Solo pueden ser números'
						,decimalSeparator: '.'
                    }
                }
            },
			importe_smdf: {
                validators: {
                    notEmpty: {
                        message: 'Número de salarios'
                    }
                }
            },
			calcula_iva: {
                validators: {
                    notEmpty: {
                        message: 'Selecciona una opción'
                    }
                }
            },
			cuenta: {
                validators: {
					digits: {
                        message: 'Solo números'
                    },
                    notEmpty: {
                        message: 'Indique la cuenta'
                    }
                }
            },
			vigente: {
                validators: {
                    notEmpty: {
                        message: 'Seleccione opción'
                    }
                }
            }
        }
    }).on('success.form.fv', function(e) {
	
		var msg;
		
		dataSend = {};
		dataSend = $("#frmConcepto").serializeArray();
		dataSend.opt = opt;
		dataSend.push({"name" : "opt", "value" : opt });
		dataSend.push({"name" : "precio_unitario", "value" : $("#precio_unitario").val() });
		dataSend.push({"name" : "monto_iva", "value" : $("#monto_iva").val() });
		
		if(opt == "addConcepto"){
			msg = "¿Esta seguro que desea agregar el nuevo concepto?";
		}
		else{
			msg = "¿Esta seguro que desea actualizar los datos del concepto <strong>"+id_concepto_pago+"</strong>?";
			dataSend.push({"name" : "id_concepto_pago", "value" : id_concepto_pago });
		}
		
		dialog.confirm(msg, function(dialogWin){dialogWin.close();  processData(); });
    });
	
	$("#importe_pesos").change(function(){ setPreciosConcepto(); });
	$("#importe_pesos").keyup(function(){ setPreciosConcepto(); });
	$("#precio_unitario").change(function(){ setPreciosConcepto(); });
	$("#importe_smdf").change(function(){ setPreciosConcepto(); });
	$("#importe_smdf").keyup(function(){ setPreciosConcepto(); });
	$("#calcula_iva").change(function(){ setPreciosConcepto(); });
	
	
	$("#btnSaveExcel").click(function(){
		
		var data;
		
		data = "opt=getExcel&id_concepto_pago="+$("#sid_concepto_pago").val();
		data+= "&id_area="+$("#sid_area").val();
		
		$.download("./processConceptosPago.php", data, "POST");
	});
	
	
	createGrid();

	$('#ct_conceptos_pago tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
	    }
        else {
            ct_conceptos_pago.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');

			id_concepto_pago = ct_conceptos_pago.row( this ).data()[0];
			nom_concepto_pago = ct_conceptos_pago.row( this ).data()[2];
			
        }
    });
	
	
	$('#ct_conceptos_pago tbody').dblclick(function() {
	
		dataSend = {};
		dataSend.opt="getConcepto";
		dataSend.id_concepto_pago = id_concepto_pago;
		processData();		
		
	});
	
	$("#btnAddConcepto").click(function(){
		resetFrmConcepto();
		$("#dvFrmConcepto").show();
		$("#btnUpdConcepto").hide();
		$("#btnSaveConcepto").show();
		$("#dvGrid").hide();
		$(".page-header").html("Agregar concepto");
		$("#id_concepto_pago").prop("disabled", false);
	});
	

	$("#btnSaveConcepto").click(function(){
		opt = "addConcepto";
		$('#frmConcepto').formValidation('validate');
	});
	
	$("#btnUpdConcepto").click(function(){
		opt = "updConcepto";
		$('#frmConcepto').formValidation('validate');
	});
	
	$("#btnCancelConcepto").click(function(){
		$("#dvFrmConcepto").hide();
		$("#dvGrid").show();
		$(".page-header").html("Administración de claves de conceptos de pago");
	});
	
	$("#btnSearch").click(function(){
		
		sid_concepto_pago = $("#sid_concepto_pago").val();
		sid_area = $("#sid_aera").val();
		createGrid();
		
	});
	
	$("#sid_area").change(function(){
		sid_concepto_pago = $("#sid_concepto_pago").val();
		sid_area = $("#sid_aera").val();
		createGrid();
		
	});
	
	
	
	
	$("#id_concepto_pago").change(function(){
		
		dataSend = {};
		dataSend.opt="validClave";
		dataSend.id_concepto_pago=$("#id_concepto_pago").val();
		processData();
		
		
	});

	
	function processData() {

        $.ajax({
            type: "POST",
            url: 'processConceptosPago.php',
            data: dataSend,
            dataType: "json",
            timeout: timeout,
            beforeSend: function() {
                dialog.show();
            },
            success: function(json) {

                dialog.close();

                if (json.error) {
                    dialog.alert(json.msg);
					console.log(json.debug);
                    return false;
                }
				
				switch(dataSend.opt){
					case "getIni":
						setIni(json.data);
						break;
					
					case "validClave":
						
						isValid = json.exist;
		
						$('#frmAddConcepto').formValidation('revalidateField', 'id_concepto_pago');
	
						break;
						
					case "addConcepto":
						dialog.message(json.msg);
						ct_conceptos_pago.ajax.reload();
						resetFrmConcepto();
						$("#dvAddConcepto").hide();
						$("#dvUpdConcepto").hide();
						$("#dvGrid").show();
						$(".page-header").html("Administración de claves de conceptos de pago");
						break;
						
					case "getConcepto":
						$.each(json.concepto, function(id, val){
							$("#"+id).val(val);
						});
						
						$("#id_concepto_pago").prop("disabled", true);
						setPreciosUpdate();
						
						$("#dvGrid").hide();
						$("#dvFrmConcepto").show();
						$(".page-header").html("Actualizar concepto");
						$("#btnUpdConcepto").show();
						$("#btnSaveConcepto").hide();
		
						break;
						
					case "updConcepto":
						dialog.message(json.msg);
						ct_conceptos_pago.ajax.reload();
						resetFrmConcepto();
						$("#dvGrid").show();
						$("#dvFrmConcepto").hide();
						$(".page-header").html("Administración de claves de conceptos de pago");
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
