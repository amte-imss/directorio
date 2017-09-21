<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author: Mr. Guag
 * @version: 1.0
 * @desc: Clase padre de los controladores del sistema
 * */
class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        //definir un estandar para los archivos de lenguaje
        $this->lang->load('interface', 'spanish');
        //$string_values = $this->lang->line('interface');    
        $usuario = $this->session->userdata('usuario');
        if (!is_null($usuario)) {
            $this->load->model('Menu_model', 'menu');
            $menu = $this->menu->get_menu_usuario($usuario['id_usuario']);
            //pr($menu);
            $this->template->setNav($menu);
            //pr($menu);
        }
    }

    /*
      Explicación $\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$
      $ = Inicio de cadena
      \S* = Cualquier set de caracteres
      (?=\S{8,}) = longitud de al menos 8 caracteres
      (?=\S*[a-z]) = asegurar que al menos existe una letra minúscula
      (?=\S*[A-Z]) = asegurar que al menos existe una letra mayúscula
      (?=\S*[\d]) = asegurar que al menos exista un número
      (?=\S*[\W]) = y asegurar que al menos tenga un caracter especial (+%#.,);
      $ = fin de la cadena */

    function valid_pass($candidate) {
        if (!preg_match_all('$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[-+%#.,;:\d])\S*$', $candidate, $condiciones)) {
            return FALSE;
        }
        return TRUE;
    }

    /** Explicación
     * ^                               - A partir de la linea/cadena
      (?=.{8})                       - busqueda incremental para asegurar que se tienen 8 caracteres
      (?=.*[A-Z])                    - ...para asegurar que tenemos al menos un caracter en mayuscula
      (?=.*[a-z])                    - ...para asegurar que tenemos al menos un caracter en minuscula
      (?=.*\d.*\d.*\d                - ...para asegurar que tenemos al menos tres digitos
      (?=.*[^a-zA-Z\d].*[^a-zA-Z\d].*[^a-zA-Z\d])
      - ...para asegurar que tiene al menos 3 caracteres especiales (caracteres diferentes a letras y numeros)
      [-+%#a-zA-Z\d]+                - combinacion de caracteres permitidos
      $                              - fin de la linea/cadena
     */
    public function password_strong($str) {
        //$exp = '/^(?=.{8})(?=.*[A-Z])(?=.*[a-z])(?=.*\d.*\d.*\d)(?=.*[^a-zA-Z\d].*[^a-zA-Z\d].*[^a-zA-Z\d])[-+%#a-zA-Z\d]+$/u';
        $exp = '/^(?=.{8})(?=.*[A-Z])(?=.*[a-z])(?=.*\d.*\d.*\d)(?=.*[^a-zA-Z\d].*[^a-zA-Z\d].*[^a-zA-Z\d])[-+%#a-zA-Z.,;:\d]+$/u';
        return (!preg_match($exp, $str)) ? FALSE : TRUE;
    }

    /**
     *
     * @param type $busqueda_especifica
     * @return int
     * @obtiene el array de los datos de session
     */
    protected function get_datos_sesion($busqueda_especifica = '*') {
        $data_usuario = $this->session->userdata('usuario');
//        $data_usuario = array(En_datos_sesion::ID_DOCENTE => 1, En_datos_sesion::MATRICULA => '311091488', En_datos_sesion::ID_UNIDAD_INSTITUTO => 1);
        if ($busqueda_especifica == '*') {
            return $data_usuario;
        } else {
            if (isset($data_usuario[$busqueda_especifica])) {
                return $data_usuario[$busqueda_especifica];
            }
        }
        return NULL; //No se encontro  una llave especifica o la session caduco
    }

    public function new_crud() {
        $db_driver = $this->db->platform();
        $model_name = 'Grocery_crud_model_' . $db_driver;
        $model_alias = 'm' . substr(md5(rand()), 0, rand(4, 15));
        unset($this->{$model_name});
        $this->load->library('grocery_CRUD');
        $crud = new Grocery_CRUD();
        if (file_exists(APPPATH . '/models/' . $model_name . '.php')) {
            $this->load->model('Grocery_crud_model');
            $this->load->model('Grocery_crud_generic_model');
            $this->load->model($model_name, $model_alias);
            $crud->basic_model = $this->{$model_alias};
        }
        $crud->set_theme('datatables');
        $crud->unset_print();
        return $crud;
    }

    /**
     * 
     * @param array $columnas Nombre de las columnas en el archivo
     * @param type $informacion Información o datos de la exportación
     * @param type $orden_columna Orden de las columnas
     * @param type $file_name Nombre del archivo exportado
     * @param type $delimiter delimitador del csv, por default será ","
     * @return type Descriptión documento a exportado ceon extención csv
     */
    protected function exportar_xls($columnas = null, $informacion = null, $column_unset = null, $orden_columna = null, $file_name = 'tmp_file_export_data', $delimiter = ',') {//$id_ciclo_evaluacion,$status,$filename
        header("Content-Encoding: UTF-8");
        header("Content-type: application/x-msexcel;charset=UTF-8");
        header('Content-Disposition: attachment; filename="' . $file_name . '.xls";');

        $f = fopen('php://output', 'w');

        fputs($f, $bom = ( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
        fputcsv($f, $columnas, $delimiter);

        //pr($info);
        if (!is_null($orden_columna)) {
            foreach ($informacion as $line) {

                $column = [];
                foreach ($orden_columna as $genera) {//Recorre las columnas extra que no se imprimen
                    if (isset($line[$genera])) {
                        $column[] = $line[$genera]; //Elimina colunas extra
                    } else {
                        $column[] = ' '; //Elimina colunas extra
                    }
                }
                fputcsv($f, $column, $delimiter);
            }
        } else {
            foreach ($informacion as $line) {
                if (!is_null($column_unset)) {

                    foreach ($column_unset as $val_unset) {//Recorre las columnas extra que no se imprimen
                        unset($line[$val_unset]);
                    }
                }
                fputcsv($f, $line, $delimiter);
            }
        }
        fclose($f);
    }

}
