<?php

namespace Model;
use Model\ActiveRecord;

class Cita extends ActiveRecord {

    protected static $tabla = 'citas';
    protected static $columnasDB = ['id', 'fecha', 'hora', 'usuarioId'];

    public $id;
    public $fecha;
    public $hora;
    public $usuarioId;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? NULL;
        $this->fecha = $args['fecha'] ?? '';
        $this->hora = $args['hora'] ?? '';
        $this->usuarioId = $args['usuarioId'] ?? '';
    }
}