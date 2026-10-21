<?php
namespace App\Controllers;

class EstadoController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function ver() {
        $respuesta = ['status' => 'ok', 'message' => 'La API responde', 'base_datos' => 'conectada (automotora)', 'tablas' => []];

        foreach (['MARCAS', 'VEHICULOS', 'CLIENTES', 'EMPLEADOS', 'VENTAS'] as $tabla) {
            try {
                $respuesta['tablas'][$tabla] = (int) $this->db->query("SELECT COUNT(*) FROM $tabla")->fetchColumn();
            } catch (\Exception $e) {
                $respuesta['tablas'][$tabla] = 'falta crear la tabla (init.sql)';
            }
        }
        echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
    }
}
