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
class Directorio extends MY_Controller {
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
        $this->load->model('Directorio_model', 'dir');
         $this->load->model('Modulo_model', 'modulo');
    }

    public function index() {

        $datos_sesion = $this->get_datos_sesion();
        if ($datos_sesion) {//Valida que exista sesión
            $id_usuario = $datos_sesion[En_datos_sesion::ID_USUARIO];
//            pr($datos_sesion);
            //***** Valida tipo de usuario
            $this->load->library('LNiveles_acceso');
            $niveles = $this->modulo->get_niveles_acceso($id_usuario, 'usuario');
            $result['mostrar_filtros'] = FALSE;
            if ($this->lniveles_acceso->nivel_acceso_valido(array(LNiveles_acceso::Admin), $niveles)) {//Valida un nivel central o administrador
//            pr($niveles);
                $result['mostrar_filtros'] = TRUE; //Mostrar filtros de unidad
            }

            //***** Fin de validación de tipo de usuario
            //Cargar información de directorios
//        $this->load->model('Directorio_model', 'dir');
//        $result['info_directorios'] = $this->dir->get_datos_directorio();
//        pr($result);
            $main_content = $this->load->view('directorio/directorio.tpl.php', $result, true);
            $this->template->setMainContent($main_content);
            $this->template->getTemplate();
        }
    }

    public function get_registros_directorio($tipo_nivel = '', $delegacion = null) {
        $datos_sesion = $this->get_datos_sesion();
        if ($datos_sesion) {//Valida que exista sesión
            $id_usuario = $datos_sesion[En_datos_sesion::ID_USUARIO];
//            $clave_unidad = $this->get_datos_sesion(En_datos_sesion::CLAVE_UNIDAD);
            //***** Valida tipo de usuario
            $this->load->library('LNiveles_acceso');
           
            $niveles = $this->modulo->get_niveles_acceso($id_usuario, 'usuario');
            $valida_nivel_acceso = $this->lniveles_acceso->nivel_acceso_valido(array(LNiveles_acceso::Admin), $niveles);
//            pr($niveles);
//            pr($valida_nivel_acceso);
            if ($valida_nivel_acceso) {//Valida un nivel central o administrador
                switch ($tipo_nivel) {
                    case 1:
                        $filtros['u.grupo_tipo_unidad!='] = 'UMAE'; //Para el caso de tipo de unidad qie es umae, para delegacional, trae todas las unidades de su delegación
                        $filtros['u.umae'] = FALSE; //No debe existir una umae  
                        break;
                    case 2:
                        $filtros['u.grupo_tipo_unidad'] = 'UMAE'; //Para el caso de tipo de unidad qie es umae, para delegacional, trae todas las unidades de su delegación
                        $filtros['u.umae'] = TRUE; //No debe existir una umae  
                        break;
                    default :
                        $filtros = null;
                }
            } else {//Nivel 2
                //Valida tipo unidad en la sesión, si es un UMAE o un delegacionl
                if ($datos_sesion[En_datos_sesion::IS_UMAE]) {//Valida si es una UMAE 
                    $filtros['u.grupo_tipo_unidad'] = 'UMAE'; //Para el caso de tipo de unidad qie es umae, para delegacional, trae todas las unidades de su delegación
                    $filtros['u.umae'] = TRUE; //No debe existir una umae  
                    $filtros['u.clave_unidad'] = $datos_sesion[En_datos_sesion::CLAVE_UNIDAD]; //No debe existir una umae  
                } else {//Is delegacional
                    $filtros['z.id_delegacion'] = $datos_sesion[En_datos_sesion::ID_DELEGACION]; //Para el caso de tipo de unidad qie es umae, para delegacional, trae todas las unidades de su delegación
                    $filtros['u.umae'] = FALSE; //No debe existir una umae  
                }
            }
//            pr($filtros);
//            exit();
            //***** Fin de validación de tipo de usuario
            $select = ["d.id_directorio", "d.clave_nombramiento", "d.matricula", "d.nombre",
                "d.apellido_p", "d.apellido_m", "d.titulo", "d.telefonos", "d.observaciones", "u.clave_unidad",
                "u.nombre AS nombre_unidad", "z.clave_delegacional", "n.nombre nombre_nombramiento"];
            $result['data'] = $this->dir->get_datos_directorio($filtros, $select);
            $result['length'] = count($result['data']);
            header('Content-Type: application/json; charset=utf-8;');
            $json = json_encode($result);
            echo $json;
        }
    }
    
    public function get_delegaciones(){
        
    }

    public function editar() {
        $data_post = $this->input->post(NULL, TRUE);
        $this->load->library('Empleados_siap');
        $datos_siap = $this->empleados_siap->buscar_usuario_siap($data_post['clave_delegacional'], $data_post['matricula']);
//        $datos_siap = $this->empleados_siap->buscar_usuario_siap($data_post['clave_delegacional'], $data_post['matricula']);
        $datos_update = array(
            'matricula' => $data_post['matricula'],
            'nombre' => $data_post['nombre'],
            'apellido_p' => $data_post['apellido_p'],
            'apellido_m' => $data_post['apellido_m'],
            'telefonos' => $data_post['telefonos'],
            'titulo' => $data_post['titulo'],
            'observaciones' => $data_post['observaciones'],
        );
        //Agrega datos de siap
        if ($datos_siap['tp_msg'] == En_tpmsg::SUCCESS AND ! empty($datos_siap['empleado'])) {//Valida que entregue información del empleado
//        pr($datos_siap['empleado']);
            $datos_update['datos_siap'] = json_encode($datos_siap['empleado']);
        } else {
            $datos_update['datos_siap'] = '{}';
        }
//Fin obtener clave delegacional
        $result = $this->dir->update_directorio($data_post['id_registro_directorio'], $datos_update);
        //Carga datos del registro
        $select = ["d.id_directorio", "d.clave_nombramiento", "d.matricula", "d.nombre",
            "d.apellido_p", "d.apellido_m", "d.titulo", "d.telefonos", "d.observaciones", "u.clave_unidad",
            "u.nombre AS nombre_unidad", "z.clave_delegacional", "n.nombre nombre_nombramiento"];
        $filtro['d.id_directorio'] = $data_post['id_registro_directorio'];
        $data = $this->dir->get_datos_directorio($filtro, $select);
        $result['tmp'] = $datos_update;
        $result['data'] = (!empty($data)) ? $data[0] : [];

        header('Content-Type: application/json; charset=utf-8;');
        $json = json_encode($result);
        echo $json;
    }

    public function exportar_datos($nivel = null) {
        $columnas = array('Clave de nombramiento', 'Clave de unidad', 'Unidad', 'Nivel', 'Matrícula', 'Nombre', 'Apellido paterno', 'Apellido materno', 'Teléfonos', 'Observaciones');

        $select = array(
            "d.clave_nombramiento",
            "u.clave_unidad",
            "u.nombre AS nombre_unidad",
            "u.umae",
            "d.matricula",
            "d.nombre",
            "d.apellido_p",
            "d.apellido_m",
            "d.telefonos",
            "d.observaciones"
        );

        $filtros = null;

        $resultado = $this->dir->get_datos_directorio($filtros, $select);
        $file_name = 'directorio_usuarios_' . date('Ymd_his', time());
        $this->exportar_xls($columnas, $resultado, null, null, $file_name);
    }

}
