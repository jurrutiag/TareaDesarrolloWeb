<?php
    require_once("../options/configuraciones.php");
    $db = new mysqli($server_name, $user_name, $user_pass, $db_name);
    $enc = $db->set_charset($encoding);
    if(!$enc) {
        if ($passed) {
            $passed = false;
            // die("No se pudo recuperar id");
            $data["result_status"] = false;
        }
    }
    $quer = "SELECT id, fecha_ida, fecha_regreso, origen, destino, kilos_disponible, espacio_disponible FROM viaje ORDER BY id DESC LIMIT 3";

    $result = $db->query($quer);
    $passed = true;
    // aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
    $data = array("result_status" => array(), "ammount" => 0);
    if (!$result) {
        $passed = false;
        $data["result_status"] = false;
    } else {
        $i = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $data["data".$i] = array();
            $origen = $row["origen"];
            $origenQuery = $db->query("SELECT nombre, region_id FROM comuna WHERE id = $origen");
            $origenArr = mysqli_fetch_assoc($origenQuery);
            $regionidOr = $origenArr["region_id"];
            $comunaOrigen = $origenArr["nombre"];
            $regionOrigen = mysqli_fetch_assoc($db->query("SELECT nombre FROM region WHERE id = $regionidOr"))["nombre"];

            $destino = $row["destino"];
            $destinoQuery = $db->query("SELECT nombre, region_id FROM comuna WHERE id = $destino");
            $destinoArr = mysqli_fetch_assoc($destinoQuery);
            $regionidDest = $destinoArr["region_id"];
            $comunaDestino = $destinoArr["nombre"];
            $regionDestino = mysqli_fetch_assoc($db->query("SELECT nombre FROM region WHERE id = $regionidDest"))["nombre"];

            $rawAddOr = $comunaOrigen.", ".$regionOrigen;
            $addressOrigen = str_replace(" ", "+", "{".$rawAddOr."+Chile+"."}");

            $addressOrigen = strtr($addressOrigen, array("á"=>"a", "é"=>"e", "í"=>"i", "ó"=>"o", "ú"=>"u", "ñ"=>"n"));

            $urlOrigen = "https://maps.googleapis.com/maps/api/geocode/json?address=$addressOrigen&key=AIzaSyAG195ROSB1lHUnAgFQjLMqBBBE7yq9Tss";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $urlOrigen);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $resp_json_origen = curl_exec($ch);
            curl_close($ch);

            $rawAddDest = $comunaDestino.", ".$regionDestino;
            $addressDestino = str_replace(" ", "+", "{".$rawAddDest."+Chile+"."}");

            $addressDestino = strtr($addressDestino, array("á"=>"a", "é"=>"e", "í"=>"i", "ó"=>"o", "ú"=>"u", "ñ"=>"n"));

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
            
            if (false) {
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


                if ((!isset($resp_orig["results"][0]["geometry"]["location"]["lat"]) || !isset($resp_orig["results"][0]["geometry"]["location"]["lng"]) || !isset($resp_dest["results"][0]["geometry"]["location"]["lat"]) || !isset($resp_orig["results"][0]["geometry"]["location"]["lng"])) && false) {
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
    echo json_encode($data, JSON_UNESCAPED_SLASHES);
?>