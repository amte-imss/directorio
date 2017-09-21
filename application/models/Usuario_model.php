<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends MY_Model {

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    public function nuevo(&$parametros = null) {
        $salida['msg'] = 'Error';
        $salida['result'] = false;


        $token = $this->seguridad->folio_random(10, TRUE);
        $pass = $this->seguridad->encrypt_sha512($token . $parametros['password'] . $token);
//        pr($usuario);
        $params['where'] = array(
            'usuarios.matricula' => $parametros['matricula']
        );
        $usuario_db = count($this->get_usuarios($params)) == 0;
        if ($usuario_db) {


            $data['usuario'] = array(
                'password' => $pass,
                'token' => $token,
                'email' => $parametros['email'],
                'matricula' => $parametros['matricula'],
                'nombre' => '',
            );
            //pr($data);
            $salida = $this->insert_guardar($data, $parametros['grupo']);
            if ($salida['result'] && isset($parametros['registro_usuario'])) {
                //  $this->load->model('Plantilla_model', 'plantilla');
                //$this->plantilla->send_mail(Plantilla_model::BIENVENIDA_REGISTRO, $parametros);
            }
        } else if (!$usuario_db) {
            $salida['msg'] = 'Usuario ya registrado';
        }
        return $salida;
    }

    public function carga_masiva(&$csv_array) {
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->trans_begin(); //Definir inicio de transacción
        $registros = [];
        $errores_presentes = false;
        foreach ($csv_array as $row) {
            if (isset($row['clave_unidad']) && isset($row['nivel_acceso']) && isset($row['email']) && isset($row['nombre'])) {
                $params['where'] = array(
                    'usuarios.matricula' => $row['email']
                );
                $usuario_db = count($this->get_usuarios($params)) == 0;
                $nivel_acceso = $this->get_nivel_acceso($row['nivel_acceso']);
                $unidad = $this->get_unidad($row['clave_unidad']);
                if ($usuario_db && !is_null($nivel_acceso) && !is_null($unidad)) {
                    $token = $this->seguridad->folio_random(10, TRUE);
                    $password = $this->seguridad->folio_random(10, TRUE);
                    $pass = $this->seguridad->encrypt_sha512($token . $password . $token);
                    $data['usuario'] = array(
                        'nombre' => $row['nombre'],
                        'email' => $row['email'],
                        'matricula' => $row['email'],
                        'password' => $pass,
                        'token' => $token,
                        'id_unidad_instituto' => $unidad['id_unidad_instituto']
                    );
                    //pr($data);
                    $this->insert_guardar($data, $nivel_acceso);
                    $row['errores'] = '';
                    $row['password'] = $password;
                } else {
                    $errores_presentes = true;
                    $row['errores'] = 'Usuario no encontrado o ya registrado en el sistema';
                }
            } else {
                $errores_presentes = true;
                $row['errores'] = 'Datos de email, nombre o  grupo inválidos';
            }
            $registros[] = $row;
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $resultado['result'] = FALSE;
            $registros[] = 'Error en la transaccion';
            $resultado['msg'] = "Ocurrió un error durante el guardado, por favor intentelo de nuevo más tarde.";
        } else {
            $this->db->trans_commit();
            $resultado['msg'] = 'Usuarios almacenado con éxito';
            if ($errores_presentes) {
                $resultado['msg'] = 'Se presentaron errores durante el registro';
            }
            $resultado['result'] = TRUE;
        }
        $resultado['data'] = $registros;
        //pr($resultado);
        return $resultado;
    }

    private function insert_guardar(&$datos, $id_grupo, $transaccion = true) {
        $this->db->flush_cache();
        $this->db->reset_query();
        if ($transaccion) {
            $this->db->trans_begin(); //Definir inicio de transacción
        }
        $this->db->insert('sistema.usuarios', $datos['usuario']); //nombre de la tabla en donde se insertaran
        $id_usuario = $this->db->insert_id();
        $data = array(
            'id_rol' => $id_grupo,
            'id_usuario' => $id_usuario
        );
        $this->db->insert('sistema.usuario_rol', $data);
        if ($transaccion) {
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $resultado['result'] = FALSE;
                $resultado['msg'] = "Ocurrió un error durante el guardado, por favor intentelo de nuevo más tarde.";
            } else {
                $this->db->trans_commit();
                $resultado['msg'] = 'Usuario almacenado con éxito';
                $resultado['result'] = TRUE;
            }
        }
        return $resultado;
    }

    private function get_nivel_acceso($nivel = '') {
        $id_nivel = null;
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->select('id_rol');
        $this->db->where('nombre', $nivel);
        $resultset = $this->db->get('sistema.roles');
        if ($resultset && count($resultset->result_array()) > 0) {
            $id_nivel = $resultset->result_array()[0]['id_rol'];
        }
        $this->db->flush_cache();
        $this->db->reset_query();
        return $id_nivel;
    }

    private function get_unidad($clave = '') {
        $this->db->flush_cache();
        $this->db->reset_query();
        $unidad = null;
        $this->db->where('anio', 2017);
        $this->db->where('clave_unidad', $clave);
        $resultset = $this->db->get('catalogo.unidades_instituto');
        if ($resultset && count($resultset->result_array()) > 0) {
            $unidad = $resultset->result_array()[0];
        }
        $this->db->flush_cache();
        $this->db->reset_query();
        return $unidad;
    }

    public function get_usuarios($params = []) {
        $this->db->flush_cache();
        $this->db->reset_query();
        $usuarios = [];
        if (isset($params['total'])) {
            $select = 'count(*) total';
        } else {
            if (isset($params['select'])) {
                $select = $params['select'];
            } else {
                $select = array(
                    'usuarios.id_usuario', 'usuarios.matricula', 'usuarios.email'
                    , 'usuarios.nombre nombre_completo', 'UNI.clave_unidad clave_unidad'
                );
            }
        }
        $this->db->select($select);
        $this->db->join('catalogo.unidades_instituto UNI', 'UNI.id_unidad_instituto = usuarios.id_unidad_instituto', 'left');
        $this->db->from('sistema.usuarios usuarios');

        if (isset($params['where'])) {
//            pr($params['where']);            
            $this->db->where($params['where']);
        }
        if (isset($params['like'])) {
            if (isset($params['like']['nombre_completo'])) {
                $params['like']['usuarios.nombre'] = $params['like']['nombre_completo'];
                unset($params['like']['nombre_completo']);
            }
            $this->db->like($params['like']);
        }
        if (isset($params['limit']) && isset($params['offset']) && !isset($params['total'])) {
            $this->db->limit($params['limit'], $params['offset']);
        } else if (isset($params['limit']) && !isset($params['total'])) {
            $this->db->limit($params['limit']);
        }
        if (isset($params['order_by']) && !isset($params['total'])) {
            $order = $params['order_by'] == 'desc' ? $params['order_by'] : 'asc';
            $this->db->order_by('usuarios.matricula', $order);
        }
        $query = $this->db->get();
        if ($query) {
            $usuarios = $query->result_array();
            $query->free_result(); //Libera la memoria 
        }
        //pr($this->db->last_query());
        $this->db->flush_cache();
        $this->db->reset_query();
        return $usuarios;
    }

    public function get_niveles_acceso($id_usuario) {
        $this->db->flush_cache();
        $this->db->reset_query();
        $select = array(
            'A.id_rol', 'A.nombre', 'B.activo'
        );
        $this->db->select($select);
        $this->db->join('sistema.usuario_rol B', " B.id_rol = A.id_rol and B.id_usuario = {$id_usuario}", 'left');
        $query = $this->db->get('sistema.roles A');
        if ($query) {
            $niveles = $query->result_array();
            $query->free_result(); //Libera la memoria 
        }
        $this->db->flush_cache();
        $this->db->reset_query();
        return $niveles;
    }

    public function update($tipo = Usuario::BASICOS, $params = []) {
        $salida = false;
        switch ($tipo) {
            case Usuario::BASICOS:
                $salida = $this->update_basicos($params);
                break;
            case Usuario::PASSWORD:
                $salida = $this->update_password($params);
                break;
            case Usuario::NIVELES_ACCESO:
                $salida = $this->update_niveles_acceso($params);
                break;
        }
        return $salida;
    }

    private function update_basicos($params = []) {
        $this->db->flush_cache();
        $this->db->reset_query();
        $salida = false;
        $this->db->trans_begin();
        $params['where'] = array(
            'usuarios.id_usuario' => $params['id_usuario']
        );
        $unidad = null;
        if (isset($params['unidad'])) {
            $unidad = $this->get_unidad($params['unidad']);
            if (!is_null($unidad) && isset($unidad['id_unidad_instituto'])) {
                $unidad = $unidad['id_unidad_instituto'];
            }
        }
        $resultado = $this->usuario->get_usuarios($params);
        if (count($resultado) == 1) {
            $usuario = $resultado[0];
            $update = array(
                'email' => $params['email'],
                'nombre' => $params['nombre'],
                'id_unidad_instituto' => $unidad
            );
            $this->db->start_cache();
            $this->db->where('id_usuario', $usuario['id_usuario']);
            $this->db->stop_cache();
            $this->db->update('sistema.usuarios', $update);
            $this->db->reset_query();

            $this->db->flush_cache();
            $this->db->reset_query();
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $salida = false;
        } else {
            $this->db->trans_commit();
            $salida = true;
        }
        $this->db->flush_cache();
        $this->db->reset_query();
        return $salida;
    }

    private function update_password($datos = null) {
        $salida = false;
        try {
            $this->db->flush_cache();
            $this->db->reset_query();
            $this->db->select('token');
            $this->db->where('id_usuario', $datos['id_usuario']);
            $resultado = $this->db->get('sistema.usuarios')->result_array();
            //pr($datos);
            //pr($this->db->last_query());
            if ($resultado) {
                $this->load->library('seguridad');
                $token = $resultado[0]['token'];
                $this->db->reset_query();
                $password = $this->seguridad->encrypt_sha512($token . $datos['pass'] . $token);
                $this->db->set('password', $password);
                $this->db->where('id_usuario', $datos['id_usuario']);
                $this->db->update('sistema.usuarios');
//                pr($this->db->last_query());
                $salida = true;
            } else {
                // pr('usuario no localizado');
            }
        } catch (Exception $ex) {
            //  pr($ex);
        }
        $this->db->flush_cache();
        $this->db->reset_query();
        return $salida;
    }

    private function update_niveles_acceso($params = []) {
        $this->load->model('Administracion_model', 'admin');
        $id_usuario = $params['id_usuario'];
        $grupos = $this->admin->get_niveles_acceso();
//        pr($grupos);
        $this->db->trans_begin();
        foreach ($grupos as $grupo) {
            $id_grupo = $grupo['id_grupo'];
            $activo = (isset($params['activo' . $id_grupo])) ? true : false;
            $this->upsert_usuario_nivel_acceso($id_usuario, $id_grupo, $activo);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $status = false;
        } else {
            $this->db->trans_commit();
            $status = true;
        }
        return $status;
    }

    private function upsert_usuario_nivel_acceso($id_usuario, $id_grupo, $activo) {
        if ($id_grupo > 0 && $id_usuario > 0) {
            $this->db->flush_cache();
            $this->db->reset_query();
            $this->db->select('count(*) cantidad');
            $this->db->start_cache();
            $this->db->where('id_rol', $id_grupo);
            $this->db->where('id_usuario', $id_usuario);
            $this->db->stop_cache();
            $existe = $this->db->get('sistema.usuario_rol')->result_array()[0]['cantidad'] != 0;
            if ($existe) {
                $this->db->set('activo', $activo);
                $this->db->update('sistema.usuario_rol');
//                pr($this->db->last_query());
            } else {
                $this->db->flush_cache();
                $insert = array(
                    'id_usuario' => $id_usuario,
                    'id_rol' => $id_grupo,
                    'activo' => $activo
                );
                $this->db->insert('sistema.usuario_rol', $insert);
            }
        }
        $this->db->flush_cache();
        $this->db->reset_query();
    }

    public function get_unidades() {
        $unidades = [];
        $cves = array('08DL080000',
            '14DL140000', '37DL370000', '34DL340000', '05DL050000', '10DL100000',
            '19DL190000', '29DL290000', '16DL160000', '25DL250000', '04DL040000',
            '30DL300000', '13DL130000', '20DL200000', '21DL210000', '18DL180000',
            '03DL030000', '02DL020000', '38DL380000', '11DL110000', '17DL170000',
            '35DL350000', '22DL220000', '12DL120000', '28DL280000', '27DL270000',
            '23DL230000', '31DL310000', '26DL260000', '24DL240000', '01DL010000',
            '07DL070000', '36DL360000', '15DL150000', '33DL330000', '06DL060000',
            '32DL320000');
        $cves = implode("','", $cves);
        $this->db->flush_cache();
        $this->db->reset_query();
        $this->db->distinct();
        $this->db->select(array('clave_unidad', 'A.nombre', 'A.grupo_tipo_unidad'));
        $this->db->join('catalogo.unidades_instituto A', 'A.id_unidad_instituto = B.id_unidad_instituto', 'inner');
        $this->db->where("((\"A\".grupo_tipo_unidad = 'UMAE' or A.grupo_tipo_unidad = 'CUAME') and A.anio = 2017)");
        $this->db->or_where("(clave_unidad in ('{$cves}') and anio = 2017)");
        $this->db->order_by('"A".grupo_tipo_unidad nulls first', null, false);
        $this->db->order_by('A.nombre');
        $unidades = $this->db->get('ods.directorio B')->result_array();
//        pr($this->db->last_query());
        $this->db->flush_cache();
        $this->db->reset_query();
        return $unidades;
    }

}
