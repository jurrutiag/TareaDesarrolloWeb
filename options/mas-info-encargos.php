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

                        // Verificar definicion de n

                        if (!isset($_GET['id'])) {
                            die("Parámetro no definido");
                        }

                        // Obtencion de n y calculo del numero de elementos antes de la pagina actual

                        $id = htmlspecialchars($_GET['id']);

                        $db = new mysqli('localhost', 'root', '', 'tarea2') or die("Hubo un problema en la conexión, intente más tarde");

                        // Obtencion del maximo N

                        if (!$ids = $db->query("SELECT id FROM encargo")) {
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

                        $result = $db->query("SELECT id, descripcion, origen, destino, espacio, kilos, foto, email_encargador, celular_encargador FROM encargo WHERE id = $id;");

                        if (!$result) {
                            die("No se pudieron recuperar los datos, intente nuevamente.");
                        }

                        $data = mysqli_fetch_assoc($result);

                        $descripcion = utf8_encode(htmlspecialchars($data['descripcion']));
                        
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

                        $espacio = $data['espacio'];
                        $espacio = mysqli_fetch_array($db->query("SELECT valor FROM espacio_encargo WHERE id = $espacio"))[0];

                        $kilos = $data['kilos'];
                        $kilos = mysqli_fetch_array($db->query("SELECT valor FROM kilos_encargo WHERE id = $kilos"))[0];

                        $destino = htmlspecialchars(utf8_encode($data['destino']));
                        $espacio = htmlspecialchars(utf8_encode($espacio));
                        $kilos = htmlspecialchars(utf8_encode($kilos));
                        $foto = '../'.htmlspecialchars(utf8_encode($data['foto']));
                        $email = htmlspecialchars(utf8_encode($data['email_encargador']));
                        $celular = htmlspecialchars(utf8_encode($data['celular_encargador']));
                        $comunaOrigen = htmlspecialchars(utf8_encode($comunaOrigen));
                        $regionOrigen = htmlspecialchars(utf8_encode($regionOrigen));
                        $comunaDestino = htmlspecialchars(utf8_encode($comunaDestino));
                        $regionDestino = htmlspecialchars(utf8_encode($regionDestino));

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
                            <button id='return-button' onclick='location.href='../../index.html'' type='button'>Volver al menú principal</button>
                            <button id='back-button' onclick='goBack()' type='button'>Volver atrás</button>
                        </div>";
                    ?>
                
            </div>
        
        </div>
        
        <script src="../../scripts.js"></script>
        <script> updateWidth(); </script>
        
    </body>
</html>