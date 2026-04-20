<?php

// Configuracion de la base de datos
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'sistemas_expertos_db');
define('DB_USER', 'postgres');
define('DB_PASS', '');

// Función para obtener la conexion a la base de datos
function getConnection(){
  try{
    $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME; //Se define el DSN para PostgreSQL, con host, puerto y nombre de la base de datos
    $pdo = new PDO($dsn, DB_USER, DB_PASS); // Se crea el objeto o la instancia de PDO, pasando el DSN, el usuario y la contraseña
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Se establece el modo de error para que lance excepciones en caso de errores
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //Configura el modo de obtencion de resultados para que devuelva un array asociativo por defecto
    return $pdo; // Se devuelve la instancia de PDO para ser utilizada 

  }catch(PDOException $e){ //Captura excepciones
    echo "Error de conexión: " . $e->getMessage(); //Muestra un mensaje de error si la conexion falla
    exit;
  }
}