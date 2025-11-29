<?php
header("Content-Type: text/html");

$host = "localhost";
$usuario = "root";
$contraseña = "";
$base_datos = "testdb";

//Conexión
$mysqli = new mysqli($host, $usuario, $contraseña, $base_datos);

//verificar conexión
if ($mysqli->connect_error)
{
    die("Error de conexión: " . $mysqli->connect_error);
    exit();
}

// Only read POST when present to avoid undefined index
$topic = $_POST['topic'] ?? null;
$payload = $_POST['payload'] ?? null;

// Run insert only on POST and when required fields exist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['topic'], $_POST['payload'])) {
    $topic = trim($_POST['topic']);
    $payload = trim($_POST['payload']);

    // Prepared statement to insert
    $stmt = $mysqli->prepare("INSERT INTO mensajes (topic, payload, time) VALUES (?, ?, CURRENT_TIMESTAMP)");
    if ($stmt) {
        $stmt->bind_param("ss", $topic, $payload);
        $stmt->execute();
        $stmt->close();
    } else {
        error_log("Prepare failed: " . $mysqli->error);
    }

    // Redirect to avoid form resubmission on refresh (PRG pattern)
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$sql = "SELECT * FROM mensajes";
$resultado = $mysqli->query($sql);
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="refresh" content="1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Tabla Invernaderos</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    <body>
        <?php include "layout.php"; ?>
        <div class = "container text-center">
            <div class="col mb-1">
                <h1>Mensajes de prueba</h1>
            </div>
            <div class="col mb-12" style="margin-left: 1100px; margin-bottom: 15px;">
                <a href="(archivo).php" class="btn btn-outline-success">Nuevo coaj</a>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-7">
                <table class="table table-responsive table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Topic</th>
                            <th scope="col">Payload</th>
                            <th scope="col">Timeeeeee</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = $resultado->fetch_assoc()) { ?>
                        <tr>
                            <td><?=$fila["topic"]?></td>
                            <td><?=$fila["payload"]?></td>
                            <td><?=$fila["time"]?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
