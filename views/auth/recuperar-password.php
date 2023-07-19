<h1 class="nombre-pagina">Recuperar password</h1>
<p class="descripcion-pagina">Coloca tu nueva password</p>

<?php
    include_once __DIR__ . "/../templates/alertas.php";
?>
<?php if($error) return ?>

<form class="formulario" method="POST">

    <div class="campo">
        <label for="password">Password</label>
        <input type="password" id="password" placeholder="Tu nuevo password" name="password">        
    </div>
    <input type="submit" class="boton" value="Confirmar password">
    </form>

    <div class="acciones">
        <a href="/">Iniciar sesion</a>
        <a href="/crear-cuenta">Crear Cuenta</a>
    </div>