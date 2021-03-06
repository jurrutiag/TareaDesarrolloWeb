<?php
    $firstTime = true;

    require_once("datos_comunas.php");
    require_once("configuraciones.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $passed = true;
        $firstTime = false;

        require_once("funciones.php");

        $descripcion = htmlspecialchars($_POST['descripcion']);
        $espacioSol = htmlspecialchars($_POST['espacio-solicitado']);
        $kilosSol = htmlspecialchars($_POST['kilos-solicitados']);
        $origen = htmlspecialchars($_POST['comuna-origen']);
        $destino = htmlspecialchars($_POST['comuna-destino']);
        $foto = $_FILES['foto-encargo']['tmp_name'];
        $mail = strtolower(htmlspecialchars($_POST['email']));
        $celular = htmlspecialchars($_POST['celular']);
        
        $db_config = new DbConfig();
        $db_arr = $db_config->getConnection();
        
        if (!$db_arr["connection"]) {
            to_error_page($db_arr["message"]);
            die();
        } else {
            $db = $db_arr["db"];
        }

        $count_query = $db->query("SELECT COUNT(*) FROM encargo");
        $id_query = $db->query("SELECT MAX(id) FROM encargo");
        if ($count_query->num_rows === 0 || $id_query->num_rows === 0) {
            
            $fid = 1;
            
        } else if (!$count_query || !$id_query) {
            $mensajeError = "2";
            to_error_page($mensajeError);
            die();
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

        if(!agregar_encargo_validacion()) {

            $passed = false;
            $mensajeError = "Error en la validación de los datos";

        } else {
            // Guardar foto
            $nombreGuardado = DateTime::createFromFormat('U.u', microtime(TRUE)); 
            $nombreGuardado = $nombreGuardado->format('Y-m-d-H-i-s-u');
            //$nombreGuardado = date("Y-m-d-H-i-s-u");
            $extension = pathinfo($_FILES['foto-encargo']['name'], PATHINFO_EXTENSION);
            $fotoDir = '../fotos/'.$nombreGuardado.'.'.$extension;
            
            if (!move_uploaded_file($foto, $fotoDir)) {
                
                $mensajeError = "3";
                to_error_page($mensajeError);
                die();

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
                $mensajeError = "2";
                to_error_page($mensajeError);
                die();
            }
        }


        $db->close();
        
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../boostrap_v4_w3c_fix.css">
        <link rel="stylesheet" href="../styles.css">
        <title>Agregar&#160;Encargo</title>
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

                    

                    echo "<form class='col-12 col-md-6 m-auto' enctype='multipart/form-data' method='post'>
                        <div class='form-group'>
                            <h5 class='m-2' id='descripcion-h'>Descripción Encargo (250 caracteres restantes):</h5>
                            <input class='form-control' name='descripcion' id='descripcion' oninput='updateDescripcion()' maxlength='250' placeholder='Ej: Caja de regalos (Max 250 caracteres)'>
            
                            <h5 class='m-2' id='espacio-solicitado-h'>Espacio:</h5>
                            <select class='form-control' name='espacio-solicitado' id='espacio-solicitado'>
                                <option value='1'>10x10x10</option>
                                <option value='2'>20x20x20</option>
                                <option value='3'>30x30x30</option>
                            </select>
            
                            <h5 class='m-2' id='kilos-solicitados-h'>Kilos:</h5>
                            <select class='form-control' name='kilos-solicitados' id='kilos-solicitados'>
                                <option value='1'>200 gr</option>
                                <option value='2'>500 gr</option>
                                <option value='3'>800 gr</option>
                                <option value='4'>1 kg</option>
                                <option value='5'>1.5 kg</option>
                                <option value='6'>2 kg</option>
                            </select>
            
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
            
                            <h5 class='m-2' id='foto-encargo-h'>Foto Encargo [Se aceptan formatos jpg, jpeg, exif, tiff, bmp, png, ppm, hdr, bpg]:</h5>
                            <input class='form-control' type='file' name='foto-encargo' id='foto-encargo'>
            
                            <h5 class='m-2' id='email-h'>Email Encargador:</h5>
                            <input class='form-control' name='email' id='email' placeholder='Ej: juan@gmail.com'>
            
                            <h5 class='m-2' id='celular-h'>Número de Celular de Encargador:</h5>
                            <input class='form-control' name='celular' id='celular' placeholder='+569XXXXXXXX'>
                            <br>
                            <input class='form-control btn btn-primary border' type='submit' value='Ingresar Encargo' onclick='return agregar_encargo_validacion()'>
                            <br>
                            <br>
                            <button class='btn btn-light form-control border' id='return-button' onclick='index(1)' type='button'>Volver al menú principal</button>
                            <br>
                            <br>
                        </div>
                    </form>";
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

                    echo "<form class='col-12 col-md-6 m-auto' enctype='multipart/form-data' method='post'>
                    <div class='form-group'>
                        <h3 class='form-control bg-danger text-white'>$mensajeError</h3>
                        <h5 class='m-2' id='descripcion-h'>Descripción Encargo (250 caracteres restantes):</h5>
                        <input class='form-control' name='descripcion' id='descripcion' oninput='updateDescripcion()' maxlength='250' placeholder='Ej: Caja de regalos (Max 250 caracteres)' value='$descripcion'>
        
                        <h5 class='m-2' id='espacio-solicitado-h'>Espacio:</h5>
                        <select class='form-control' name='espacio-solicitado' id='espacio-solicitado'>
                            <option value='1' $esp1>10x10x10</option>
                            <option value='2' $esp2>20x20x20</option>
                            <option value='3' $esp3>30x30x30</option>
                        </select>
        
                        <h5 class='m-2' id='kilos-solicitados-h'>Kilos:</h5>
                        <select class='form-control' name='kilos-solicitados' id='kilos-solicitados'>
                            <option value='1' $k1>200 gr</option>
                            <option value='2' $k2>500 gr</option>
                            <option value='3' $k3>800 gr</option>
                            <option value='4' $k4>1 kg</option>
                            <option value='5' $k5>1.5 kg</option>
                            <option value='6' $k6>2 kg</option>
                        </select>
        
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
        
                        <h5 class='m-2' id='foto-encargo-h'>Foto Encargo [Se aceptan formatos jpg, jpeg, exif, tiff, bmp, png, ppm, hdr, bpg]:</h5>
                        <input class='form-control' type='file' name='foto-encargo' id='foto-encargo'>
        
                        <h5 class='m-2' id='email-h'>Email Encargador:</h5>
                        <input class='form-control' name='email' id='email' placeholder='Ej: juan@gmail.com' value='$mail'>
        
                        <h5 class='m-2' id='celular-h'>Número de Celular de Encargador:</h5>
                        <input class='form-control' name='celular' id='celular' placeholder='+569XXXXXXXX' value='$celular'>
                        <br>
                        <input class='form-control btn btn-primary border' type='submit' value='Ingresar Encargo' onclick='return agregar_encargo_validacion()'>
                        <br>
                        <br>
                        <button class='btn btn-light form-control border' id='return-button' onclick='index(1)' type='button'>Volver al menú principal</button>
                        <br>
                        <br>
                    </div>
                    </form>";
                } else if ($passed) {
                    echo "<div class='list-group col-md-6 col-12 m-auto'>
                        <h6 class='list-group-item active bg-success'>Encargo Ingresado</h6>
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