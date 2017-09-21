<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config["login"] = array(
    array(
        'field' => 'usuario',
        'label' => 'Usuario',
        'rules' => 'required',
        'errors' => array(
            'required' => 'El campo %s es obligatorio, favor de ingresarlo.',
        ),
    ),
    array(
        'field' => 'password',
        'label' => 'Contraseña',
        'rules' => 'required',
        'errors' => array(
            'required' => 'El campo %s es obligatorio, favor de ingresarlo.',
        ),
    ),
    /*
    array(
        'field' => 'captcha',
        'label' => 'Imagen de seguridad',
        'rules' => 'required|check_captcha_helper',
        'errors' => array(
            'required' => 'El campo %s es obligatorio, favor de ingresarlo.',
            'check_captcha_helper' => "El texto no coincide con la imagen, favor de verificarlo."
        ),
    ),
    */
);

$config['form_registro'] = array(
    array(
        'field' => 'matricula',
        'label' => 'Username',
        'rules' => 'required'
    ),    
    array(
        'field' => 'email',
        'label' => 'Correo electrónico',
        'rules' => 'trim|required|valida_correo_electronico' //|callback_valid_pass
    ),
    array(
        'field' => 'pass',
        'label' => 'Contraseña',
        'rules' => 'required' //|callback_valid_pass
    ),
    array(
        'field' => 'repass',
        'label' => 'Confirmación contraseña',
        'rules' => 'required|matches[pass]'
    ),
    array(
        'field' => 'niveles',
        'label' => 'Niveles de Atencion',
        'rules' => 'required'
    )
);

$config['form_actualizar'] = array(
    array(
        'field' => 'email',
        'label' => 'Correo electrónico',
        'rules' => 'trim|required|valida_correo_electronico' //|callback_valid_pass
    ),    
);

$config['form_user_update_password'] = array(
    array(
        'field' => 'pass',
        'label' => 'Contraseña',
        'rules' => 'required|min_length[8]'
    ),
    array(
        'field' => 'pass_confirm',
        'label' => 'Confirmar contraseña',
        'rules' => 'required|min_length[8]' //|callback_valid_pass
    ),
);

$config['form_niveles_acceso_usuario'] = array(
    array(
        'field' => 'niveles',
        'label' => 'niveles',
        'rules' => 'required'
    )
);
