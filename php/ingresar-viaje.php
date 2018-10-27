<?php
    require "funciones.php";
    if(empty($_SERVER['CONTENT_TYPE'])) {
        echo "Arreglar content type";
    }
    $db = new mysqli('localhost', 'root', '', 'tarea2') or die("Hubo un problema en la conexión, intente más tarde");
    
    $origen = $_POST['comuna-origen'];
    $destino = $_POST['comuna-destino'];
    $fechaIda = reformatDate($_POST['fecha-viaje']);
    $fechaRegreso = $_POST['fecha-viaje'];
    $kilosDisp = $_POST['kilos-disponibles'];
    $espacioDisp = $_POST['espacio-disponible'];
    $mail = $_POST['email'];
    $celular = $_POST['celular'];

    if (!$id = mysqli_fetch_array($db->query("SELECT MAX(id) FROM viaje"))) {
        header("Location: ../index.html");
        die("No se pudo recuperar id");
    } else {
        $fid = $id[0] + 1;
    }

    // VALIDACION

    if(!agregar_viaje_validacion()) {
        header("Location: ../index.html");
        die("Validación de datos incorrecta");
    } else {
        // CAMBIAR FECHA VIAJE POR FECHA IDA Y VUELTA
        if(!$db->query("INSERT INTO viajess (id, fecha_ida, fecha_regreso, origen, destino, kilos_disponible, espacio_disponible, email_viajero, celular_viajero) VALUES ('$fid', '$fechaIda','$fechaRegreso','$origen','$destino','$kilosDisp','$espacioDisp','$mail','$celular');")) {
            // echo mysqli_error($db);
            header("Location: ../index.html");
            die("No se pudieron ingresar los datos, intente nuevamente.");
        }
    }


    $db->close();
?>