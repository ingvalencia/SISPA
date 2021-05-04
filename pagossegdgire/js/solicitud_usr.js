$(function() {


	var clave = 0;
	var cantidad = 0;
	var num_ext = 0;
	var num_int = 0;
	var id_cp = 0;

	var conceptos=[];
	var xc=0;


	$("#dvFactura").hide();


	//solo numeros
	$("#clave").mask("9999");
	$("#cantidad").mask("9999");
	$("#num_ext").mask("9999");
	$("#num_int").mask("9999");
	$("#id_cp").mask("99999");


	function delConcepto(cl){
		var xConceptos=[];

		//console.log("sss__:"+cl);
		//console.log("conceptos: "+conceptos);

		$.each(conceptos, function(id, d){
			//console.log(d);
			//console.log(d.clave);
			if(d.clave != cl ){
				xConceptos.push(d);
			}
		});

		console.log("qqq");
		console.log(xConceptos);
		console.log("sss_"+conceptos);
		conceptos = xConceptos;
	}


	//Funcion borrar
	jQuery.fn.borrar = function(){
			$(this).each (function() {
					$($(this)).val('');
					});
		}


	function clearForm(){

		$("#clave").borrar();
		$("#lst_conceptos").borrar();
		$("#cantidad").borrar();
		$("#precio_unitario").borrar();
		$("#tSolicitud").borrar();

	}


	//#################################################//
	/*Busca concepto por clave*/

	$("#clave").change(function(){
	var c;
		c = $("#clave").val();


		//console.log(c);
		$("#lst_conceptos").val(c);

		//console.log($("#lst_conceptos").val());

		if($("#lst_conceptos").val()==null){

			msg = "";
            msg = "La clave del concepto no existe";
			dialog.alert(msg)
			//$('#precio_unitario').html('<span style="font-size:12px; color:red;">La clave del concepto no existe</span>');
            $("#clave").borrar();
			return false;

		}

		$("#cantidad").prop("disabled", false);
		$("#cantidad").val();
		get_datos();

		//console.log(precio_unitario);
		$("#precio_unitario").html(precio_unitario);

	});
	//#################################################//

	//#################################################//
	/*Buscar concepto por lista*/

	$("#lst_conceptos").change(function(){
	var c;
		c = $("#lst_conceptos").val();


		//console.log(c);
		$("#clave").val(c);

		//console.log($("#clave").val());

		if($("#clave").val()==null){
			//alert("Error");
			return false;
		}

		$("#cantidad").prop("disabled", false);
		$("#cantidad").val();
		get_datos();

		//console.log(precio_unitario);
		$("#precio_unitario").html(precio_unitario);

	});
	//#################################################//


	//#################################################//
	/*Obtiene monto*/
	    var clave;
		var concepto;
		var pesos;
		var num_smdf;
		var if_iva;
		var precio_unitario;


	//obtiene y muestra el monto por la clave del concepto
	function get_datos(){

		clave = $('#lst_conceptos option:selected').val();
		concepto =  $('#lst_conceptos option:selected').attr('nombre');
		pesos =  $('#lst_conceptos option:selected').attr('importe_pesos');
		if_iva =  $('#lst_conceptos option:selected').attr('calcula_iva');
		num_smdf =  $('#lst_conceptos option:selected').attr('smdf');


		if(num_smdf!=0){

			precio_unitario= "$" +parseFloat(smdf*num_smdf);
		}
		else{

			precio_unitario= "$" +parseFloat(pesos);

		}


	}
	//#################################################//


	//#################################################//
	//obtiene y muestra el monto por la lista del concepto
	$("#lst_conceptos").change(function(){

		get_datos();
		$("#cantidad").prop("disabled", false);
		$("#cantidad").val("");
		//$("#precio_unitario").html(monto);

	});
	//#################################################//


	//#################################################//

	//Verifica el concepto
	function verificaConceptoValido(){
	var error=0;

		$('#dvGrid tbody tr').each(function() {

			if ($(this).find("td").eq(0).html()==$('#lst_conceptos').val()){
				error=1;
			}

		});

	return error;
	}

	//#################################################//

	//#################################################//
	//Añadir concepto

	$("#btnAddConcepto").click(function(){

		if( verificaConceptoValido()==0 ){

					var cantidad;
					var p_iva =0;
					var pp_iva =0;
					var importe;
					var importe_unitario;
					var precio_unitario;

				if(num_smdf!=0){
					precio_unitario = smdf*num_smdf;

				}
				else{
					precio_unitario=pesos;

				}

				cantidad= $("#cantidad").val();


				if(cantidad == 0){

					msg = "";
					msg = "La cantidad debe ser mayor a 0";
					dialog.alert(msg)
					$("#cantidad").borrar();
					return false;
				}


				monto = precio_unitario*cantidad;

				importe_unitario = precio_unitario;

				if(if_iva==1){
					p_iva = monto*iva;
					pp_iva= p_iva.toFixed(2);
					importe_unitario_p = monto + p_iva;
					importe_unitario = importe_unitario_p.toFixed(2);
				}

				importe = monto + p_iva;
				importe_p = importe.toFixed(2)

				var x ="";

				x+= "<tr id='tr_"+clave+"'>";
				x+= "<td>"+clave+"</td>";
				x+= "<td>"+concepto+"</td>";
				x+= "<td>"+importe_unitario+"</td>";
				x+= "<td>"+cantidad+"</td>";
				x+= "<td>"+precio_unitario+"</td>";
				x+= "<td>"+pp_iva+"</td>";
				x+= "<td>"+importe_p+"</td>";
				x+= "<td><button id='ss_"+clave+"' clave='"+clave+"' class='btn btn-outline btn-danger center-block clsEliminaCon btn-xs'><span class='glyphicon glyphicon-trash'></span></button></td>";
				x+= "</tr>";

				conceptos.push({"clave": clave, "cantidad": cantidad});
				console.log(conceptos);

				/////////////////////////
				//inserta el contenido final de los conceptos
				$("#tSolicitud").append(x);
				MuestraTotal();
				/////////////////////////


				//limpiar los campos

				$("#cantidad").borrar();
				$("#lst_conceptos").borrar();
				$("#clave").borrar();
				$("#precio_unitario").html("");

				//bloqueo campo cantidad
				$("#cantidad").prop("disabled", true);



			}else{
					msg = "";
					msg = "La clave del concepto ya fue agregada";
					dialog.alert(msg)

					//limpiar los campos
					$("#cantidad").borrar();
					$("#lst_conceptos").borrar();
					$("#clave").borrar();
					$("#precio_unitario").html("");

					//bloqueo campo cantidad
					$("#cantidad").prop("disabled", true);
					return false;
			}





	});
	//#################################################//


	//#################################################//
	//Boton elimina concepto
	$(document).on('click','.clsEliminaCon',function(){

		var xClave = $(this).find("td").eq(0).html();
		xClave = $(this).attr("clave");
		$(this).parents("#tSolicitud tbody tr").remove();
		delConcepto(xClave);
		MuestraTotal();
		$("#clave").borrar();

	});
	//#################################################//


	//#################################################//

	//Muestra el total de los conceptos

	function MuestraTotal(){

	    var stotal = 0.00;
		var ivat = 0.00;
		var mtotal = 0.00;
		var numero;
		//console.log(stotal);

		$('#tSolicitud tbody tr').each(function() {

			//console.log("monto: "+$(this).find("td").eq(6).html());

			if($(this).find("td").eq(6).html() != null){

				stotal +=parseFloat($(this).find("td").eq(4).html().replace(',',''));
				//console.log(stotal);
				ivat +=parseFloat($(this).find("td").eq(5).html().replace(',',''));
				mtotal +=parseFloat($(this).find("td").eq(6).html().replace(',',''));

			}

		});

		$("#stotal").html('$'+stotal.toFixed(2));
		$("#ivat").html('$'+ivat.toFixed(2));
		$("#mtotal").html('$'+mtotal.toFixed(2));
		//console.log(stotal);

	}
	//#################################################//


	//#################################################//

	//Boton registra solicitud
	$("#btnRegistrar").click(function(){

	
	
		console.log(1);
		//alert(1);

		if($("#dvGrid tbody tr").length > 0){
		
		console.log(2);
		//alert(2);
		/******************/
			getDataTable();
			opt = "addSolUsu";
			
			console.log(3);
			//alert(3);
			if($(chFactura).is(':checked')) {
			
				console.log(4);
				//alert(4);
				/*************************************/
					var div = $('#SiRFC');
					selrfc = document.getElementById("dvDataRFC").selectedIndex;

					console.log(5);
					//alert(5);
					if(div.is(':visible')){

						console.log(6);
						//alert(6);
						if( selrfc == null || selrfc == 0 ) {
	  						if( $('#reFactura').is(':checked')) {
										console.log(7);
										//alert(7);
										$('#frmrfactura').formValidation('validate');
								}else{
										console.log(8);
										//alert(8);
											msg = "Debe seleccionar un RFC con el que desee facturar o Marque la casilla si desea ingresar un nuevo RFC.";
											dialog.alert(msg)
											return false;
										}
						}else{
							console.log(9);
							//alert(9);
									$('#frmrfactura').formValidation('validate');
								}

							
					}else{
						console.log(10);
						//alert(10);
							if( $('#reFactura1').is(':checked') ) {
								console.log(11);
								//alert(11);
										$('#frmrfactura').formValidation('validate');
							}else{
								console.log(12);
								//alert(12);
										msg = "Debe marcar la casilla para agregar un RFC.";
										dialog.alert(msg)
										return false;
									}
					}
	
 				/*************************************/
console.log(13);
//alert(13);
			}
			else{
				console.log(14);
				//alert(14);
				agregar_solicitud();
			}

		/******************/

		}else{
			console.log(15);
			//alert(15);
				msg = "Debe agregar un concepto para generar la solicitud";
				dialog.alert(msg)
				return false;
			}
			
			console.log(16);
			//alert(16);
			
			return false;

	});
	//#################################################//
	
	//#################################################//

	var xclave;
	var xcantidad;

	function getDataTable(){

	xclave=[];
	xcantidad=[];
	i=0;
	$('#xbody tr').each(function() {
		xclave[i] = $(this).find("td").eq(0).html();
		xcantidad[i] = $(this).find("td").eq(3).html();
		i++;
		console.log($(this).find("td").eq(0).html());
		//console.log(xclave);
		//console.log(xcantidad);
	});


	}
	//#################################################//


	//*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*///
								/*rfactura.php*/

	//#################################################//
	//Marca casilla si desea facturar

	$("#chFactura").click(function(){

		if($(this).is(':checked')) {
			$("#dvFactura").show();
			$('#frmrfactura')
			//.formValidation('enableFieldValidators', 'selTipoP', true);

		}
		else{
			$("#dvFactura").hide();
			$("#dvDataRFC").borrar();
			$("#reFactura").prop("checked", false);
			$("#reFactura1").prop("checked", false);
			$("#check_colonia").prop("checked", false);
			$("#datOtraColonia").hide();
			//$("#check_colonia").prop("checked", false);
			$("#dvregfactura").hide();
			$('#frmrfactura')
			//.formValidation('enableFieldValidators', 'selTipoP', false);

			resetrfactura();

		}

	});
	
	//Marca casilla ingresar un nuevo RFC
	$("#reFactura").click(function(){

		if($(this).is(':checked')) {
			$("#dvregfactura").show();
			$("#dvDataRFC").borrar();
			$('#frmrfactura')
			.formValidation('enableFieldValidators', 'tipo_persona', true)
			.formValidation('enableFieldValidators', 'rfc', true)
			.formValidation('enableFieldValidators', 'nombre_fisc', true)
			.formValidation('enableFieldValidators', 'fnombre', true)
			.formValidation('enableFieldValidators', 'fap_paterno', true)
			.formValidation('enableFieldValidators', 'calle', true)
			.formValidation('enableFieldValidators', 'num_ext', true)
			.formValidation('enableFieldValidators', 'id_cp', true)
			.formValidation('enableFieldValidators', 'id_colonia', true)
			
			//.formValidation('enableFieldValidators', 'txtOtraCol', true);
			
		}
		else{
			$("#dvregfactura").hide();
			$('#frmrfactura')
			.formValidation('enableFieldValidators', 'tipo_persona', false)
			.formValidation('enableFieldValidators', 'rfc', false)
			.formValidation('enableFieldValidators', 'nombre_fisc', false)
			.formValidation('enableFieldValidators', 'fnombre', false)
			.formValidation('enableFieldValidators', 'fap_paterno', false)
			.formValidation('enableFieldValidators', 'calle', false)
			.formValidation('enableFieldValidators', 'num_ext', false)
			.formValidation('enableFieldValidators', 'id_cp', false)
			.formValidation('enableFieldValidators', 'id_colonia', false)
			.formValidation('enableFieldValidators', 'txtOtraCol', false);
			
			$("#check_colonia").prop("checked", false);
			$("#datOtraColonia").hide();
			
			
			resetrfactura();



		}

	});
	
	/**/
	$("#reFactura1").click(function(){

		if($(this).is(':checked')) {
			$("#dvregfactura").show();
			$("#dvDataRFC").borrar();
			$('#frmrfactura')
			.formValidation('enableFieldValidators', 'tipo_persona', true)
			.formValidation('enableFieldValidators', 'rfc', true)
			.formValidation('enableFieldValidators', 'nombre_fisc', true)
			.formValidation('enableFieldValidators', 'fnombre', true)
			.formValidation('enableFieldValidators', 'fap_paterno', true)
			.formValidation('enableFieldValidators', 'calle', true)
			.formValidation('enableFieldValidators', 'num_ext', true)
			.formValidation('enableFieldValidators', 'id_cp', true)
			.formValidation('enableFieldValidators', 'id_colonia', true)
			//.formValidation('enableFieldValidators', 'txtOtraCol', true);
			
		}
		else{
			$("#dvregfactura").hide();
			$('#frmrfactura')
			.formValidation('enableFieldValidators', 'tipo_persona', false)
			.formValidation('enableFieldValidators', 'rfc', false)
			.formValidation('enableFieldValidators', 'nombre_fisc', false)
			.formValidation('enableFieldValidators', 'fnombre', false)
			.formValidation('enableFieldValidators', 'fap_paterno', false)
			.formValidation('enableFieldValidators', 'calle', false)
			.formValidation('enableFieldValidators', 'num_ext', false)
			.formValidation('enableFieldValidators', 'id_cp', false)
			.formValidation('enableFieldValidators', 'id_colonia', false)
			.formValidation('enableFieldValidators', 'txtOtraCol', false);
			
			$("#check_colonia").prop("checked", false);
			$("#datOtraColonia").hide();
			
			
			resetrfactura();



		}

	});
	//#################################################//
	
	//#################################################//
	//limpia casilla nuevo RFC
	
	$(document).on('change', '#dvDataRFC', function(){

		   
			$("#reFactura").prop("checked", false);
			$("#reFactura1").prop("checked", false);
			$("#dvregfactura").hide();
			$('#frmrfactura')
			.formValidation('enableFieldValidators', 'tipo_persona', false)
			.formValidation('enableFieldValidators', 'rfc', false)
			.formValidation('enableFieldValidators', 'nombre_fisc', false)
			.formValidation('enableFieldValidators', 'fnombre', false)
			.formValidation('enableFieldValidators', 'fap_paterno', false)
			.formValidation('enableFieldValidators', 'calle', false)
			.formValidation('enableFieldValidators', 'num_ext', false)
			.formValidation('enableFieldValidators', 'id_cp', false)
			.formValidation('enableFieldValidators', 'id_colonia', false)
			.formValidation('enableFieldValidators', 'txtOtraCol', false);
			resetrfactura();
			
			
		
	});
	
	//#################################################//
	
	//#################################################//
	//selecciona tipo persona
	
	//$(document).ready( function (){
	 
		$('#tipo_persona').change(function(){

			$("#fnombre").val("");
			$("#fap_paterno").val("");
			$("#nombre_fisc").val("");

			/*MORAL*/
			if($('#tipo_persona').val()==1){
				//oculto
				$("#cedulaMoral").hide();
				
				//muestro
				$("#cedulaPerfisica").show();

				//
				$('#frmrfactura')
			 .formValidation('enableFieldValidators', 'nombre_fisc', false)
			 .formValidation('enableFieldValidators', 'fnombre', true)
			 .formValidation('enableFieldValidators', 'fap_paterno', true);
			
				
			}
			
			/*FISICA*/		
			if($('#tipo_persona').val()==2){
				//muestro
				$("#cedulaMoral").show();
				
				//oculto
				$("#cedulaPerfisica").hide();

				//
				$('#frmrfactura')
				.formValidation('enableFieldValidators', 'nombre_fisc', true)
				.formValidation('enableFieldValidators', 'fnombre', false)
				.formValidation('enableFieldValidators', 'fap_paterno', false);

			}
			
			
			
			if($('#tipo_persona').val()==''){
			
				$("#cedulaMoral").hide();
				$("#cedulaPerfisica").hide();
			}
		
		});
	//});
	
	//#################################################//
	
	//#################################################//
	//No colonia
	
	$("#check_colonia").click(function(){

		if($(this).is(':checked')) {
			
			//muestro
			$("#datOtraColonia").show();
			
			$('#frmrfactura')
			.formValidation('enableFieldValidators', 'id_colonia', false)
			.formValidation('enableFieldValidators', 'txtOtraCol', true);
			$("#id_colonia").borrar();
			
		}
		else{
			
			//oculto
			$("#datOtraColonia").hide();
			$('#frmrfactura')
			.formValidation('enableFieldValidators', 'id_colonia', true)
			.formValidation('enableFieldValidators', 'txtOtraCol', false);
			$("#txtOtraCol").borrar();
			
		}

	});
	
	
	
	//#################################################//
	

	//#################################################//
	//borra inputs del formulario

	function resetrfactura(){
		
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
	//#################################################//


	//#################################################//
	//Realiza validaciones

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
                validators: {
                    notEmpty: {
                        message: 'Ingrese su RFC'
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

	//alert("x1");
    	agregar_solicitud();
    })
	.on('err.form.fv', function(e) {
		
		//alert("sdfsdfsd");
		
	});

  //#################################################//


	//#################################################//

	function agregar_solicitud(){
		var msg;
	
		dataSend = {};
		dataSend = new Array();
		
		if($("#chFactura").is(':checked')) {
			dataSend = $("#frmrfactura").serializeArray();
		}
			
		dataSend.opt = opt;
		dataSend.push({"name" : "opt", "value" : opt });
		dataSend.push({"name" : "cantidad", "value" : xcantidad });
		dataSend.push({"name" : "clave", "value" : xclave });
		
		msg = "¿Esta seguro que desea agregar el Registro?";
		
		dataSend.opt = "addSolUsu";
		dataSend.push({"name" : "opt", "value" : "addSolUsu" });
		
		/********/
		
		if($("#chFactura").is(':checked')) {
			dataSend.push({"name" : "chFactura", "value" :  1});
			if($("#reFactura").is(':checked')) {
				dataSend.push({"name" : "reFactura", "value" :  1});
			}
			else{
				dataSend.push({"name" : "reFactura", "value" :  0});
			}
		}
		else{
			dataSend.push({"name" : "chFactura", "value" :  0});
		}
		
		/********/
		//alert("x3");
		//return false;
		//console.log(dataSend);
		dialog.confirm(msg, function(dialogWin){dialogWin.close(); 
		//alert("x4"); 
		processData(); });

	}
	
	//#################################################//


	//#################################################//
	
	//imprime ficha deposito
	
	$("#btnfichad").click(function(){
		
		var data;
		data = "opt=getPDF&folio_sol="+$("#folio_sol").val();
		
		$.download("./processSolusr.php", data, "POST");
			
		
	});
	 
	//#################################################//

	
	//#################################################//
	
	//Busca localidad por CP
	
	$("#id_cp").change(function(){
		
		dataSend = {};
		dataSend.opt = "getColonia";
		dataSend.id_cp = $("#id_cp").val();
		processData();
		
		
	});
	
	
	//#################################################//
	
	//#################################################//
	
	//obtiene y muestra el resultado del CP
	
	
	
	//#################################################//


	//*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*///

	//*****************************************************************************//
	//procesamiento

	function processData() {

	//alert("x0");
        $.ajax({
            type: "POST",
            url: 'processSolusr.php',
            data: dataSend,
            dataType: "json",
            timeout: timeout,
            beforeSend: function() {
                dialog.show();
				//alert("x5");
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

						var x;
						var y;
						var z;
						
						/*################################################*/
						$.each(json.data.conceptos, function(id, val){
							
							x = "<option value='"+val.id_concepto_pago+"' nombre='"+val.nom_concepto_pago+"' smdf='"+val.importe_smdf+"' importe_pesos='"+val.importe_pesos+"' calcula_iva='"+val.calcula_iva+"'>"+val.id_concepto_pago+" - "+val.nom_concepto_pago+"</option>";
							$("#lst_conceptos").append(x);

						});
						/*################################################*/
						
						/*################################################*/
						if(json.data.rfc != ''){

								$("#SiRFC").show();
								$.each(json.data.rfc, function(id, val){
								y = "<option value='"+val.rfc+"'>"+val.rfc+"</option>";
								$('#dvDataRFC').append(y);
								
								});
								
						}else
							{
								$("#NoRFC").show();
							}
						/*################################################*/
						
						/*################################################*/
						iva = json.data.iva/100;
						smdf = json.data.smdf;
						/*################################################*/
						

						break;

					case "addSolUsu":
						
						/*################################################*/
						$("#dvGrid").hide();
						$("#dvConfirm").show();
						$("#folio_sol").html(json.folio_sol);
						$("#monto_total").html(json.monto_total);
						/*################################################*/

						break;
					
					case "getColonia":
						
						/*################################################*/
						$("#id_colonia").empty();
						//$("#id_colonia").find("option").remove();
						//$('#mySelect').find('option')
						$("#id_colonia").append("<option value=''>Seleccione...</option>");
						
						$.each(json.colonias, function(id, col){
							$("#id_edo").val(col.id_edo);
							$("#nom_edo").val(col.nom_edo);
							$("#id_ciudad").val(col.id_ciudad);
							$("#nom_ciudad").val(col.nom_ciudad);
							$("#id_municipio").val(col.id_municipio);
							$("#nom_municipio").val(col.nom_municipio);
							
							$("#id_colonia").append("<option value='"+col.id_colonia+"'>"+col.nom_colonia+"</option>");
						});
						console.log(json);
						/*################################################*/
	
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
    //*****************************************************************************//


	dataSend.opt="getIni";
	processData();

});
