<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Admnistracion
 *
 * @author chrigarc
 */
class Administracion extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library("session");
        $this->load->helper('url');
        $this->template->setTitle('Administración');
        $this->load->database();
        $this->load->library('grocery_CRUD');
    }

    public function index()
    {
        echo "Error 404";
    }

    /**
     * Grocery crud de grupos registrados
     * @author Christian Garcia
     * @version 8 marzo 2017
     */
    public function niveles_acceso()
    {
        try
        {
            $this->db->schema = 'sistema';
            //pr($this->db->list_tables()); //Muestra el listado de tablas pertenecientes al esquema seleccionado

            $crud = $this->new_crud();
            $crud->set_table('roles');


            $crud->columns('nombre', 'descripcion', 'orden', 'activo');
            $crud->fields('nombre', 'descripcion', 'orden', 'activo');
            
            $crud->change_field_type('activo', 'true_false', array(0 => 'Inactivo', 1 => 'Activo'));            

            $output = $crud->render();
            $main_content = $this->load->view('catalogo/gc_output', $output, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        } catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
        
    public function bitacora()
    {
        pr('En construccion ...');
        $params = array(
            'where' => array(
               'fecha >=' => date('Y-m-d') 
                
            ), 
            'limit' => 100           
        );
        $bitacora = $this->bitacora->get_registros($params);
        pr($bitacora);
    }

    public function delegaciones(){
        try{
            $this->db->schema = 'ods';
            $crud = $this->new_crud();
            $crud->set_table('delegaciones');
            $crud->set_subject('Delegaciones');
            $crud->set_primary_key('id_delegacion');

            $crud->columns('nombre','clave_delegacional','id_region','activo','configuraciones','latitud','longitud','grupo_delegacion', 'nombre_grupo_delegacion');
            $crud->fields('nombre','clave_delegacional');

            $crud->display_as('nombre','Nombre de la delegación');
            $crud->display_as('clave_delegacional','Clave de delegacion');
            
            $crud->unset_read();

            $output = $crud->render();

            $vista = $this->load->view('catalogo/gc_output.php', $output, true);
            $this->template->setMainContent($vista);
            $this->template->getTemplate();
        }catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

}
