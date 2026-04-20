<?php
require_once __DIR__ . '/../models/Bodega.php';
require_once __DIR__ . '/../models/Encargado.php';

class BodegaController
{
  private $model;
  private $encargadoModel;

  public function __construct()
  {
    $this->model          = new Bodega();
    $this->encargadoModel = new Encargado();
  }

  // Listar todas las bodegas
  public function index()
  {
    $filtro  = $_GET['estado'] ?? 'todos';
    $bodegas = $this->model->getAll($filtro);
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
    $codigo    = trim($_POST['codigo']    ?? '');
    $nombre    = trim($_POST['nombre']    ?? '');
    $ubicacion = trim($_POST['ubicacion'] ?? '');
    $dotacion  = trim($_POST['dotacion']  ?? '');
    $estado    = trim($_POST['estado']    ?? '');

    $errores = [];

    if (empty($codigo)) {
      $errores[] = 'El codigo es obligatorio';
    } elseif (strlen($codigo) > 5) {
      $errores[] = 'El codigo no puede superar los 5 caracteres';
    }

    if (empty($nombre)) {
      $errores[] = 'El nombre es obligatorio';
    } elseif (strlen($nombre) > 100) {
      $errores[] = 'El nombre no puede superar los 100 caracteres';
    }

    if (empty($ubicacion)) {
      $errores[] = 'La ubicacion es obligatoria';
    } elseif (strlen($ubicacion) > 200) {
      $errores[] = 'La ubicacion no puede superar los 200 caracteres';
    }

    if ($dotacion === '') {
      $errores[] = 'La dotacion es obligatoria';
    } elseif (!is_numeric($dotacion) || (int)$dotacion < 0) {
      $errores[] = 'La dotacion debe ser un numero mayor o igual a 0';
    }

    if (empty($estado)) {
      $errores[] = 'El estado es obligatorio';
    } elseif (!in_array($estado, ['Activada', 'Desactivada'])) {
      $errores[] = 'El estado ingresado no es valido';
    }

    if (!empty($errores)) {
      $error = implode('<br>', $errores);
      require_once __DIR__ . '/../views/bodegas/create.php';
      return;
    }

    $this->model->create($codigo, $nombre, $ubicacion, (int)$dotacion, $estado);
    header('Location: index.php?action=index');
    exit;
  }

  // Mostrar formulario de edicion
  // Se cargan todos los encargados para el select y los encargados actuales de la bodega
  public function edit()
  {
    $id     = (int) ($_GET['id'] ?? 0);
    $bodega = $this->model->getById($id);

    if (!$bodega) {
      header('Location: index.php?action=index');
      exit;
    }

    $encargados         = $this->encargadoModel->getAll();           // Todos los encargados (para el select)
    $encargadosActuales = $this->encargadoModel->getByBodegaId($id); // Encargados actuales de esta bodega
    $idsActuales        = array_column($encargadosActuales, 'id');   // Solo los ids para marcar selected

    require_once __DIR__ . '/../views/bodegas/edit.php';
  }

  // Actualizar bodega existente
  public function update()
  {
    $id          = (int) ($_GET['id'] ?? 0);
    $codigo      = trim($_POST['codigo']      ?? '');
    $nombre      = trim($_POST['nombre']      ?? '');
    $ubicacion   = trim($_POST['ubicacion']   ?? '');
    $dotacion    = trim($_POST['dotacion']    ?? '');
    $estado      = trim($_POST['estado']      ?? '');
    $encargadoId = (int) ($_POST['encargado_id'] ?? 0); // Encargado seleccionado en el formulario

    if ($id <= 0) {
      header('Location: index.php?action=index');
      exit;
    }

    $errores = [];

    if (empty($codigo)) {
      $errores[] = 'El codigo es obligatorio';
    } elseif (strlen($codigo) > 5) {
      $errores[] = 'El codigo no puede superar los 5 caracteres';
    }

    if (empty($nombre)) {
      $errores[] = 'El nombre es obligatorio';
    } elseif (strlen($nombre) > 100) {
      $errores[] = 'El nombre no puede superar los 100 caracteres';
    }

    if (empty($ubicacion)) {
      $errores[] = 'La ubicacion es obligatoria';
    } elseif (strlen($ubicacion) > 200) {
      $errores[] = 'La ubicacion no puede superar los 200 caracteres';
    }

    if ($dotacion === '') {
      $errores[] = 'La dotacion es obligatoria';
    } elseif (!is_numeric($dotacion) || (int)$dotacion < 0) {
      $errores[] = 'La dotacion debe ser un numero mayor o igual a 0';
    }

    if (empty($estado)) {
      $errores[] = 'El estado es obligatorio';
    } elseif (!in_array($estado, ['Activada', 'Desactivada'])) {
      $errores[] = 'El estado ingresado no es valido';
    }

    if (!empty($errores)) {
      $error              = implode('<br>', $errores);
      $bodega             = $this->model->getById($id);
      $encargados         = $this->encargadoModel->getAll();
      $encargadosActuales = $this->encargadoModel->getByBodegaId($id);
      $idsActuales        = array_column($encargadosActuales, 'id');
      require_once __DIR__ . '/../views/bodegas/edit.php';
      return;
    }

    // Actualizar datos de la bodega
    $this->model->update($id, $codigo, $nombre, $ubicacion, (int)$dotacion, $estado);

    // Reasignar encargado si se selecciono uno valido
    if ($encargadoId > 0) {
      $this->encargadoModel->reasignar($encargadoId, $id);
    }

    header('Location: index.php?action=index');
    exit;
  }

  // Eliminar bodega por id
  public function delete()
  {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id <= 0) {
      header('Location: index.php?action=index');
      exit;
    }
    $this->model->delete($id); // Los encargados se borran por ON DELETE CASCADE
    header('Location: index.php?action=index');
    exit;
  }
}
