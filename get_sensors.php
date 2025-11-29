<?php
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_error.log');

// Start output buffering to avoid accidental partial output/BOM issues
if (!ob_get_level()) ob_start();

try {
    include "conn.php";

    if (!isset($mysqli) || !($mysqli instanceof mysqli)) {
        throw new Exception('Database connection ($mysqli) not available from conn.php');
    }
    $mysqli->set_charset('utf8mb4');

    $wait = isset($_GET['wait']) ? intval($_GET['wait']) : 0;
    if (isset($_GET['last_id'])) {
        $last = intval($_GET['last_id']);
    } elseif (isset($_GET['last'])) {
        $last = intval($_GET['last']);
    } else {
        $last = 0;
    }

    if ($wait === 1) {
        set_time_limit(0);
        ignore_user_abort(true);
        $start = time();
        while (true) {
            $res_last = $mysqli->query("SELECT id FROM lecturas ORDER BY id DESC LIMIT 1");
            if ($res_last === false) {
                error_log("Query error (res_last): " . $mysqli->error);
                break;
            }
            $row_last = $res_last->fetch_assoc();
            if ($row_last && isset($row_last['id'])) {
                $currentId = intval($row_last['id']);
                if ($currentId > $last) {
                    break;
                }
            }
            if (time() - $start >= 25) {
                break;
            }
            usleep(300000);
        }
    }

    function fetch_latest($mysqli, $sensor_id) {
        $sensor_id = intval($sensor_id);
        $sql = "SELECT * FROM lecturas WHERE id_sensor = {$sensor_id} ORDER BY id DESC LIMIT 1";
        $sql_unit = "SELECT unidades FROM sensor WHERE id = {$sensor_id} ORDER BY id DESC LIMIT 1";

        $res = $mysqli->query($sql);
        $unit = $mysqli->query($sql_unit);
        
        $row = $res->fetch_assoc();
        $row_u = ($unit && method_exists($unit, 'fetch_assoc')) ? $unit->fetch_assoc() : null;
        if (!$row) return null;

        $ultima = null;
        if (isset($row['ultima_act'])) $ultima = $row['ultima_act'];
        elseif (isset($row['created_at'])) $ultima = $row['created_at'];
        elseif (isset($row['timestamp'])) $ultima = $row['timestamp'];
        elseif (isset($row['fecha'])) $ultima = $row['fecha'];

        return [
            'id' => isset($row['id']) ? intval($row['id']) : null,
            'valor' => isset($row['valor']) ? $row['valor'] : (isset($row['value']) ? $row['value'] : null),
            'unidades' => isset($row_u['unidades']) ? $row_u['unidades'] : (isset($row_u['units']) ? $row_u['units'] : null),
            'ultima_act' => isset($row['hora']) ? $row['hora'] : (isset($row['hora']) ? $row['hora'] : null)
        ];
    }

    $s1 = fetch_latest($mysqli, 1);
    $s2 = fetch_latest($mysqli, 2);
    $s3 = fetch_latest($mysqli, 3);

    $last_id = 0;
    foreach ([$s1, $s2, $s3] as $r) {
        if ($r && isset($r['id']) && $r['id'] !== null) {
            $last_id = max($last_id, intval($r['id']));
        }
    }

    $response = [
        'ok' => true,
        'last_id' => $last_id,
        'lecturas' => [
            'sensor_1' => $s1,
            'sensor_2' => $s2,
            'sensor_3' => $s3
        ]
    ];

    $json = json_encode($response, JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        $json = json_encode(['ok' => false, 'error' => 'json_encode_error', 'details' => json_last_error_msg()]);
    }

    echo $json;
    @ob_end_flush();
    exit;
} catch (Throwable $e) {
    // Ensure a JSON error is always returned and log the exception
    error_log("Exception in get_sensors.php: " . $e->getMessage());
    http_response_code(500);
    $payload = ['ok' => false, 'error' => 'server_exception', 'message' => $e->getMessage()];
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    @ob_end_flush();
    exit;
}
?>