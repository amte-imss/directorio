<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author: Mr. Guag
 * @version: 1.0
 * @desc: Clase padre de los controladores del sistema

  function model_load_model($model_name)
  {
  $CI =& get_instance();
  $CI->load->model($model_name);
  return $CI->$model_name;
  }
 * */
class MY_Model extends CI_Model {

    private $val_id;
    private $pk;
    private $nombre;
    private $tabla;

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    //gets
    function getPK() {
        return $this->pk;
    }

    function getValId() {
        return $this->val_id;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getTabla() {
        return $this->tabla;
    }

    //sets
    function setPK($pk) {
        $this->pk = $pk;
    }

    function setValId($val_id) {
        $this->id = $val_id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setTabla($tabla) {
        $this->tabla = $tabla;
    }

    function toString() {
        $this->getNombre();
    }

}
