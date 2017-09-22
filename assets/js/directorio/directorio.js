var is_carga = true;
$(document).ready(function () {
//	mostrar_loader();
    $('#exportar_datos').on('click', function () {
        document.location.href = site_url + '/directorio/exportar_datos/';
    });
    if (document.getElementById("nivel_reporte")) {
        $('#nivel_reporte').on('change', function () {
            console.log(this.value)
            is_carga = true;
            grid_directorios(this.value);
        });
        $("#nivel_reporte").trigger('change');
    } else {
        is_carga = true;
        grid_directorios("");
    }
});

var dba;
function grid_directorios(tipo_nivel) {

    var grid = $('#jsGridDirectorio').jsGrid({
        height: "600px",
        width: "100%",
        deleteConfirm: "¿Deseas eliminar este registro?",
        filtering: true,
        inserting: false,
        editing: true,
        sorting: true,
        selecting: false,
        paging: true,
        autoload: true,
        rowClick: null,
        pageSize: 5,
        pageButtonCount: 3,
        pagerFormat: "Paginas: {pageIndex} de {pageCount}    {first} {prev} {pages} {next} {last}   Total: {itemCount}",
        pagePrevText: "Anterior",
        pageNextText: "Siguiente",
        pageFirstText: "Primero",
        pageLastText: "Último",
        pageNavigatorNextText: "...",
        pageNavigatorPrevText: "...",
        noDataContent: "No se encontraron datos",
        invalidMessage: "",
        loadMessage: "Por favor espere",
        onItemUpdating: function (args) {
            grid._lastPrevItemUpdate = args.previousItem;
        },
        controller: {
            loadData: function (filter) {
                //console.log(filter);
                var d = $.Deferred();
                //var result = null; 

                $.ajax({
                    type: "GET",
                    url: site_url + "/directorio/get_registros_directorio/" + tipo_nivel,
                    data: filter,
                    dataType: "json"
                })
                        .done(function (result) {
                            d.resolve(result['data']);
                        });

                return d.promise();
            },
            updateItem: function (item) {
                console.log(item);
                var de = $.Deferred();
                var datos_nuevos_registro = {
                    id_registro_directorio: item['id_directorio'],
                    matricula: item['matricula'],
                    clave_delegacional: item['clave_delegacional'],
                    nombre: item['nombre'],
                    apellido_p: item['apellido_p'],
                    apellido_m: item['apellido_m'],
                    titulo: item['titulo'],
                    telefonos: item['telefonos'],
                    observaciones: item['observaciones'],
                }

                $.ajax({
                    type: "POST",
                    url: site_url + "/directorio/editar",
                    data: datos_nuevos_registro,
                    dataType: "json"
                })
                        .done(function (data) {
                            console.log(data);
//                            alert(data['message']);
                            apprise(data['message'], {verify: false}, function (btnClick) {
                                if (data['success'] === 0) {
                                    de.resolve(data['data']);
                                } else {
                                    de.resolve(grid._lastPrevItemUpdate);
                                }
                            });
                        })
                        .fail(function (jqXHR, error, errorThrown) {
                            console.log("error");
                            console.log(jqXHR);
                            console.log(error);
                            console.log(errorThrown);
                        });
                return de.promise();

            }
        },
        fields: [
            {name: "nombre_unidad", title: "Unidad", align: "center", type: "label", inserting: false, editing: false},
            {name: "clave_unidad", title: "Clave unidad", align: "center", type: "label", inserting: false, editing: false},
            {name: "nombre_nombramiento", title: "Nombramiento", align: "center", type: "label", inserting: false, editing: false},
            {name: "matricula", title: "Matrícula", type: "text", align: "center",
                validate: [
                    {
                        validator: "required",
                        message: function (value, item) {
                            return "El campo matrícula no puede ser vacío.";
                        }
                    },
                    {
                        validator: "maxLength",
                        message: function (value, item) {
                            return "El número máximo de caracteres para matrícula es 15.";
                        },
                        param: 15
                    }
                ]
            },
            {name: "nombre", title: "Nombre", align: "center", type: "text", inserting: false, editing: true},
            {name: "apellido_p", title: "Apellido paterno", align: "center", type: "text", inserting: false, editing: true},
            {name: "apellido_m", title: "Apellido materno", align: "center", type: "text", inserting: false, editing: true},
            {name: "telefonos", title: "Telefonos", align: "center", type: "text", inserting: false, editing: true},
            {name: "titulo", title: "Titulo", align: "center", type: "text", inserting: false, editing: true},
            {name: "observaciones", title: "Observaciones", align: "center", type: "text", inserting: false, editing: true},
//            {name: "id_directorio", title: "Identificador directorio", align: "center", type: "hidden", inserting: false, editing: false},
            {type: "control", editButton: true, deleteButton: true,
                searchModeButtonTooltip: "Cambiar a modo búsqueda", // tooltip of switching filtering/inserting button in inserting mode
                editButtonTooltip: "Editar", // tooltip of edit item button
                searchButtonTooltip: "Buscar", // tooltip of search button
                clearFilterButtonTooltip: "Limpiar filtros de búsqueda", // tooltip of clear filter button
                updateButtonTooltip: "Actualizar", // tooltip of update item button
                cancelEditButtonTooltip: "Cancelar", // tooltip of cancel editing button
            }
        ]
    });
}

function ditto_column_event() {
    var data_id = $(this).attr('data-id');
    var status = $(this).is(':checked');
    $("#jsGrid").jsGrid("fieldOption", data_id, "visible", status);
}

