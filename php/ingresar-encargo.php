<?php
    require "funciones.php";
    if(empty($_SERVER['CONTENT_TYPE'])) {
        echo "Arreglar content type";
    }
    $db = new mysqli('localhost', 'root', '', 'tarea2') or die("Hubo un problema en la conexión");
    
    $descripcion = $_POST['descripcion'];
    $espacioSol = $_POST['espacio-solicitado'];
    $kilosSol = $_POST['kilos-solicitados'];
    $origen = $_POST['comuna-origen'];
    $destino = $_POST['comuna-destino'];
    $foto = $_FILES['foto-encargo']['tmp_name'];
    $mail = $_POST['email'];
    $celular = $_POST['celular'];


    if (!$id = mysqli_fetch_array($db->query("SELECT MAX(id) FROM encargo"))) {
        header("Location: ../index.html");
        die("No se pudo recuperar id");
    } else {
        $fid = $id[0] + 1;
    }

    // VALIDACION

    if(false) {
        // header("Location: ../index.html");
        die("Validación de datos incorrecta");
    } else {
        // Guardar foto
        $fotoDir = '../fotos/'.$fid.'.jpg';
        if (!move_uploaded_file($foto, $fotoDir)) {
            // header...
            //move_uploaded_file(basename($foto), $fotoDir);
            die('Error al subir foto');
        }

        // CAMBIAR FECHA VIAJE POR FECHA IDA Y VUELTA
        if(!$db->query("INSERT INTO encargo (id, descripcion, origen, destino, espacio, kilos, foto, email_encargador, celular_encargador) VALUES ($fid, '$descripcion','$origen', '$destino', '$espacioSol', '$kilosSol', '$fotoDir', '$mail', '$celular');")) {
            // echo mysqli_error($db);
            // header("Location: ../index.html");
            die("No se pudieron ingresar los datos, intente nuevamente.");
        }
    }


    $db->close();
?>