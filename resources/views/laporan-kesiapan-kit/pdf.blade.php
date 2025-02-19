<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kesiapan KIT</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            
        }
        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14px;
            margin-bottom: 5px;
        }
        .unit-section {
            margin-bottom: 25px;
        }
        .unit-header {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            padding: 5px;
            background-color: #f3f4f6;
        }
        .unit-stats {
            margin-left: 20px;
            margin-bottom: 10px;
        }
        .machine-list {
            margin-left: 30px;
        }
        .machine-item {
            margin-bottom: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-style: italic;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
        }
        .status-badge {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            display: inline-block;
        }
        .status-ops { background-color: #d1fae5; color: #065f46; }
        .status-rsh { background-color: #fef3c7; color: #92400e; }
        .status-fo { background-color: #fee2e2; color: #991b1b; }
        .status-mo { background-color: #fff7ed; color: #9a3412; }
        .status-po { background-color: #dbeafe; color: #1e40af; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/navlogo.png') }}" alt="PLN Logo" class="logo">
        <div class="title">Laporan Kesiapan Pembangkit PLN Nusantara Power</div>
        <div class="subtitle">Unit Pembangkitan Kendari</div>
        <div class="subtitle">{{ now()->format('d F Y') }}</div>
        <div class="subtitle">Pukul: {{ now()->format('H:i') }} WITA</div>
    </div>

    @foreach($units as $unit)
    <div class="unit-section">
        <div class="unit-header">{{ $unit->name }}</div>
        <div class="unit-stats">
            <table>
                <tr>
                    <td width="150">DMN</td>
                    <td>: {{ number_format($unit->machines->sum('dmn'), 2) }} MW</td>
                </tr>
                <tr>
                    <td>DMP</td>
                    <td>: {{ number_format($unit->machines->sum('dmp'), 2) }} MW</td>
                </tr>
                <tr>
                    <td>Beban</td>
                    <td>: {{ number_format($unit->machines->sum(function($machine) {
                        return optional($machine->logs->first())->current_load ?? 0;
                    }), 2) }} MW</td>
                </tr>
            </table>
        </div>

        <div class="machine-list">
            @foreach($unit->machines as $machine)
                <div class="machine-item">
                    - {{ $machine->name }}: 
                    @if($machine->logs->first())
                        {{ number_format($machine->logs->first()->capable_power, 2) }}MW/
                        {{ number_format($machine->logs->first()->supply_power, 2) }}MW/
                        <span class="status-badge status-{{ strtolower($machine->logs->first()->status) }}">
                            {{ $machine->logs->first()->status }}
                        </span>
                        @if($machine->logs->first()->current_load > 0)
                            {{ number_format($machine->logs->first()->current_load, 2) }} MW
                        @endif
                    @else
                        N/A
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endforeach

    <div class="footer">
        Barakallahu Fikhum dan Terima Kasih
    </div>
</body>
</html> 