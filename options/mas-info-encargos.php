<!DOCTYPE html>

<html lang="es">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="../../styles.css">

        <title>Más Información Encargos</title>
    </head>
    <body>

        <div class="container">

            <div class="first-half">
                
            </div>

            <div class="second-half">
                
                    <?php
                        require "configuraciones.php";
                        // Verificar definicion de n

                        $passed = true;
                        if (!isset($_GET['id'])) {
                            $passed = false;
                            $mensajeError = "Operación inválida";
                        }

                        // Obtencion de n y calculo del numero de elementos antes de la pagina actual

                        $id = htmlspecialchars($_GET['id']);

                        $db = new mysqli($server_name, $user_name, $user_pass, $db_name);
                        $enc = $db->set_charset($encoding);
                        if(!$enc) {
                            if ($passed) {
                                $passed = false;
                                // die("No se pudo recuperar id");
                                $mensajeError = "Error en el encoding";
                            }
                        }
                        if ($db->connect_error) {
                            if ($passed) {
                                $passed = false;
                                // die("No se pudo recuperar id");
                                $mensajeError = "Error en la conexión al servidor";
                            }
                            
                        }
                        
                        // Obtencion del maximo N

                        if (!$ids = $db->query("SELECT id FROM encargo")) {
                            if ($passed) {
                                $passed = false;
                                // die("No se pudo recuperar id");
                                $mensajeError = "Error en la solicitud al servidor";
                            }
                        }


                        $idArray = Array();
                        while ($row = mysqli_fetch_assoc($ids)) {
                            $idArray[] = $row['id'];
                        }

                        if (!in_array($id, $idArray)) {
                            if ($passed) {
                                $passed = false;
                                // die("No se pudo recuperar id");
                                $mensajeError = "Operación inválida";
                            }
                        }

                        $result = $db->query("SELECT id, descripcion, origen, destino, espacio, kilos, foto, email_encargador, celular_encargador FROM encargo WHERE id = $id;");

                        if (!$result) {
                            if ($passed) {
                                $passed = false;
                                // die("No se pudo recuperar id");
                                $mensajeError = "Error en la solicitud al servidor";
                            }
                        }

                        $data = mysqli_fetch_assoc($result);

                        $descripcion = htmlspecialchars($data['descripcion']);
                        
                        $origen = $data["origen"];
                        $origenQuery = $db->query("SELECT nombre, region_id FROM comuna WHERE id = $origen");
                        $origenArr = mysqli_fetch_assoc($origenQuery);
                        $regionidOr = $origenArr["region_id"];
                        $comunaOrigen = $origenArr["nombre"];
                        $regionOrigen = mysqli_fetch_assoc($db->query("SELECT nombre FROM region WHERE id = $regionidOr"))["nombre"];
                        
                        $destino = $data["destino"];
                        $destinoQuery = $db->query("SELECT nombre, region_id FROM comuna WHERE id = $destino");
                        $destinoArr = mysqli_fetch_assoc($destinoQuery);
                        $regionidDest = $destinoArr["region_id"];
                        $comunaDestino = $destinoArr["nombre"];
                        $regionDestino = mysqli_fetch_assoc($db->query("SELECT nombre FROM region WHERE id = $regionidDest"))["nombre"];

                        $espacio = $data['espacio'];
                        $espacioQuery = $db->query("SELECT valor FROM espacio_encargo WHERE id = $espacio");
                        $espacio = mysqli_fetch_array($espacioQuery)[0];

                        $kilos = $data['kilos'];
                        $kilosQuery = $db->query("SELECT valor FROM kilos_encargo WHERE id = $kilos");
                        $kilos = mysqli_fetch_array($kilosQuery)[0];

                        if (!$kilosQuery || !$espacioQuery || !$destinoQuery || !$origenQuery) {
                            if ($passed) {
                                $passed = false;
                                // die("No se pudo recuperar id");
                                $mensajeError = "Error en la solicitud al servidor";
                            }
                        }

                        $destino = htmlspecialchars($data['destino']);
                        $espacio = htmlspecialchars($espacio);
                        $kilos = htmlspecialchars($kilos);
                        $foto = '../'.htmlspecialchars($data['foto']);
                        $email = htmlspecialchars($data['email_encargador']);
                        $celular = htmlspecialchars($data['celular_encargador']);
                        $comunaOrigen = htmlspecialchars($comunaOrigen);
                        $regionOrigen = htmlspecialchars($regionOrigen);
                        $comunaDestino = htmlspecialchars($comunaDestino);
                        $regionDestino = htmlspecialchars($regionDestino);

                        if ($passed) {
                            echo "<div id='main-div' class='vertical-form'>
                            <h3>Descripción:</h3>
                            <h4 id='descripcion' class='info'>$descripcion</h4>
                            <h3>Espacio solicitado:</h3>
                            <h4 id='espacio-solicitado' class='info'>$espacio</h4>
                            <h3>Kilos Solicitados:</h3>
                            <h4 id='kilos-solicitados' class='info'>$kilos</h4>
                            <h3>Región Origen:</h3>
                            <h4 id='region-origen' class='info'>$regionOrigen</h4>
                            <h3>Comuna Origen:</h3>
                            <h4 id='comuna-origen' class='info'>$comunaOrigen</h4>
                            <h3>Región Destino:</h3>
                            <h4 id='region-destino' class='info'>$regionDestino</h4>
                            <h3>Comuna Destino:</h3>
                            <h4 id='comuna-destino' class='info'>$comunaDestino</h4>
                            <h3>Foto Encargo:</h3>
                            <img id='foto-encargo' class='foto-info' src=$foto alt='Foto Encargo' onclick='changeSize()'>
                            <h3>Email Encargador:</h3>
                            <h4 id='email-encargador' class='info'>$email</h4>
                            <h3>Número celular:</h3>
                            <h4 id='celular-encargador' class='info'>$celular</h4>
                            </div>
                            <br>
    
                            <div class='button-container'>
                                <button id='return-button' onclick='index(2)' type='button'>Volver al menú principal</button>
                                <button id='back-button' onclick='goBack()' type='button'>Volver atrás</button>
                            </div>";

                            $db->close();
                        } else {
                            echo "<ul class='vertical-menu'>
                            <li><label class='active' style='background-color: red;'>$mensajeError, intente nuevamente.</label></li>
                            </ul>";
                        }
                        
                    ?>
                
            </div>
        
        </div>
        
        <script src="../../scripts.js"></script>
        <script> updateWidth(); </script>
        
    </body>
</html>