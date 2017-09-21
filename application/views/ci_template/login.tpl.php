<?php
/*
 * Cuando escribí esto sólo Dios y yo sabíamos lo que hace.
 * Ahora, sólo Dios sabe.
 * Lo siento.
 */
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url('assets/img/template_proyecto/apple-icon.png'); ?>" />
        <link rel="icon" type="image/png" href="<?php echo base_url('assets/img/template_proyecto/favicon.ico'); ?>" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>
            Registro de usuarios
        </title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
        <meta name="viewport" content="width=device-width" />
        <!-- BOOTSTRAP STYLES-->
        <?php echo css('bootstrap.css'); ?>
        <!-- FONTAWESOME ICONS STYLES-->
        <!--CUSTOM STYLES-->
        <!-- HTML5 Shiv and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <script type="text/javascript">
            var url = "<?php echo base_url(); ?>";
            var site_url = "<?php echo site_url(); ?>";
            var img_url_loader = "<?php echo base_url('assets/img/loader.gif'); ?>";
        </script>
        <?php echo css('estilo_perfil.css'); ?>
        <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
        <?php echo css('font-awesome.css'); ?>
        <?php echo css('style.css'); ?>
        <?php echo css("date/datepicker.css"); ?>
        <?php echo css("datepicker.less"); ?>

        <?php echo css('apprise.css'); ?>


        <?php echo js("jquery.js"); ?>
        <?php echo js("jquery.min.js"); ?>
        <?php echo js("jquery.ui.min.js"); ?>

        <!--Let browser know website is optimized for mobile-->
    </head>
    <body>
        <?php echo js('captcha.js'); ?>
        <div style="    padding: 70px;">
            <div class="login-page">
                <div class="form">
                    <?php echo form_open('inicio/', array('id' => 'session_form', 'autocomplete' => 'off')); ?>
                    <div class="sign-in-htm">
                        <div class="form-group">
                            <label for="user" class="pull-left"><span class="glyphicon glyphicon-user"></span> Nombre de usuario:</label>
                            <input id="usuario"
                                   name="usuario"
                                   type="text"
                                   class="input form-control"
                                   placeholder="<?php echo $texts['user']; ?>:">

                        </div>
                        <?php
                        echo form_error_format('usuario');
                        if ($this->session->flashdata('flash_usuario')) {
                            ?>
                            <div class="alert alert-danger" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <?php echo $this->session->flashdata('flash_usuario');
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="form-group">
                            <label for="pass" class="pull-left"><span class="glyphicon glyphicon-eye-open"></span> Contraseña:</label>
                            <input id="password"
                                   name="password"
                                   type="password"
                                   class="input form-control"
                                   data-type="password"
                                   placeholder="<?php echo $texts['passwd']; ?>:">
                        </div>
                        <?php
                        echo form_error_format('password');
                        if ($this->session->flashdata('flash_password')) {
                            ?>

                            <div class="alert alert-danger" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <?php echo $this->session->flashdata('flash_password'); ?>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="form-group" style="text-align:center;">
                            <!--label for="captcha" class="label"></label-->
                            <input id="captcha"

                                   name="captcha"
                                   type="text"
                                   class="input form-control "
                                   placeholder="<?php echo $texts['captcha']; ?>">
                                   <?php
                                   echo form_error_format('captcha');
                                   ?>
                            <br>
                            <div class="captcha-container" id="captcha_first">
                                <img id="captcha_img" src="<?php echo site_url(); ?>/inicio/captcha" alt="CAPTCHA Image" />
                                <a class="btn btn-lg btn-success pull-right" onclick="new_captcha()">
                                    <span class="glyphicon glyphicon-refresh"></span>
                                </a>
                            </div>
                        </div>
                        <br>
                        <div class="">
                            <input type="submit" class="btn btn-success btn-block" value="Iniciar sesión">
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
