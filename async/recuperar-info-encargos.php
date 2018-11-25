<?php

    function recuperar_info_encargos($passedId=NULL) {
        require_once("../options/configuraciones.php");
        require_once("../options/funciones.php");
        // Verificar definicion de n
        
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

        if (!$ids = $db->query("SELECT id FROM encargo")) {
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

        $result = $db->query("SELECT id, descripcion, origen, destino, espacio, kilos, foto, email_encargador, celular_encargador FROM encargo WHERE id = $id;");

        if (!$result) {
            $mensajeError = "2";
            to_error_page($mensajeError);
            die();
        }

        $data = mysqli_fetch_assoc($result);

        $descripcion = htmlspecialchars($data['descripcion']);

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

        $espacio = $data['espacio'];
        $espacioQuery = $db->query("SELECT valor FROM espacio_encargo WHERE id = $espacio");
        $espacio = mysqli_fetch_array($espacioQuery)[0];

        $kilos = $data['kilos'];
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
        $foto = htmlspecialchars($data['foto']);
        $email = htmlspecialchars($data['email_encargador']);
        $celular = htmlspecialchars($data['celular_encargador']);
        $comunaOrigen = htmlspecialchars($comunaOrigen);
        $regionOrigen = htmlspecialchars($regionOrigen);
        $comunaDestino = htmlspecialchars($comunaDestino);
        $regionDestino = htmlspecialchars($regionDestino);

        $db->close();

        return "<div id='main-div' class='list-group col-12 p-0 col-md-7 m-auto'>
        <h5 class='list-group-item list-group-item-dark'>Descripción:</h5>
        <h6 id='descripcion' class='list-group-item list-group-item-light'>$descripcion</h6>
        <h5 class='list-group-item list-group-item-dark'>Espacio solicitado:</h5>
        <h6 id='espacio-solicitado' class='list-group-item list-group-item-light'>$espacio</h6>
        <h5 class='list-group-item list-group-item-dark'>Kilos Solicitados:</h5>
        <h6 id='kilos-solicitados' class='list-group-item list-group-item-light'>$kilos</h6>
        <h5 class='list-group-item list-group-item-dark'>Región Origen:</h5>
        <h6 id='region-origen' class='list-group-item list-group-item-light'>$regionOrigen</h6>
        <h5 class='list-group-item list-group-item-dark'>Comuna Origen:</h5>
        <h6 id='comuna-origen' class='list-group-item list-group-item-light'>$comunaOrigen</h6>
        <h5 class='list-group-item list-group-item-dark'>Región Destino:</h5>
        <h6 id='region-destino' class='list-group-item list-group-item-light'>$regionDestino</h6>
        <h5 class='list-group-item list-group-item-dark'>Comuna Destino:</h5>
        <h6 id='comuna-destino' class='list-group-item list-group-item-light'>$comunaDestino</h6>
        <h5 class='list-group-item list-group-item-dark'>Foto Encargo:</h5>
        <img id='foto-encargo' class='list-group-item list-group-item-light foto-info m-auto' src=$foto alt='Foto Encargo' onclick='changeSize()'>
        <h5 class='list-group-item list-group-item-dark'>Email Encargador:</h5>
        <h6 id='email-encargador' class='list-group-item list-group-item-light'>$email</h6>
        <h5 class='list-group-item list-group-item-dark'>Número celular:</h5>
        <h6 id='celular-encargador' class='list-group-item list-group-item-light'>$celular</h6>
        </div>";

    }
?>