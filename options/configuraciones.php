<?php
    require_once("db_config.php");

    $errors = array("0" => "Error en la conexión al servidor","1" => "Error en el encoding", "2" => "Error en la solicitud al servidor", "3" => "Error en la subida de archivo", "4" => "Operación inválida");

    $encoding = "utf8";

    // Configuracion para objetos DateTime

    date_default_timezone_set('Chile/Continental');

?>