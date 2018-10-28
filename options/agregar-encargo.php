<?php
    $firstTime = true;

    require "datos_comunas.php";
    require "configuraciones.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $passed = true;
        $firstTime = false;

        require "funciones.php";

        $descripcion = htmlspecialchars($_POST['descripcion']);
        $espacioSol = htmlspecialchars($_POST['espacio-solicitado']);
        $kilosSol = htmlspecialchars($_POST['kilos-solicitados']);
        $origen = htmlspecialchars($_POST['comuna-origen']);
        $destino = htmlspecialchars($_POST['comuna-destino']);
        $foto = $_FILES['foto-encargo']['tmp_name'];
        $mail = htmlspecialchars($_POST['email']);
        $celular = htmlspecialchars($_POST['celular']);
        
        if(empty($_SERVER['CONTENT_TYPE'])) {
            $passed = false;
            // echo "Arreglar content type";
        }
        $db = new mysqli($server_name, $user_name, $user_pass, $db_name);

        if ($db->connect_error) {

            $passed = false;
            $mensajeError = "Error en la conexión al servidor";

        } else {

            if (!$id = mysqli_fetch_array($db->query("SELECT MAX(id) FROM encargo"))) {
                $passed = false;
                $mensajeError = "Error en la solicitud al servidor";
                // die("No se pudo recuperar id");
            } else {
                $fid = $id[0] + 1;
            }

            // VALIDACION

            if(!agregar_encargo_validacion()) {
                // header("Location: ../index.html");
                $passed = false;
                $mensajeError = "Error en la validación de los datos";
                // die("Validación de datos incorrecta");
            } else {
                // Guardar foto
                $fotoDir = '../fotos/'.$fid.'.jpg';
                if (!move_uploaded_file($foto, $fotoDir)) {
                    // header...
                    //move_uploaded_file(basename($foto), $fotoDir);
                    $passed = false;
                    $mensajeError = "Error en la subida del archivo";
                    // die('Error al subir foto');
                }

                $stmt = $db->prepare("INSERT INTO encargo (id, descripcion, origen, destino, espacio, kilos, foto, email_encargador, celular_encargador) VALUES (?,?,?,?,?,?,?,?,?);");
                if ($stmt) {
                    $bp = $stmt->bind_param("isiiiisss", $fid, $descripcion, $origen, $destino, $espacioSol, $kilosSol, $fotoDir, $mail, $celular);
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
        <title>Agregar Encargo</title>
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
                        <button id='return-button' onclick='index(1)' type='button'>Volver al menú principal</button>
                    </div>
                    <br>
                    <br>";
                } else if (!$passed) {
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
                        if ($i == $espacioSol) {
                            ${"esp" . $i} = "selected";
                        } else {
                            ${"esp" . $i} = "";
                        }
                    }
                    for ($i = 1; $i < 7; $i++) {
                        if ($i == $kilosSol) {
                            ${"k" . $i} = "selected";
                        } else {
                            ${"k" . $i} = "";
                        }
                    }

                    echo "<ul class='vertical-menu'>
                        <li><label class='active' style='background-color: red;'>$mensajeError, intente más tarde</label></li>
                        </ul>";

                    echo "<form enctype='multipart/form-data' action='' method='post'>
                    <div id='main-div' class='vertical-form'>
                        <h3 id='descripcion-h'>Descripción Encargo (250 caracteres restantes):</h3>
                        <input name='descripcion' id='descripcion' oninput='updateDescripcion()' maxlength='250' placeholder='Ej: Caja de regalos (Max 250 caracteres)' value=$descripcion>
        
                        <h3 id='espacio-solicitado-h'>Espacio:</h3>
                        <select name='espacio-solicitado' id='espacio-solicitado'>
                            <option value='1' $esp1>10x10x10</option>
                            <option value='2' $esp2>20x20x20</option>
                            <option value='3' $esp3>30x30x30</option>
                        </select>
        
                        <h3 id='kilos-solicitados-h'>Kilos:</h3>
                        <select name='kilos-solicitados' id='kilos-solicitados'>
                            <option value='1' $k1>200 gr</option>
                            <option value='2' $k2>500 gr</option>
                            <option value='3' $k3>800 gr</option>
                            <option value='4' $k4>1 kg</option>
                            <option value='5' $k5>1.5 kg</option>
                            <option value='6' $k6>2 kg</option>
                        </select>
        
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
        
                        <h3 id='foto-encargo-h'>Foto Encargo [Se aceptan formatos jpg, jpeg, exif, tiff, bmp, png, ppm, hdr, bpg]:</h3>
                        <input type='file' name='foto-encargo' id='foto-encargo'>
        
                        <h3 id='email-h'>Email Encargador:</h3>
                        <input name='email' id='email' placeholder='Ej: juan@gmail.com' value=$mail>
        
                        <h3 id='celular-h'>Número de Celular de Encargador:</h3>
                        <input name='celular' id='celular' placeholder='+569XXXXXXXX' value=$celular>
                        <br>
                        <input type='submit' value='Grabar Encargo' onclick='return agregar_encargo_validacion()'>
                        
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
                        <li><label class='active' style='background-color: green;'>Encargo Ingresado</label></li>
                        <li><a href='../index.html'>Volver al menú principal.</a></li>
                        </ul>";
                }
                
                ?>
            </div>
        </div>

        <script src="../scripts.js"></script>
    </body>
</html>