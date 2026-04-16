@extends('layouts.app')

@section('title', 'System Alerts')

@vite([
    'resources/css/system-alerts.css',
    'resources/js/system-alerts.js'
])

@section('content')
<div class="alerts-container">

    <!-- Header -->
    <div class="alerts-header">
        <div>
            <h1 class="alerts-title">
                <span class="icon-alert">!</span>
                Anomalies Detected
            </h1>
            <p class="alerts-subtitle">
                High-priority resolution required for 
                <strong id="active-conflicts">0</strong> active RFID gate conflicts.
            </p>
        </div>

        <div class="alerts-actions">
            <button class="btn-rescan" id="rescanBtn" type="button">
                ⟳ Rescan Gate
            </button>
            <a href="{{ route('dashboard') }}" class="btn-return">
                → Return to Session
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon stat-blue">#</div>
            <div>
                <div id="total-errors" class="stat-value">00</div>
                <div class="stat-label">TOTAL ERRORS</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-green">⚠</div>
            <div>
                <div id="unknown-sku" class="stat-value">00</div>
                <div class="stat-label">UNKNOWN SKU</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-red">⧉</div>
            <div>
                <div id="duplicates" class="stat-value">00</div>
                <div class="stat-label">DUPLICATES</div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div id="alerts-main" class="alerts-main">

        <!-- Conflict Queue -->
        <div class="conflict-queue">
            <div class="queue-header">
                <div class="queue-title">
                    <span class="queue-icon">⏱</span>
                    <h5>Conflict Queue</h5>
                </div>
                <span class="sync-badge">REAL-TIME SYNC ACTIVE</span>
            </div>

            <!-- Table with Scroll -->
            <div class="table-scroll">
                <table class="conflict-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Tag Identifier</th>
                            <th>Location</th>
                            <th>Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="anomalies-table-body">
                        <!-- Data will be injected by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Resolve Conflict Panel -->
        <div id="resolve-panel" class="resolve-panel">
            <div class="panel-header">
                <div>
                    <span id="detail-type-badge" class="badge unknown">Unknown</span>
                    <span id="detail-time" class="time">--:--:--</span>
                </div>
                <button id="close-panel" class="close-panel-btn" type="button" aria-label="Close">
                    &times;
                </button>
            </div>

            <h4>Resolve Conflict</h4>

            <div class="form-group">
                <label for="detail-tag-id">Tag Identifier</label>
                <input 
                    type="text" 
                    id="detail-tag-id" 
                    class="input-field" 
                    placeholder="Tag ID"
                    readonly
                >
            </div>

            <div class="form-group">
                <label for="detail-assigned-product">Assign Product SKU</label>
                <input 
                    type="text" 
                    id="detail-assigned-product" 
                    class="input-field" 
                    placeholder="Search product name or SKU..."
                >
            </div>

            <div class="input-row">
                <div class="form-group">
                    <label for="detail-batch-number">Batch Number</label>
                    <input 
                        type="text" 
                        id="detail-batch-number" 
                        class="input-field" 
                        placeholder="Optional"
                    >
                </div>
                <div class="form-group">
                    <label for="detail-quantity">Quantity</label>
                    <input 
                        type="number" 
                        id="detail-quantity" 
                        class="input-field" 
                        value="1"
                        min="1"
                    >
                </div>
            </div>

            <button id="resolve-btn" class="btn-primary-action" type="button">
                ✔ Register Tag & Continue
            </button>
            <button id="skip-btn" class="btn-skip" type="button">
                Skip for Now
            </button>
        </div>

    </div>
</div>

<!-- Global configuration for JavaScript -->
<script>
    window.systemAlertsConfig = {
        dockId: @json($dockId ?? null),
        scanSessionId: @json($scanSessionId ?? null)
    };
</script>
@endsection