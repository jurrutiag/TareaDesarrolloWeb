<!DOCTYPE html>

<html lang="es">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="../boostrap v4 w3c fix.css">
        <link rel="stylesheet" href="../styles.css">
        <title>Ver Viajes</title>
    </head>
    <body class="bg-light">

        <div class="container-fluid bg-light">
            <div class="row bg-light">
                <div class="jumbotron jumbotron-fluid col-md-12 display-4 d-none d-md-block" id="top-part"></div>
            </div>

            <div class="row bg-light">
                    <nav class="navbar navbar-dark bg-dark col-12 flex-md-nowrap" style="height: 55.6px">
                        
                    </nav>
                    <?php
                        require_once("configuraciones.php");
                        require_once("funciones.php");

                        // Verificar definicion de n
                        $passed = true;
                        if (!isset($_GET['n'])) {
                            header("Location: ver-viajes.php?n=1");
                            die("Parámetro no definido");
                        }

                        // Obtencion de n y calculo del numero de elementos antes de la pagina actual

                        $n = htmlspecialchars($_GET['n']);
                        $numElems = 5 * ($n - 1);

                        // Se inicia la conexion

                        $db_config = new DbConfig();
                        $db_arr = $db_config->getConnection();
                        
                        if (!$db_arr["connection"]) {
                            to_error_page($db_arr["message"]);
                            die();
                        } else {
                            $db = $db_arr["db"];
                        }
                        
                        // Obtencion del maximo N

                        if (!$numRows = mysqli_fetch_array($db->query("SELECT COUNT(*) FROM viaje"))) {
                            // header("Location: index.html");
                            $mensajeError = "2";
                            to_error_page($mensajeError);
                            die();
                            
                        } else {

                            $maxN = ceil($numRows[0] / 5);

                            // Si alguien ingresa un n mayor se recarga la pagina con el mayor n posible

                            if ($n > $maxN && $maxN >= 1) {
                                header("Location: ver-viajes.php?n=$maxN");
                                die();
                            }
                            if ($n <= 0) {
                                header("Location: ver-viajes.php?n=1");
                                die();
                            }
                        }

                        // Se seleccionan los elementos

                        if ($numElems == 0) {
                            $result = $db->query("SELECT id, fecha_ida, origen, destino, kilos_disponible, espacio_disponible, email_viajero FROM viaje ORDER BY id DESC LIMIT 5;");
                        } else {
                            $result = $db->query("SELECT id, fecha_ida, origen, destino, kilos_disponible, espacio_disponible, email_viajero FROM viaje ORDER BY id DESC LIMIT $numElems, 5;");
                        }

                        
                        if(!$result) {
                            $mensajeError = "2";
                            to_error_page($mensajeError);
                            die();
                        }
                        
                        $tabla = "<table class='table table-responsive table-striped table-hover col-12 col-md-10 m-auto bg-light' id='table'>
                                    <thead>
                                        <tr>
                                            <th scope='col'>#</th>
                                            <th scope='col'>Origen</th>
                                            <th scope='col'>Destino</th>
                                            <th scope='col'>Fecha de Viaje</th>
                                            <th scope='col'>Espacio</th>
                                            <th scope='col'>Kilos</th>
                                            <th scope='col'>Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                        $counter = 1;
                        while ($row = mysqli_fetch_assoc($result)){

                            $id = $row['id'];

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
                            
                            $fechaIda = date('d-m-Y', strtotime($row["fecha_ida"]));

                            $espacioDisp = $row['espacio_disponible'];
                            $espacioQuery = $db->query("SELECT valor FROM espacio_encargo WHERE id = $espacioDisp");
                            $espacioDisp = mysqli_fetch_array($espacioQuery)[0];
                            
                            $kilosDisp = $row['kilos_disponible'];
                            $kilosQuery = $db->query("SELECT valor FROM kilos_encargo WHERE id = $kilosDisp");
                            $kilosDisp = mysqli_fetch_array($kilosQuery)[0];

                            $mail = $row['email_viajero'];

                            $elemNum = $counter + 5*($n - 1);
                            
                            $is_success = $is_success_destino && $is_success_origen && $espacioQuery && $kilosQuery;

                            if ($is_success) {
                                $tabla = $tabla."<tr id='$id' onclick='masInfoViajes($id)'>
                                    <th scope='row'> $elemNum </th>
                                    <td> $origen </td>
                                    <td> $destino </td>
                                    <td> $fechaIda </td>
                                    <td> $espacioDisp </td>
                                    <td> $kilosDisp </td>
                                    <td> $mail </td>
                                </tr>";

                            } else {
                                
                                $mensajeError = "2";
                                to_error_page($mensajeError);
                                die();
                            }
                            $counter += 1;
                        }
                        $tabla = $tabla."</tbody></table>";

                        echo $tabla;

                    ?>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="row bg-light">
                <?php
                    $next = $n + 1;
                    $prev = $n - 1;
                    $nextLocation = "ver-viajes.php?n=$next";
                    $nextLocation = "location.href='$nextLocation'";

                    $prevLocation = "ver-viajes.php?n=$prev";
                    $prevLocation = "location.href='$prevLocation'";
                    echo "<div class='btn-group m-auto' role='group'>";
                    if ($prev > 0) {
                        echo "<button type='button' onclick=$prevLocation class='btn btn-secondary'>Anterior</button>";
                    }
                    if ($next <= $maxN) {
                        echo "<button type='button' onclick=$nextLocation class='btn btn-secondary'>Siguiente</button>";
                    }
                    echo "</div>";
                    
                ?>
            </div>
            <br>
            <div class="row bg-light">
                <button id="return-button" class="btn m-auto btn-light border" onclick="index()" type="button">Volver al menú principal</button>
            </div>
            
            <br>
            <br>

        </div>

        <script src="../scripts.js"></script>
        <script src="../bootstrap.js"></script>
    </body>
</html>
