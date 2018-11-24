<?php

    function reformatDate($dat) {
        $ldat = explode('/' ,$dat);
        return $ldat[2].'-'.$ldat[1].'-'.$ldat[0];
    }

    function validarFecha($dat) {
        
        $today = new DateTime('NOW');
        $datetime = new DateTime(reformatDate($dat));
        
        if ($today > $datetime) {

            return FALSE;
        }

        if (!preg_match('/^(\d{2}\/\d{2}\/\d{4})/', $dat)) {
            return FALSE;
        }
        $diasMeses = array("31", "28", "31", "30", "31", "30", "31", "31", "30", "31", "30", "31");

        $sepDate = explode('/', $dat);
        $dia = intval($sepDate[0]);
        $mes = intval($sepDate[1]);
        $anio = intval($sepDate[2]);

        // anios bisiestos
        if (($anio % 4 == 0 and $anio % 100 != 0) or $anio % 400 == 0) {
            $diasMeses[1] = 29;
        }

        // rangos
        if ($mes > 12 or $mes <= 0 or $anio > 5000 or $anio < 2018 or $dia <= 0) {
            return FALSE;
        }

        if ($dia > $diasMeses[$mes - 1]) {
            return FALSE;
        }

        return TRUE;
    }

    function validarSelect($id, $isValid) {
        if(!htmlspecialchars($_POST[$id])){
            $isValid = FALSE;
        }
        return $isValid;
    }

    function validarEmail($isValid) {
        $mail = htmlspecialchars($_POST["email"]);
        if (strlen($mail) > 30) {
            $isValid = FALSE;
        }

        if (!preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD', $mail)) {
            $isValid = FALSE;
        }
        return $isValid;
    }

    function validarCelular($isValid) {
        $celular = htmlspecialchars($_POST["celular"]);
        if (strlen($celular) > 12) {
            $isValid = FALSE;
        }
        if (!preg_match('/^\+56\d{1}\d{8}/', $celular)) {
            $isValid = FALSE;
        }
        return $isValid;
    }

    function validarDescripcion($isValid) {
        $descripcion = htmlspecialchars($_POST["descripcion"]);
        $maxCaracteres = 250;
        if (strlen($descripcion) > $maxCaracteres) {
            $isValid = FALSE;
        }
        return $isValid;
    }

    function validarFoto($isValid) {
        $foto = $_FILES['foto-encargo']['name'];
        $extensiones = array("jpg", "jpeg", "exif", "tiff", "bmp", "png", "ppm", "hdr", "bpg");
        
        $splFoto = explode('.', $foto);
        
        if (strlen($foto) <= 0 or !in_array(strtolower($splFoto[sizeof($splFoto) - 1]), $extensiones)) {
            $isValid = FALSE;
        }
        return $isValid;
    }

    function agregar_viaje_validacion() {
        $isValid = TRUE;

        $ids = array("region-origen", "comuna-origen", "region-destino", "comuna-destino");
        $fechaIdaVal = htmlspecialchars($_POST["fecha-ida"]);

        $fechaRegresoVal = htmlspecialchars($_POST["fecha-regreso"]);

        // validación regiones y comunas
        for ($i = 0; $i < 4; $i++) {
            $isValid = validarSelect($ids[$i], $isValid);
        }

        if (htmlspecialchars($_POST["comuna-origen"]) === htmlspecialchars($_POST["comuna-destino"])) {
            $isValid = FALSE;
        }

        // validación fecha

        if (!validarFecha($fechaIdaVal)) {
            $isValid = FALSE;
        }

        if ($fechaRegresoVal !== '') {
            $ida = new DateTime(reformatDate($fechaIdaVal));
            $regreso = new DateTime(reformatDate($fechaRegresoVal));
            // Si la fecha de regreso no esta vacia, se valida y se verifica que no sea menor a la de ida
            if ((!is_null($fechaRegresoVal) && !validarFecha($fechaRegresoVal)) || $ida > $regreso) {
                $isValid = FALSE;
            }
        }
        
        // validación espacio

        $isValid = validarSelect("espacio-disponible", $isValid);

        // validación kilos

        $isValid = validarSelect("kilos-disponibles", $isValid);

        // validación email

        $isValid = validarEmail($isValid);

        // validación celular
        
        $isValid = validarCelular($isValid);

        
        return $isValid;
    }

    function agregar_encargo_validacion() {
        $isValid = TRUE;

        $ids = array("region-origen", "comuna-origen", "region-destino", "comuna-destino");

        // validacion descripcion
        $isValid = validarDescripcion("descripcion", $isValid);


        // validación regiones y comunas
        for ($i = 0; $i < 4; $i++) {
            $isValid = validarSelect($ids[$i], $isValid);
        }
        if (htmlspecialchars($_POST["comuna-origen"]) === htmlspecialchars($_POST["comuna-destino"])) {
            $isValid = FALSE;
        }

        // validación espacio

        $isValid = validarSelect("espacio-solicitado", $isValid);

        // validación kilos

        $isValid = validarSelect("kilos-solicitados", $isValid);

        // validación email

        $isValid = validarEmail($isValid);

        // validación celular

        $isValid = validarCelular($isValid);

        // validacion foto

        $isValid = validarFoto($isValid);
        
        return $isValid;
    }

    function mensaje_error($mensajeError, $returnPath) {
        echo "<div class='list-group col-md-6 col-12 m-auto'>
            <li class='list-group-item active bg-warning'>$mensajeError</li>
            </div>";
    }
?>