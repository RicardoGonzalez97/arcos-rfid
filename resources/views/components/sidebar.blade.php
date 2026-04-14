<div class="sidebar">
    <div class="sidebar-top">
        <!-- Logo -->
        <div class="logo-container">
            <img src="/images/login-img.png" class="logo-img">
            <div class="logo-text">LogiSync RFID</div>
        </div>

        <!-- Menu -->
        <div class="menu mt-3">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="menu-icon" viewBox="0 0 522 546.40729" fill="currentColor">
                    <path d="M261,541.4073A97.416533,97.416533 0 0 1 214.06667,529.15343L55.82453,442.2841A97.655467,97.655467 0 0 1 5,356.95076V189.39023A97.621333,97.621333 0 0 1 55.7904,104.0569L213.82773,17.153434a97.143467,97.143467 0 0 1 94.1056,0L466.17547,104.09103A97.655467,97.655467 0 0 1 517,189.42436v167.5264A97.621333,97.621333 0 0 1 466.24373,442.2841L308.17227,529.15343A97.416533,97.416533 0 0 1 261,541.4073Z"/>
                </svg>
                Active Session
            </a>

            <!-- System Alerts -->
            <a href="{{ route('system.alerts') }}"
               class="{{ request()->routeIs('system.alerts') ? 'active' : '' }}">
                <img src="/images/alert.png" class="menu-icon">
                System Alerts
            </a>

            <!-- Gate Settings -->
            <a href="{{ route('gate.settings') }}"
               class="{{ request()->routeIs('gate.settings') ? 'active' : '' }}">
                <img src="/images/settings.png" class="menu-icon">
                Gate Settings
            </a>
        </div>
    </div>

    <!-- Bottom -->
    <div class="sidebar-bottom">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-btn">
                <img src="/images/logout.png" class="menu-icon">
                Sign Out
            </button>
        </form>
    </div>
</div>