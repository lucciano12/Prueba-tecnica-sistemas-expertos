<?php
require_once __DIR__ . '/controllers/BodegaController.php'; // Se importa el controlador principal

$controller = new BodegaController(); // Se instancia el controlador

// Se obtiene la acción desde la URL, por defecto muestra el listado
$action = $_GET['action'] ?? 'index';

// Se verifica que el metodo exista en el controlador antes de ejecutarlo
// Esto previene llamadas a métodos no definidos
$allowedActions = ['index', 'create', 'store', 'edit', 'update', 'delete'];

if (in_array($action, $allowedActions)) {
    $controller->$action(); // Se ejecuta el metodo correspondiente a la accion
} else {
    // Si la accion no existe, redirige al listado
    header('Location: index.php?action=index');
    exit;
}