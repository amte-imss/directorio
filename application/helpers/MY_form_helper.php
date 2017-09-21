<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*

 * Extendiendo form_helper para sportar la creación de help_popover dom objects     

 */

if( !function_exists('html_help_popover')){
    
    /*
     *  html_help_popover: función para crear objetos que muestran cuadros de ayuda, principalmente en formularios.
     * 
     *  @param: $attributes  array   Atributos para la creción del popover
     *          $attributes['data-placement']   string      lugar donde colocar el popover ['left', 'right', 'top', 'bottom'] default: 'top'       
     *          $attributes['title']            string      titulo del popover (opcional)  
     *          $attributes['data-content']     string      Texto a mostrar en el popover (obligatorio)
     *  @param: $in_group                         bool        muestra la etiqueta del popover dentro de un span.input-group-addon para formularios (default true)
     */
    function html_help_popover($attributes, $in_group = true){
        if( isset($attributes['data-content']) && $attributes['data-content'] !== '' ){
            $attributes['data-placement'] = ( isset($attributes['data-placement']) ? $attributes['data-placement'] : 'top' );        
            $printObjetc = '<a tabindex="0" class="help-popover" role="" data-toggle="popover" data-trigger="focus"  data-content="'.$attributes['data-content'].'" data-placement="'.$attributes['data-placement'].'" ' ;          
            if(isset($attributes['title'])) $printObjetc.= ' title="'.$attributes['title'].'" ';
            $printObjetc .= ' >
                                <i class="glyphicon glyphicon-question-sign"></i>
                            </a>';
            $header = '';
            $footer = '';
            if($in_group){
                $header = '<span class="input-group-addon">';
                $footer = '</span>';
            }
            return $header.$printObjetc.$footer;
        }else{
            return "";
        }
    }
    
};

?>