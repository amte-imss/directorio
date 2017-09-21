$(document).ready(function() {
	$('#curso').attr('disabled', true);	
	$('#boton_curso').attr('disabled', true);	

	$('#anio').on('change',function(){
        var anio = $(this).val();

        if(anio){
            $('#curso').attr('disabled', false);
            get_lista_cursos(anio);
        }else{
            $('#curso').attr('disabled', true);
            $('#boton_curso').attr('disabled', true);
            $('#curso').empty();
        }
    });

    $('#curso').on('change', function() {
    	var curso = $(this).val();

    	if(curso){
    		$('#boton_curso').attr('disabled', false);
    	}else{
    		$('#boton_curso').attr('disabled', true);
    	}
    });

});


function get_lista_cursos(anio){
    $.ajax({
        url : site_url + '/carga_informacion/get_lista_cursos/' + anio,
        type: "GET",
        dataType: "json",
        success:function(data) {
            $('#curso').empty();
            $('#curso').append('<option value="">Selecciona un curso</option>');
            $.each(data, function(key, value) {
                $('#curso').append('<option value="'+ key +'">'+ value +'</option>');
            });
        }
    });
}

$(document).on('submit', '#form_cursos', function(event) {
	var anio = $('#anio').val();
	var curso = $('#curso').val();
	var destino = site_url + '/carga_informacion/grid_registro/' + anio + '/' + curso;
	//alert(site_url + '/carga_informacion/grid_registro/' + anio + '/' + curso);
	//window.location.replace(site_url + '/carga_informacion/grid_registro/' + anio + '/' + curso);
	//data_ajax(destino,'#form_cursos');
	$(this).attr('action', destino);
});