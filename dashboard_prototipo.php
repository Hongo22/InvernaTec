<?php 
include "conn.php"; 

$sql = "SELECT * FROM acciones WHERE eliminado = 0";
$res_acc = $mysqli->query($sql);

$sql = "SELECT * FROM sensor WHERE eliminado = 0";
$res_sen = $mysqli->query($sql);

$sql = "SELECT * FROM actuador WHERE eliminado = 0";
$res_act = $mysqli->query($sql);
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Prototipo Dashboard — 2 sensores / 2 actuadores</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<!-- in <head> -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
:root{--bg:#f4f6f8;--card:#ffffff;--accent:#0ea5a4;--muted:#6b7280}
body{font-family:Inter,system-ui,Segoe UI,Arial,sans-serif;margin:0;background:var(--bg);color:#111}
.container{max-width:1100px;margin:28px auto;padding:16px}
header{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px}
.brand{display:flex;gap:12px;align-items:center}
.brand h1{font-size:3.15rem;margin:0}
.grid{display:grid;gap:128px}
.grid-3{grid-template-columns:1fr 400px;}
.card{background:var(--card);border-radius:12px;padding:14px;box-shadow:0 6px 18px rgba(39, 38, 38, 0.50)}
.card_error{background:var(--card);border-radius:12px;padding:14px;box-shadow:0 6px 18px rgba(129, 1, 1, 0.75)}
.card_valid{background:var(--card);border-radius:12px;padding:14px;box-shadow:0 6px 18px rgba(0, 121, 0, 0.50)}
.sensor-row{display:flex;gap:12px;align-items:center}
.big-value{font-size:2.25rem;font-weight:600}
.muted{color:var(--muted);font-size:0.9rem}
.toggle{display:inline-flex;align-items:center;gap:10px}
.btn{background:var(--accent);color:white;padding:8px 12px;border-radius:8px;border:none;cursor:pointer}
.btn.secondary{background:#eee;color:#111}
.small{font-size:0.85rem}
</style>
</head>

<body>

<?php include "layout.php"; ?>

<div class="container">
<header>
<div class="brand"><h1>Dashboard</h1></div>
<button class="btn" id="refreshBtn"><i class="fa-solid fa-arrows-rotate"></i> Actualizar</button>
</header>

<main class="grid grid-3">

<div style="display:grid;gap:16px">
<?php 
while ($fila = $res_sen->fetch_assoc()) {
    $fila_id_sen = $fila["id"];
    $sql = "SELECT * FROM lecturas WHERE id_sensor = '$fila_id_sen' ORDER BY id DESC LIMIT 1";
    $res_lec = $mysqli->query($sql);
    while ($lectura = $res_lec->fetch_assoc()) {
?>
<div class="<?= $fila["estado"] == 0 ? "card" : ($fila["estado"] == 1 ? "card_valid" : "card_error") ?>">
  <header>
    <h3>Sensor #<?=$fila["id"]?> - <?=$fila["tipo"]?></h3> 
    <?php $id = $fila["id"]; ?>
    <div class="dropdown">
      <button class="btn btn-secondary dropdown-toggle"
              id="dropdownMenuLink<?= $id ?>"
              data-bs-toggle="dropdown"
              aria-expanded="false">
        Opciones
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink<?= $id ?>">
        <?php if ($fila["id"] == 1) { ?>
        <a class="dropdown-item" href="#" onclick="event.preventDefault(); setChartType(<?= $fila['id'] ?>,'line')">Line</a>
        <a class="dropdown-item" href="#" onclick="event.preventDefault(); setChartType(<?= $fila['id'] ?>,'progress')">Progress</a>
        <?php } ?>
        <?php if ($fila["id"] == 2) { ?>
        <a class="dropdown-item" href="#" onclick="event.preventDefault(); setChartType(<?= $fila['id'] ?>,'line')">Line</a>
        <a class="dropdown-item" href="#" onclick="event.preventDefault(); setChartType(<?= $fila['id'] ?>,'bar')">Bar</a>
        <?php } ?>
        <?php if ($fila["id"] == 3) { ?>
        <a class="dropdown-item" href="#" onclick="event.preventDefault(); setChartType(<?= $fila['id'] ?>,'line')">Line</a>
        <a class="dropdown-item" href="#" onclick="event.preventDefault(); setChartType(<?= $fila['id'] ?>,'progress')">Progress</a>
        <?php } ?>
      </ul>
    </div>
  </header>

<div class="sensor-row" style="margin-top:12px;">
<div style="flex:1">
    <div class="big-value" id="sensor<?=$fila["id"]?>_value"><?=$lectura["valor"]?> <?=$fila["unidades"]?></div>
    <div class="muted small" id="sensor<?=$fila["id"]?>_update">Última actualiz.: --</div>
</div>

<div style="width:500px;height:200px">
    <canvas id="chart<?=$fila["id"]?>" height="200"></canvas>
</div>

</div>
</div>
<?php } } ?>
</div>

<aside style="display:flex;flex-direction:column;gap:16px">
<div class="card">
<h3>Actuadores</h3>
<div style="margin-top:12px;display:flex;flex-direction:column;gap:10px">
<?php while ($fila = $res_act->fetch_assoc()) { ?>
<div class="actuator">
<div>
    <div class="muted">Actuador <?=$fila["id"]?></div>
    <div class="small"><?=$fila["tipo"]?></div>
</div>
<label class="small" id="act<?=$fila["id"]?>_state">OFF</label>
<div class="toggle">
    <button class="btn secondary" onclick="toggleActuator('actuator_<?=$fila['id']?>')">Toggle</button>
</div>
</div>
<?php } ?>
</div>
</div>
</aside>

</main>
</div>

<script>
const charts = {};
const histories = {};
const maxPoints = 30;

function pushHistory(id, val){
    if(!histories[id]) histories[id] = [];
    const arr = histories[id];
    if(arr.length >= maxPoints) arr.shift();
    arr.push(val);
}

function updateChartById(id){
    const chart = charts[id];
    const arr = histories[id] || [];
    if(!chart) return;
    if(chart.config.type === 'bar'){
        chart.data.labels = ["Current"];
        chart.data.datasets[0].data = [arr.length ? arr[arr.length-1] : 0];
    } else {
        chart.data.labels = arr.map((_,i)=>i);
        chart.data.datasets[0].data = arr;
    }
    chart.update();
}

function updateBarById(id, value){
    const chart = charts[id];
    if(!chart) return;
    chart.data.labels = ["Current"];
    chart.data.datasets[0].data = [value];
    chart.update();
}

function updateProgressById(id, value){
    const chart = charts[id];
    if(!chart) return;

    const v = Math.max(0, Math.min(100, value));

    chart.data.datasets[0].data = [100 - v]; // track
    chart.data.datasets[1].data = [v];       // foreground

    chart.update();
}


function setChartType(id, type) {
    const chart = charts[id];
    if(!chart) return;
    const oldData = JSON.parse(JSON.stringify(chart.config.data));
    const values = histories[id] ?? []; 
    const labels = histories[id].map((_, i) => i);

    const canvas = document.getElementById("chart" + id);
    canvas.width = canvas.width;
    const ctx = canvas.getContext("2d");

    chart.destroy();

  if (type == 'bar')
  {
    
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, "#ff4e50");
    gradient.addColorStop(1, "#14bcffff");

    const lastValue = histories[id]?.length ? histories[id][histories[id].length - 1] : 0; 

    charts[id] = new Chart(ctx, {
        type: type,
        data: {
            labels: [""],
            datasets: [{
                label: 'Temp',
                data: [lastValue],
                borderRadius: 12,
                backgroundColor: gradient,
                barThickness: 75
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { display: false, grid: { display: false } },
                y: { display: false, grid: { display: false }, min: 5, max: 35 }
            },
            plugins: { legend: { display: false }, tooltip: { enabled: false } }
        }
    });

  }
  else if (type == 'progress')
  {
    
    const gradient = ctx.createLinearGradient(0, 0, 300, 0);
    gradient.addColorStop(0, "#34d399");
    gradient.addColorStop(1, "#0e8375bb");

    const lastValue = histories[id]?.length ? histories[id][histories[id].length - 1] : 0;
    const value = Math.max(0, Math.min(100, lastValue)); // clamp 0–100

    charts[id] = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [""],
            datasets: [{
                label: "track",
                data: [100 - lastValue],
                backgroundColor: "rgba(0,0,0,0.08)",
                borderRadius: 15,
                barThickness: 35,
                order: 2,
                stack: "progress"
            },
            {
                label: "value",
                data: [lastValue],
                backgroundColor: gradient,
                borderRadius: 15,
                barThickness: 35,
                order: 1,
                stack: "progress",
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    min: 0, max: 100,
                    stacked: true,
                    display: false,
                    grid: {display: false}
                },
                y: { 
                  stacked: true,
                  display: false
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            }
        }
    });
  }
  else {
      charts[id] = new Chart(ctx, {
          type: type,
          data: {
              labels: labels,
              datasets: [{
                  data: values,
                  tension: 0.40,
                  pointRadius: 4,
                  pointBackgroundColor: "#040fa1d7",
                  borderWidth: 3,
                  borderColor: "#4680fdd2",
                  backgroundColor: "rgba(14,165,164,0.15)"
              }]
          },
          options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: { legend: { display: false } },
              scales: {
                  x: { grid: { display: false } },
                  y: { grid: { display: false } }
              }
          }
      });
    }
}

