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
                            $passed = false;
                            $mensajeError = "Error en la conexión al servidor";
                        }

                        // Obtencion del maximo N

                        if (!$numRows = mysqli_fetch_array($db->query("SELECT COUNT(*) FROM viaje"))) {
                            // header("Location: index.html");
                            if ($passed) {
                                $passed = false;
                                // die("No se pudo recuperar id");
                                $mensajeError = "Error en la solicitud al servidor";
                            }
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
                            // echo mysqli_error($db);
                            if ($passed) {
                                $passed = false;
                                // die("No se pudo recuperar id");
                                $mensajeError = "Error en la solicitud al servidor";
                            }
                        }
                        if ($passed) {
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

                                //$origen = utf8_encode($origen);
                                //$destino = utf8_encode($destino);
                                //$fechaIda = utf8_encode($fechaIda);
                                //$espacioDisp = utf8_encode($espacioDisp);
                                //$kilosDisp = utf8_encode($kilosDisp);
                                //$mail = utf8_encode($mail);

                                $elemNum = $counter + 5*($n - 1);
                                
                                if ($origenArr && $destinoArr && $espacioDisp && $kilosDisp) {
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
                                    if ($passed) {
                                        $passed = false;
                                        // die("No se pudo recuperar id");
                                        $mensajeError = "Error en la solicitud al servidor";
                                    }
                                }
                                $counter += 1;
                            }
                            $tabla = $tabla."</tbody></table>";

                            if($passed) {
                                echo $tabla;
                            } else {
                                echo mensaje_error($mensajeError, "../index.php");
                            }

                        } else {
                            echo mensaje_error($mensajeError, "../index.php");
                        }

                    ?>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="row bg-light">
                <?php

                    if ($passed) {
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
                    }
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
