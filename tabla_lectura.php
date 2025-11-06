<?php 

    include "conn.php";
    //Ejecutar consulta
    $sql = "SELECT * FROM lecturas WHERE eliminado = 0";
    $resultado = $mysqli->query($sql);
    
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Tabla Lecturas</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    <body>
        <?php include "layout.php"; ?>
        <div class = "container text-center">
            <div class="col mb-1">
                <h1>Lecturas</h1>
            </div>
            <div class="col mb-12" style="margin-left: 1100px; margin-bottom: 15px;">
                <a href="(archivo).php" class="btn btn-outline-success">Nueva Lectura</a>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-9">
                <table class="table table-responsive table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">ID de Sensor</th>
                            <th scope="col">Unidades</th>
                            <th scope="col">Valor</th>
                            <th scope="col">Hora</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = $resultado->fetch_assoc()) { ?>
                        <tr>
                            <td><?=$fila["id"]?></td>
                            <td><?=$fila["id_sensor"]?></td>
                            <td><?=$fila["unidades"]?></td>
                            <td><?=$fila["valor"]?></td>
                            <td><?=$fila["hora"]?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="(archivo).php?id=<?=$fila['id']?>" class="btn btn-outline-primary">Editar</a>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Crear
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="(archivo).php?id=<?=$fila['id']?>">Placeholder</a></li>
                                        </ul>
                                    </div>
                                    <a href="(archivo).php?id=<?=$fila['id']?>" class="btn btn-outline-danger">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>