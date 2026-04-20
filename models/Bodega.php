<?php
require_once __DIR__ . '/../config/database.php';

class Bodega
{
  private $pdo; // Almacena la conexion a la base de datos

  public function __construct() // El constructor se ejecuta al crear una instancia de la clase y establece la conexion a la BD
  {
    $this->pdo = getConnection();
  }

  // Obtener todas las bodegas con el nombre completo de sus encargados
  // Se usa LEFT JOIN para incluir bodegas sin encargado y STRING_AGG para concatenar
  // multiples encargados en una sola columna separados por ' / '
  public function getAll($filtro = 'todos')
  {
    $sql = "
      SELECT
        b.*,
        STRING_AGG(
          e.nombre || ' ' || e.apellido1 || ' ' || COALESCE(e.apellido2, ''),
          ' / '
          ORDER BY e.id
        ) AS encargado_nombre
      FROM bodegas b
      LEFT JOIN encargados e ON e.bodega_id = b.id
    ";

    if ($filtro === 'todos') {
      $sql .= "GROUP BY b.id ORDER BY b.id ASC";
      $stmt = $this->pdo->query($sql); // Se ejecuta sin parametros cuando no hay filtro
    } else {
      $sql .= "WHERE b.estado = :estado GROUP BY b.id ORDER BY b.id ASC";
      $stmt = $this->pdo->prepare($sql); // Se prepara la consulta con filtro por estado para evitar inyeccion SQL
      $stmt->execute([':estado' => $filtro]);
    }

    return $stmt->fetchAll(); // Devuelve el resultado como array asociativo
  }

  // Obtener una bodega por su id
  public function getById($id)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM bodegas WHERE id = :id"); // Consulta preparada para obtener una bodega por su id
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(); // Devuelve un solo registro como array asociativo
  }

  // Crear una nueva bodega
  public function create($codigo, $nombre, $ubicacion, $dotacion, $estado)
  {
    $stmt = $this->pdo->prepare(
      "INSERT INTO bodegas (codigo, nombre, ubicacion, dotacion, estado)
       VALUES (:codigo, :nombre, :ubicacion, :dotacion, :estado)"
    ); // Se usan placeholders para prevenir inyeccion SQL
    return $stmt->execute([
      ':codigo'    => $codigo,
      ':nombre'    => $nombre,
      ':ubicacion' => $ubicacion,
      ':dotacion'  => $dotacion,
      ':estado'    => $estado
    ]);
  }

  // Actualizar una bodega existente
  // Se actualiza updated_at con CURRENT_TIMESTAMP para registrar la fecha de modificacion
  public function update($id, $codigo, $nombre, $ubicacion, $dotacion, $estado)
  {
    $stmt = $this->pdo->prepare(
      "UPDATE bodegas
         SET codigo = :codigo,
             nombre = :nombre,
             ubicacion = :ubicacion,
             dotacion = :dotacion,
             estado = :estado,
             updated_at = CURRENT_TIMESTAMP
       WHERE id = :id"
    );
    return $stmt->execute([
      ':id'        => $id,
      ':codigo'    => $codigo,
      ':nombre'    => $nombre,
      ':ubicacion' => $ubicacion,
      ':dotacion'  => $dotacion,
      ':estado'    => $estado
    ]);
  }

  // Eliminar una bodega por su id
  // Los encargados asociados se eliminan automaticamente por el ON DELETE CASCADE definido en la BD
  public function delete($id)
  {
    $stmt = $this->pdo->prepare("DELETE FROM bodegas WHERE id = :id");
    return $stmt->execute([':id' => $id]);
  }
}
