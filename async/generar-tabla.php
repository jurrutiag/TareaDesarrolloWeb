<?php

    function generar_tabla_encargos($ids, $elemNums, $origens, $destinos, $fotos, $espacioDisps, $kilosDisps, $mails) {
        $tabla = "<table class='table table-responsive table-striped table-hover col-12 m-auto bg-light' id='table'>
                <thead>
                    <tr>
                        <th scope='col'>#</th>
                        <th scope='col'>Origen</th>
                        <th scope='col'>Destino</th>
                        <th scope='col'>Foto</th>
                        <th scope='col'>Espacio</th>
                        <th scope='col'>Kilos</th>
                        <th scope='col'>Email</th>
                    </tr>
                </thead>
                <tbody>";

        for ($i = 0; $i < sizeof($ids); $i++) {
            $tabla = $tabla."<tr id='$ids[$i]' onclick='masInfoEncargos($ids[$i])'>
                <th scope='row'> $elemNums[$i] </th>
                <td> $origens[$i] </td>
                <td> $destinos[$i] </td>
                <td> <img alt='Foto encargo $ids[$i]' src='$fotos[$i]' class='foto-tabla'/> </td>
                <td> $espacioDisps[$i] </td>
                <td> $kilosDisps[$i] </td>
                <td> $mails[$i] </td>
                </tr>";
        }

        $tabla = $tabla."</tbody></table>";
        
        return $tabla;

    }

    function generar_tabla_encargos_resumida($ids, $descs , $origens, $destinos, $fotos) {
        $tabla = "<table class='table table-responsive table-striped table-hover col-12 m-auto bg-light' id='table'>
                <thead>
                    <tr>
                        <th scope='col'>Descripción</th>
                        <th scope='col'>Origen</th>
                        <th scope='col'>Destino</th>
                        <th scope='col'>Foto</th>
                    </tr>
                </thead>
                <tbody>";

        for ($i = 0; $i < sizeof($ids); $i++) {
            $tabla = $tabla."<tr id='$ids[$i]' onclick='masInfoEncargos($ids[$i])'>
                <td> $descs[$i] </td>
                <td> $origens[$i] </td>
                <td> $destinos[$i] </td>
                <td> <img alt='Foto encargo $ids[$i]' src='$fotos[$i]' class='foto-tabla'/> </td>
                </tr>";
        }

        $tabla = $tabla."</tbody></table>";
        
        return $tabla;

    }

    function generar_tabla_viajes($ids, $elemNums, $origens, $destinos, $fechaIdas, $espacioDisps, $kilosDisps, $mails) {
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
        for ($i = 0; $i < sizeof($ids); $i++) {
            $tabla = $tabla."<tr id='$ids[$i]' onclick='masInfoViajes($ids[$i])'>
                    <th scope='row'> $elemNums[$i] </th>
                    <td> $origens[$i] </td>
                    <td> $destinos[$i] </td>
                    <td> $fechaIdas[$i] </td>
                    <td> $espacioDisps[$i] </td>
                    <td> $kilosDisps[$i] </td>
                    <td> $mails[$i] </td>
                </tr>";            
        }

        $tabla = $tabla."</tbody></table>";

        return $tabla;
    }
?>