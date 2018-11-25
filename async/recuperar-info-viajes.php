<?php
    // Verificar definicion de n

    function recuperar_info_viajes($passedId=NULL) {
        require_once("../options/configuraciones.php");
        require_once("../options/funciones.php");

        if (!isset($_GET['id'])) {
            if (!is_null($passedId)) {
                $id = $passedId;
            } else {
                $mensajeError = "4";
                to_error_page($mensajeError);
                die();
            }
        }

        // Obtencion de n y calculo del numero de elementos antes de la pagina actual

        $id = htmlspecialchars($_GET['id']);

        $db_config = new DbConfig();
        $db_arr = $db_config->getConnection();
        
        if (!$db_arr["connection"]) {
            to_error_page($db_arr["message"]);
            die();
        } else {
            $db = $db_arr["db"];
        }
        
        // Obtencion del maximo N

        if (!$ids = $db->query("SELECT id FROM viaje"))
        {
            $mensajeError = "2";
            to_error_page($mensajeError);
            die();
            
        }


        $idArray = Array();
        while ($row = mysqli_fetch_assoc($ids)) {
            $idArray[] = $row['id'];
        }

        if (!in_array($id, $idArray)) {
            $mensajeError = "4";
            to_error_page($mensajeError);
            die();
        }
        
        $result = $db->query("SELECT id, fecha_ida, fecha_regreso, origen, destino, kilos_disponible, espacio_disponible, email_viajero, celular_viajero FROM viaje WHERE id = $id;");

        if (!$result) {
            $mensajeError = "2";
            to_error_page($mensajeError);
            die();
        }

        $data = mysqli_fetch_assoc($result);

        $fecha_ida = date('d-m-Y', strtotime($data["fecha_ida"]));
        $fecha_regreso = date('d-m-Y', strtotime($data["fecha_regreso"]));
        if ($fecha_regreso === date('d-m-Y', strtotime('00-00-0000'))) {
            $fecha_regreso = "No regresa";
        }

        $origen = $data["origen"];

        $origen_arr = get_region_comuna($db, $origen);
        $comunaOrigen = $origen_arr["comuna"];
        $regionOrigen = $origen_arr["region"];
        $is_success_origen = $origen_arr["success"];

        $destino = $data["destino"];

        $destino_arr = get_region_comuna($db, $destino);
        $comunaDestino = $destino_arr["comuna"];
        $regionDestino = $destino_arr["region"];
        $is_success_destino = $destino_arr["success"];

        $espacio = $data['espacio_disponible'];
        $espacioQuery = $db->query("SELECT valor FROM espacio_encargo WHERE id = $espacio");
        $espacio = mysqli_fetch_array($espacioQuery)[0];

        $kilos = $data['kilos_disponible'];
        $kilosQuery = $db->query("SELECT valor FROM kilos_encargo WHERE id = $kilos");
        $kilos = mysqli_fetch_array($kilosQuery)[0];

        $is_success = $is_success_origen && $is_success_destino && $espacioQuery && $kilosQuery;

        if (!$is_success) {
            $mensajeError = "2";
            to_error_page($mensajeError);
            die();
        }

        $destino = htmlspecialchars($data['destino']);
        $espacio = htmlspecialchars($espacio);
        $kilos = htmlspecialchars($kilos);
        $email = htmlspecialchars($data['email_viajero']);
        $celular = htmlspecialchars($data['celular_viajero']);
        $comunaOrigen = htmlspecialchars($comunaOrigen);
        $regionOrigen = htmlspecialchars($regionOrigen);
        $comunaDestino = htmlspecialchars($comunaDestino);
        $regionDestino = htmlspecialchars($regionDestino);

        $db->close();
            
        return "<div id='main-div' class='list-group col-12 col-md-6 p-0 m-auto'>
        <h5 class='list-group-item list-group-item-dark'>Región Origen:</h5>
        <h6 id='region-origen' class='list-group-item list-group-item-light'>$regionOrigen</h6>
        <h5 class='list-group-item list-group-item-dark'>Comuna Origen:</h5>
        <h6 id='comuna-origen' class='list-group-item list-group-item-light'>$comunaOrigen</h6>
        <h5 class='list-group-item list-group-item-dark'>Región Destino:</h5>
        <h6 id='region-destino' class='list-group-item list-group-item-light'>$regionDestino</h6>
        <h5 class='list-group-item list-group-item-dark'>Comuna Destino:</h5>
        <h6 id='comuna-destino' class='list-group-item list-group-item-light'>$comunaDestino</h6>
        <h5 class='list-group-item list-group-item-dark'>Fecha Viaje:</h5>
        <h6 id='fecha-viaje' class='list-group-item list-group-item-light'>$fecha_ida</h6>
        <h5 class='list-group-item list-group-item-dark'>Fecha Regreso:</h5>
        <h6 id='fecha-regreso' class='list-group-item list-group-item-light'>$fecha_regreso</h6>
        <h5 class='list-group-item list-group-item-dark'>Espacio Disponible:</h5>
        <h6 id='espacio-disponible' class='list-group-item list-group-item-light'>$espacio</h6>
        <h5 class='list-group-item list-group-item-dark'>Kilos Disponibles:</h5>
        <h6 id='kilos-disponibles' class='list-group-item list-group-item-light'>$kilos</h6>
        <h5 class='list-group-item list-group-item-dark'>Email:</h5>
        <h6 id='email-viajero' class='list-group-item list-group-item-light'>$email</h6>
        <h5 class='list-group-item list-group-item-dark'>Número celular:</h5>
        <h6 id='celular-viajero' class='list-group-item list-group-item-light'>$celular</h6>
        </div>";
    }

?>