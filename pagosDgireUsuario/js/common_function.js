var dialog={};

dialog.alert = function (msg){
	 BootstrapDialog.show({
                type: BootstrapDialog.TYPE_DANGER,
                title: 'Alerta',
                message: msg,
                buttons: [{
                    label: 'Aceptar'
                    	,cssClass: 'btn btn-outline btn-danger'
					,action: function(thisDialog){ thisDialog.close();}
                }]
            });     
			
//	BootstrapDialog.alert(msg);
};


dialog.show = function (msg){

	if(msg === undefined){
		msg='<div style="text-align:center">Procesando solicitud...</div>';
	}
	else{
		msg = '<div style="text-align:center">'+msg+'</div>';
	}

	//try {
	dialog.b = new BootstrapDialog({ message: msg, size_normal: "10px",closable: false });
	
    dialog.b.realize();
    dialog.b.getModalHeader().hide();
	dialog.b.getModalFooter().hide();
    dialog.b.getModalBody().css('background-color', '#337ab7');
    dialog.b.getModalBody().css('color', '#fff');
	dialog.b.open();
	//}
//catch(err) {

//}
	//return false;
};

dialog.close = function (){
	dialog.b.close();
};

function aux_dialog_close(dialogWin){ dialogWin.close();}

dialog.message = function(msg, aceptFunction){
	BootstrapDialog.show({
		title: "Información"
		,message: msg
        ,buttons: [{
            	label: 'aceptar'
              	,cssClass: 'btn btn-outline btn-primary'
              	//,action: aceptFunction === undefined ? aux_dialog_close : aceptFunction
                ,action:  function(dialog) {
                            aceptFunction === undefined ? aux_dialog_close : aceptFunction
                            location.reload();
                        } 

                
          	}]

    });
}

dialog.confirm = function(msg, aceptFunction, closeFunction){
	BootstrapDialog.show({
		title: "Confirmar"
		,message: msg
        ,buttons: [{
            	label: 'aceptar'
              	,cssClass: 'btn btn-outline btn-primary'
              	,action: aceptFunction
          	}, {
              	label: 'Cancelar'
				,cssClass: 'btn btn-outline btn-danger'
				,action: closeFunction === undefined ? aux_dialog_close : closeFunction
        }]
    });
}


var opLanguage = {
     "lengthMenu": "Mostrar _MENU_ registros por página"
     ,"zeroRecords": "Nothing found - sorry"
     ,"info": "Página _PAGE_ de _PAGES_ total _TOTAL_"
     ,"infoEmpty": "Información no disponible"
     ,"infoFiltered": "(filtered from _MAX_ total records)"
	 ,"paginate": {
        "first":      "Primero"
        ,"last":       "Último"
        ,"next":       "Siguiente"
        ,"previous":   "Anterior"
    	}
};

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
