<?php
header("Content-Type: text/html");

include "conn.php";

// Only read POST when present to avoid undefined index
$topic = $_POST['topic'] ?? null;
$payload = $_POST['payload'] ?? null;

// Run insert only on POST and when required fields exist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['topic'], $_POST['payload'])) {
    $topic = trim($_POST['topic']);
    $payload = trim($_POST['payload']);

    $arr = explode(", ", $payload);
    $id_sensor = $arr[0];
    $valor = $arr[1];

    // Prepared statement to insert
    $stmt = $mysqli->prepare("INSERT INTO lecturas (id_sensor, valor, hora) VALUES (?, ?, CURRENT_TIMESTAMP)");
    if ($stmt) {
        $stmt->bind_param("ss", $id_sensor, $valor);
        $stmt->execute();
        $stmt->close();
    } else {
        error_log("Prepare failed: " . $mysqli->error);
    }

    // Redirect to avoid form resubmission on refresh (PRG pattern)
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

?>
