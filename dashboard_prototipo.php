<?php 

    include "conn.php";
    //Ejecutar consulta
    $sql = "SELECT * FROM acciones WHERE eliminado = 0";
    $res_acc = $mysqli->query($sql);

    //$sql = "SELECT * FROM lecturas WHERE eliminado = 0";
    //$res_lec = $mysqli->query($sql);

    $sql = "SELECT * FROM sensor WHERE eliminado = 0";
    $res_sen = $mysqli->query($sql);

    $sql = "SELECT * FROM actuador WHERE eliminado = 0";
    $res_act = $mysqli->query($sql);  
?>

<!doctype html>
<html lang="es">
<head>
  <meta http-equiv="refresh" content="2" > 
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Prototipo</title>
</head>
<body class="bg-light">
    <?php include "layout.php"; ?>
  <div class="container py-4">
    <h1 class="h4 mb-4 text-center"><i class="fas fa-gauge-high text-info"></i> Dashboard Simple</h1>

    <div class="row g-3">
        <?php while ($fila = $res_sen->fetch_assoc()) { 
            $fila_id_sen = $fila["id"];
            $sql = "SELECT * FROM lecturas WHERE id_sensor = '$fila_id_sen' ORDER BY id DESC LIMIT 1";
            $res_lec = $mysqli->query($sql);
            while ($lectura = $res_lec->fetch_assoc()){
            ?>
      <div class="col-md-6">
        <div class="card text-center">
          <div class="card-body">
            <h5 class="card-title text-muted">Sensor <?=$fila["id"]?> — <?=$fila["tipo"]?></h5>
            <p class="display-6 fw-bold mb-1"><?=$lectura["valor"]?> <?=$lectura["unidades"]?></p>
            <small class="text-muted">Actualizado: <?=$lectura["hora"]?></small>
          </div>
        </div>
      </div>
      <?php } ?>
      <?php } ?>
      <!--<div class="col-md-6">
        <div class="card text-center">
          <div class="card-body">
            <h5 class="card-title text-muted">Sensor 2 — Humedad</h5>
            <p class="display-6 fw-bold mb-1">58.7 %</p>
            <small class="text-muted">Actualizado: 17:35</small>
          </div>
        </div>
      </div> -->
    </div>
    <div class="row g-3 mt-3">
      <?php while ($fila = $res_act->fetch_assoc()) { ?>
      <div class="col-md-6">
        <div class="card text-center">
          <div class="card-body">
            <h5 class="card-title text-muted">Actuador <?=$fila["id"]?> — <?=$fila["tipo"]?></h5>
            <?php if($fila["estado"] == 0) { ?>
            <span class="badge bg-secondary fs-6">OFF</span>
            <?php }
            elseif($fila["estado"] == 1) { ?>
            <span class="badge bg-success fs-6">ON</span>
            <?php } 
            else { ?>
            <span class="badge bg-danger fs-6">ERROR</span>
            <?php } ?>

            
          </div>
        </div>
      </div>
      <?php } ?>
      <!--<div class="col-md-6">
        <div class="card text-center">
          <div class="card-body">
            <h5 class="card-title text-muted">Actuador 2 — Válvula</h5>
            <span class="badge bg-success fs-6">ON</span>
          </div>
        </div>
      </div> -->
    </div>

    <footer class="text-center text-muted mt-4 small">Prototipo — Estado actual mostrado sin conexión a servidor, pero con conexión y actualización con la base de datos</footer>
  </div>
</body>
</html>