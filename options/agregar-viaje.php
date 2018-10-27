<?php
    $passed = true;
    $posted = false;

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $posted = true;
        require "funciones.php";

        if(empty($_SERVER['CONTENT_TYPE'])) {
            $passed = false;
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
            $passed = false;
            // die("No se pudo recuperar id");
        } else {
            $fid = $id[0] + 1;
        }

        // VALIDACION

        if(!agregar_viaje_validacion()) {
            $passed = false;
            // die("Validación de datos incorrecta");
        } else {
            // CAMBIAR FECHA VIAJE POR FECHA IDA Y VUELTA
            if(!$db->query("INSERT INTO viaje (id, fecha_ida, fecha_regreso, origen, destino, kilos_disponible, espacio_disponible, email_viajero, celular_viajero) VALUES ('$fid', '$fechaIda','$fechaRegreso','$origen','$destino','$kilosDisp','$espacioDisp','$mail','$celular');")) {
                // echo mysqli_error($db);
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

        <title>Agregar Viaje</title>
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
                    echo "<form id='main-form' action='' method='post' enctype='multipart/form-data'>
                        <div id='main-div' class='vertical-form'>
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
                            <h3 id='fecha-viaje-h'>Fecha Viaje:</h3>
                            <input name='fecha-viaje' id='fecha-viaje' size='10' placeholder='DD/MM/AAAA'>
                            
                            <h3 id='espacio-disponible-h'>Espacio Disponible:</h3>
                            <select name='espacio-disponible' id='espacio-disponible'>
                                <option value='1'>10x10x10</option>
                                <option value='2'>20x20x20</option>
                                <option value='3'>30x30x30</option>
                            </select>
                            <h3 id='kilos-disponibles-h'>Kilos Disponibles:</h3>
                            <select name='kilos-disponibles' id='kilos-disponibles'>
                                <option value='1'>200 gr</option>
                                <option value='2'>500 gr</option>
                                <option value='3'>800 gr</option>
                                <option value='4'>1 kg</option>
                                <option value='5'>1.5 kg</option>
                                <option value='6'>2 kg</option>
                            </select>
                            <h3 id='email-h'>Email:</h3>
                            <input name='email' id='email' placeholder='Ej: juan@gmail.com'>
                            
                            <h3 id='celular-h'>Número celular:</h3>
                            <input name='celular' id='celular' placeholder='+569XXXXXXXX'>
                            <br>
                            <input type='submit' value='Ingresar Viaje' onclick='return agregar_viaje_validacion()'>
            
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