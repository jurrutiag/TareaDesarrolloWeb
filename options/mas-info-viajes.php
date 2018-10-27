<!DOCTYPE html>

<html lang="es">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="../../styles.css">

        <title>Más Información Viajes</title>
    </head>
    <body onload="loadMasInfoViajes()">

        <div class="container">

            <div class="first-half">
                
            </div>

            <div class="second-half">
                
                <?php

                    // Verificar definicion de n

                    if (!isset($_GET['id'])) {
                        die("Parámetro no definido");
                    }

                    // Obtencion de n y calculo del numero de elementos antes de la pagina actual

                    $id = htmlspecialchars($_GET['id']);

                    $db = new mysqli('localhost', 'root', '', 'tarea2') or die("Hubo un problema en la conexión, intente más tarde");

                    // Obtencion del maximo N

                    if (!$ids = $db->query("SELECT id FROM viaje")) {
                        // header("Location: ../index.html");
                        die("Error en la conexión");
                    }


                    $idArray = Array();
                    while ($row = mysqli_fetch_assoc($ids)) {
                        $idArray[] = $row['id'];
                    }

                    if (!in_array($id, $idArray)) {
                        die("id inválido");
                    }

                    $result = $db->query("SELECT id, fecha_ida, fecha_regreso, origen, destino, kilos_disponible, espacio_disponible, email_viajero, celular_viajero FROM viaje WHERE id = $id;");

                    if (!$result) {
                        die("No se pudieron recuperar los datos, intente nuevamente.");
                    }

                    $data = mysqli_fetch_assoc($result);

                    $fecha = date('d-m-Y', strtotime($data["fecha_ida"]));

                    $origen = $data["origen"];
                    $origenArr = mysqli_fetch_assoc($db->query("SELECT nombre, region_id FROM comuna WHERE id = $origen"));
                    $regionidOr = $origenArr["region_id"];
                    $comunaOrigen = $origenArr["nombre"];
                    $regionOrigen = mysqli_fetch_assoc($db->query("SELECT nombre FROM region WHERE id = $regionidOr"))["nombre"];

                    $destino = $data["destino"];
                    $destinoArr = mysqli_fetch_assoc($db->query("SELECT nombre, region_id FROM comuna WHERE id = $destino"));
                    $regionidDest = $destinoArr["region_id"];
                    $comunaDestino = $destinoArr["nombre"];
                    $regionDestino = mysqli_fetch_assoc($db->query("SELECT nombre FROM region WHERE id = $regionidDest"))["nombre"];

                    $espacio = $data['espacio_disponible'];
                    $espacio = mysqli_fetch_array($db->query("SELECT valor FROM espacio_encargo WHERE id = $espacio"))[0];

                    $kilos = $data['kilos_disponible'];
                    $kilos = mysqli_fetch_array($db->query("SELECT valor FROM kilos_encargo WHERE id = $kilos"))[0];

                    $destino = htmlspecialchars(utf8_encode($data['destino']));
                    $espacio = htmlspecialchars(utf8_encode($espacio));
                    $kilos = htmlspecialchars(utf8_encode($kilos));
                    $email = htmlspecialchars(utf8_encode($data['email_viajero']));
                    $celular = htmlspecialchars(utf8_encode($data['celular_viajero']));
                    $comunaOrigen = htmlspecialchars(utf8_encode($comunaOrigen));
                    $regionOrigen = htmlspecialchars(utf8_encode($regionOrigen));
                    $comunaDestino = htmlspecialchars(utf8_encode($comunaDestino));
                    $regionDestino = htmlspecialchars(utf8_encode($regionDestino));

                    echo "<div id='main-div' class='vertical-form'>
                    <h3>Región Origen:</h3>
                    <h4 id='region-origen' class='info'>$regionOrigen</h4>
                    <h3>Comuna Origen:</h3>
                    <h4 id='comuna-origen' class='info'>$comunaOrigen</h4>
                    <h3>Región Destino:</h3>
                    <h4 id='region-destino' class='info'>$regionDestino</h4>
                    <h3>Comuna Destino:</h3>
                    <h4 id='comuna-destino' class='info'>$comunaDestino</h4>
                    <h3>Fecha Viaje:</h3>
                    <h4 id='fecha-viaje' class='info'>$fecha</h4>
                    <h3>Espacio Disponible:</h3>
                    <h4 id='espacio-disponible' class='info'>$espacio</h4>
                    <h3>Kilos Disponibles:</h3>
                    <h4 id='kilos-disponibles' class='info'>$kilos</h4>
                    <h3>Email:</h3>
                    <h4 id='email-viajero' class='info'>$email</h4>
                    <h3>Número celular:</h3>
                    <h4 id='celular-viajero' class='info'>$celular</h4>
                    </div>
                    <br>
                    <div class='button-container'>
                        <button id='return-button' onclick='index(2)' type='button'>Volver al menú principal</button>
                        <button id='back-button' onclick='goBack()' type='button'>Volver atrás</button>
                    </div>";   

                    $db->close();
                ?>
                
            </div>
        
        </div>
        
        
        <script src="../../scripts.js"></script>
        
        
    </body>
</html>