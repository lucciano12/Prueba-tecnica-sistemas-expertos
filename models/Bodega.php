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
  public function getAll()
  {
    $stmt = $this->pdo->query("SELECT * from bodegas"); //Se ejecuta una consulta SQL para obtener todas las filas de la tabla bodegas
    return $stmt->fetchAll(); //Se devuelve el resultado de la consulta como un array asociativo
  }

  //Obtener una bodega por su id
  public function getById($id)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM bodegas WHERE id = :id"); //Se prepara una consulta SQL para obtener una bodega por su id, utilizando un marcador de posición :id
    $stmt->execute(['id' => $id]); //Se ejecuta la consulta, pasando el valor del id como un parámetro
    return $stmt->fetch(); //Se devuelve el resultado de la consulta como un array asociativo
  }

  //Crear una nueva bodega
  public function create($nombre, $ubicacion, $capacidad, $estado){
    $stmt = $this->pdo-> prepare(
      "INSERT INTO bodegas (nombre, ubicacion, capacidad, estado) VALUES (:nombre, :ubicacion, :capacidad, :estado)
      "
    ); //Se prepara una consulta para insertar una nueva bodega en la tabla bodegas y utilizamos placeholders para los valores que se van a insertar
    return $stmt->execute([
      'nombre' => $nombre,
      'ubicacion' => $ubicacion,
      'capacidad' => $capacidad,
      'estado' => $estado
    ]);
  }

  //Actualizar una bodega existente
  public function update($id, $nombre, $ubicacion, $capacidad, $estado){
    $stmt = $this->pdo->prepare(
      "UPDATE bodegas SET nombre = :nombre, ubicacion = :ubicacion, capacidad = :capacidad, estado = :estado WHERE id = :id"
    ); //Se prepara una consulta para actualizar una bodega existente en la tabla bodegas, utilizando placeholders para los valores que se van a actualizar y el id de la bodega que se va a actualizar
    return $stmt->execute([
      'id' => $id,
      'nombre' => $nombre,
      'ubicacion' => $ubicacion,
      'capacidad' => $capacidad,
      'estado' => $estado
    ]);
  }

  //Eliminar una bodega por su id
  public function delete($id){
    $stmt = $this->pdo->prepare("DELETE FROM bodegas WHERE id = :id");// Se prepara una consulta para eliminar una bodega por su id
    return $stmt->execute(['id' => $id]);
  }

}
