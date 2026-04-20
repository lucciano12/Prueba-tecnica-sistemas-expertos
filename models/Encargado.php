<?php
require_once __DIR__ . '/../config/database.php';

class Encargado
{
  private $pdo;

  public function __construct()
  {
    $this->pdo = getConnection();
  }

  // Obtener todos los encargados para poblar el select del formulario de edicion
  public function getAll()
  {
    $stmt = $this->pdo->query(
      "SELECT id, bodega_id, nombre || ' ' || apellido1 || ' ' || COALESCE(apellido2, '') AS nombre_completo
       FROM encargados
       ORDER BY nombre ASC"
    );
    return $stmt->fetchAll();
  }

  // Obtener los encargados actuales de una bodega especifica
  public function getByBodegaId($bodegaId)
  {
    $stmt = $this->pdo->prepare(
      "SELECT id FROM encargados WHERE bodega_id = :bodega_id"
    );
    $stmt->execute([':bodega_id' => $bodegaId]);
    return $stmt->fetchAll();
  }

  // Reasignar un encargado a otra bodega
  // Se actualiza el bodega_id del encargado seleccionado
  public function reasignar($encargadoId, $bodegaId)
  {
    $stmt = $this->pdo->prepare(
      "UPDATE encargados SET bodega_id = :bodega_id WHERE id = :id"
    );
    return $stmt->execute([
      ':bodega_id' => $bodegaId,
      ':id'        => $encargadoId
    ]);
  }
}
