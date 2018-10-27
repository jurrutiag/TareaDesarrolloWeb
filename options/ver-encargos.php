<!DOCTYPE html>

<html lang="es">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="../../styles.css">
        <title>Ver Encargos</title>
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
                            <th>Foto</th>
                            <th>Espacio</th>
                            <th>Kilos</th>
                            <th>Email</th>
                        </tr>
                        <?php

                            // Verificar definicion de n

                            if (!isset($_GET['n'])) {
                                die("Parámetro no definido");
                            }

                            // Obtencion de n y calculo del numero de elementos antes de la pagina actual

                            $n = htmlspecialchars($_GET['n']);
                            $numElems = 5 * ($n - 1);

                            // Se inicia la conexion

                            $db = new mysqli('localhost', 'root', '', 'tarea2') or die("Hubo un problema en la conexión, intente más tarde");

                            // Obtencion del maximo N

                            if (!$numRows = mysqli_fetch_array($db->query("SELECT COUNT(*) FROM encargo"))) {
                                // header("Location: ../index.html");
                                die("Error en la conexión");
                            }

                            $maxN = ceil($numRows[0] / 5);

                            if ($n > $maxN or $n <= 0) {
                                die("Página incorrecta");
                            }

                            // Se seleccionan los elementos

                            if ($numElems == 0) {
                                $result = $db->query("SELECT id, origen, destino, espacio, kilos, foto, email_encargador FROM encargo ORDER BY id DESC LIMIT 5;");
                            } else {
                                $result = $db->query("SELECT id, origen, destino, espacio, kilos, foto, email_encargador FROM encargo ORDER BY id DESC LIMIT $numElems, 5;");
                            }

                            if(!$result) {
                                // echo mysqli_error($db);
                                die("No se pudieron recuperar los datos, intente nuevamente.");
                            }


                            $i = 0;
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

                                $foto = $row['foto'];
                                $foto = '../'.$foto;

                                $espacioDisp = $row['espacio'];
                                $espacioDisp = mysqli_fetch_array($db->query("SELECT valor FROM espacio_encargo WHERE id = $espacioDisp"))[0];
                                
                                $kilosDisp = $row['kilos'];
                                $kilosDisp = mysqli_fetch_array($db->query("SELECT valor FROM kilos_encargo WHERE id = $kilosDisp"))[0];

                                $mail = $row['email_encargador'];

                                $origen = utf8_encode($origen);
                                $destino = utf8_encode($destino);
                                $espacioDisp = utf8_encode($espacioDisp);
                                $kilosDisp = utf8_encode($kilosDisp);
                                $mail = utf8_encode($mail);
                                
                                echo "<tr id='$id' onclick='masInfoEncargos($id)'>
                                    <td> $origen </td>
                                    <td> $destino </td>
                                    <td> <img alt='Foto encargo $i' src='$foto' class='foto-tabla'/> </td>
                                    <td> $espacioDisp </td>
                                    <td> $kilosDisp </td>
                                    <td> $mail </td>
                                </tr>";
                                $i += 1;
                            };

                        ?>
                    </table>
                </div>
                <br>
                <div class="button-container">
                    <button id="return-button" onclick="location.href='../../index.html'" type="button">Volver al menú principal</button>
                </div>
                <br>
                    <?php
                        $next = $n + 1;
                        $prev = $n - 1;
                        $nextLocation = "../ver-encargos.php/?n=$next";
                        $nextLocation = "location.href='$nextLocation'";

                        $prevLocation = "../ver-encargos.php/?n=$prev";
                        $prevLocation = "location.href='$prevLocation'";
                        echo "<div class='button-container'>";
                        if ($prev > 0) {
                            echo "<button id='next-button' onclick=$prevLocation type='button'>Anterior</button>";
                        }
                        if ($next <= $maxN) {
                            echo "<button id='next-button' onclick=$nextLocation type='button'>Siguiente</button>";
                        }
                        echo "</div>";
                    ?>
                <br>
                <br>
            </div>
            
        </div>

        <script src="../../scripts.js"></script>
    </body>
</html>