<h1 class="nombre-pagina">Panel de Administración</h1>

<?php
include_once __DIR__ . '/../templates/barra.php';
?>
<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha"></label>
            <input type="date" id="fecha" name="fecha" value="<?php  echo $fecha;?>">
        </div>

    </form>
</div>
<?php
    if(count($citas) === 0) {
        echo "<h2>No Hay citas en esta fecha</h2>";
    }
?>

<div id="citas-admin">
    <ul class="citas">
    <?php
    $idCita ='';
        foreach($citas as $key => $cita) { //ESTAMOS ITERAND SOBRE CADA CITA DE LOS CLIENTES QUE ES POR SU ID
            if($idCita !== $cita->id){        
                $total = 0;
    ?>
        <li>
            <p>ID: <span><?php echo $cita->id; ?></span></p>
            <p>Hora: <span><?php echo $cita->hora; ?></span></p>
            <p>Cliente: <span><?php echo $cita->cliente; ?></span></p>
            <p>Email: <span><?php echo $cita->email; ?></span></p>
            <p>Telefono: <span><?php echo $cita->telefono; ?></span></p>
            <h3>Servicios</h3>
                <?php $idCita = $cita->id;
            } //FIN IF
            $total += $cita->precio; //SUMAMOS LOS SERVICIO?> 
            <p class="servicio">Servicio: <?php echo $cita->servicio . " " . $cita->precio; ?></p>
            <?php
            $actual = $cita->id; //NOS TRAE EL ID ACTUAL EN EL QUE NOS ENCONTRAMOS
            $proximo = $citas[$key +1]->id ?? 0;//ES EL INDICE EN EL ARREGLO DE LA BD 0,1,2,3... VA A IDENTIFICAR CUAL ES EL ULTIMO REGISTRO CON EL MISMO ID

            if(esUltimo($actual, $proximo)) { ?>
                <p class="total">Total: <span>$ <?php echo $total; ?></span></p></p>

                <form action="/api/eliminar" method="POST">
                <input type="hidden" name="id" value="<?php echo $cita->id; ?>" >
                <input type="submit" class="boton-eliminar" value="Eliminar">
                </form>
            <?php
            }
            ?>
        
         <?php } ?>
    </ul>
</div>

<?php
$script = "<script src='build/js/buscador.js'></script>"
?>