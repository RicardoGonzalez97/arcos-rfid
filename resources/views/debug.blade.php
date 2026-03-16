<!DOCTYPE html>
<html>

<head>

<title>Warehouse Debug Console</title>

<style>

body{
font-family:Arial;
background:#0f172a;
color:white;
padding:40px;
}

.container{
display:flex;
gap:20px;
align-items:flex-start;
}

.left{
width:40%;
}

.right{
width:60%;
}

.panel{
background:#1e293b;
padding:20px;
border-radius:10px;
margin-bottom:20px;
}

.panel h3{
margin-top:0;
}

label{
display:block;
margin-top:10px;
font-size:13px;
color:#94a3b8;
}

button{
padding:10px 20px;
border-radius:6px;
border:none;
cursor:pointer;
margin-top:10px;
margin-right:10px;
}

.start{background:#22c55e;color:white;}
.close{background:#ef4444;color:white;}
.scan{background:#3b82f6;color:white;}

input{
padding:10px;
border-radius:5px;
border:none;
margin-top:5px;
width:200px;
}

.logs{
background:black;
padding:20px;
border-radius:10px;
height:650px;
overflow:auto;
font-family:monospace;
}

.log{margin-bottom:6px;}

.success{color:#22c55e;}
.error{color:#ef4444;}
.info{color:#38bdf8;}

table{
width:100%;
border-collapse:collapse;
margin-top:10px;
}

td,th{
border:1px solid #334155;
padding:8px;
}

</style>

</head>

<body>

<div class="container">

<div class="left">

<div class="panel">

<h3>System Logs</h3>

<button onclick="clearLogs()">🧹 Limpiar logs</button>

<div class="logs" id="logs"></div>

</div>

</div>

<div class="right">

<div class="panel">

<h3>1️⃣ Start / Close Scan Session</h3>

<label>Dock ID</label>
<input id="dock_id" placeholder="Ejemplo: 1">

<br>

<button class="start" onclick="startSession()">Start Session</button>
<button class="close" onclick="closeSession()">Close Session</button>

</div>


<div class="panel">

<h3>2️⃣ Simular escaneo RFID</h3>

<div id="rfid-container">

<div class="rfid-row">

<label>RFID del producto</label>
<input class="rfid" placeholder="Ejemplo: 12345">

<label>Cantidad</label>
<input class="qty" type="number" placeholder="Ejemplo: 3" value="1">

</div>

</div>

<br>

<button onclick="addRFID()">➕ Agregar otro RFID</button>
<button class="scan" onclick="scan()">Enviar lote</button>

</div>


<div class="panel">

<h3>3️⃣ Resultados de sesión</h3>

<button onclick="loadResults()">Load Results</button>

<div id="session-totals" style="margin-bottom:15px;font-size:14px;color:#cbd5f5;"></div>

<table id="results">

<thead>
<tr>
<th>Order</th>
<th>Expected</th>
<th>Scanned</th>
<th>Missing</th>
<th>Status</th>
</tr>
</thead>

<tbody></tbody>

</table>

</div>

</div>

</div>


<script>

function log(msg,type="info"){

let logs=document.getElementById("logs")

let div=document.createElement("div")

div.className="log "+type

div.innerText=new Date().toLocaleTimeString()+"  "+msg

logs.appendChild(div)

logs.scrollTop=logs.scrollHeight

}

function clearLogs(){
document.getElementById("logs").innerHTML=""
}



function addRFID(){

let container=document.getElementById("rfid-container")

let row=document.createElement("div")

row.className="rfid-row"
row.style.marginTop="15px"

row.innerHTML=`

<label>RFID del producto</label>
<input class="rfid" placeholder="Ejemplo: 98765">

<label>Cantidad</label>
<input class="qty" type="number" placeholder="Ejemplo: 2" value="1">

<button onclick="this.parentElement.remove()">❌</button>

`

container.appendChild(row)

}



async function startSession(){

let dock=document.getElementById("dock_id").value

if(!dock){
log("Dock ID requerido","error")
return
}

log("Iniciando sesión para dock "+dock)

/* START RFID */

log("Iniciando lector RFID...")

let rfid = await fetch("/api/rfid/start",{

method:"POST",

headers:{
"Content-Type":"application/json"
},

body:JSON.stringify({
dock_id:parseInt(dock)
})

})

let text = await rfid.text()

if(!rfid.ok){

log("Error al iniciar lector RFID","error")
log("HTTP status: "+rfid.status,"error")
log("Respuesta: "+text,"error")

return
}

log("Lector RFID iniciado correctamente","success")
log("Respuesta: "+text,"info")

log("Lector RFID iniciado correctamente","success")

/* TU API */

let res=await fetch("/api/scan-sessions",{

method:"POST",

headers:{
"Content-Type":"application/json"
},

body:JSON.stringify({
dock_id:dock
})

})

let data=await res.json()

if(res.ok){

log(data.message,"success")
log("Session ID: "+data.data.scan_session_id)

}else{

log(data.message,"error")

}

}



async function closeSession(){

let dock=document.getElementById("dock_id").value

log("Cerrando sesión para dock "+dock)

/* STOP RFID */

log("Deteniendo lector RFID...")

let rfid = await fetch("/api/rfid/stop",{

method:"POST",

headers:{
"Content-Type":"application/json"
},

body:JSON.stringify({
dock_id:parseInt(dock)
})

})

if(rfid.ok){
log("Lector RFID detenido correctamente","success")
}else{
log("Error al detener lector RFID","error")
}

/* TU API */

let res=await fetch("/api/docks/"+dock+"/close-session",{
method:"POST"
})

let data=await res.json()

if(res.ok){

log(data.message,"success")
log("Estado actual: "+data.data.status)

}else{

log(data.message,"error")

}

}



async function scan(){

let dock=document.getElementById("dock_id").value

let rfidInputs=document.querySelectorAll(".rfid")
let qtyInputs=document.querySelectorAll(".qty")

let events=[]

for(let i=0;i<rfidInputs.length;i++){

let rfid=rfidInputs[i].value
let qty=parseInt(qtyInputs[i].value)

if(!rfid || !qty) continue

for(let j=0;j<qty;j++){
events.push({rfid:rfid})
}

log("Preparando RFID "+rfid+" x"+qty)

}

if(events.length===0){
log("No hay eventos para enviar","error")
return
}

log("Enviando batch con "+events.length+" eventos")

let res=await fetch("/api/scan-events/batch",{

method:"POST",

headers:{
"Content-Type":"application/json"
},

body:JSON.stringify({
dock_id:dock,
events:events
})

})

let data=await res.json()

if(res.ok){

log("Lote procesado correctamente","success")

log("Escaneados correctos en este lote: "+data.data.scanned_in_this_batch)
log("Extras en este lote: "+data.data.extras_in_this_batch)
log("Extras acumulados en la sesión: "+data.data.total_extras_in_session)

log("Total esperado: "+data.data.expected_total)
log("Total escaneado: "+data.data.scanned_total)
log("Total faltante: "+data.data.missing_total)

log("Estado de la sesión: "+data.data.status)

if(data.data.LED === "VERDE"){
log("Indicador LED: VERDE","success")
}
else if(data.data.LED === "ROJO"){
log("Indicador LED: ROJO","error")
}

}else{

log(data.message,"error")

}

}



async function loadResults(){

let dock=document.getElementById("dock_id").value

log("Buscando resultados cerrados")

let attempts=0

while(attempts<10){

let res=await fetch("/api/docks/"+dock+"/closed-results")

let data=await res.json()

if(res.ok){
    let totals=data.data.totals

let totalsDiv=document.getElementById("session-totals")

totalsDiv.innerHTML=`

<b>Expected:</b> ${totals.expected_total} &nbsp;&nbsp;
<b>Scanned:</b> ${totals.scanned_total} &nbsp;&nbsp;
<b>Missing:</b> ${totals.missing_total} &nbsp;&nbsp;
<b style="color:#f87171">Extras:</b> ${totals.extra_total}

`
let table=document.querySelector("#results tbody")
table.innerHTML=""

data.data.orders.forEach(o=>{

let tr=document.createElement("tr")

tr.innerHTML=`

<td>${o.order_id}</td>
<td>${o.expected_total}</td>
<td>${o.scanned_total}</td>
<td>${o.missing_total}</td>
<td>${o.status}</td>

`

table.appendChild(tr)

})

log("Resultados cargados correctamente","success")

return

}

else{

log("La sesión aún se está cerrando... esperando","info")

await new Promise(r=>setTimeout(r,2000))

attempts++

}

}

log("No se pudieron obtener resultados aún","error")

}

</script>

</body>

</html>