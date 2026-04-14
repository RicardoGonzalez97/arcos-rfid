import './app';

document.addEventListener('DOMContentLoaded', () => {

    console.log("✅ Dashboard cargado correctamente");

    let currentDockId = localStorage.getItem('dockId')
        ? parseInt(localStorage.getItem('dockId'))
        : (window.dashboardConfig?.dockId || 2);

    let totalTags = 0;
    let anomalies = 0;
    let isPaused = false;

    const table = document.getElementById('scan-table-body');
    let cards = document.querySelectorAll('.reader-card');

    const dockData = {};
    const dockSessions = {};
    const dockSessionStartTimes = {};
    const echoSubscriptions = {};

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    function formatDateTime(dateString) {
        if (!dateString) return "--:--:--";
        const date = new Date(dateString);
        return date.toLocaleString('es-MX', {
            dateStyle: 'short',
            timeStyle: 'medium'
        });
    }

    function updateSessionButtons(hasActiveSession) {
        const startBtn = document.getElementById('start-btn');
        const endBtn = document.getElementById('end-btn');
        const pauseBtn = document.getElementById('pause-btn');

        if (hasActiveSession) {
            startBtn.style.display = 'none';
            endBtn.style.display = 'flex';
            pauseBtn.style.display = 'flex';
        } else {
            startBtn.style.display = 'flex';
            endBtn.style.display = 'none';
            pauseBtn.style.display = 'none';
        }
    }

    function setActiveCard(dockId) {
        cards.forEach(card => {
            const isActive = parseInt(card.dataset.dock) === dockId;
            card.classList.toggle('active', isActive);
            card.querySelector('.led').classList.toggle('active', isActive);
        });
    }

    function rowHTML(d) {
        return `
            <tr class="new-row">
                <td>${d.timestamp || '-'}</td>
                <td>${d.tag_id || '-'}</td>
                <td>${d.product_name || '---'}</td>
                <td><span class="badge-status ${d.status}">${d.status || '-'}</span></td>
                <td>${d.cantidad || 0}</td>
                <td>${d.order_id || '-'}</td>
            </tr>`;
    }

    function renderTable(dockId) {
        table.innerHTML = '';
        (dockData[dockId] || []).slice().reverse().forEach(d => {
            table.insertAdjacentHTML('beforeend', rowHTML(d));
        });
    }

    function connectToDock(dockId) {
        console.log("🔄 Cambiando a dock:", dockId);

        localStorage.setItem('dockId', dockId);
        currentDockId = dockId;

        const hasActiveSession = !!dockSessions[dockId];

        document.getElementById('session-id').innerText =
            hasActiveSession ? dockSessions[dockId] : "--";

        document.getElementById('start-time').innerText =
            hasActiveSession
                ? formatDateTime(dockSessionStartTimes[dockId])
                : "--:--:--";

        updateSessionButtons(hasActiveSession);

        totalTags = dockData[dockId]?.reduce(
            (sum, d) => sum + (d.cantidad || 1), 0
        ) || 0;

        anomalies = dockData[dockId]?.filter(
            d => d.status === 'extra'
        ).length || 0;

        document.getElementById('total-tags').innerText = `${totalTags} items`;
        document.getElementById('anomalies').innerText = `${anomalies} Flags`;

        setActiveCard(dockId);
        renderTable(dockId);
    }

    function handleEvent(e, dockId) {
        console.log('📦 handleEvent recibido:', e);
        if (isPaused) return;

        const d = e.data || e;

        dockData[dockId] = dockData[dockId] || [];
        dockData[dockId].push(d);

        if (dockId === currentDockId) {
            totalTags += d.cantidad || 1;
            if (d.status === 'extra') anomalies++;

            document.getElementById('total-tags').innerText = `${totalTags} items`;
            document.getElementById('anomalies').innerText = `${anomalies} Flags`;

            table.insertAdjacentHTML('afterbegin', rowHTML(d));
        }
    }

    async function loadDocks() {
        const container = document.getElementById('docks-container');

        try {
            const response = await fetch('/api/docks/initialization', {
                headers: { 'Accept': 'application/json' }
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'No se pudieron cargar los docks');
            }

            const docks = result.data;
            container.innerHTML = '';

            docks.forEach(dock => {
                dockData[dock.id] = dock.scanned_products || [];

                if (dock.has_active_session && dock.scan_session_id) {
                    dockSessions[dock.id] = dock.scan_session_id;
                    dockSessionStartTimes[dock.id] = dock.session_started_at;
                }

                if (!echoSubscriptions[dock.id]) {
                    console.log(`📡 Suscribiéndose al canal: scan-session.${dock.id}`);
                    echoSubscriptions[dock.id] = window.Echo
                        .channel(`scan-session.${dock.id}`)
                        .listen('.ProductScanned', (e) => {
                            console.log(`📡 Evento recibido en dock ${dock.id}`, e);
                            handleEvent(e, dock.id);
                        });
                }
            });

            docks.forEach(dock => {
                const card = document.createElement('div');
                card.className = 'reader-card';
                card.dataset.dock = dock.id;

                card.innerHTML = `
                    <div class="card-header">
                        <div class="left">
                            <img src="/images/monitor.png" class="dock-icon">
                            <span>${dock.name}</span>
                        </div>
                        <span class="led ${dock.has_active_session ? 'active' : ''}"></span>
                    </div>
                    <div class="divider"></div>
                    <div class="card-body">
                        <img src="/images/box-dock.png" class="center-icon">
                    </div>
                `;

                card.addEventListener('click', () =>
                    connectToDock(parseInt(dock.id))
                );

                container.appendChild(card);
            });

            cards = document.querySelectorAll('.reader-card');

            const initialDockId = docks.some(d => d.id === currentDockId)
                ? currentDockId
                : docks[0].id;

            connectToDock(initialDockId);

            console.log("✅ Docks cargados correctamente con sesiones activas");

        } catch (error) {
            console.error("❌ Error al cargar docks:", error);
            container.innerHTML = `<div style="color:#ef4444; padding:20px;">Error al cargar los andenes.</div>`;
        }
    }

    async function startSession() {
        const dock = currentDockId;

        if (!dock) {
            alert("Debe seleccionar un dock antes de iniciar la sesión.");
            return;
        }

        if (dockSessions[dock]) {
            alert(`El dock ${dock} ya tiene una sesión activa.`);
            return;
        }

        try {
            const sessionResponse = await fetch("/api/scan-sessions", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    "Accept": "application/json"
                },
                body: JSON.stringify({ dock_id: parseInt(dock) })
            });

            const sessionData = await sessionResponse.json();

            if (!sessionResponse.ok) {
                throw new Error(sessionData.message || "Error al crear la sesión.");
            }

            const sessionId = sessionData?.data?.scan_session_id;

            dockSessions[dock] = sessionId;
            dockSessionStartTimes[dock] = new Date().toISOString();

            document.getElementById('session-id').innerText = sessionId;
            document.getElementById('start-time').innerText =
                formatDateTime(dockSessionStartTimes[dock]);

            updateSessionButtons(true);
            alert(`Sesión iniciada correctamente para el dock ${dock}`);

        } catch (error) {
            console.error("❌ Error en startSession:", error);
            alert(error.message);
        }
    }

    async function endSession() {
        const dock = currentDockId;

        if (!dockSessions[dock]) {
            alert(`No hay una sesión activa para el dock ${dock}.`);
            return;
        }

        if (!confirm("¿Deseas finalizar la sesión actual?")) return;

        try {
            const sessionResponse = await fetch(`/api/docks/${dock}/close-session`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                }
            });

            const sessionData = await sessionResponse.json();

            if (!sessionResponse.ok) {
                throw new Error(sessionData.message || "Error al cerrar la sesión.");
            }

            delete dockSessions[dock];
            delete dockSessionStartTimes[dock];
            dockData[dock] = [];

            document.getElementById('session-id').innerText = "--";
            document.getElementById('start-time').innerText = "--:--:--";
            document.getElementById('total-tags').innerText = "0 items";
            document.getElementById('anomalies').innerText = "0 Flags";

            updateSessionButtons(false);
            renderTable(dock);

            alert(`Sesión finalizada correctamente para el dock ${dock}`);

        } catch (error) {
            console.error(error);
            alert(error.message);
        }
    }

    function pauseSession() {
        isPaused = !isPaused;
        alert(isPaused ? "Sesión en pausa" : "Sesión reanudada");
    }

    function waitForEcho(cb) {
        if (window.Echo) return cb();
        setTimeout(() => waitForEcho(cb), 100);
    }

    waitForEcho(async () => {
        console.log("✅ Echo listo, cargando docks...");
        await loadDocks();
    });

    window.startSession = startSession;
    window.endSession = endSession;
    window.pauseSession = pauseSession;
});