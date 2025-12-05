<?php 

    include "conn.php";
    //Ejecutar consulta
    $sql = "SELECT * FROM sensor WHERE eliminado = 0";
    $resultado = $mysqli->query($sql);
    
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Tabla Sensores</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    <body>
        <?php include "layout.php"; ?>
        <div class = "container text-center" style ="margin-bottom:30px;">
            <div class="col mb-1">
                <h1>Sensores</h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-9">
                <table class="table table-responsive table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Sensor - Unidades</th>
                            <th scope="col">Modelo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = $resultado->fetch_assoc()) { ?>
                        <tr>
                            <td><?=$fila["id"]?></td>
                            <td><?=$fila["tipo"]?> - <?=$fila["unidades"]?></td>
                            <td><?=$fila["modelo"]?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>