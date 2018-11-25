<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="../boostrap v4 w3c fix.css">
        <link rel="stylesheet" href="../styles.css">

        <title>Error</title>
    </head>
    <body class="bg-light">
        
        <div class="container-fluid h-100 bg-light">
            <div class="row bg-light">
                <div class="jumbotron jumbotron-fluid col-md-12 display-4 d-none d-md-block" id="top-part"></div>
            </div>


            <div class="row bg-light">
                <?php
                    require_once("funciones.php");
                    require_once("configuraciones.php");

                    $id = htmlspecialchars($_GET["errid"]);

                    if (!array_key_exists($id, $errors)) {
                        $mensajeError = "Página Inválida";
                    } else {
                        $mensajeError = $errors[$id];
                    }
                    
                    mensaje_error($mensajeError, "../index.php");
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