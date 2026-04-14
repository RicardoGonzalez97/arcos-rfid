<div class="topbar">
    <!-- Row 1 -->
    <div class="topbar-row">
        <span class="badge-online">
            <img src="/images/rfid-icon.png" class="icon-small">
            RFID Portal: Online
        </span>

        <div class="user-info">
            <div class="text-end">
                <strong>Ulises González</strong><br>
                <small class="text-muted">Floor Supervisor</small>
            </div>
            <img src="https://i.pravatar.cc/40" class="user-avatar">
        </div>
    </div>

    <!-- Row 2 -->
    <div class="topbar-row space-between">
        <div class="session-info">
            <div class="info-block">
                <span class="label">ACTIVE SESSION</span>
                <div class="value">
                    <span id="session-id">--</span>
                    <span class="badge-live">● LIVE</span>
                </div>
            </div>

            <div class="info-block">
                <span class="label">
                    <img src="/images/clock.png" class="icon-small"> START TIME
                </span>
                <div id="start-time" class="value">--:--:--</div>
            </div>

            <div class="info-block">
                <span class="label">
                    <img src="/images/hashtag.png" class="icon-small"> TOTAL TAGS
                </span>
                <div id="total-tags" class="value">0 items</div>
            </div>

            <div class="info-block">
                <span class="label">
                    <img src="/images/anomalies.png" class="icon-small"> ANOMALIES
                </span>
                <div id="anomalies" class="value text-danger">0 Flags</div>
            </div>
        </div>

        <div class="button-group">
            <button id="pause-btn" class="btn-outline-pause" onclick="pauseSession()" style="display:none;">
                <img src="/images/pause.png" class="icon-small"> Pause
            </button>

            <button id="end-btn" class="btn-outline-danger-custom" onclick="endSession()" style="display:none;">
                <img src="/images/end-session.png" class="icon-small"> End Session
            </button>

            <button id="start-btn" class="btn-blue-custom" onclick="startSession()" style="display:none;">
                <img src="/images/start-session.png" class="icon-small"> Start New Session
            </button>
        </div>
    </div>
</div>