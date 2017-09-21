<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['salt'] = "B0no5"; //salt

$config['columnas_unidades_grid'] = array(
    'id' => array('name' => 'id_unidad_instituto', 'type' => "text", 'title' => 'Id'),
    'cve_unidad' => array('name' => 'clave_unidad', 'type' => "text", 'title' => 'Clave'),
    'nombre_unidad' => array('name' => 'unidad', 'type' => "text", 'title' => 'Unidad/UMAE'),
    'cve_presupuestal' => array('name' => 'clave_presupuestal', 'type' => "text", 'title' => 'Cve presupuestal'),
    'nivel_atencion' => array('name' => 'nivel_atencion', 'type' => "int", 'title' => 'Nivel de atención'),
    'latitud' => array('name' => 'latitud', 'type' => "float", 'title' => 'Latitud'),
    'longitud' => array('name' => 'longitud', 'type' => "float", 'title' => 'Longitud'),
    'clave_delegacional' => array('name' => 'clave_delegacional', 'type' => "text", 'title' => 'Cve delegacional'),
    'delegacion' => array('name' => 'delegacion', 'type' => "float", 'title' => 'Delegación'),
    'region' => array('name' => 'region', 'type' => "text", 'title' => 'Región'),
);

$config['filtros_unidades'] = array(
    'localizador_sede_id_delegacion_' => 'delegacion', 
    'localizador_sede_id_servicio_' => 'nivel_servicio', 
    'localizador_sede_id_nivel_' => "nivel"
);