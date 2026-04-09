<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    @vite('resources/js/app.js')

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f5f7fb;
            overflow: hidden; 
        }

        .layout { display: flex; height: 100vh; }

       .sidebar {
            width: 220px;
            background: #fff;
            border-right: 1px solid #eee;
            padding: 20px;
            display: flex;
            flex-direction: column;
            height: 100vh; /* 🔥 IMPORTANTE */
        }

        .sidebar-top {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        /* 🔥 magia aquí */
        .sidebar-bottom {
            margin-top: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

                .menu a,
        .sidebar-bottom a,
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 10px;
            color: #555;
            text-decoration: none;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
        }

        .menu a {
            font-size: 14px;
        }
        .menu a.active {
            background: #e8f0ff;
            color: #2f6fed;
        }

        .menu-icon {
            width: 16px;
            height: 16px;
            fill: currentColor;
            flex-shrink: 0;
        }

        /* logout estilo */
        .logout-btn {
            color: #eb4747;
            cursor: pointer;
        }

        .sidebar .menu a {
            display: block;
            padding: 10px;
            border-radius: 8px;
            color: #555;
            text-decoration: none;
            margin-bottom: 5px;
        }

        .sidebar .menu a.active {
            background: #e8f0ff;
            color: #2f6fed;
        }

         .btn-outline-pause{
            background: transparent;
            color: #16181d; /* bootstrap danger */
            border: 1px solid #bec2ca;
            font-size: 13px;
            padding: 4px 12px;
            border-radius: 8px; /* mismo estilo */
            display: flex;
            align-items: center;
            gap: 6px;
            height: 36px;
            white-space: nowrap;
            cursor: pointer;
        }

        .btn-blue-custom {
            background:#258cf4;
            color:white;
            font-size:14px;
            border:none;
            padding:4px 10px;
            border-radius:8px;
            display:flex;
            align-items:center;
            gap:6px;
            height:36px; 
            white-space: nowrap;
        }

        .btn-outline-danger-custom {
            background: transparent;
            color: #dc3545; /* bootstrap danger */
            border: 1px solid #bec2ca;
            font-size: 13px;
            padding: 4px 12px;
            border-radius: 8px; /* mismo estilo */
            display: flex;
            align-items: center;
            gap: 6px;
            height: 36px;
            white-space: nowrap;
            cursor: pointer;
        }

        /* 🔥 hover bonito */
        .btn-outline-danger-custom:hover {
            background: #dc3545;
            color: white;
        }

        .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }

        .topbar {
            background: #fff;
            padding: 15px 25px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
             width: 100%;
        }
            

        .badge-live {
            background: #e6f7ee;
            color: #1fa971;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
        }

        .badge-online {
            background: #e6f7ee;
            color: #1fa971;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

       .content {
            padding: 20px;
            display: flex;
            gap: 20px;
            flex: 1;
            overflow: hidden; /* 🔥 ya NO scroll aquí */
        }

        .dock-icon {
            width: 22px;
            height: 22px;
        }

        .card-body {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .table-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .title-icon {
            width: 12px;
            height: 12px;
            opacity: 0.8; /* opcional para que no se vea tan pesado */
        }

        .center-icon {
            width: 90px;
            opacity: 0.4;
        }

        .divider {
            height: 1px;
            background: rgba(255,255,255,0.15);
            margin: 15px 0;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header .left {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
        }

        .cards {
            width: 340px; /* un poquito más por padding */
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding: 20px;
            max-height: 100%;   /* 🔥 importante */
            overflow-y: auto;   /* 🔥 scroll SOLO aquí */
            padding-right: 5px; /* evita que el scroll tape */
        }

        .center-icon {
            width: 90px;
            opacity: 0.25;
            filter: brightness(1.2);
        }

        .reader-card {
            background: linear-gradient(135deg, #020617, #0f172a);
            border-radius: 16px;
            width: 300px;     /* 🔥 fijo */
            height: 210px;
            color: white;
            flex-shrink: 0;
            padding: 15px;
            position: relative;
            cursor: pointer;
            transition: all 0.25s ease;
            flex-direction: column;
        }

        .reader-card:hover {
            transform: scale(1.03);
        }

        .reader-card.active {
            transform: scale(1.02);
            box-shadow:
                0 0 0 3px rgba(47,111,237,0.9);
        }

        .led {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: gray;
        }

        .led.active {
            background: #00ff9c;
            box-shadow: 0 0 8px #00ff9c;
        }

        .cards::-webkit-scrollbar {
            width: 6px;
        }

        .cards::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.2);
            border-radius: 10px;
        }

       .table-container {
            flex: 1;
            background: white;
            border-radius: 16px;
            padding: 20px;

            overflow-y: auto; /* 🔥 scroll independiente */
        }

        .badge-status {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            text-transform: capitalize;
        }

        .scanned { background: #e6f7ee; color: #1fa971; }
        .extra { background: #ffe6e6; color: #d33; }

        .new-row {
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>

<div class="layout">

    <!-- 🔥 SIDEBAR RESTAURADO -->
    <div class="sidebar">

        <!-- 🔼 TOP -->
        <div class="sidebar-top">

            <!-- LOGO -->
            <div style="display: flex; align-items: center; gap: 3px;">
                <img src="/images/login-img.png" style="width:40px; height:40px;">
                
                <div style="
                    color:#258cf4; 
                    margin:0; 
                    font-size:20px; 
                    font-weight:600;
                ">
                    LogiSync RFID
                </div>
            </div>

            <!-- MENU -->
           <div class="menu mt-3">
                <a class="active">
                    <svg class="menu-icon" viewBox="0 0 522 546.40729" fill="currentColor">
                        <path
                            d="M 261,541.4073 A 97.416533,97.416533 0 0 1 214.06667,529.15343 L 55.82453,442.2841 A 97.655467,97.655467 0 0 1 5,356.95076 V 189.39023 A 97.621333,97.621333 0 0 1 55.7904,104.0569 L 213.82773,17.153434 a 97.143467,97.143467 0 0 1 94.1056,0 L 466.17547,104.09103 A 97.655467,97.655467 0 0 1 517,189.42436 v 167.5264 A 97.621333,97.621333 0 0 1 466.24373,442.2841 L 308.17227,529.15343 A 97.416533,97.416533 0 0 1 261,541.4073 Z m 0,-498.995196 a 60.0064,60.0064 0 0 0 -28.9792,7.50933 L 73.84693,136.8249 a 60.142933,60.142933 0 0 0 -31.30026,52.56533 v 167.5264 a 60.177067,60.177067 0 0 0 31.4368,52.56533 l 158.24213,86.9376 a 59.767467,59.767467 0 0 0 58.02667,0 l 158.07146,-86.9376 a 60.177067,60.177067 0 0 0 31.36854,-52.56533 V 189.39023 A 60.177067,60.177067 0 0 0 448.25547,136.8249 L 289.8768,49.887304 A 60.0064,60.0064 0 0 0 261,42.412104 Z"
                        />
                        <path
                            d="M 261,294.48676 35.1056,171.26543 A 18.74206,18.74206 0 0 1 53.05973,138.3609 L 261,251.8201 468.94027,138.39503 a 18.7392,18.7392 0 0 1 17.92,32.90453 z"
                        />
                        <path
                            d="m 261,529.15343 a 18.7392,18.7392 0 0 1 -18.7392,-18.7392 v -237.2608 a 18.773335,18.773335 0 1 1 37.54667,0 v 237.2608 A 18.7392,18.7392 0 0 1 261,529.15343 Z"
                        />
                    </svg>
                    Active Session
                </a>
                <a>
                    <img src="/images/alert.png" class="menu-icon">
                    System Alerts
                </a>

            </div>

        </div>
        <!-- 🔽 BOTTOM -->
        <div class="sidebar-bottom" style="padding-bottom: 10px; border-top: 1px solid #e5e7eb;">
            <a>
                <img src="/images/settings.png" class="menu-icon">
                Gate Settings
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout-btn">
                    <img src="/images/logout.png" class="menu-icon">
                    Sign Out
                </button>
            </form>
        </div>
    </div>
    <div class="main">

            <!-- 🔥 HEADER COMPLETO -->
        <div class="topbar" style="flex-direction: column; gap: 10px;">
            <!-- 🔥 FILA 1 -->
            <div style="width: 100%; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: flex-end; align-items: center; gap: 20px;">

              <span class="badge-online" style="display:flex; align-items:center; gap:8px;">
                <img src="/images/rfid-icon.png" style="width:16px; height:12px;">
                RFID Portal: Online
            </span>

              <div style="
                display: flex;
                align-items: center;
                gap: 10px;
                border-left: 1px solid #e5e7eb;
                padding-left: 20px;
                margin-left: 10px;
                 margin-bottom: 10px;
            ">
            <div class="text-end">
                        <strong>Ulises González</strong><br>
                        <small style="color: gray;">Floor Supervisor</small>
                    </div>

                    <img src="https://i.pravatar.cc/40" style="border-radius:50%;">
                </div>

            </div>

            <!-- 🔥 FILA 2 -->
            <div class="d-flex align-items-center justify-content-between">

                <!-- IZQUIERDA -->
                <div class="d-flex align-items-center gap-3" style="flex:1;"  >

                   <div style="
                        display:flex; 
                        flex-direction:column; 
                        gap:2px; 
                        padding-right:20px; 
                        border-right:1px solid #e5e7eb;
                    ">

                        <!-- 🔥 LABEL -->
                        <div style="
                            color:#6b7280; 
                            font-size:10px; 
                            font-weight:500;
                        ">
                            ACTIVE SESSION
                        </div>

                        <!-- 🔥 VALOR + BADGE -->
                        <div style="display:flex; align-items:center; gap:8px;">
                            
                            <div id="session-id" style="
                                color:#111827; 
                                font-size:14px; 
                                font-weight:600;
                            ">
                                REC-2024-0812
                            </div>

                            <span class="badge-live" style="
                                font-size:10px;
                                padding:4px 8px;
                                border-radius:999px;
                            ">
                                ● LIVE
                            </span>

                        </div>

                    </div>

                   <div style="display:flex; flex-direction:column; gap:2px;">

                        <!-- 🔥 LABEL CON ICONO -->
                        <div style="display:flex; align-items:center; gap:6px; color:#6b7280; font-size:10px; font-weight:500;">
                            
                            <img src="/images/clock.png" style="width:14px; height:14px;">
                            
                            START TIME
                        </div>

                        <!-- 🔥 VALOR -->
                        <div id="start-time" style="color:#111827; font-size:14px; font-weight:600;">
                            --:--:--
                        </div>

                    </div>

                    <div style="display:flex; flex-direction:column; gap:2px;">

                        <!-- 🔥 LABEL CON ICONO -->
                        <div style="display:flex; align-items:center; gap:6px; color:#6b7280; font-size:10px; font-weight:500;">
                            
                            <img src="/images/hashtag.png" style="width:14px; height:14px;">
                            
                            TOTAL TAGS
                        </div>

                        <!-- 🔥 VALOR -->
                        <div id="total-tags" style="color:#111827; font-size:14px; font-weight:600;">
                            0 items
                        </div>

                    </div>

                    <div style="display:flex; flex-direction:column; gap:2px;">

                        <!-- 🔥 LABEL CON ICONO -->
                        <div style="display:flex; align-items:center; gap:6px; color:#6b7280; font-size:10px; font-weight:500;">
                            
                            <img src="/images/anomalies.png" style="width:14px; height:14px;">
                            
                            ANOMALIES
                        </div>

                        <!-- 🔥 VALOR -->
                        <div id="anomalies" style="color:#e53935; font-size:14px; font-weight:600;">
                            0 Flags
                        </div>

                    </div>

                </div>

                <!-- DERECHA (BOTONES) -->
                <div class="d-flex gap-2" style="margin-left:20px;">

                    <button class="btn-outline-pause" onclick="pauseSession()">
                         <img src="/images/pause.png" style="width:14px; height:14px;">
                        Pause
                    </button>

                    <button 
                        onclick="endSession()"
                        class="btn-outline-danger-custom"
                    >
                        <img src="/images/end-session.png" style="width:14px; height:14px;">
                        End Session
                    </button>
                   <button 
                        onclick="startSession()"
                        class = "btn-blue-custom"
                    >
                        <img src="/images/start-session.png" style="width:14px; height:14px;">
                        Start New Session
                    </button>

                </div>

            </div>

        </div>

        <div class="content">

           <div class="cards">

                <div class="reader-card" data-dock="1">
                    <div class="card-header">
                        <div class="left">
                            <img src="/images/monitor.png" class="dock-icon">
                            <span>Anden 1</span>
                        </div>
                        <span class="led active"></span>
                    </div>

                    <div class="divider"></div>

                    <div class="card-body">
                        <img src="/images/box-dock.png" class="center-icon">
                    </div>
                </div>

                <div class="reader-card" data-dock="2">
                    <div class="card-header">
                        <div class="left">
                            <img src="/images/monitor.png" class="dock-icon">
                            <span>Anden 2</span>
                        </div>
                        <span class="led"></span>
                    </div>

                    <div class="divider"></div>

                    <div class="card-body">
                        <img src="/images/box-dock.png" class="center-icon">
                    </div>
                </div>

                <div class="reader-card" data-dock="3">
                    <div class="card-header">
                        <div class="left">
                            <img src="/images/monitor.png" class="dock-icon">
                            <span>Anden 3</span>
                        </div>
                        <span class="led"></span>
                    </div>

                    <div class="divider"></div>

                    <div class="card-body">
                        <img src="/images/box-dock.png" class="center-icon">
                    </div>
                </div>

                <div class="reader-card" data-dock="4">
                    <div class="card-header">
                        <div class="left">
                            <img src="/images/monitor.png" class="dock-icon">
                            <span>Anden 4</span>
                        </div>
                        <span class="led"></span>
                    </div>

                    <div class="divider"></div>

                    <div class="card-body">
                        <img src="/images/box-dock.png" class="center-icon">
                    </div>
                </div>

            </div>

            <div class="table-container">
               <div class="table-title">
                    <img src="/images/db.png" class="title-icon">
                    <h6>Live Detection Stream</h6>
                </div>

                <table class="table mt-3 align-middle">
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>Tag ID</th>
                            <th>Product</th>
                            <th>Status</th>
                            <th>Cantidad</th>
                            <th>Orden</th>
                        </tr>
                    </thead>

                    <tbody id="scan-table-body"></tbody>
                </table>
            </div>

        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    let currentDockId = localStorage.getItem('dockId')
        ? parseInt(localStorage.getItem('dockId'))
        : {{ $dockId ?? 2 }};

    let totalTags = 0;
    let anomalies = 0;
    let sessionStartedAt = null;
    let isPaused = false;

    const table = document.getElementById('scan-table-body');
    const cards = document.querySelectorAll('.reader-card');

    const dockData = {1: [],2: [],3: [],4: []};

    function startSession() {
        sessionStartedAt = new Date();
        totalTags = 0;
        anomalies = 0;

        document.getElementById('total-tags').innerText = `0 items`;
        document.getElementById('anomalies').innerText = `0 Flags`;

        setInterval(() => {
            if (sessionStartedAt) {
                document.getElementById('start-time').innerText =
                    sessionStartedAt.toLocaleTimeString();
            }
        }, 1000);
    }

    function pauseSession() {
        isPaused = !isPaused;
    }

    function endSession() {
        alert('Session ended');
    }

    function setActiveCard(dockId) {
        cards.forEach(card => {
            card.classList.toggle('active', parseInt(card.dataset.dock) === dockId);
            card.querySelector('.led').classList.toggle('active', parseInt(card.dataset.dock) === dockId);
        });
    }

    function updateDockLabel(dockId) {
        document.getElementById('dock-label').innerText = `Dock ${dockId}`;
    }

    function renderTable(dockId) {
        table.innerHTML = '';

        dockData[dockId].slice().reverse().forEach(d => {
            table.insertAdjacentHTML('beforeend', rowHTML(d));
        });
    }

    function rowHTML(d){
        return `
        <tr class="new-row">
            <td>${d.timestamp || '-'}</td>
            <td>${d.product_id || '-'}</td>
            <td>${d.product_name || '---'}</td>
            <td><span class="badge-status ${d.status}">${d.status || '-'}</span></td>
            <td>${d.cantidad || 0}</td>
            <td>${d.order_id || '-'}</td>
        </tr>`;
    }

    function connectToDock(dockId) {
        localStorage.setItem('dockId', dockId);
        currentDockId = dockId;

        renderTable(dockId);
        setActiveCard(dockId);
        updateDockLabel(dockId);
    }

    function handleEvent(e, dockId) {

        if (isPaused) return;

        const d = e.data || {};

        totalTags += d.cantidad || 1;
        if (d.status === 'extra') anomalies++;

        document.getElementById('total-tags').innerText = `${totalTags} items`;
        document.getElementById('anomalies').innerText = `${anomalies} Flags`;

        dockData[dockId].push(d);
        if (dockData[dockId].length > 100) dockData[dockId].shift();

        if (dockId === currentDockId) {
            table.insertAdjacentHTML('afterbegin', rowHTML(d));
            if (table.children.length > 50) table.removeChild(table.lastElementChild);
        }
    }

    function waitForEcho(cb){
        if(window.Echo) return cb();
        setTimeout(()=>waitForEcho(cb),100);
    }

    waitForEcho(()=>{
        [1,2,3,4].forEach(id=>{
            window.Echo.channel(`scan-session.${id}`)
                .listen('.ProductScanned', e=>handleEvent(e,id));
        });

        cards.forEach(c=>{
            c.onclick=()=>connectToDock(parseInt(c.dataset.dock));
        });

        connectToDock(currentDockId);
    });

});
</script>

</body>
</html>