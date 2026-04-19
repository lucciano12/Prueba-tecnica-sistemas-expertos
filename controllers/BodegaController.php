<?php
require_once __DIR__ . '/../models/Bodega.php';

class BodegaController
{
  private $model; //Nos sirve para almacenar la instancia del modelo Bodega

  public function __construct()
  { //Definimos el constructor
    $this->model = new Bodega(); //Se crea una instancia del modelo Bodega y se asigna a la propiedad model
  }

  //Listar todas las bodegas
  public function index()
  {
    $filtro = $_GET['estado'] ?? 'todos'; //Obtenemos el valor del filtro de estado desde la URL, si no se proporciona se asigna 'todos' por defecto
    $bodegas = $this->model->getAll($filtro); //Se llama al método getAll del modelo para obtener todas las bodegas de la base de datos y se almacena en la variable bodegas
    require_once __DIR__ . '/../views/bodegas/index.php';
  }

  //Mostrar formulario de creacion de una nueva bodega
  public function create()
  {
    require_once __DIR__ . '/../views/bodegas/create.php';
  }

  //Guardar una nueva bodega
  public function store()
  {
    $codigo = $_POST['codigo'] ?? ''; //Obtenemos el valor del campo codigo del formulario
    $nombre = $_POST['nombre'] ?? ''; //Obtenemos el valor del campo nombre del formulario
    $ubicacion = $_POST['ubicacion'] ?? ''; //Obtenemos el valor del campo ubicacion del formulario
    $dotacion = $_POST['dotacion'] ?? 0; //Obtenemos el valor del campo dotacion del formulario
    $estado = $_POST['estado'] ?? 'Activada'; //Obtenemos el valor del campo estado del formulario

    $this->model->create($codigo, $nombre, $ubicacion, $dotacion, $estado); //Se llama al método create del modelo para guardar la nueva bodega en la base de datos, pasando los valores obtenidos del formulario como parametros
    header('Location: index.php?action=index'); //Redirigimos al usuario a la página principal después de guardar la nueva bodega
    exit;
  }

  //Mostrar formulario de edicion de una bodega existente
  public function edit(){
    $id = $_GET['id'] ?? null;
    $bodega = $this->model->getById($id);
    require_once __DIR__ . '/../views/bodegas/edit.php';
  }

  //Actualizar bodega
  public function update(){
    $id = $_POST['id'] ?? null; //Obtenemos el valor del campo id 
    $codigo = $_POST['codigo'] ?? ''; //Obtenemos el valor del campo codigo
    $nombre = $_POST['nombre'] ?? ''; //Obtenemos el valor del campo nombre 
    $ubicacion = $_POST['ubicacion'] ?? ''; //Obtenemos el valor del campo ubicacion 
    $dotacion = $_POST['dotacion'] ?? 0; //Obtenemos el valor del campo dotacion 
    $estado = $_POST['estado'] ?? 'Activada'; //Obtenemos el valor del campo estado
    $this->model->update($id, $codigo, $nombre, $ubicacion, $dotacion, $estado);
    header('Location: index.php?action=index');
    exit;
  }

  //Eliminar bodega
  public function delete(){
    $id = $_GET['id'] ?? null; //Obtenemos el valor del campo id desde la URL
    $this->model->delete($id); //Se llama al metodo delete del modelo para eliminar la bodega de la base de datos, pasando el id como parametro
    header('Location: index.php?action=index'); //Redirigimos al usuario a la pagina principal despues de eliminar la bodega
    exit;
  }

}
