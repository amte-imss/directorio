<link href="<?php echo base_url('assets/third-party/jsgrid-1.5.3/dist/jsgrid.min.css'); ?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/third-party/jsgrid-1.5.3/dist/jsgrid-theme.min.css'); ?>" rel="stylesheet" />
<script src="<?php echo base_url(); ?>assets/third-party/jsgrid-1.5.3/dist/jsgrid.min.js"></script>

<style>
    .config-panel {
        padding: 10px;
        margin: 10px 0;
        background: #fcfcfc;
        border: 1px solid #e9e9e9;
        display: inline-block;
    }

    .config-panel label {
        margin-right: 10px;
    }
    #page-inner{
        min-height: 1250px !important;
    }
</style>

<?php
$nivel_accesso = 1;
echo js("rama_organica/listas.js");
echo js("directorio/directorio.js");
?>

<div id="page-inner">
    <div class="panel-heading">
        <h1 class="page-head-line">Directorios
        </h1>
        <div class="col-md-12 col-sm-12 ">
            <label><h4> Profesores almacenados</h4></label>
        </div>
    </div>
    <div id="form_filtro">
        <?php if ($mostrar_filtros) {
            ?>
            <br>
            <form class="form-inline">
                <div class="form-group tipo_actividad_class col-sm-4">
                    <i class="fa fa-question-circle sipimss-helper" data-help="tipo_actividad"></i>
                    <label for="tipo_nivel">Nivel </label>
                    <div class=".col-5">
                        <select name="nivel_reporte" id="nivel_reporte" class="form-control">
                            <option value=1>Delegación</option>
                            <option value=2>UMAE</option>
                        </select>
                    </div>
                </div>
            </form>
        <?php } ?>
    </div>

    <br>
    <br>
    <div class="col-sm-12 col-md-12 text-right">
        <button id="exportar_datos" name="exportar" type="button" class="btn btn-lg btnverde">
            Exportar a Excel
        </button>
    </div>
    <div  class="col-sm-12 col-md-12 text-right">
        <h4>
            <a href="#<?php // echo site_url('/reporte/exportar_datos_detalle_cursos_registros/');    ?>">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i></a> Totales de registros por curso
        </h4>
    </div>
    <div class="col-sm-12">        
        <div id="jsGridDirectorio"></div>
    </diV>

</div>
