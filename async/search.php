<?php
    require_once("../options/configuraciones.php");
    require_once("../options/funciones.php");
    require_once("generar-tabla.php");
    $db_config = new DbConfig();
    $db_arr = $db_config->getConnection();
    
    if (!$db_arr["connection"]) {
        echo "<h5 class='list-group-item list-group-item-action list-group-item-white border-top-0 border-right-0 border-left-0 border-bottom-0'>No se encontraron resultados</h5>";
    } else {
        $db = $db_arr["db"];

        $main = $_POST["main"];
        if ($main === "true") {
            $main = true;
        } else {
            $main = false;
        }

        $searchBox = remove_special_chars($_POST['search']);

        if ($main) {
            $result = $db->query("SELECT descripcion, id FROM encargo");
        } else {
            $result = $db->query("SELECT descripcion, origen, destino, foto, id FROM encargo");
            $ids = array();
            $descs = array();
            $origens = array();
            $destinos = array();
            $fotos = array();
        }
        
        $count = 0;

        while ($row = mysqli_fetch_assoc($result)) {
            $desc = $row['descripcion'];
            $id = $row['id'];
            $loc = "options/mas-info-encargos.php?id=";
            
            if ($main) {
                if (strpos(strtolower(remove_special_chars($desc)), strtolower($searchBox)) !== false) {
                    if ($count === 0) {
                        echo "<h5 onclick='masInfoEncargos($id, \"$loc\")' class='list-group-item list-group-item-action list-group-item-white border-top-0 border-right-0 border-left-0'>$desc</h5>";
                        //<a href="options/agregar-viaje.php" class="list-group-item list-group-item-action list-group-item-white border-top-0 border-right-0">Agregar Viaje</a>
                        $count += 1;
                    } else {
                        echo "<h5 onclick='masInfoEncargos($id, \"$loc\")' class='list-group-item list-group-item-action list-group-item-white border-right-0 border-left-0'>$desc</h5>";
                        $count += 1;
                    }
                }
            } else {
                if (strpos(strtolower(remove_special_chars($desc)), strtolower($searchBox)) !== false) {
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
                    
                    $origen = $comunaOrigen.' / '.$regionOrigen;
                    
                    $destino = $comunaDestino.' / '.$regionDestino;

                    $foto = $row['foto'];

                    array_push($ids, $id);
                    array_push($descs, $desc);
                    array_push($origens, $origen);
                    array_push($destinos, $destino);
                    array_push($fotos, $foto);

                    $count += 1;
                }
            }
        }
        if (!$main && $count !== 0) {
            echo generar_tabla_encargos_resumida($ids, $descs, $origens, $destinos, $fotos);
        }
        if ($count === 0) {
            echo "<h5 class='list-group-item list-group-item-action list-group-item-white border-top-0 border-right-0 border-left-0 border-bottom-0'>No se encontraron resultados</h5>";
        }
    }
    
?>