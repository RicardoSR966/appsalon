<h1 class="nombre-pagina">Olvide Password</h1>
    <p class="descripcion-pagina">Restablece tu password con tu email</p>

    <?php
    include_once __DIR__ . "/../templates/alertas.php";
    ?>

    <form class="formulario" method="POST" action="/olvide">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" id="email" placeholder="Tu email" name="email">
    </div>

    <input type="submit" class="boton" value="Enviar Instrucciones">
    </form>

    <div class="acciones">
        <a href="/">Inicia Sesion</a>
        <a href="/crear-cuenta">Crear Cuenta</a>
    </div>