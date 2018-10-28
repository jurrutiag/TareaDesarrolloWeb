<?php
    $firstTime = true;

    require "datos_comunas.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        $passed = true;
        $firstTime = false;

        require "funciones.php";


        $unFormattedGoDate = htmlspecialchars($_POST['fecha-viaje']);
        $origen = htmlspecialchars($_POST['comuna-origen']);
        $destino = htmlspecialchars($_POST['comuna-destino']);
        $kilosDisp = htmlspecialchars($_POST['kilos-disponibles']);
        $espacioDisp = htmlspecialchars($_POST['espacio-disponible']);
        $mail = htmlspecialchars($_POST['email']);
        $celular = htmlspecialchars($_POST['celular']);

        $fechaIda = reformatDate($unFormattedGoDate);
        $fechaRegreso = reformatDate($unFormattedGoDate);

        if(empty($_SERVER['CONTENT_TYPE'])) {
            $passed = false;
            // echo "Arreglar content type";
        }
        $db = new mysqli('localhost', 'root', '', 'tarea2');
        if ($db->connect_error) {
            $passed = false;
            $mensajeError = "Error en la conexión al servidor";
        } else {

            if (!$id = mysqli_fetch_array($db->query("SELECT MAX(id) FROM viaje"))) {
                $passed = false;
                // die("No se pudo recuperar id");
                $mensajeError = "Error en la solicitud al servidor";
            } else {
                $fid = $id[0] + 1;
            }

            // VALIDACION

            if(!agregar_viaje_validacion()) {
                $passed = false;
                $mensajeError = "Error en la validación de los datos";
                // die("Validación de datos incorrecta");
            } else {
                $stmt = $db->prepare("INSERT INTO viaje (id, fecha_ida, fecha_regreso, origen, destino, kilos_disponible, espacio_disponible, email_viajero, celular_viajero) VALUES (?,?,?,?,?,?,?,?,?);");
                if ($stmt) {
                    $bp = $stmt->bind_param("issiiiiss",  $fid ,  $fechaIda , $fechaRegreso , $origen , $destino , $kilosDisp , $espacioDisp , $mail , $celular );
                    if ($bp) {
                        $ex = $stmt->execute();
                    }
                }
                // CAMBIAR FECHA VIAJE POR FECHA IDA Y VUELTA
                if(!$stmt || !$bp || !$ex) {
                    // echo mysqli_error($db);
                    $passed = false;
                    $mensajeError = "Error en la solicitud al servidor";
                    // die("No se pudieron ingresar los datos, intente nuevamente.");
                }
            }


            $db->close();
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="../styles.css">

        <title>Agregar Viaje</title>
    </head>
    <body>
        
        <div class="container">

            <div class="first-half">
                
            </div>


            <div class="second-half">
                <?php
                    if ($firstTime) {
                        $reg_origen = "";
                        $reg_destino = "";
                        $com_origen = "";
                        $com_destino = "";
                        for ($i = 1; $i < sizeof($regiones_ids); $i++) {
                            
                            $reg_origen = $reg_origen."<option value=$i> $regiones_ids[$i] </option>";
                            $reg_destino = $reg_destino."<option value=$i> $regiones_ids[$i] </option>";

                        }

                        for($com = 0; $com < sizeof($comunasArr[1]); $com++) {
                            $com_origen_val = $comunasArr[1][$com][0];
                            $com_origen_name = $comunasArr[1][$com][1];
                            $com_destino_val = $comunasArr[1][$com][0];
                            $com_destino_name = $comunasArr[1][$com][1];
                            $com_origen = $com_origen."<option value = $com_origen_val> $com_origen_name </option>";
                            $com_destino = $com_destino."<option value = $com_destino_val> $com_destino_name </option>";

                        }
                        echo "<form id='main-form' action='' method='post' enctype='multipart/form-data'>
                            <div id='main-div' class='vertical-form'>
                                <h3 id='region-origen-h'>Región Origen:</h3>
                                <select name='region-origen' id='region-origen' onchange='comunaOrigen()'>
                                $reg_origen
                                </select>
                                <h3 id='comuna-origen-h'>Comuna Origen:</h3>
                                <select name='comuna-origen' id='comuna-origen'>
                                $com_origen
                                </select>
                                <h3 id='region-destino-h'>Región Destino:</h3>
                                <select name='region-destino' id='region-destino' onchange='comunaDestino()'>
                                $reg_destino
                                </select>
                                
                                <h3 id='comuna-destino-h'>Comuna Destino:</h3>
                                <select name='comuna-destino' id='comuna-destino'>
                                $com_destino
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
                            <button id='return-button' onclick='index(1)' type='button'>Volver al menú principal</button>
                        </div>
                        <br>
                        <br>";
                    } else if (!$passed) {
                        // creacion de selects
                        //onload="onloadFunction()"

                        $reg_origen = "";
                        $reg_destino = "";
                        $com_origen = "";
                        $com_destino = "";
                        for ($i = 1; $i < sizeof($regiones_ids); $i++) {

                            if ($i == htmlspecialchars($_POST['region-origen'])) {
                                $reg_origen = $reg_origen."<option value=$i selected> $regiones_ids[$i] </option>";
                                for($com = 0; $com < sizeof($comunasArr[$i]); $com++) {
                                    
                                    $com_origen_val = $comunasArr[$i][$com][0];
                                    $com_origen_name = $comunasArr[$i][$com][1];
                                    
                                    if ($com_origen_val == $origen) {
                                        $com_origen = $com_origen."<option value = $com_origen_val selected> $com_origen_name </option>";
                                    } else {
                                        $com_origen = $com_origen."<option value = $com_origen_val> $com_origen_name </option>";
                                    }
                                }
                            } else {
                                $reg_origen = $reg_origen."<option value=$i> $regiones_ids[$i] </option>";
                            }
                            if ($i == htmlspecialchars($_POST['region-destino'])) {
                                $reg_destino = $reg_destino."<option value=$i selected> $regiones_ids[$i] </option>";

                                for($com = 0; $com < sizeof($comunasArr[$i]); $com++) {

                                    $com_destino_val = $comunasArr[$i][$com][0];
                                    $com_destino_name = $comunasArr[$i][$com][1];

                                    if ($com_destino_val == $destino) {
                                        $com_destino = $com_destino."<option value = $com_destino_val selected> $com_destino_name </option>";
                                    } else {
                                        $com_destino = $com_destino."<option value = $com_destino_val> $com_destino_name </option>";
                                    }
                                }
                            } else {
                                $reg_destino = $reg_destino."<option value=$i> $regiones_ids[$i] </option>";
                            }

                            

                        }
                        
                        for ($i = 1; $i < 4; $i++) {
                            if ($i == $espacioDisp) {
                                ${"esp" . $i} = "selected";
                            } else {
                                ${"esp" . $i} = "";
                            }
                        }
                        for ($i = 1; $i < 7; $i++) {
                            if ($i == $kilosDisp) {
                                ${"k" . $i} = "selected";
                            } else {
                                ${"k" . $i} = "";
                            }
                        }

                        echo "<ul class='vertical-menu'>
                            <li><label class='active' style='background-color: red;'>$mensajeError, intente nuevamente.</label></li>
                            </ul>";

                        echo "<form id='main-form' action='' method='post' enctype='multipart/form-data'>
                            <div id='main-div' class='vertical-form'>
                                <h3 id='region-origen-h'>Región Origen:</h3>
                                <select name='region-origen' id='region-origen' onchange='comunaOrigen()'>
                                $reg_origen
                                </select>
                                <h3 id='comuna-origen-h'>Comuna Origen:</h3>
                                <select name='comuna-origen' id='comuna-origen'>
                                $com_origen
                                </select>
                                <h3 id='region-destino-h'>Región Destino:</h3>
                                <select name='region-destino' id='region-destino' onchange='comunaDestino()'>
                                $reg_destino
                                </select>
                                
                                <h3 id='comuna-destino-h'>Comuna Destino:</h3>
                                <select name='comuna-destino' id='comuna-destino'>
                                $com_destino
                                </select>
                                <h3 id='fecha-viaje-h'>Fecha Viaje:</h3>
                                <input name='fecha-viaje' id='fecha-viaje' size='10' placeholder='DD/MM/AAAA' value=$unFormattedGoDate>
                                
                                <h3 id='espacio-disponible-h'>Espacio Disponible:</h3>
                                <select name='espacio-disponible' id='espacio-disponible'>
                                    <option value='1' $esp1>10x10x10</option>
                                    <option value='2' $esp2>20x20x20</option>
                                    <option value='3' $esp3>30x30x30</option>
                                </select>
                                <h3 id='kilos-disponibles-h'>Kilos Disponibles:</h3>
                                <select name='kilos-disponibles' id='kilos-disponibles'>
                                    <option value='1' $k1>200 gr</option>
                                    <option value='2' $k2>500 gr</option>
                                    <option value='3' $k3>800 gr</option>
                                    <option value='4' $k4>1 kg</option>
                                    <option value='5' $k5>1.5 kg</option>
                                    <option value='6' $k6>2 kg</option>
                                </select>
                                <h3 id='email-h'>Email:</h3>
                                <input name='email' id='email' placeholder='Ej: juan@gmail.com' value=$mail>
                                
                                <h3 id='celular-h'>Número celular:</h3>
                                <input name='celular' id='celular' placeholder='+569XXXXXXXX' value=$celular>
                                <br>
                                <input type='submit' value='Ingresar Viaje' onclick='return agregar_viaje_validacion()'>
                
                            </div>
                
                        </form>
                        <br>
                        <div class='button-container'>
                            <button id='return-button' onclick='index(1)' type='button'>Volver al menú principal</button>
                        </div>
                        <br>
                        <br>";
                    } else if ($passed) {
                        echo "<ul class='vertical-menu'>
                            <li><label class='active' style='background-color: green;'>Viaje Ingresado</label></li>
                            <li><a href='../index.html'>Volver al menú principal.</a></li>
                            </ul>";
                    }
                ?>
            </div>
        
        </div>
        
        <script src="../scripts.js"></script>
        
        
    </body>
</html>