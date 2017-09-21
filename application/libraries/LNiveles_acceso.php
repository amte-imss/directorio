<?php

/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */

/**
 * Description of LNiveles_acceso
 *
 * @author chrigarc
 */
class LNiveles_acceso {

    const
            Admin = 1,
            N2 = 2
    ;

//put your code here
    public function __construct() {
        
    }

    public function nivel_acceso_valido($niveles_requeridos, $niveles_disponibles = []) {
        $status = false;
        foreach ($niveles_disponibles as $nivel) {
            if (is_array($nivel) && isset($nivel['id_grupo'])) {
                $nivel = $nivel['id_grupo'];
            }
            if (in_array($nivel, $niveles_requeridos)) {
                $status = true;
            }
        }
        return $status;
    }

}
