<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogos_model extends MY_Model {
    public function __construct() {
        // Call the CI_Model constructor
        parent::__construct();
    }

    public function get_existe_implementacion($cve_corta_curso = ''){
    	$this->db->flush_cache();
        $this->db->reset_query();

    	$select = array('count(*) total');
    	$this->db->select($select);
        $this->db->where('cve_corta_curso', $cve_corta_curso);
    	$query = $this->db->get('catalogo.implementaciones')->result_array();
    	$total=$query[0]['total'];

    	$this->db->flush_cache();
        $this->db->reset_query();
        
        return $total;
    }
    public function get_delegacioes(){
    	$this->db->flush_cache();
        $this->db->reset_query();

    	$select = array('count(*) total');
    	$this->db->select($select);
        $this->db->where('cve_corta_curso', $cve_corta_curso);
    	$query = $this->db->get('catalogo.delegaciones')->result_array();
    	$total=$query[0]['total'];

    	$this->db->flush_cache();
        $this->db->reset_query();
        
        return $total;
    }

}
?>