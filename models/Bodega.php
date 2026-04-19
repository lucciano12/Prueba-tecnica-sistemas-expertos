<?php
require_once __DIR__ . '/../config/database.php';

class Bodega
{
  private $pdo; //Nos sirve para almacenar la conexion a la base de datos

  public function __construct() //El constructor se ejecuta al crear una instancia de la clase bodega y se encarga de hacer la conexion a la BD
  {
    $this->pdo = getConnection();
  }

  //Obetener todas las bodegas
  public function getAll($filtro = 'todos') //Con el parametro filtro podemos obtener todas las bodegas o solo las activas o desactivadas
  {
    if ($filtro === 'todos') {
      $stmt = $this->pdo->query("SELECT * FROM bodegas ORDER BY id ASC"); // Se obtienen todas las bodegas ordenadas por id ascendente para garantizar un orden consistente en el listado
    }else{
      $stmt = $this->pdo->prepare("SELECT * FROM bodegas WHERE estado = :estado ORDER BY id ASC");  // Se prepara la consulta con filtro por estado para evitar inyeccion SQL
      $stmt->execute([':estado' => $filtro]); //Se ejecuta la consulta, pasando el valor del filtro como un parametro
    }
    return $stmt->fetchAll(); //Se devuelve el resultado de la consulta como un array asociativo
  }

  //Obtener una bodega por su id
  public function getById($id)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM bodegas WHERE id = :id"); //Se prepara una consulta SQL para obtener una bodega por su id, utilizando un marcador de posición :id
    $stmt->execute([':id' => $id]); //Se ejecuta la consulta, pasando el valor del id como un parámetro
    return $stmt->fetch(); //Se devuelve el resultado de la consulta como un array asociativo
  }

  //Crear una nueva bodega
  public function create($codigo, $nombre, $ubicacion, $dotacion, $estado)
  {
    $stmt = $this->pdo->prepare(
      "INSERT INTO bodegas (codigo, nombre, ubicacion,  dotacion, estado) VALUES (:codigo, :nombre, :ubicacion, :dotacion, :estado)
      "
    ); //Se prepara una consulta para insertar una nueva bodega en la tabla bodegas y utilizamos placeholders para los valores que se van a insertar
    return $stmt->execute([
      ':codigo' => $codigo,
      ':nombre' => $nombre,
      ':ubicacion' => $ubicacion,
      ':dotacion' => $dotacion,
      ':estado' => $estado
    ]);
  }

  //Actualizar una bodega existente
  public function update($id, $codigo, $nombre, $ubicacion, $dotacion, $estado)
  {
    $stmt = $this->pdo->prepare(
      "UPDATE bodegas 
         SET codigo = :codigo, nombre = :nombre, ubicacion = :ubicacion, dotacion = :dotacion, estado = :estado, updated_at = CURRENT_TIMESTAMP WHERE id = :id"
    ); //Se prepara una consulta para actualizar una bodega existente en la tabla bodegas, utilizando placeholders para los valores que se van a actualizar y el id de la bodega que se va a actualizar
    return $stmt->execute([
      ':id' => $id,
      ':codigo' => $codigo,
      ':nombre' => $nombre,
      ':ubicacion' => $ubicacion,
      ':dotacion' => $dotacion,
      ':estado' => $estado
    ]);
  }

  //Eliminar una bodega por su id
  public function delete($id)
  {
    $stmt = $this->pdo->prepare("DELETE FROM bodegas WHERE id = :id"); // Se prepara una consulta para eliminar una bodega por su id
    return $stmt->execute([':id' => $id]);
  }
}
