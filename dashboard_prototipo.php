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
<h3>Sensor #<?=$fila["id"]?> - <?=$fila["tipo"]?></h3>

<div class="sensor-row" style="margin-top:12px;">
<div style="flex:1">
    <div class="big-value" id="sensor<?=$fila["id"]?>_value"><?=$lectura["valor"]?> <?=$fila["unidades"]?></div>
    <div class="muted small" id="sensor<?=$fila["id"]?>_update">Última actualiz.: --</div>
</div>

<div style="width:500px;height:200px">
    <canvas id="chartSensor<?=$fila["id"]?>" height="80"></canvas>
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
let history1=[],history2=[],history3=[];
const maxPoints = 30;

const canvas1 = document.getElementById('chartSensor1');
const canvas2 = document.getElementById('chartSensor2');
const canvas3 = document.getElementById('chartSensor3');

const ctx1 = canvas1?.getContext('2d');
const ctx2 = canvas2?.getContext('2d');
const ctx3 = canvas3?.getContext('2d');

const chart1 = new Chart(ctx1,{
    type:'line',
    data:{labels:[],datasets:[{label:'Hum',data:[],tension:0.4,pointRadius:4}]},
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}}}
});

const chart2 = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: [""],    // optional, empty label
        datasets: [{
            label: 'Temp',
            data: [0],    // one bar
            borderRadius: 12,     // ROUND EDGES
            backgroundColor: 'red',  // or any color
            barThickness : 75
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,

        scales: {
            x: {
                display: false,   // remove axis
                grid: { display: false }
            },
            y: {
                display: false,   // remove axis
                grid: { display: false },
                min: 5,           // set your scale
                max: 35
            }
        },

        plugins: {
            legend: { display: false },
            tooltip: { enabled: false }
        }
    }
});



const chart3 = new Chart(ctx3,{
    type:'line',
    data:{labels:[],datasets:[{label:'Soil',data:[],tension:0.4,pointRadius:4}]},
    options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}}}
});

function pushHistory(arr,val){
    if(arr.length>=maxPoints) arr.shift();
    arr.push(val);
}

function updateChart(chart,arr){
    chart.data.labels = arr.map((_,i)=>i);
    chart.data.datasets[0].data = arr;
    chart.update();
}

function updateBar(chart, value)
{
  chart.data.labels = ["Current"];
    chart.data.datasets[0].data = [value];
    chart.update();
}



async function fetchSensors(s1, s2, s3){
    document.getElementById('sensor1_value').textContent = s1.valor + ' ' + s1.unidades;
    document.getElementById('sensor1_update').textContent = 'Última actualiz.: ' + new Date(s1.ultima_act).toLocaleTimeString();

    document.getElementById('sensor2_value').textContent = s2.valor + ' ' + s2.unidades;
    document.getElementById('sensor2_update').textContent = 'Última actualiz.: ' + new Date(s2.ultima_act).toLocaleTimeString();

    document.getElementById('sensor3_value').textContent = s3.valor + ' ' + s3.unidades;
    document.getElementById('sensor3_update').textContent = 'Última actualiz.: ' + new Date(s3.ultima_act).toLocaleTimeString();

    pushHistory(history1,s1.valor);
    pushHistory(history2,s2.valor);
    pushHistory(history3,s3.valor);

    updateChart(chart1,history1);
    updateBar(chart2,s2.valor);
    updateChart(chart3,history3);
}

let lastId = 0;

async function fetchSensorsLongPolling(){
    try{
        const res = await fetch("get_sensors.php?wait=1&last_id="+lastId);
        const json = await res.json();
        if(!json.ok) return;

        lastId = json.last_id;

        fetchSensors(
            json.lecturas.sensor_1,
            json.lecturas.sensor_2,
            json.lecturas.sensor_3
        );

    }catch(e){
        console.error("Connection error:",e);
    }

    fetchSensorsLongPolling();
}

fetchSensorsLongPolling();
</script>

</body>
</html>
