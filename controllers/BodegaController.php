<?php
require_once __DIR__ . '/../models/Bodega.php';

class BodegaController
{
  private $model; // Almacena la instancia del modelo Bodega

  public function __construct()
  {
    $this->model = new Bodega(); // Se crea una instancia del modelo Bodega
  }

  // Listar todas las bodegas
  public function index()
  {
    $filtro  = $_GET['estado'] ?? 'todos'; // Se obtiene el filtro de estado desde la URL, por defecto 'todos'
    $bodegas = $this->model->getAll($filtro); // Se obtienen las bodegas segun el filtro
    require_once __DIR__ . '/../views/bodegas/index.php';
  }

  // Mostrar formulario de creacion
  public function create()
  {
    require_once __DIR__ . '/../views/bodegas/create.php';
  }

  // Guardar nueva bodega
  public function store()
  {
    // Se obtienen y limpian los valores del formulario
    $codigo    = trim($_POST['codigo']    ?? '');
    $nombre    = trim($_POST['nombre']    ?? '');
    $ubicacion = trim($_POST['ubicacion'] ?? '');
    $dotacion  = trim($_POST['dotacion']  ?? '');
    $estado    = trim($_POST['estado']    ?? '');

    // Validacion servidor: se verifica cada campo antes de guardar en la BD
    $errores = [];

    // Codigo: obligatorio y maximo 5 caracteres segun especificacion
    if (empty($codigo)) {
      $errores[] = 'El codigo es obligatorio';
    } elseif (strlen($codigo) > 5) {
      $errores[] = 'El codigo no puede superar los 5 caracteres';
    }

    // Nombre: obligatorio y maximo 100 caracteres
    if (empty($nombre)) {
      $errores[] = 'El nombre es obligatorio';
    } elseif (strlen($nombre) > 100) {
      $errores[] = 'El nombre no puede superar los 100 caracteres';
    }

    // Ubicacion: obligatoria y maximo 200 caracteres
    if (empty($ubicacion)) {
      $errores[] = 'La ubicacion es obligatoria';
    } elseif (strlen($ubicacion) > 200) {
      $errores[] = 'La ubicacion no puede superar los 200 caracteres';
    }

    // Dotacion: obligatoria, debe ser un numero entero mayor o igual a 0
    if ($dotacion === '') {
      $errores[] = 'La dotacion es obligatoria';
    } elseif (!is_numeric($dotacion) || (int)$dotacion < 0) {
      $errores[] = 'La dotacion debe ser un numero mayor o igual a 0';
    }

    // Estado: debe ser uno de los valores permitidos
    if (empty($estado)) {
      $errores[] = 'El estado es obligatorio';
    } elseif (!in_array($estado, ['Activada', 'Desactivada'])) {
      $errores[] = 'El estado ingresado no es valido';
    }

    // Si hay errores se vuelve al formulario mostrando los mensajes
    if (!empty($errores)) {
      $error = implode('<br>', $errores);
      require_once __DIR__ . '/../views/bodegas/create.php';
      return;
    }

    // Sin errores: se guarda la bodega en la BD
    $this->model->create($codigo, $nombre, $ubicacion, (int)$dotacion, $estado);
    header('Location: index.php?action=index');
    exit;
  }

  // Mostrar formulario de edicion
  public function edit()
  {
    $id     = $_GET['id'] ?? null;
    $bodega = $this->model->getById($id);

    // Si no existe la bodega se redirige al listado
    if (!$bodega) {
      header('Location: index.php?action=index');
      exit;
    }

    require_once __DIR__ . '/../views/bodegas/edit.php';
  }

  // Actualizar bodega existente
  public function update()
  {
    // Se obtienen y limpian los valores del formulario
    $id        = trim($_POST['id']        ?? '');
    $codigo    = trim($_POST['codigo']    ?? '');
    $nombre    = trim($_POST['nombre']    ?? '');
    $ubicacion = trim($_POST['ubicacion'] ?? '');
    $dotacion  = trim($_POST['dotacion']  ?? '');
    $estado    = trim($_POST['estado']    ?? '');

    // Validacion servidor: mismas reglas que en store()
    $errores = [];

    // Codigo: obligatorio y maximo 5 caracteres segun especificacion
    if (empty($codigo)) {
      $errores[] = 'El codigo es obligatorio';
    } elseif (strlen($codigo) > 5) {
      $errores[] = 'El codigo no puede superar los 5 caracteres';
    }

    // Nombre: obligatorio y maximo 100 caracteres
    if (empty($nombre)) {
      $errores[] = 'El nombre es obligatorio';
    } elseif (strlen($nombre) > 100) {
      $errores[] = 'El nombre no puede superar los 100 caracteres';
    }

    // Ubicacion: obligatoria y maximo 200 caracteres
    if (empty($ubicacion)) {
      $errores[] = 'La ubicacion es obligatoria';
    } elseif (strlen($ubicacion) > 200) {
      $errores[] = 'La ubicacion no puede superar los 200 caracteres';
    }

    // Dotacion: obligatoria, debe ser un numero entero mayor o igual a 0
    if ($dotacion === '') {
      $errores[] = 'La dotacion es obligatoria';
    } elseif (!is_numeric($dotacion) || (int)$dotacion < 0) {
      $errores[] = 'La dotacion debe ser un numero mayor o igual a 0';
    }

    // Estado: debe ser uno de los valores permitidos
    if (empty($estado)) {
      $errores[] = 'El estado es obligatorio';
    } elseif (!in_array($estado, ['Activada', 'Desactivada'])) {
      $errores[] = 'El estado ingresado no es valido';
    }

    // Si hay errores se obtiene la bodega y se vuelve al formulario de edicion
    if (!empty($errores)) {
      $error  = implode('<br>', $errores);
      $bodega = $this->model->getById($id);
      require_once __DIR__ . '/../views/bodegas/edit.php';
      return;
    }

    // Sin errores: se actualiza la bodega en la BD
    $this->model->update($id, $codigo, $nombre, $ubicacion, (int)$dotacion, $estado);
    header('Location: index.php?action=index');
    exit;
  }

  // Eliminar bodega por id
  public function delete()
  {
    $id = $_GET['id'] ?? null; // Se obtiene el id desde la URL
    $this->model->delete($id); // Se elimina la bodega, los encargados se borran por ON DELETE CASCADE
    header('Location: index.php?action=index');
    exit;
  }
}
