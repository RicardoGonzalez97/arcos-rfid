@extends('layouts.dashboard')

@section('content')
<div class="cards" id="docks-container"></div>

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

<script>
    window.dashboardConfig = {
        dockId: {{ $dockId ?? 2 }}
    };
</script>
@endsection