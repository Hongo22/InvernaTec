<?php 
include "conn.php"; 

$sql = "SELECT * FROM acciones WHERE eliminado = 0";
$res_acc = $mysqli->query($sql);

$sql = "SELECT * FROM sensor WHERE eliminado = 0";
$res_sen = $mysqli->query($sql);

$sql = "SELECT * FROM actuador WHERE eliminado = 0";
$res_act_admin = $mysqli->query($sql);
$res_act_user  = $mysqli->query($sql);
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard</title>


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

<div id="userViewLayout" style="display: none;" >
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<div  style="margin-bottom: 15px;">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-2">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">InvernaTec</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="dashboard.php">Dashboard</a>
          </li>
        </ul>
        <form class="d-flex">
          <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRe6uhqIud1jbG0UfOZv0HU16AgE8TMzOLO2wCNLyr83w&s" class="rounded float-end" width="50" height="50">
        </form>
      </div>
    </div>
  </nav>
</div>
</div>


<div id="adminViewLayout" style="display: none;" >
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<div  style="margin-bottom: 15px;">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-2">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">InvernaTec</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="dashboard.php">Dashboard</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Tablas
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="tabla_invernadero.php">Invernaderos</a></li>
              <li><a class="dropdown-item" href="tabla_cultivo.php">Cultivos</a></li>
              <li><a class="dropdown-item" href="tabla_sensor.php">Sensores</a></li>
              <li><a class="dropdown-item" href="tabla_actuador.php">Actuadores</a></li>
            </ul>
          </li>
        </ul>
        <form class="d-flex">
          <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRe6uhqIud1jbG0UfOZv0HU16AgE8TMzOLO2wCNLyr83w&s" class="rounded float-end" width="50" height="50">
        </form>
      </div>
    </div>
  </nav>
