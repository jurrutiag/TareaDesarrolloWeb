<?php

    function reformatDate($dat) {
        $ldat = explode('/' ,$dat);
        return $ldat[2].'-'.$ldat[1].'-'.$ldat[0];
    }

    function validarFecha($dat) {

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
        if(!$_POST[$id]) {
            $isValid = FALSE;
        }
        return $isValid;
    }

    function validarEmail($isValid) {
        $mail = $_POST["email"];

        if (!preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD', $mail)) {
            $isValid = FALSE;
        }
        return $isValid;
    }

    function validarCelular($isValid) {
        $celular = $_POST["celular"];
        if (!preg_match('/^\+56\d{1}\d{8}/', $celular)) {
            $isValid = FALSE;
        }
        return $isValid;
    }

    function validarDescripcion($isValid) {
        $descripcion = $_POST["descripcion"];
        $maxCaracteres = 250;
        if (strlen($descripcion) > $maxCaracteres) {
            $isValid = FALSE;
        }
        return $isValid;
    }

    function validarFoto($isValid) {
        $foto = $_FILES['foto-encargo'];
        $extensiones = array("jpg", "jpeg", "exif", "tiff", "bmp", "png", "ppm", "hdr", "bpg");

        $splFoto = explode('.', $foto);
        if (strlen($foto) <= 0 or !in_array(strtolower($splFoto[strlen($splFoto) - 1]), $extensiones)) {
            $isValid = FALSE;
        }
        return $isValid;
    }

    function agregar_viaje_validacion() {
        $isValid = TRUE;

        $ids = array("region-origen", "comuna-origen", "region-destino", "comuna-destino");
        $fechaVal = $_POST["fecha-viaje"];
        // validación regiones y comunas
        for ($i = 0; $i < 4; $i++) {
            $isValid = validarSelect($ids[$i], $isValid);
        }


        // validación fecha

        if (!validarFecha($fechaVal)) {
            $isValid = FALSE;
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
?>