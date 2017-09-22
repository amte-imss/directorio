<?php

/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Inicio
 *
 * @author chrigarc
 */
class Inicio extends MY_Controller {
    //put your code here

    /**
     * * Carga de clases para el acceso a base de datos y para la creación de elementos del formulario
     * * @access 		: public
     * * @modified 	: 
     */
    public function __construct() {
        parent::__construct();
        $this->load->library('form_complete');
        $this->load->library('form_validation');
        $this->load->library('seguridad');
        $this->load->helper(array('secureimage'));
        $this->load->model('Sesion_model', 'sesion');
        $this->load->model('Usuario_model', 'usuario');
    }

    public function index() {
        $output = array();
        $output["texts"] = $this->lang->line('formulario'); //textos del formulario        
        //
        if ($this->input->post()) {
            $post = $this->input->post(null, true);

            $this->config->load('form_validation'); //Cargar archivo con validaciones
            $validations = $this->config->item('login'); //Obtener validaciones de archivo general
            $this->form_validation->set_rules($validations);

            if ($this->form_validation->run() == TRUE) {
                $valido = $this->sesion->validar_usuario($post["usuario"], $post["password"]);
                $mensajes = $this->lang->line('mensajes');
                switch ($valido) {
                    case 1:
                        //redirect to home //load menu...etc etc
                        $params = array(
                            'where' => array('matricula' => $post['usuario']),
                            'select' => array(
                                'usuarios.id_usuario', 'usuarios.matricula', 'usuarios.nombre',
                                'UNI.id_unidad_instituto','UNI.clave_unidad clave_unidad', 'UNI.umae', 
                                'UNI.id_delegacion'
                            )
                        );
                        $usuario = $this->usuario->get_usuarios($params)[0];
                        $this->session->set_userdata('usuario', $usuario);
//                        pr($usuario);
                        redirect(site_url('directorio'));
                        break;
                    case 2:
                        $this->session->set_flashdata('flash_password', $mensajes[$valido]);
                        break;
                    case 3:
                        $this->session->set_flashdata('flash_usuario', $mensajes[$valido]);
                        break;
                    default :
                        break;
                }
            } else {
                pr(validation_errors());
                $data['errores'] = validation_errors();
            }
        }

        $usuario = $this->session->userdata('usuario');
        if (isset($usuario['id_usuario'])) {
	        redirect(site_url('directorio'));
        } else {
            //cargamos plantilla
//            pr(validation_errors());
            $view = $this->load->view("ci_template/login.tpl.php", $output);            
        }
    }

    public function dashboard() {
        redirect(site_url('directorio'));
    }

    public function captcha() {
        new_captcha();
    }

    public function cerrar_sesion() {
        $this->session->sess_destroy();
        redirect(site_url());
    }

}
