import './app';

// ==============================
// CONFIGURACIÓN
// ==============================
const config = window.systemAlertsConfig || {};

// ==============================
// UTILIDADES
// ==============================
function formatTime(dateString) {
    if (!dateString) return '--:--:--';
    const date = new Date(dateString);
    return date.toLocaleTimeString('es-MX', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
}

function getTypeBadge(type) {
    switch (type) {
        case 'unknown':
            return `<span class="badge badge-unknown">Unknown</span>`;
        case 'duplicate':
            return `<span class="badge badge-duplicate">Duplicate</span>`;
        case 'extra':
            return `<span class="badge badge-extra">Extra</span>`;
        case 'missing':
            return `<span class="badge badge-missing">Missing</span>`;
        default:
            return `<span class="badge badge-unknown">${type}</span>`;
    }
}

function getActionButton(anomaly) {
    return `
        <button class="btn-investigate investigate-btn" data-id="${anomaly.anomaly_id}">
            Investigate →
        </button>
    `;
}

// ==============================
// REFERENCIAS DEL DOM
// ==============================
let tableBody;
let alertsMain;
let closePanelBtn;
let skipBtn;

// ==============================
// CONTROL DEL PANEL COLAPSABLE
// ==============================
function openResolvePanel() {
    if (alertsMain) {
        alertsMain.classList.add('panel-open');
    }
}

function closeResolvePanel() {
    if (alertsMain) {
        alertsMain.classList.remove('panel-open');
    }
}

// ==============================
// 1️⃣ CARGAR KPIs (SUMMARY)
// ==============================
async function loadAnomalySummary() {
    try {
        const params = new URLSearchParams();

        if (config.dockId) params.append('dock_id', config.dockId);
        if (config.scanSessionId) params.append('scan_session_id', config.scanSessionId);

        const response = await fetch(`/api/anomalies/summary?${params.toString()}`, {
            headers: { 'Accept': 'application/json' }
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Error loading summary');
        }

        const summary = result.data || {};

        const totalErrorsEl = document.getElementById('total-errors');
        const unknownSkuEl = document.getElementById('unknown-sku');
        const duplicatesEl = document.getElementById('duplicates');
        const activeConflictsEl = document.getElementById('active-conflicts');

        if (totalErrorsEl) {
            totalErrorsEl.textContent =
                String(summary.total_errors || 0).padStart(2, '0');
        }

        if (unknownSkuEl) {
            unknownSkuEl.textContent =
                String(summary.unknown || 0).padStart(2, '0');
        }

        if (duplicatesEl) {
            duplicatesEl.textContent =
                String(summary.extras || 0).padStart(2, '0');
        }

        if (activeConflictsEl) {
            activeConflictsEl.textContent = summary.total_errors || 0;
        }

        console.log('✅ Summary loaded:', summary);

    } catch (error) {
        console.error('❌ Error loading summary:', error);
    }
}

// ==============================
// 2️⃣ CARGAR TABLA DE ANOMALÍAS
// ==============================
async function loadAnomalies(page = 1) {
    try {
        const params = new URLSearchParams({
            page: page,
            per_page: 10
        });

        if (config.dockId) params.append('dock_id', config.dockId);
        if (config.scanSessionId) params.append('scan_session_id', config.scanSessionId);

        const response = await fetch(`/api/anomalies?${params.toString()}`, {
            headers: { 'Accept': 'application/json' }
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Error loading anomalies');
        }

        const anomalies = result?.data?.data || [];

        if (!tableBody) return;

        tableBody.innerHTML = '';

        if (anomalies.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" style="text-align:center; padding:20px; color:#6b7280;">
                        No anomalies found
                    </td>
                </tr>
            `;
            return;
        }

        anomalies.forEach(anomaly => {
            const row = `
                <tr>
                    <td>${getTypeBadge(anomaly.anomaly_type)}</td>
                    <td>${anomaly.tag_id}</td>
                    <td>${anomaly.dock?.name ?? 'Dock ' + (anomaly.dock?.number ?? '-')}</td>
                    <td>${formatTime(anomaly.detected_at)}</td>
                    <td>${getActionButton(anomaly)}</td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
        });

        console.log('✅ Anomalies loaded:', anomalies);

    } catch (error) {
        console.error('❌ Error loading anomalies:', error);
    }
}

// ==============================
// 3️⃣ DETALLE DE UNA ANOMALÍA
// ==============================
async function loadAnomalyDetail(id) {
    try {
        const response = await fetch(`/api/anomalies/${id}`, {
            headers: { 'Accept': 'application/json' }
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Error loading anomaly detail');
        }

        const anomaly = result.data;

        const tagIdInput = document.getElementById('detail-tag-id');
        const timeEl = document.getElementById('detail-time');
        const badgeEl = document.getElementById('detail-type-badge');
        const quantityInput = document.getElementById('detail-quantity');
        const batchInput = document.getElementById('detail-batch-number');
        const assignedProductInput = document.getElementById('detail-assigned-product');

        if (tagIdInput) tagIdInput.value = anomaly.tag_id || '';
        if (timeEl) timeEl.textContent = formatTime(anomaly.detected_at);

        if (badgeEl) {
            badgeEl.textContent = (anomaly.anomaly_type || 'unknown').toUpperCase();
            badgeEl.className = `badge ${anomaly.anomaly_type || 'unknown'}`;
        }

        if (quantityInput) quantityInput.value = anomaly.quantity || 1;
        if (batchInput) batchInput.value = anomaly.batch_number || '';
        if (assignedProductInput) {
            assignedProductInput.value = anomaly.assigned_product_id || '';
        }

        openResolvePanel();

        console.log('✅ Anomaly detail loaded:', anomaly);

    } catch (error) {
        console.error('❌ Error loading anomaly detail:', error);
    }
}

// ==============================
// 4️⃣ EVENTOS DE LA INTERFAZ
// ==============================
function initializeEventListeners() {
    // Botón de cierre del panel
    if (closePanelBtn) {
        closePanelBtn.addEventListener('click', closeResolvePanel);
    }

    // Botón "Skip for Now"
    if (skipBtn) {
        skipBtn.addEventListener('click', closeResolvePanel);
    }

    // Botón "Investigate" en la tabla
    document.addEventListener('click', function (e) {
        const button = e.target.closest('.investigate-btn');
        if (!button) return;

        const anomalyId = button.dataset.id;
        if (anomalyId) {
            loadAnomalyDetail(anomalyId);
        }
    });
}

// ==============================
// 5️⃣ INICIALIZACIÓN
// ==============================
document.addEventListener('DOMContentLoaded', () => {
    // Referencias del DOM
    tableBody = document.getElementById('anomalies-table-body');
    alertsMain = document.getElementById('alerts-main');
    closePanelBtn = document.getElementById('close-panel');
    skipBtn = document.getElementById('skip-btn');

    // Inicializar eventos
    initializeEventListeners();

    // Cargar datos iniciales
    loadAnomalySummary();
    loadAnomalies();

    // Actualización automática cada 10 segundos
    setInterval(() => {
        loadAnomalySummary();
        loadAnomalies();
    }, 10000);

    console.log('✅ System Alerts initialized');
});