</div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<div class="container">
<header>
<div class="brand" style="margin-bottom:20px;"><h1>Dashboard</h1></div>
<button class="btn btn-outline-success" onclick="event.preventDefault(); changeuser()"><i class="fa-solid fa-arrows-rotate"></i>  Cambiar Usuario</button>
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
      <button class="btn btn-outline-primary dropdown-toggle"
              id="dropdownMenuLink<?= $id ?>"
              data-bs-toggle="dropdown"
              aria-expanded="false">
        Gráfica
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink<?= $id ?>">
        <?php if ($fila["id"] == 1) { ?>
        <a class="dropdown-item" href="#" onclick="event.preventDefault(); setChartType(<?= $fila['id'] ?>,'line')">Linea</a>
        <a class="dropdown-item" href="#" onclick="event.preventDefault(); setChartType(<?= $fila['id'] ?>,'progress')">Progreso</a>
        <?php } ?>
        <?php if ($fila["id"] == 2) { ?>
        <a class="dropdown-item" href="#" onclick="event.preventDefault(); setChartType(<?= $fila['id'] ?>,'line')">Linea</a>
        <a class="dropdown-item" href="#" onclick="event.preventDefault(); setChartType(<?= $fila['id'] ?>,'bar')">Barra</a>
        <?php } ?>
        <?php if ($fila["id"] == 3) { ?>
        <a class="dropdown-item" href="#" onclick="event.preventDefault(); setChartType(<?= $fila['id'] ?>,'line')">Linea</a>
        <a class="dropdown-item" href="#" onclick="event.preventDefault(); setChartType(<?= $fila['id'] ?>,'progress')">Progreso</a>
        <?php } ?>
        <?php if ($fila["id"] == 4) { ?>
        <a class="dropdown-item" href="#" onclick="event.preventDefault(); setChartType(<?= $fila['id'] ?>,'line')">Linea</a>
        <a class="dropdown-item" href="#" onclick="event.preventDefault(); setChartType(<?= $fila['id'] ?>,'gauge')">Medidor</a>
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


<div id="adminView" style="display: none;" >
    <aside style="display:flex;flex-direction:column;gap:16px">
        <?php while ($fila = $res_act_admin->fetch_assoc()) { ?>
        <div class="card">
            <h3>Actuador #<?=$fila["id"]?></h3>
            <div style="margin-top:12px;margin-bottom:12px;display:flex;flex-direction:column;gap:10px">
                <div class="actuator">
                    <div>
                        <div><?=$fila["tipo"]?></div>
                    </div>
                    <?php if ($fila["estado"] == 0) {?>
                    <div class="toggle" style="margin-right: 150px;">
                        <label class="small" id="act<?=$fila["id"]?>_state">OFFLINE</label>
                    </div>
                    <?php } ?>
                    <?php if ($fila["estado"] == 1) {?>
                    <div class="toggle" style="margin-right: 175px;">
                        <label class="small" id="act<?=$fila["id"]?>_state">ONLINE</label>
                    </div>
                    <?php } ?>
                    <?php if ($fila["id"] == 1) {?>
                    <div class="toggle">
                        <button class="btn secondary" onclick="toggleActuator('<?=$fila['id']?>')">Bomba</button>
                    </div>
                    <?php } ?>
                    <?php if ($fila["id"] == 2) { ?>
                    <div class="toggle">
                        <button class="btn secondary" onclick="toggleActuator('<?=$fila['id']?>')">Frío</button>
                    </div>
                    <div class="toggle">
                        <button class="btn secondary" onclick="toggleActuator('<?=$fila['id']+1?>')">Caliente</button>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </aside>
</div>

<div id="userView" style="display: none;" >
    <aside style="display:flex;flex-direction:column;gap:16px">
        <?php while ($fila = $res_act_user->fetch_assoc()) { ?>
        <div class="card">
            <h3>Actuador #<?=$fila["id"]?></h3>
            <div style="margin-top:12px;margin-bottom:12px;display:flex;flex-direction:column;gap:10px">
                <div class="actuator">
                    <div>
                        <div><?=$fila["tipo"]?></div>
                    </div>
                    <?php if ($fila["estado"] == 0) {?>
                    <div class="toggle" style="margin-right: 150px;">
                        <label class="small" id="act<?=$fila["id"]?>_state">OFFLINE</label>
                    </div>
                    <?php } ?>
                    <?php if ($fila["estado"] == 1) {?>
                    <div class="toggle" style="margin-right: 175px;">
                        <label class="small" id="act<?=$fila["id"]?>_state">ONLINE</label>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </aside>
</div>

</main>
</div>

<script>

user = "admin";

function changeuser()
{
    document.getElementById(user + "View").style.display = "none";
    document.getElementById(user + "ViewLayout").style.display = "none";
    if (user == "admin")
    {
        user = "user";
    }
    else if (user == "user")
    {
        user = "admin";
    }
    document.getElementById(user + "View").style.display = "block";
    document.getElementById(user + "ViewLayout").style.display = "block";
}

document.getElementById(user + "View").style.display = "block";
document.getElementById(user + "ViewLayout").style.display = "block";

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

function drawGauge(g, value){
    const canvas = g.canvas;
    const ctx = canvas.getContext("2d");
    const centerX = canvas.width / 2;
    const centerY = canvas.height;
    const radius = 120;

    const gradientg = ctx.createLinearGradient(0, 0, 300, 0);
    gradientg.addColorStop(0, "#00b309ff");
    gradientg.addColorStop(0.5, "#ffd000ff");
    gradientg.addColorStop(1, "#e20000bb");

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Background arc
    ctx.beginPath();
    ctx.lineWidth = 20;
    ctx.strokeStyle = gradientg;
    ctx.arc(centerX, centerY, radius, Math.PI, 0, false);
    ctx.stroke();

    // Value arc
    const angle = Math.PI * (value / 7);

    // Needle
    const needleAngle = Math.PI + angle;
    const nx = centerX + Math.cos(needleAngle) * (radius - 20);
    const ny = centerY + Math.sin(needleAngle) * (radius - 20);

    ctx.beginPath();
    ctx.strokeStyle = "black";
    ctx.lineWidth = 4;
    ctx.moveTo(centerX, centerY);
    ctx.lineTo(nx, ny);
    ctx.stroke();
}

// === UPDATE GAUGE ===
function updateGaugeById(id, newValue){
    const gauge = charts[id];
    if(!gauge || gauge.type !== "gauge") return;

    const start = gauge.value || 0;
    const end = Math.max(0, Math.min(100, newValue));
    const duration = 500; // ms
    const startTime = performance.now();

    function animate(now){
        const progress = Math.min((now - startTime) / duration, 1);
        const current = start + (end - start) * progress;

        drawGauge(gauge, current);

        if(progress < 1){
            requestAnimationFrame(animate);
        } else {
            gauge.value = end; // final store
        }
    }

    requestAnimationFrame(animate);
}


function setChartType(id, type) {
    const chart = charts[id];
    if(!chart) return;

    let oldData = null;
    if (chart && chart.config) {
        oldData = JSON.parse(JSON.stringify(chart.config.data));
    }

    const values = histories[id] ?? []; 
    const labels = histories[id].map((_, i) => i);

    const canvas = document.getElementById("chart" + id);
    canvas.width = canvas.width;
    const ctx = canvas.getContext("2d");

    if (chart && chart.destroy) {
        chart.destroy();
    }

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
                y: { display: false, grid: { display: false }, min: 20, max: 29 }
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
  else if (type == 'gauge')
  {
    const lastValue = histories[id]?.length ? histories[id][histories[id].length - 1] : 0;
    const v = Math.max(0, Math.min(100, lastValue));

    charts[id] = {
        type: "gauge",
        canvas: canvas,
        value: v
    };

    drawGauge(charts[id], v);
  }
  else {
      charts[id] = new Chart(ctx, {
          type: 'line',
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

            if (id == 4)
            {
                if (valueEl) valueEl.textContent = s.unidades + ' #' + (s.valor || '');
            }
            else
            {
                if (valueEl) valueEl.textContent = s.valor + ' ' + (s.unidades || '');
            }
            if (updateEl) updateEl.textContent = 'Última actualiz.: ' + new Date(s.ultima_act).toLocaleTimeString();

            // ⭐ NEW PART — Update color dynamically:
            const card = valueEl.closest('.card, .card_valid, .card_error');
            if (card) {
                card.className =
                    s.estado == 0 ? "card" :
                    s.estado == 1 ? "card_valid" :
                                    "card_error";
            }

            pushHistory(id, s.valor);

            const chart = charts[id];
            if(chart){
                if (chart.type === "gauge") {
                    updateGaugeById(id, s.valor);
                }
                else if (chart.config?.type === 'bar' && chart.config.data.datasets.length === 1) {
                    updateBarById(id, s.valor);
                }
                else if (chart.config?.type === 'bar' && chart.config.data.datasets.length === 2) {
                    updateProgressById(id, s.valor);
                }
                else {
                    updateChartById(id);
                }
            }
            console.log("sensor", id, s);
        }
    }catch(e){
        console.error("Connection error:",e);
    }

    fetchSensorsLongPolling();
}

let lastId = 0;
fetchSensorsLongPolling();

function toggleActuator(a) {

    url = "http://127.0.0.1:5000/toggle";


    if (a == 1)
    {
        fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ value: "bomba"})
        })
        .then(res => res.json())
        .then(data => {
            console.log("Python server response:", data);
        })
        .catch(err => {
            console.error("Error sending to Python:", err);
        });
    }
    if (a == 2)
    {
        fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ value: "frio"})
        })
        .then(res => res.json())
        .then(data => {
            console.log("Python server response:", data);
        })
        .catch(err => {
            console.error("Error sending to Python:", err);
        });
    }
    if (a == 3)
    {
        fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ value: "caliente"})
        })
        .then(res => res.json())
        .then(data => {
            console.log("Python server response:", data);
        })
        .catch(err => {
            console.error("Error sending to Python:", err);
        });
    }
}

</script>
<!-- right before </body> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>