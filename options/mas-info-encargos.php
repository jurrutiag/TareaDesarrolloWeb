<!DOCTYPE html>

<html lang="es">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="../boostrap v4 w3c fix.css">
        <link rel="stylesheet" href="../styles.css">

        <title>Más Información Encargos</title>
    </head>
    <body class="bg-light">

        <div class="container-fluid bg-light">
            <div class="row bg-light">
                <div class="jumbotron jumbotron-fluid col-md-12 display-4 d-none d-md-block" id="top-part"></div>
            </div>

            <div class="row bg-light">
                
                <?php
                    require_once("../async/recuperar-info-encargos.php");
                    echo recuperar_info_encargos();
                ?>
                
            </div>
            <br>
            <div class='row bg-light'>
            <button type='button' id='return-button' class='btn btn-light border m-auto' onclick='index()'>Volver al menú principal</button>
            </div>
            <br>
            <div class='row bg-light'>
            <button type='button' id='return-button' class='btn btn-light border m-auto' onclick='goBack()'>Volver atrás</button>
            </div>
            <br>
            <br>
        </div>
        
        <script src="../scripts.js"></script>
        <script> updateWidth(); </script>
        
    </body>
</html>