// initialize charts for all canvases with id="chart{n}"
document.querySelectorAll("canvas[id^='chart']").forEach(canvas => {
    const id = canvas.id.replace("chart", "");
    const ctx = canvas.getContext("2d");

    charts[id] = new Chart(ctx, {
          type: 'line',
          data: {
              labels: [],
              datasets: [{
                  data: [],
                  tension: 0.4,
                  pointRadius: 4,
                  pointBackgroundColor: "#040fa1d7",
                  borderWidth: 3,
                  borderColor: "#4680fdd2",
                  backgroundColor: "rgba(14,165,164,0.15)"
              }]
          },
          options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: { legend: { display: false } },
              scales: {
                  x: { grid: { display: false } },
                  y: { grid: { display: false } }
              }
          }
      });
    histories[id] = [];
});

async function fetchSensorsLongPolling(){
    try{
        const res = await fetch("get_sensors.php?wait=1&last_id="+lastId);
        const json = await res.json();
        if(!json.ok) return;

        lastId = json.last_id;

        // json.lecturas expected structure: { sensor_1: {...}, sensor_2: {...}, ... }
        for(const key in json.lecturas){
            if(!json.lecturas.hasOwnProperty(key)) continue;
            const id = key.replace('sensor_','');
            const s = json.lecturas[key];

            const valueEl = document.getElementById('sensor' + id + '_value');
            const updateEl = document.getElementById('sensor' + id + '_update');
            if(valueEl) valueEl.textContent = s.valor + ' ' + (s.unidades || '');
            if(updateEl) updateEl.textContent = 'Última actualiz.: ' + new Date(s.ultima_act).toLocaleTimeString();

            pushHistory(id, s.valor);

            const chart = charts[id];
            if(chart){
                if (chart.config.type === 'bar' && chart.config.data.datasets.length === 1) {
                    updateBarById(id, s.valor);  // normal bar
                }
                else if (chart.config.type === 'bar' && chart.config.data.datasets.length === 2) {
                    updateProgressById(id, s.valor); // progress bar
                }
                else {
                    updateChartById(id);
                }
            }
        }

    }catch(e){
        console.error("Connection error:",e);
    }

    fetchSensorsLongPolling();
}

let lastId = 0;
fetchSensorsLongPolling();
</script>
<!-- right before </body> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>