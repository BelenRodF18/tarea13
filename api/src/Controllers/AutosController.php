<?php
namespace App\Controllers;

use App\Models\AutoModel;

class AutosController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    private function responder($codigo, $payload) {
        http_response_code($codigo);
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    }

    private function validar($modelo) {
        $d = json_decode(file_get_contents('php://input'), true);
        if (!is_array($d)) {
            return [null, 'El cuerpo de la peticion debe ser JSON'];
        }

        $idMarca = (int) ($d['id_marca'] ?? 0);
        $nombre  = trim((string) ($d['modelo'] ?? ''));
        $anio    = $d['anio'] ?? null;
        $precio  = $d['precio'] ?? null;
        $estado  = trim((string) ($d['estado'] ?? ''));

        if ($idMarca <= 0 || $nombre === '' || $anio === null || $anio === '' || $precio === null || $precio === '' || $estado === '') {
            return [null, 'Marca, modelo, año, precio y estado son obligatorios'];
        }
        if (mb_strlen($nombre) > 50) {
            return [null, 'El modelo no puede superar los 50 caracteres'];
        }
        if (!is_numeric($anio) || (int) $anio != $anio || $anio < 1886 || $anio > (int) date('Y') + 1) {
            return [null, 'El año debe ser un numero entre 1886 y ' . ((int) date('Y') + 1)];
        }
        if (!is_numeric($precio) || $precio < 0 || $precio > 99999999.99) {
            return [null, 'El precio debe ser un numero valido mayor o igual a 0'];
        }
        if (!in_array($estado, ['Nuevo', 'Usado'], true)) {
            return [null, 'El estado debe ser Nuevo o Usado'];
        }
        if (!$modelo->marcaExiste($idMarca)) {
            return [null, 'La marca seleccionada no existe'];
        }

        return [[$idMarca, $nombre, (int) $anio, (float) $precio, $estado], null];
    }

    public function listar() {
        $autos = (new AutoModel($this->db))->listar();
        if ($autos === false) {
            return $this->responder(500, ['status' => 'error', 'message' => 'No se pudo leer la lista de autos']);
        }
        $this->responder(200, ['status' => 'ok', 'data' => $autos]);
    }

    public function ver($id) {
        $auto = (new AutoModel($this->db))->buscarPorId((int) $id);
        if (!$auto) {
            return $this->responder(404, ['status' => 'error', 'message' => 'No se encontró ese auto']);
        }
        $this->responder(200, ['status' => 'ok', 'data' => $auto]);
    }

    public function marcas() {
        $marcas = (new AutoModel($this->db))->marcas();
        if ($marcas === false) {
            return $this->responder(500, ['status' => 'error', 'message' => 'No se pudieron leer las marcas']);
        }
        $this->responder(200, ['status' => 'ok', 'data' => $marcas]);
    }

    public function crear() {
        $modelo = new AutoModel($this->db);
        [$datos, $error] = $this->validar($modelo);
        if ($error) {
            return $this->responder(400, ['status' => 'error', 'message' => $error]);
        }

        $nuevoId = $modelo->crear(...$datos);
        if (!$nuevoId) {
            return $this->responder(500, ['status' => 'error', 'message' => 'No se pudo guardar el auto']);
        }
        $this->responder(201, ['status' => 'ok', 'message' => 'Auto agregado correctamente', 'id' => $nuevoId]);
    }

    public function actualizar($id) {
        $modelo = new AutoModel($this->db);
        if (!$modelo->buscarPorId((int) $id)) {
            return $this->responder(404, ['status' => 'error', 'message' => 'No se encontró ese auto']);
        }

        [$datos, $error] = $this->validar($modelo);
        if ($error) {
            return $this->responder(400, ['status' => 'error', 'message' => $error]);
        }

        if (!$modelo->actualizar((int) $id, ...$datos)) {
            return $this->responder(500, ['status' => 'error', 'message' => 'No se pudo modificar el auto']);
        }
        $this->responder(200, ['status' => 'ok', 'message' => 'Los datos del auto fueron modificados correctamente']);
    }
}
