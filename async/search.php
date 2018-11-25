<?php
    require_once("../options/configuraciones.php");
    require_once("../options/funciones.php");
    $db = new mysqli($server_name, $user_name, $user_pass, $db_name);
    $enc = $db->set_charset($encoding);
        
    if ($db->connect_error) {

        if ($passed) {
            $passed = false;
            // die("No se pudo recuperar id");
            $mensajeError = "Error en la conexión al servidor";
        }

    } else {
        $searchBox = remove_special_chars($_POST['search']);
        $result = $db->query("SELECT descripcion, id FROM encargo");
        $count = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $desc = $row['descripcion'];
            $id = $row['id'];
            $loc = "options/mas-info-encargos.php?id=";
            if (strpos(strtolower(remove_special_chars($desc)), strtolower($searchBox)) !== false) {
                if ($count === 0) {
                    echo print_on_page($page, $id, $loc, $desc, true);
                    //<a href="options/agregar-viaje.php" class="list-group-item list-group-item-action list-group-item-white border-top-0 border-right-0">Agregar Viaje</a>
                    $count += 1;
                } else {
                    echo print_on_page($page, $id, $loc, $desc);
                    $count += 1;
                }
            }
        }
        if ($count === 0) {
            echo "<h5 class='list-group-item list-group-item-action list-group-item-white border-top-0 border-right-0 border-left-0 border-bottom-0'>No se encontraron resultados</h5>";
        }
    }

    function print_on_main_page($page, $id, $loc, $desc, $top=false) {
        if ($page === "main") {
            if ($top) {
                return "<h5 onclick='masInfoEncargos($id, \"$loc\")' class='list-group-item list-group-item-action list-group-item-white border-top-0 border-right-0 border-left-0'>$desc</h5>";
            } else {
                return "<h5 onclick='masInfoEncargos($id, \"$loc\")' class='list-group-item list-group-item-action list-group-item-white border-right-0 border-left-0'>$desc</h5>";
            }
        } else if ($page === "encargos") {
            
        }
        
    }
?>