<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1 class="nombre-pagina">ghola</h1>
    <p class="descripcion-pagina">Inicia sesion con tus datos</p>
    <?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>
    
    <form class="formulario" method="POST" action="/">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" id="email" placeholder="Tu email" name="email">
    </div>
    <div class="campo">
        <label for="password">Password</label>
        <input type="password" id="password" placeholder="Tu password" name="password">        
    </div>
    <input type="submit" class="boton" value="Iniciar sesion">
    </form>

    <div class="acciones">
        <a href="/crear-cuenta">Aun no tienes cuenta crea una</a>
        <a href="olvide">¿Olvidaste tu password?</a>
    </div>
</body>
</html>