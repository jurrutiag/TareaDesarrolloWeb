<?php
    $passed = true;
    $posted = false;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $posted = true;

        require "funciones.php";
        
        if(empty($_SERVER['CONTENT_TYPE'])) {
            $passed = false;
            // echo "Arreglar content type";
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
            $passed = false;
            // die("No se pudo recuperar id");
        } else {
            $fid = $id[0] + 1;
        }

        // VALIDACION

        if(false) {
            // header("Location: ../index.html");
            $passed = false;
            // die("Validación de datos incorrecta");
        } else {
            // Guardar foto
            $fotoDir = '../fotos/'.$fid.'.jpg';
            if (!move_uploaded_file($foto, $fotoDir)) {
                // header...
                //move_uploaded_file(basename($foto), $fotoDir);
                $passed = false;
                // die('Error al subir foto');
            }

            // CAMBIAR FECHA VIAJE POR FECHA IDA Y VUELTA
            if(!$db->query("INSERT INTO encargo (id, descripcion, origen, destino, espacio, kilos, foto, email_encargador, celular_encargador) VALUES ($fid, '$descripcion','$origen', '$destino', '$espacioSol', '$kilosSol', '$fotoDir', '$mail', '$celular');")) {
                // echo mysqli_error($db);
                // header("Location: ../index.html");
                $passed = false;
                // die("No se pudieron ingresar los datos, intente nuevamente.");
            }
        }


        $db->close();
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="/TareaDesarrolloWeb/styles.css">
        <title>Agregar Encargo</title>
    </head>
    <body onload="onloadFunction()">

        <div class="container">

            <div class="first-half">

            </div>

            <div class="second-half">
                <?php
                if (!$passed) {
                    echo "<ul class='vertical-menu'>
                        <li><label class='active' style='background-color: red;'>Hubo un error en la solicitud, intente más tarde</label></li>
                        <li><a href='/TareaDesarrolloWeb/index.html'>Volver al menú principal.</a></li>
                        </ul>";
                }
                if (!$posted) {
                    echo "<form enctype='multipart/form-data' action='' method='post'>
                        <div id='main-div' class='vertical-form'>
                            <h3 id='descripcion-h'>Descripción Encargo (250 caracteres restantes):</h3>
                            <input name='descripcion' id='descripcion' oninput='updateDescripcion()' maxlength='250' placeholder='Ej: Caja de regalos (Max 250 caracteres)'>
            
                            <h3 id='espacio-solicitado-h'>Espacio:</h3>
                            <select name='espacio-solicitado' id='espacio-solicitado'>
                                <option value='1'>10x10x10</option>
                                <option value='2'>20x20x20</option>
                                <option value='3'>30x30x30</option>
                            </select>
            
                            <h3 id='kilos-solicitados-h'>Kilos:</h3>
                            <select name='kilos-solicitados' id='kilos-solicitados'>
                                <option value='1'>200 gr</option>
                                <option value='2'>500 gr</option>
                                <option value='3'>800 gr</option>
                                <option value='4'>1 kg</option>
                                <option value='5'>1.5 kg</option>
                                <option value='6'>2 kg</option>
                            </select>
            
                            <h3 id='region-origen-h'>Región Origen:</h3>
                            <select name='region-origen' id='region-origen' onchange='comunaOrigen()'>
            
                            </select>
                            <h3 id='comuna-origen-h'>Comuna Origen:</h3>
                            <select name='comuna-origen' id='comuna-origen'>
            
                            </select>
                            <h3 id='region-destino-h'>Región Destino:</h3>
                            <select name='region-destino' id='region-destino' onchange='comunaDestino()'>
            
                            </select>
                            
                            <h3 id='comuna-destino-h'>Comuna Destino:</h3>
                            <select name='comuna-destino' id='comuna-destino'>
            
                            </select>
            
                            <h3 id='foto-encargo-h'>Foto Encargo [Se aceptan formatos jpg, jpeg, exif, tiff, bmp, png, ppm, hdr, bpg]:</h3>
                            <input type='file' name='foto-encargo' id='foto-encargo'>
            
                            <h3 id='email-h'>Email Encargador:</h3>
                            <input name='email' id='email' placeholder='Ej: juan@gmail.com'>
            
                            <h3 id='celular-h'>Número de Celular de Encargador:</h3>
                            <input name='celular' id='celular' placeholder='+569XXXXXXXX'>
                            <br>
                            <input type='submit' value='Grabar Encargo' onclick='return agregar_encargo_validacion()'>
                            
                        </div>
                    </form>
                    <br>
                    <div class='button-container'>
                        <button id='return-button' onclick='index()' type='button'>Volver al menú principal</button>
                    </div>
                    <br>
                    <br>";
                }
                ?>
            </div>
        </div>

        <script src="/TareaDesarrolloWeb/scripts.js"></script>
    </body>
</html>