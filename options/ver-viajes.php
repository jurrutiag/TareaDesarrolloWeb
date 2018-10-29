<!DOCTYPE html>

<html lang="es">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="../../styles.css">
        <title>Ver Viajes</title>
    </head>
    <body>

        <div class="container">

            <div class="first-half">

            </div>

            <div class="second-half">
                <div class="table">
                    <table id="table">
                        <tr>
                            <th>Origen</th>
                            <th>Destino</th>
                            <th>Fecha de Viaje</th>
                            <th>Espacio</th>
                            <th>Kilos</th>
                            <th>Email</th>
                        </tr>
                        <?php
                            require "configuraciones.php";
                            // Verificar definicion de n
                            $passed = true;
                            if (!isset($_GET['n'])) {
                                header("Location: ../ver-viajes.php/?n=1");
                                die("Parámetro no definido");
                            }

                            // Obtencion de n y calculo del numero de elementos antes de la pagina actual

                            $n = htmlspecialchars($_GET['n']);
                            $numElems = 5 * ($n - 1);

                            // Se inicia la conexion

                            $db = new mysqli($server_name, $user_name, $user_pass, $db_name);
                            

                            if ($db->connect_error) {
                                $passed = false;
                                $mensajeError = "Error en la conexión al servidor";
                            }

                            // Obtencion del maximo N

                            if (!$numRows = mysqli_fetch_array($db->query("SELECT COUNT(*) FROM viaje"))) {
                                // header("Location: ../index.html");
                                $passed = false;
                                $mensajeError = "Error en la solicitud al servidor";
                            } else {

                                $maxN = ceil($numRows[0] / 5);

                                // Si alguien ingresa un n mayor se recarga la pagina con el mayor n posible

                                if ($n > $maxN && $maxN >= 1) {
                                    header("Location: ../ver-viajes.php/?n=$maxN");
                                    die();
                                }
                                if ($n <= 0) {
                                    header("Location: ../ver-viajes.php/?n=1");
                                    die();
                                }
                            }

                            // Se seleccionan los elementos

                            if ($numElems == 0) {
                                $result = $db->query("SELECT id, fecha_ida, fecha_regreso, origen, destino, kilos_disponible, espacio_disponible, email_viajero FROM viaje ORDER BY id DESC LIMIT 5;");
                            } else {
                                $result = $db->query("SELECT id, fecha_ida, fecha_regreso, origen, destino, kilos_disponible, espacio_disponible, email_viajero FROM viaje ORDER BY id DESC LIMIT $numElems, 5;");
                            }

                            
                            if(!$result) {
                                // echo mysqli_error($db);
                                $passed = false;
                                $mensajeError = "Error en la solicitud al servidor";
                            }
                            if ($passed) {
                                $i = 1;
                                $tabla = "";
                                while ($row = mysqli_fetch_assoc($result)){

                                    $origen = $row["origen"];
                                    $origenArr = mysqli_fetch_assoc($db->query("SELECT nombre, region_id FROM comuna WHERE id = $origen"));
                                    $regionidOr = $origenArr["region_id"];
                                    $origen = $origenArr["nombre"].' / '.mysqli_fetch_assoc($db->query("SELECT nombre FROM region WHERE id = $regionidOr"))["nombre"];
                                    
                                    $destino = $row["destino"];
                                    $destinoArr = mysqli_fetch_assoc($db->query("SELECT nombre, region_id FROM comuna WHERE id = $destino"));
                                    $regionidDest = $destinoArr["region_id"];
                                    $destino = $destinoArr["nombre"].' / '.mysqli_fetch_assoc($db->query("SELECT nombre FROM region WHERE id = $regionidDest"))["nombre"];

                                    
                                    $fechaIda = date('d-m-Y', strtotime($row["fecha_ida"]));

                                    $espacioDisp = $row['espacio_disponible'];
                                    $espacioDisp = mysqli_fetch_array($db->query("SELECT valor FROM espacio_encargo WHERE id = $espacioDisp"))[0];
                                    
                                    $kilosDisp = $row['kilos_disponible'];
                                    $kilosDisp = mysqli_fetch_array($db->query("SELECT valor FROM kilos_encargo WHERE id = $kilosDisp"))[0];

                                    $mail = $row['email_viajero'];

                                    $origen = utf8_encode($origen);
                                    $destino = utf8_encode($destino);
                                    $fechaIda = utf8_encode($fechaIda);
                                    $espacioDisp = utf8_encode($espacioDisp);
                                    $kilosDisp = utf8_encode($kilosDisp);
                                    $mail = utf8_encode($mail);
                                    
                                    if ($origenArr && $destinoArr && $espacioDisp && $kilosDisp) {
                                        $tabla = $tabla."<tr id='$i' onclick='masInfoViajes($i)'>
                                            <td> $origen </td>
                                            <td> $destino </td>
                                            <td> $fechaIda </td>
                                            <td> $espacioDisp </td>
                                            <td> $kilosDisp </td>
                                            <td> $mail </td>
                                        </tr>";
                                    } else {
                                        $passed = false;
                                        $mensajeError = "Error en la solicitud al servidor";
                                    }
                                    $i += 1;
                                }
                            
                                echo $tabla;
                            } else {
                                echo "<ul class='vertical-menu'>
                                <li><label class='active' style='background-color: red;'>$mensajeError, intente nuevamente.</label></li>
                                </ul>";
                            }

                        ?>

                    </table>
                </div>
                <br>
                <div class="button-container">
                    <button id="return-button" onclick="location.href='../../index.html'" type="button">Volver al menú principal</button>
                </div>
                <?php
                    if ($passed) {
                        $next = $n + 1;
                        $prev = $n - 1;
                        $nextLocation = "../ver-viajes.php/?n=$next";
                        $nextLocation = "location.href='$nextLocation'";

                        $prevLocation = "../ver-viajes.php/?n=$prev";
                        $prevLocation = "location.href='$prevLocation'";
                        echo "<div class='button-container'>";
                        if ($prev > 0) {
                            echo "<button id='next-button' onclick=$prevLocation type='button'>Anterior</button>";
                        }
                        if ($next <= $maxN) {
                            echo "<button id='next-button' onclick=$nextLocation type='button'>Siguiente</button>";
                        }
                        echo "</div>";
                    }
                ?>
                <br>
                <br>

            </div>
        </div>

        <script src="../../scripts.js"></script>
    </body>
</html>
