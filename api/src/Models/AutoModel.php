<?php
namespace App\Models;

class AutoModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Listado: cada auto con su marca (JOIN) y si ya fue vendido.
    public function listar() {
        try {
            $stmt = $this->db->query(
                "SELECT v.id_vehiculo, m.nombre_marca, v.modelo, v.anio, v.precio, v.estado,
                        (ve.id_venta IS NOT NULL) AS vendido
                 FROM VEHICULOS v
                 INNER JOIN MARCAS m ON m.id_marca = v.id_marca
                 LEFT JOIN VENTAS ve ON ve.id_vehiculo = v.id_vehiculo
                 ORDER BY m.nombre_marca, v.modelo"
            );
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return false;
        }
    }

    // Un auto con toda su informacion (incluye datos de la marca y de la venta, si la hubo).
    public function buscarPorId($id) {
        try {
            $stmt = $this->db->prepare(
                "SELECT v.id_vehiculo, v.id_marca, m.nombre_marca, m.pais_origen,
                        v.modelo, v.anio, v.precio, v.estado,
                        ve.fecha_venta, ve.monto_total,
                        c.nombre_completo AS cliente, e.nombre_completo AS vendedor
                 FROM VEHICULOS v
                 INNER JOIN MARCAS m ON m.id_marca = v.id_marca
                 LEFT JOIN VENTAS ve ON ve.id_vehiculo = v.id_vehiculo
                 LEFT JOIN CLIENTES c ON c.id_cliente = ve.id_cliente
                 LEFT JOIN EMPLEADOS e ON e.id_empleado = ve.id_empleado
                 WHERE v.id_vehiculo = :id
                 LIMIT 1"
            );
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function marcas() {
        try {
            $stmt = $this->db->query("SELECT id_marca, nombre_marca, pais_origen FROM MARCAS ORDER BY nombre_marca");
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function marcaExiste($idMarca) {
        $stmt = $this->db->prepare("SELECT 1 FROM MARCAS WHERE id_marca = ?");
        $stmt->execute([$idMarca]);
        return (bool) $stmt->fetchColumn();
    }

    // Devuelve el id nuevo o false.
    public function crear($idMarca, $modelo, $anio, $precio, $estado) {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO VEHICULOS (id_marca, modelo, anio, precio, estado)
                 VALUES (:id_marca, :modelo, :anio, :precio, :estado)"
            );
            $stmt->execute([
                'id_marca' => $idMarca, 'modelo' => $modelo, 'anio' => $anio,
                'precio' => $precio, 'estado' => $estado
            ]);
            return (int) $this->db->lastInsertId();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function actualizar($id, $idMarca, $modelo, $anio, $precio, $estado) {
        try {
            $stmt = $this->db->prepare(
                "UPDATE VEHICULOS
                 SET id_marca = :id_marca, modelo = :modelo, anio = :anio, precio = :precio, estado = :estado
                 WHERE id_vehiculo = :id"
            );
            $stmt->execute([
                'id_marca' => $idMarca, 'modelo' => $modelo, 'anio' => $anio,
                'precio' => $precio, 'estado' => $estado, 'id' => $id
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
