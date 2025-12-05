<?php

    include "conn.php";
    //Ejecutar consulta
    $sql = "SELECT * FROM invernadero WHERE eliminado = 0";
    $resultado = $mysqli->query($sql);
    
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Tabla Invernaderos</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    <body>
        <?php include "layout.php"; ?>
        <div class = "container text-center">
            <div class="col mb-1" style="margin-bottom:30px;">
                <h1>Invernaderos</h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-7">
                <table class="table table-responsive table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Tipo    </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = $resultado->fetch_assoc()) { ?>
                        <tr>
                            <td><?=$fila["id"]?></td>
                            <td><?=$fila["nombre"]?></td>
                            <td><?=$fila["ubicacion"]?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>