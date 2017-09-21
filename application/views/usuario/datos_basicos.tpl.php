<?php
if (isset($status) && $status) {
    echo html_message('Usuario actualizado con éxito', 'success');
}
?>

<div class="col-md-12 form-inline" role="form" id="informacion_general">

    <form class="form-horizontal" id="form_datos_generales" method="post" accept-charset="utf-8">


        <div class="row">

            <div class="col-md-2">
                <label class="righthoralign control-label">
                    Matrícula: </label>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-addon">
                        <span class="fa fa-male"> </span>
                    </span>
                    <?php
                    echo $this->form_complete->create_element(array(
                        'id' => 'matricula',
                        'type' => 'text',
                        'value' => $usuario['matricula'],
                        'attributes' => array('readonly' => ' ', 'class' => 'form-control')));
                    ?>

                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4">
                        <label for="materno" class="control-label">
                            Nombre:</label>
                    </div>
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <span class="fa fa-female"> </span>
                            </span>
                            <?php
                            echo $this->form_complete->create_element(array(
                                'id' => 'nombre',
                                'type' => 'text',
                                'value' => $usuario['nombre'],
                                'attributes' => array('class' => 'form-control')));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        
        <div class="row">

            <div class="col-md-2">
                <label class="righthoralign control-label">
                    Correo electrónico: </label>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-addon">
                        <span class="fa fa-male"> </span>
                    </span>
                    <?php
                    echo $this->form_complete->create_element(array(
                        'id' => 'email',
                        'type' => 'email',
                        'value' => $usuario['email'],
                        'attributes' => array('name' => 'email', 'class' => 'form-control')));
                    ?>
                </div>
            </div>            
            <div class="col-md-2">
                <label class="righthoralign control-label">
                    Delegación/UMAE: </label>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-addon">
                        <span class="fa fa-male"> </span>
                    </span>
                    <?php
                    echo $this->form_complete->create_element(array(
                        'id' => 'unidad',
                        'type' => 'dropdown',
                        'first' => array(''=>'Sin asignar'), 
                        'options' => $unidades,
                        'value' => $usuario['clave_unidad'],
                        'attributes' => array('class' => 'form-control')));
                    ?>
                </div>
            </div>            
        </div>
        <br>
    </form>
</div>

<br>
<div class="col-md-12">
    <div class="col-md-5">

    </div>
    <div class="col-md-1">
        <label class=" control-label"></label>
        <button id="submit" name="submit" type="submit" class="btn btn-success"  style=" background-color:#008EAD">Guardar <span class=""></span></button>
    </div>

</div>
