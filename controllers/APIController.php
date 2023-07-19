<?php
namespace  Controllers;

use Model\Servicio;
use Model\Cita;
use Model\CitaServicio;

class APIController {
    public static function index() {
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }

    public static function guardar() {
        //ALMACENA LA CITA Y DEVUELVE EL ID
        
        $cita = new Cita($_POST);
        $resultado  = $cita->guardar();
        $id = $resultado['id'];

        //ALMACENA LOS SERVICIOS CON EL ID DE LA CITA
        $idServicios = explode(',', $_POST['servicios']);
        foreach($idServicios as $idServicio) {
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();            
        }
        //RETORNAMOS UNA RESUESTA   
        $respuesta = [
            'resultado' => $resultado
        ];
        echo json_encode($respuesta);
    }

    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $cita = Cita::find($id);            
            $cita->eliminar();
            header('Location:' . $_SERVER['HTTP_REFER']);
        }
    }
}