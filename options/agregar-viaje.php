<?php
    $firstTime = true;

    require_once("datos_comunas.php");
    require_once("configuraciones.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        $passed = true;
        $firstTime = false;

        require_once("funciones.php");

        $unFormattedGoDate = htmlspecialchars($_POST['fecha-ida']);
        $unFormattedReturnDate = htmlspecialchars($_POST['fecha-regreso']);
        $origen = htmlspecialchars($_POST['comuna-origen']);
        $destino = htmlspecialchars($_POST['comuna-destino']);
        $kilosDisp = htmlspecialchars($_POST['kilos-disponibles']);
        $espacioDisp = htmlspecialchars($_POST['espacio-disponible']);
        $mail = strtolower(htmlspecialchars($_POST['email']));
        $celular = htmlspecialchars($_POST['celular']);


        if(empty($_SERVER['CONTENT_TYPE'])) {
            $passed = false;
            // echo "Arreglar content type";
            $mensajeError = "Error en la página";
        }
        
        $db = new mysqli($server_name, $user_name, $user_pass, $db_name);


        if ($db->connect_error) {
            if ($passed) {
                $passed = false;
                // die("No se pudo recuperar id");
                $mensajeError = "Error en la conexión al servidor";
            }
            
        } else {
            $enc = $db->set_charset($encoding);
            if(!$enc) {
                if ($passed) {
                    $passed = false;
                    // die("No se pudo recuperar id");
                    $mensajeError = "Error en el encoding";
                }
            }

            $count_query = $db->query("SELECT COUNT(*) FROM viaje");
            $id_query = $db->query("SELECT MAX(id) FROM viaje");
            if ($count_query->num_rows === 0 || $id_query->num_rows === 0) {
                
                $fid = 1;

            } else if (!$count_query || !$id_query) {
                if ($passed) {
                    $passed = false;
                    // die("No se pudo recuperar id");
                    $mensajeError = "Error en la solicitud al servidor";
                }
            } else {
                $id = mysqli_fetch_array($id_query);
                $numRows = mysqli_fetch_array($count_query);
                if ($numRows[0] == 0) {
                    $fid = 1;
                } else {
                    $fid = $id[0] + 1;
                }
            }
            // VALIDACION

            if(!agregar_viaje_validacion()) {
                if ($passed) {
                    $passed = false;
                    // die("No se pudo recuperar id");
                    $mensajeError = "Error en la validación de los datos";
                }
                
                // die("Validación de datos incorrecta");
            } else {

                $fechaIda = reformatDate($unFormattedGoDate);
                if ($unFormattedReturnDate === '' || is_null($unFormattedReturnDate)) {
                    $fechaRegreso = '0-0-0';
                } else {
                    // Fecha por defecto que significa viaje indefinido
                    $fechaRegreso = reformatDate($unFormattedReturnDate);
                }


                $stmt = $db->prepare("INSERT INTO viaje (id, fecha_ida, fecha_regreso, origen, destino, kilos_disponible, espacio_disponible, email_viajero, celular_viajero) VALUES (?,?,?,?,?,?,?,?,?);");
                if ($stmt) {
                    $bp = $stmt->bind_param("issiiiiss",  $fid ,  $fechaIda , $fechaRegreso , $origen , $destino , $kilosDisp , $espacioDisp , $mail , $celular);
                    if ($bp) {
                        $ex = $stmt->execute();
                    }
                }
                // CAMBIAR FECHA VIAJE POR FECHA IDA Y VUELTA
                if(!$stmt || !$bp || !$ex) {
                    // echo mysqli_error($db);
                    if ($passed) {
                        $passed = false;
                        // die("No se pudo recuperar id");
                        $mensajeError = "Error en la solicitud al servidor";
                    }
                    
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
        <link rel="stylesheet" href="../boostrap v4 w3c fix.css">
        <link rel="stylesheet" href="../styles.css">

        <title>Agregar Viaje</title>
    </head>
    <body class="bg-light">
        
        <div class="container-fluid h-100 bg-light">
            <div class="row bg-light">
                <div class="jumbotron jumbotron-fluid col-md-12 display-4 d-none d-md-block" id="top-part"></div>
            </div>


            <div class="row bg-light">
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
                        
                        echo "<form class='col-12 col-md-6 m-auto' method='post' enctype='multipart/form-data'>
                            <div class='form-group'>
                                <h5 class='m-2' id='region-origen-h'>Región Origen:</h5>
                                <select class='form-control' name='region-origen' id='region-origen' onchange='comunaOrigen()'>
                                $reg_origen
                                </select>
                                <h5 class='m-2' id='comuna-origen-h'>Comuna Origen:</h5>
                                <select class='form-control' name='comuna-origen' id='comuna-origen'>
                                $com_origen
                                </select>
                                <h5 class='m-2' id='region-destino-h'>Región Destino:</h5>
                                <select class='form-control' name='region-destino' id='region-destino' onchange='comunaDestino()'>
                                $reg_destino
                                </select>
                                
                                <h5 class='m-2' id='comuna-destino-h'>Comuna Destino:</h5>
                                <select class='form-control' name='comuna-destino' id='comuna-destino'>
                                $com_destino
                                </select>
                                <h5 class='m-2' id='fecha-ida-h'>Fecha Ida:</h5>
                                <input class='form-control' name='fecha-ida' id='fecha-ida' size='10' placeholder='DD/MM/AAAA'>
                                <h5 class='m-2' id='fecha-regreso-h'>Fecha Regreso:</h5>
                                <input class='form-control' name='fecha-regreso' id='fecha-regreso' size='10' placeholder='DD/MM/AAAA'>
                                <h5 class='m-2' id='espacio-disponible-h'>Espacio Disponible:</h5>
                                <select class='form-control' name='espacio-disponible' id='espacio-disponible'>
                                    <option value='1'>10x10x10</option>
                                    <option value='2'>20x20x20</option>
                                    <option value='3'>30x30x30</option>
                                </select>
                                <h5 class='m-2' id='kilos-disponibles-h'>Kilos Disponibles:</h5>
                                <select class='form-control' name='kilos-disponibles' id='kilos-disponibles'>
                                    <option value='1'>200 gr</option>
                                    <option value='2'>500 gr</option>
                                    <option value='3'>800 gr</option>
                                    <option value='4'>1 kg</option>
                                    <option value='5'>1.5 kg</option>
                                    <option value='6'>2 kg</option>
                                </select>
                                <h5 class='m-2' id='email-h'>Email:</h5>
                                <input class='form-control' name='email' id='email' placeholder='Ej: juan@gmail.com'>
                                
                                <h5 class='m-2' id='celular-h'>Número celular:</h5>
                                <input class='form-control' name='celular' id='celular' placeholder='+569XXXXXXXX'>
                                <br>
                                <input class='form-control btn btn-primary border' type='submit' value='Ingresar Viaje' onclick='return agregar_viaje_validacion()'>
                                <br>
                                <br>
                                <button class='btn btn-light form-control border' id='return-button' onclick='index(1)' type='button'>Volver al menú principal</button>
                                <br>
                                <br>
                            </div>
                
                        </form>";
                        
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
                        
                        echo "<form class='col-12 col-md-6 m-auto' method='post' enctype='multipart/form-data'>
                            <div class='form-group'>
                                <h3 class='form-control bg-danger text-white'>$mensajeError</h3>
                                <h5 class='m-2' id='region-origen-h'>Región Origen:</h5>
                                <select class='form-control' name='region-origen' id='region-origen' onchange='comunaOrigen()'>
                                $reg_origen
                                </select>
                                <h5 class='m-2' id='comuna-origen-h'>Comuna Origen:</h5>
                                <select class='form-control' name='comuna-origen' id='comuna-origen'>
                                $com_origen
                                </select>
                                <h5 class='m-2' id='region-destino-h'>Región Destino:</h5>
                                <select class='form-control' name='region-destino' id='region-destino' onchange='comunaDestino()'>
                                $reg_destino
                                </select>
                                
                                <h5 class='m-2' id='comuna-destino-h'>Comuna Destino:</h5>
                                <select class='form-control' name='comuna-destino' id='comuna-destino'>
                                $com_destino
                                </select>
                                <h5 class='m-2' id='fecha-ida-h'>Fecha Ida:</h5>
                                <input class='form-control' name='fecha-ida' id='fecha-ida' size='10' placeholder='DD/MM/AAAA' value=$unFormattedGoDate>
                                <h5 class='m-2' id='fecha-regreso-h'>Fecha Regreso:</h5>
                                <input class='form-control' name='fecha-regreso' id='fecha-regreso' size='10' placeholder='DD/MM/AAAA' value=$unFormattedReturnDate>
                                <h5 class='m-2' id='espacio-disponible-h'>Espacio Disponible:</h5>
                                <select class='form-control' name='espacio-disponible' id='espacio-disponible'>
                                    <option value='1' $esp1>10x10x10</option>
                                    <option value='2' $esp2>20x20x20</option>
                                    <option value='3' $esp3>30x30x30</option>
                                </select>
                                <h5 class='m-2' id='kilos-disponibles-h'>Kilos Disponibles:</h5>
                                <select class='form-control' name='kilos-disponibles' id='kilos-disponibles'>
                                    <option value='1' $k1>200 gr</option>
                                    <option value='2' $k2>500 gr</option>
                                    <option value='3' $k3>800 gr</option>
                                    <option value='4' $k4>1 kg</option>
                                    <option value='5' $k5>1.5 kg</option>
                                    <option value='6' $k6>2 kg</option>
                                </select>
                                <h5 class='m-2' id='email-h'>Email:</h5>
                                <input class='form-control' name='email' id='email' placeholder='Ej: juan@gmail.com' value='$mail'>
                                
                                <h5 class='m-2' id='celular-h'>Número celular:</h5>
                                <input class='form-control' name='celular' id='celular' placeholder='+569XXXXXXXX' value='$celular'>
                                <br>
                                <input class='form-control btn btn-primary border' type='submit' value='Ingresar Viaje' onclick='return agregar_viaje_validacion()'>
                                <br>
                                <br>
                                <button class='btn btn-light form-control border' id='return-button' onclick='index(1)' type='button'>Volver al menú principal</button>
                                <br>
                                <br>
                            </div>
                
                        </form>";
                    } else if ($passed) {
                        echo "<div class='list-group col-md-6 col-12 m-auto'>
                            <li class='list-group-item active bg-success'>Viaje Ingresado</li>
                            <a class='list-group-item list-group-item-action' href='../index.php'>Volver al menú principal</a>
                            </div>";
                    }
                ?>
            </div>
        
        </div>
        
        <script src="../scripts.js"></script>
        <script src="../bootstrap.js"></script>
        
    </body>
</html>