$(function() {
	$("#jsGrid").jsGrid({
        height: "300px",
        width: "1000px",

        deleteButton: false, 

        filtering: true,
        inserting: false,
        editing: false,
        sorting: true,
        
        paging: true,
        autoload: true,
        pageSize: 10,
        pageButtonCount: 3,
        pagerFormat: "Paginas: {first} {prev} {pages} {next} {last}    {pageIndex} de {pageCount}",
	    pagePrevText: "Anterior",
	    pageNextText: "Siguiente",
	    pageFirstText: "Primero",
	    pageLastText: "Último",
	    pageNavigatorNextText: "...",
	    pageNavigatorPrevText: "...",

        noDataContent: "No se encontraron datos",

	    //editValue

		rowDoubleClick: function (value) {
			var item = value['item'];
			//alert(JSON.stringify(item['id_ciclo_evaluacion']));
			ciclo_editar(item['id_ciclo_evaluacion']);
            $('#myModal').modal('show');
		},

        controller: {
            loadData: function(filter) {
                return $.ajax({
                    type: "GET",
                    url: site_url + "/ciclo_evaluacion/ciclos_evaluacion",
                    data: filter
                });
            }
        },
        fields: [
        	{ name: "id_ciclo_evaluacion" , title: "#" , type: "number",width:40, align: "center", visible:false},
            { name: "clave" , title: "Clave", type: "text", align: "center"},
        	{ name: "fecha_inicio_periodo" , title: "Fecha de inicio de periodo", type:"text", align:"center"},
            { name: "fecha_fin_periodo" , title: "Fecha de fin de periodo", type:"text", align: "center"},
            { name: "fecha_inicio_revision" , title: "Fecha de inicio de revisión", type:"text", align: "center"},
            { name: "fecha_fin_revision" , title: "Fecha de fin de revisión", type:"text", align: "center"},
        	{ name: "puntaje_minimo" , title: "Puntaje mínimo de actividad", type: "number", validate: {validator: "min", param:0}, align: "center"},
        	{ name: "porcentaje_minimo", title: "Porcentaje mínimo de actuación", type: "number", validate: {validator: "min", param:0}, align: "center" },
        	{ name: "activa", title: "Activo", type: "checkbox", width:50, align: "center"},
        	{ type: "control", deleteButton: false, editButton:false, width: 50,
                itemTemplate: function(value,item) {
                    return "<a href = '" + site_url + "/ciclo_evaluacion/get_ciclo_evaluacion/" + item['id_ciclo_evaluacion'] + "'> Ver </a>";
                }
            }
        ]
	});
});

$(document).ready(function() {
    $("#close_modal").on('click',function(event) {
        event.preventDefault();
        window.location.reload();
    });
});

function ciclo_nuevo(){
    var destino = site_url + '/ciclo_evaluacion/nuevo';
    data_ajax(destino, null, '#my_modal_content');
}

function ciclo_editar(id_ciclo_evaluacion){
	//var destino = site_url + '/ciclo_evaluacion/get_ciclo_evaluacion/' + id_ciclo_evaluacion +'/config';
    var destino = site_url + '/ciclo_evaluacion/editar/' + id_ciclo_evaluacion;
	data_ajax(destino,null,'#my_modal_content');
}