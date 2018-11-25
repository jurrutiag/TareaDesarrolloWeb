<?php
    require_once("../options/configuraciones.php");
    require_once("../options/funciones.php");

    $passed = true;
    $data = array("result_status" => array(), "ammount" => 0);
    
    $db_config = new DbConfig();
    $db_arr = $db_config->getConnection();
    
    if (!$db_arr["connection"]) {
        $passed = false;
        $data["result_status"] = false;
    } else {
        $db = $db_arr["db"];
    }

    $quer = "SELECT id, fecha_ida, fecha_regreso, origen, destino, kilos_disponible, espacio_disponible FROM viaje ORDER BY id DESC LIMIT 3";

    $result = $db->query($quer);
    
    if (!$result) {
        $passed = false;
        $data["result_status"] = false;
    } else if ($passed) {
        $i = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $data["data".$i] = array();

            $origen = $row["origen"];

            $origen_arr = get_region_comuna($db, $origen);
            $comunaOrigen = $origen_arr["comuna"];
            $regionOrigen = $origen_arr["region"];
            $is_success_origen = $origen_arr["success"];
    
            $destino = $row["destino"];
    
            $destino_arr = get_region_comuna($db, $destino);
            $comunaDestino = $destino_arr["comuna"];
            $regionDestino = $destino_arr["region"];
            $is_success_destino = $destino_arr["success"];

            $rawAddOr = $comunaOrigen.", ".$regionOrigen;
            $addressOrigen = str_replace(" ", "+", "{".$rawAddOr."+Chile+"."}");

            $addressOrigen = remove_special_chars($addressOrigen);

            $urlOrigen = "https://maps.googleapis.com/maps/api/geocode/json?address=$addressOrigen&key=AIzaSyAG195ROSB1lHUnAgFQjLMqBBBE7yq9Tss";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $urlOrigen);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $resp_json_origen = curl_exec($ch);
            curl_close($ch);

            $rawAddDest = $comunaDestino.", ".$regionDestino;
            $addressDestino = str_replace(" ", "+", "{".$rawAddDest."+Chile+"."}");

            $addressDestino = remove_special_chars($addressDestino);

            $urlDestino = "https://maps.googleapis.com/maps/api/geocode/json?address=$addressDestino&key=AIzaSyAG195ROSB1lHUnAgFQjLMqBBBE7yq9Tss";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $urlDestino);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $resp_json_Destino = curl_exec($ch);
            curl_close($ch);

            $resp_orig = json_decode($resp_json_origen, true);
            $resp_dest = json_decode($resp_json_Destino, true);

            $fecha_ida = $row["fecha_ida"];
            $fecha_ida = date('d-m-Y', strtotime($fecha_ida));
            
            $espacio = $row['espacio_disponible'];
            $espacioQuery = $db->query("SELECT valor FROM espacio_encargo WHERE id = $espacio");
            $espacio = mysqli_fetch_array($espacioQuery)[0];

            $kilos = $row['kilos_disponible'];
            $kilosQuery = $db->query("SELECT valor FROM kilos_encargo WHERE id = $kilos");
            $kilos = mysqli_fetch_array($kilosQuery)[0];
            
            $fecha_regreso = $row["fecha_regreso"];
            $fecha_regreso = date('d-m-Y', strtotime($fecha_regreso));

            if ($fecha_regreso === date('d-m-Y', strtotime('00-00-0000'))) {
                $fecha_regreso = "No regresa";
            }

            $id = $row["id"];

            $is_success = $is_success_destino && $is_success_origen && $kilosQuery && $espacioQuery;
            
            if (!$is_success) {
                $passed = false;
                $data["result_status"] = false;
            } else {
                $tag_text = "<div>
                <h5>Información:</h5>
                <h5>Fecha viaje: $fecha_ida</h5>
                <h5>Fecha Regreso: $fecha_regreso</h5>
                <h5>Comuna Origen: $comunaOrigen</h5>
                <h5>Comuna Destino: $comunaDestino</h5>
                <h5>Kilos Disponibles: $kilos</h5>
                <h5>Espacio Disponible: $espacio</h5>
                <button class='btn btn-light' onclick='masInfoViajes($id, \"options/mas-info-viajes.php?id=\")'>Más información</button>
                </div>";


                if ((!isset($resp_orig["results"][0]["geometry"]["location"]["lat"]) || !isset($resp_orig["results"][0]["geometry"]["location"]["lng"]) || !isset($resp_dest["results"][0]["geometry"]["location"]["lat"]) || !isset($resp_orig["results"][0]["geometry"]["location"]["lng"]))) {
                    $passed = false;
                    $data["result_status"] = false;
                } else {
                    $lator = $resp_orig["results"][0]["geometry"]["location"]["lat"];
                    $lngor = $resp_orig["results"][0]["geometry"]["location"]["lng"];
                    $latdest = $resp_dest["results"][0]["geometry"]["location"]["lat"];
                    $lngdest = $resp_dest["results"][0]["geometry"]["location"]["lng"];

                    $data["data".$i] = array(array("lat"=>$lator, "lng"=>$lngor), array("lat"=>$latdest, "lng"=>$lngdest), "title" => "Desde ".$rawAddOr.", Hasta ".$rawAddDest, "tag_text" => $tag_text);
                }
                $i += 1;
            }
        }
        $data["ammount"] = $i - 1;
    }
    echo json_encode($data);
?>