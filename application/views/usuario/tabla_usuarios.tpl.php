<table class="table table-striped">
    <thead>
        <tr>
            <th>Username</th>
            <th>Nombre</th>
            <th>Correo electrónico</th>            
            <th>Editar</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($usuarios as $row)
        {
            ?>
            <tr>
                <td><?php echo $row['matricula']; ?></td>
                <td><?php echo $row['nombre_completo']; ?></td>
                <td><?php echo $row['email']; ?></td>                
                <!-- <td><a href="<?php echo site_url() ?>/usuario/ver_usuario_tabla/<?php echo $row['id_usuario']; ?>">Ver</a></td> -->
                <td><a href="<?php echo site_url() ?>/usuario/get_usuarios/<?php echo $row['id_usuario']; ?>"><i class="glyphicon glyphicon-pencil"></i></a>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>

