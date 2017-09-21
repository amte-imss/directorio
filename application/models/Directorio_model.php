<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Modulo_model
 *
 * @author chrigarc
 */
class Directorio_model extends MY_Model {

    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    /**
     * 
     * @param type $filtro filtro para obtener lo esperado en la consulta, por ejemplo que unicamente traiga unidades tipo UMAE
     * @return type
     */
    public function get_datos_directorio($filtro = null, $select = null) {
        $this->db->flush_cache();
        $this->db->reset_query();
        if (is_null($select)) {
            $select = array(
                "d.id_directorio",
                "d.clave_nombramiento",
                "d.matricula",
                "d.nombre",
                "d.apellido_p",
                "d.apellido_m",
                "d.titulo",
                "d.telefonos",
                "d.observaciones",
                "d.datos_siap",
                "d.id_unidad_instituto",
                "u.clave_unidad",
                "u.nombre AS nombre_unidad"
            );
        }
        $this->db->select($select);
        $this->db->join('catalogo.unidades_instituto u', 'u.id_unidad_instituto = d.id_unidad_instituto', 'inner');
        $this->db->join('catalogo.delegaciones z', 'z.id_delegacion = u.id_delegacion', 'inner');
        $this->db->join('catalogo.nombramiento n', 'n.clave_nombramiento = d.clave_nombramiento', 'left');
        $this->db->order_by('d.matricula');
        if (!is_null($filtro) AND ! empty($filtro)) {
            foreach ($filtro as $k => $v)
                $this->db->where($k, $v);
        }
//        $this->db->limit(30);
        $result = $this->db->get('ods.directorio d')->result_array();
        return $result;
    }

    public function update_directorio($id_directorio = null, $data = null) {
        $string_value = get_elementos_lenguaje(array(En_catalogo_textos::GENERAL)); //Carga archivo de texto de lenguajes
        $this->db->trans_begin(); //Inicia la transacción
        $this->db->where('ods.directorio.id_directorio', $id_directorio); //Id directorio
        $this->db->update('ods.directorio', $data);
        if ($this->db->trans_status() === FALSE) {//Valida que se inserto el archvo success
            $this->db->trans_rollback();
            $respuesta = array('success' => 0, 'message' => $string_value['actualizar_falla']);
        } else {
            $this->db->trans_commit();
            $respuesta = array('success' => 1, 'message' => $string_value['actualizar_correcto']);
        }
        return $respuesta;
    }

}
