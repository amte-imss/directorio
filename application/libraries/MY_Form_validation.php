<?php

class MY_Form_validation extends CI_Form_validation {

    function __construct($config = array()) {
        parent::__construct($config);
        /*
          $this->CI =& get_instance();
          $this->CI->load->helper('captcha');
          $this->CI->load->library('session'); */
    }

    public function alpha_accent_space($str) {
        $exp = '/^[\p{L}- ]*$/u';
        return (!preg_match($exp, $str)) ? FALSE : TRUE;
    }

    public function alpha_numeric_accent_space($str) {
        $exp = '/^[\p{L}-0123456789 ]*$/u';
        return (!preg_match($exp, $str)) ? FALSE : TRUE;
    }

    public function alpha_numeric_accent_space_dot($str) {
        $exp = '/^[\p{L}-0123456789,. \s]*$/u';
        return (!preg_match($exp, $str)) ? FALSE : TRUE;
    }

    /**/

    public function alpha_accent_space_dot_quot($str) {
        $exp = '/^[\p{L}-,.\s]*$/u';
        return (!preg_match($exp, $str)) ? FALSE : TRUE;
    }

    public function alpha_numeric_accent_slash($str) {
        $exp = '/^[\p{L}-0123456789.\/]*$/u';
        return (!preg_match($exp, $str)) ? FALSE : TRUE;
    }

    public function alpha_numeric_accent_space_dot_parent($str) {
        $exp = '/^[\p{L}-0123456789,.:\(\)\/\s]*$/u';
        return (!preg_match($exp, $str)) ? FALSE : TRUE;
    }

    public function alpha_numeric_accent_space_dot_double_quot($str) {
        $exp = '/^[\p{L}-0123456789,.:\(\)\/\s]*$/u';
        //$exp = '/^[\p{L}-0123456789,.:;\'\"\(\)\/\s]*$/u';
        return (!preg_match($exp, $str)) ? FALSE : TRUE;
    }

    public function exists_a_number($str) {
        $exp = '/^[0123456789,]+*$/u';
        //$exp = '/^[\p{L}-0123456789,.:;\'\"\(\)\/\s]*$/u';
        return (!preg_match($exp, $str)) ? FALSE : TRUE;
    }

    public function exists_a_uppercase($str) {
        $exp = '/^[A-Z,]*$/u';
        //$exp = '/^[\p{L}-0123456789,.:;\'\"\(\)\/\s]*$/u';
        return (!preg_match($exp, $str)) ? FALSE : TRUE;
    }

    public function exists_a_lowercase($str) {
        $exp = '/^[a-z,]*$/u';
        return (!preg_match($exp, $str)) ? FALSE : TRUE;
    }

    public function exists_a_aspecial_character($str) {
        $exp = '/^[%$#,]*$/u';
        return (!preg_match($exp, $str)) ? FALSE : TRUE;
    }

    public function exists_a_aspecial_character_password($str) {
        $exp = '/^[*\/_+-,]*$/u';
        return (!preg_match($exp, $str)) ? FALSE : TRUE;
    }

    /*
    * validación contraseña
    */
    public function valid_pass_user($str) {
        $exp = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])([A-Za-z\d$@$!%*?&]|[^ ]){8,15}$/";
        return (!preg_match($exp, $str)) ? FALSE : TRUE;
    }
    /*
      falta validacion pa ra % $ # & !
    */
    // /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])([A-Za-z\d$@$!%*?&]|[^ ]){8,15}$/
    public function validate_url($url) {
        $url = trim($url);
        $url = stripslashes($url);
        $url = htmlspecialchars($url);

        // check address syntax is valid or not(this regular expression also allows dashes in the URL)
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /* Formato yyyy-mm-dd */

    public function validate_date($date) {
        $exp = '/^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/';
        return (!preg_match($exp, $date)) ? FALSE : TRUE;
    }

    public function validate_date_dd_mm_yyyy($date) {
        $exp = '/^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/';
        return (!preg_match($exp, $date)) ? FALSE : TRUE;
    }
    
    /*public function compare_date($end, $start){
        if($start>$end){
            return false;
        } else {
            return true;
        }
    }*/

    public function radio_buttom_validation($str) {
//        $exp = '/^[0-9]+$/';
//        return (!preg_match($exp, $value)) ? FALSE : TRUE;
        return is_array($str) ? (bool) count($str) : (trim($str) !== '');
    }

    public function check_captcha($str) {
        $this->CI = & get_instance();
        $this->CI->load->library('session');

        $word = $this->CI->session->userdata('captchaWord');
        if (strcmp(strtoupper($str), strtoupper($word)) == 0) {
            return true;
        } else {
            //$this->form_validation->set_message('check_captcha','Por favor introduce correctamente los caracteres');
            return false;
        }

    }
    /**
     * @author LEAS
     * @fecha creación 03/abril/2016
     * @param String $str
     * @return true si el correo es valido 
     * 
     */
    public function valida_correo_electronico($str) {
        $exp = '/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(\.[a-z]{2,4})$/';
        return (!preg_match($exp, $str)) ? FALSE : TRUE;
    }
    
    public function matches($str, $field){
        if ( ! isset($_POST[$field]))
        {
            return FALSE;
        }   
        $field = $_POST[$field];    
        return ($str !== $field) ? FALSE : TRUE;
    }


    public function implementacion_valida($str) {
        $this->CI = & get_instance();
        $this->CI->load->model('Catalogos_model', 'cm');
        $resultado = $this->CI->cm->get_existe_implementacion($str);
        return !($resultado > 0); 
    }

}
