<?php
namespace Controllers;
use Classes\Email;
use Model\Usuario;
use MVC\Router;



class LoginController {
    
    public static function login(Router $router) {       
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if(empty($alertas)) {
                //COMPROBAR QUE EL USUARIO EXISTA
                $usuario = Usuario::where('email', $auth->email);
                
                if($usuario) {                    
                    //VERIFICAR PASSWORD  
                    if($usuario->comprobarPasswordAndVerificado($auth->password)){
                    //AUTENTICAR EL USUARIO                    
                    $_SESSION['id'] = $usuario->id;
                    $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                    $_SESSION['email'] = $usuario->email;
                    $_SESSION['login'] = true;

                    //REDIRECCIONAMIENTO
                        if($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }                    
                    }

                } else {    
                    $alertas = Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }
        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }
    public static function logout() {
        $_SESSION = [];
        header('Location: /');
    }
    public static function olvide(Router $router) {
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();
            if(empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);
                
                if($usuario && $usuario->confirmado === "1") {
                    //GENERAR TOKEN
                    $usuario->crearToken();
                    $usuario->guardar();
                    
                    //ENVIAR EMAIL
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarInstrucciones();
                    //ALERTAS
                    Usuario::setAlerta('exito', 'Revisa tu email');
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');                    
                }
                $alertas = Usuario::getAlertas();
            }
        }
        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }
    public static function recuperar(Router $router) {
        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);
        $error = false;
        
        if(empty($usuario)) {
            Usuario::setAlerta('error','Token no valido');
            $error = true;
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //LEER EL NUEVO PASSWORD Y GUARDARLO
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)){
                $usuario->password = null;

                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;
                $resultado = $usuario->guardar();                

                if($resultado) {
                    header('Location: /');
                }
            }
        }
        Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }
    public static function crear(Router $router) {
        $usuario = new Usuario;
        
        //ALERTAS
        $alertas =[];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
         
            //REVISAR QUE ALERTA ESTE VACION
            if(empty($alertas)) {
                //VERIFICAR QUE EL USUARIO NO ESTE REGISTRADO
                $resultado = $usuario->existeUsuario();
                if($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    //HASHEAR PASSWORD
                    $usuario->hashPassword();

                    //CREAR TOKEN
                    $usuario->crearToken();

                    //Enviar email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();
//debuguear($usuario);
                    //CREAR EL USUARIO
                    $resultado = $usuario->guardar();
                    if($resultado) {
                        header('Location: /mensaje');
                    }


                }
            }
        }
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
        
    }

    public static function mensaje(Router $router) {
        $router->render('auth/mensaje', [            
        ]);
    }

    public static function confirmar(Router $router) {
        $alertas = [];
        $token = s($_GET['token']);

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            //MOSTRAR MENSAJE DE ERROR
            Usuario::setAlerta('error', 'Token no valido');
        } else {
            //CAMBIAR A USUARIO CONFIRMADO
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario ->guardar();
            Usuario::setAlerta('exito', 'Cuenta comprobada correctamente');
        }
        //OBTENER ALERTAS
        $alertas = Usuario::getAlertas();

        //RENDERIZAR VISTA
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
    
}