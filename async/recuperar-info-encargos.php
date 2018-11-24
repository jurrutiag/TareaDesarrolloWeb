<?php

    function recuperar_info_encargos($passedId=NULL) {
        require_once("../options/configuraciones.php");
        require_once("../options/funciones.php");
        // Verificar definicion de n

        $passed = true;
        if (!isset($_GET['id'])) {
            if (!is_null($passedId)) {
                $id = $passedId;
            } else {
                $passed = false;
                $mensajeError = "Operación inválida";
            }
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
        $foto = htmlspecialchars($data['foto']);
        $email = htmlspecialchars($data['email_encargador']);
        $celular = htmlspecialchars($data['celular_encargador']);
        $comunaOrigen = htmlspecialchars($comunaOrigen);
        $regionOrigen = htmlspecialchars($regionOrigen);
        $comunaDestino = htmlspecialchars($comunaDestino);
        $regionDestino = htmlspecialchars($regionDestino);

        if ($passed) {
            return "<div id='main-div' class='list-group col-12 p-0 col-md-7 m-auto'>
            <h5 class='list-group-item list-group-item-dark'>Descripción:</h3>
            <h6 id='descripcion' class='list-group-item list-group-item-light'>$descripcion</h4>
            <h5 class='list-group-item list-group-item-dark'>Espacio solicitado:</h3>
            <h6 id='espacio-solicitado' class='list-group-item list-group-item-light'>$espacio</h4>
            <h5 class='list-group-item list-group-item-dark'>Kilos Solicitados:</h3>
            <h6 id='kilos-solicitados' class='list-group-item list-group-item-light'>$kilos</h4>
            <h5 class='list-group-item list-group-item-dark'>Región Origen:</h3>
            <h6 id='region-origen' class='list-group-item list-group-item-light'>$regionOrigen</h4>
            <h5 class='list-group-item list-group-item-dark'>Comuna Origen:</h3>
            <h6 id='comuna-origen' class='list-group-item list-group-item-light'>$comunaOrigen</h4>
            <h5 class='list-group-item list-group-item-dark'>Región Destino:</h3>
            <h6 id='region-destino' class='list-group-item list-group-item-light'>$regionDestino</h4>
            <h5 class='list-group-item list-group-item-dark'>Comuna Destino:</h3>
            <h6 id='comuna-destino' class='list-group-item list-group-item-light'>$comunaDestino</h4>
            <h5 class='list-group-item list-group-item-dark'>Foto Encargo:</h3>
            <img id='foto-encargo' class='list-group-item list-group-item-light foto-info m-auto' src=$foto alt='Foto Encargo' onclick='changeSize()'>
            <h5 class='list-group-item list-group-item-dark'>Email Encargador:</h3>
            <h6 id='email-encargador' class='list-group-item list-group-item-light'>$email</h4>
            <h5 class='list-group-item list-group-item-dark'>Número celular:</h3>
            <h6 id='celular-encargador' class='list-group-item list-group-item-light'>$celular</h4>
            </div>";

            $db->close();
        } else {
            return mensaje_error($mensajeError, "../index.php");
        }
    }
?>