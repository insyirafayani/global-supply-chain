@php
    $ports = $country->ports;
@endphp

<div class="card card-dark dashboard-card p-4">
    <h4 class="mb-4 text-info">🚢 Shipping Ports & Trade Corridors</h4>
    
    @if($ports->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-dark table-hover" style="border: 1px solid #1e293b; border-radius: 4px; overflow: hidden; font-size:13px; vertical-align: middle;">
                <thead>
                    <tr style="border-bottom:1px solid #1e293b; background:#020617;">
                        <th style="font-size:10px; text-transform:uppercase; color:#64748b; letter-spacing:0.8px; padding:12px;">Port Name</th>
                        <th style="font-size:10px; text-transform:uppercase; color:#64748b; letter-spacing:0.8px; padding:12px;">Code</th>
                        <th style="font-size:10px; text-transform:uppercase; color:#64748b; letter-spacing:0.8px; padding:12px;">Location</th>
                        <th style="font-size:10px; text-transform:uppercase; color:#64748b; letter-spacing:0.8px; padding:12px;">Volume (TEUs/yr)</th>
                        <th style="font-size:10px; text-transform:uppercase; color:#64748b; letter-spacing:0.8px; padding:12px;">Capacity (TEUs)</th>
                        <th style="font-size:10px; text-transform:uppercase; color:#64748b; letter-spacing:0.8px; padding:12px;">Congestion</th>
                        <th style="font-size:10px; text-transform:uppercase; color:#64748b; letter-spacing:0.8px; padding:12px;">Risk</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ports as $port)
                        @php
                            $congestionColor = match($port->congestion) {
                                'High' => '#ef4444',
                                'Medium' => '#f59e0b',
                                default => '#22c55e'
                            };
                            $riskColor = match($port->risk) {
                                'High Risk' => '#ef4444',
                                'Medium Risk' => '#f59e0b',
                                default => '#22c55e'
                            };
                        @endphp
                        <tr style="border-bottom:1px solid #1e293b;">
                            <td style="padding:12px; font-weight:600; color:#f1f5f9;">{{ $port->port_name }}</td>
                            <td style="padding:12px; color:#38bdf8; font-family: monospace;">{{ $port->port_code }}</td>
                            <td style="padding:12px; color:#94a3b8;">{{ $port->location }}</td>
                            <td style="padding:12px; color:#f1f5f9;">{{ number_format($port->trade_volume) }}</td>
                            <td style="padding:12px; color:#f1f5f9;">{{ number_format($port->capacity) }}</td>
                            <td style="padding:12px;">
                                <span class="badge" style="background: {{ $congestionColor }}12; color: {{ $congestionColor }}; border: 1px solid {{ $congestionColor }}44;">
                                    {{ $port->congestion }}
                                </span>
                            </td>
                            <td style="padding:12px;">
                                <span class="badge" style="background: {{ $riskColor }}12; color: {{ $riskColor }}; border: 1px solid {{ $riskColor }}44;">
                                    {{ $port->risk }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-5 text-secondary">
            <span style="font-size: 40px;">📭</span>
            <p class="mt-2">No port or trade infrastructure registered for this country.</p>
        </div>
    @endif
</div>
