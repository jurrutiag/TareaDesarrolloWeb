<!DOCTYPE html>

<html lang="es">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="boostrap_v4_w3c_fix.css">
        <link rel="stylesheet" href="styles.css">
        <title>Página&#160;Principal</title>
        <style>
        #map {
            height: 100%;
            width: 100%;
            }
        </style>
    </head>

    <body class="bg-light">

        <div class="container-fluid h-100 bg-light">
            <div class="row bg-secondary">
                <div class="jumbotron jumbotron-fluid col-md-12 display-4 d-none d-md-block" id="top-part"></div>
            </div>
            <div class="row bg-light">
                <nav class="navbar navbar-dark bg-dark col-12 flex-md-nowrap">
                    <a class="navbar-brand col-sm-2 col-12 text-light mr-0" href="">Menú</a>
                    <input id="search" class="form-control form-control-dark w-100" placeholder="Buscar por descripción">
                </nav>
            </div>
            <div class="row bg-light h-100">
                <div id="sidebar" class="list-group col-md-2 col-12 p-0 bg-white">
                    <a href="options/agregar-viaje.php" class="list-group-item list-group-item-action list-group-item-white border-top-0 border-right-0">Agregar Viaje</a>
                    <a href="options/agregar-encargo.php" class="list-group-item list-group-item-action list-group-item-white border-right-0">Agregar Encargo</a>
                    <a href="options/ver-viajes.php?n=1" class="list-group-item list-group-item-action list-group-item-white border-right-0">Ver Viajes</a>
                    <a href="options/ver-encargos.php?n=1" class="list-group-item list-group-item-action list-group-item-white border-bottom-0 border-right-0">Ver Encargos</a>
                    
                </div>
                <div id="map" class="col-12 col-md-10 h-100 w-100"></div>
                <div id="search-result" class="list-group col-md-10 col-12 p-0 bg-white h-100 w-100 rounded">
                    
                </div>
                
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="scripts.js"></script>
        <script src="search.js"></script>
        <script src="bootstrap.js"></script>
        
        
        <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAG195ROSB1lHUnAgFQjLMqBBBE7yq9Tss&callback=initMap">
        </script>
        
        <script src="map.js"></script>
    </body>

</html>
