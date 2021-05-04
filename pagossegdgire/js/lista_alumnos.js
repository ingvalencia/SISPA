var dataSend = {};
$(function() {
	
	var timeout = 100000;
	var id_alumno = 0;
    
	$("#btnUpdateAlumno").hide();
	
	$("#btnUpdateAlumno").click(function(){
		localStorage.setItem('actionAlumno', 'getAlumno');
		localStorage.setItem('id_alumno', id_alumno);	
		//alert(id_alumno);
		window.location="./alumno.php";	
	});
	
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

		    
});
