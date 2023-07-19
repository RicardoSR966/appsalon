<?php

namespace Model;
use Model\ActiveRecord;

class CitaServicio extends ActiveRecord {

    protected static $tabla = 'citaservicios';
    protected static $columnasDB = ['id', 'citaId', 'servicioId'];

    public $id;
    public $citaId;
    public $servicioId;    

    public function __construct($args = []) {
        $this->id = $args['id'] ?? NULL;
        $this->citaId = $args['citaId'] ?? '';
        $this->servicioId = $args['servicioId'] ?? '';        
    }
}