<x-dashboard-layout>

@section('title', 'Global Analytics')

<style>
.analytics-wrapper { animation: fadeInUp 0.5s ease both; }
@keyframes fadeInUp {
    from { opacity:0; transform:translateY(20px); }
    to   { opacity:1; transform:translateY(0); }
}
.section-card {
    background:#0f172a; border:1px solid #1e293b;
    border-radius:18px; padding:24px; margin-bottom:24px;
    transition:box-shadow 0.3s;
}
.section-card:hover { box-shadow:0 0 30px rgba(37,99,235,0.08); }
.section-title {
    font-size:15px; font-weight:700; color:#f8fafc;
    margin-bottom:20px; display:flex; align-items:center; gap:8px;
}
.badge-pill {
    font-size:10px; background:rgba(56,189,248,0.15);
    color:#38bdf8; border:1px solid rgba(56,189,248,0.25);
    padding:2px 8px; border-radius:20px; font-weight:600;
}
.page-title {
    font-size:24px; font-weight:800;
    background:linear-gradient(135deg,#38bdf8,#2563eb);
    -webkit-background-clip:text; -webkit-text-fill-color:transparent;
    background-clip:text;
}
</style>

<div class="analytics-wrapper">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <div class="page-title">📈 Global Supply Chain Analytics</div>
            <p class="text-secondary mb-0" style="font-size:13px;">Macroeconomic, weather, currency, and risk trend visualizations</p>
        </div>
        <span class="badge" style="background:rgba(56,189,248,0.1);border:1px solid rgba(56,189,248,0.2);color:#38bdf8;font-size:11px;">
            📊 Historical Trends
        </span>
    </div>

    {{-- CHARTS ROW 1: Risk & GDP --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-6">
            <div class="section-card">
                <div class="section-title">
                    ⚠️ Global Average Risk Score Trend
                    <span class="badge-pill">Risk History</span>
                </div>
                <div style="height:320px; position:relative;">
                    <canvas id="chartRiskTrend"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="section-card">
                <div class="section-title">
                    💵 Average GDP Growth Trend
                    <span class="badge-pill">GDP (USD)</span>
                </div>
                <div style="height:320px; position:relative;">
                    <canvas id="chartGdpTrend"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- CHARTS ROW 2: Inflation & Currency --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-6">
            <div class="section-card">
                <div class="section-title">
                    📈 Global Inflation Trend
                    <span class="badge-pill">Average CPI %</span>
                </div>
                <div style="height:320px; position:relative;">
                    <canvas id="chartInflationTrend"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="section-card">
                <div class="section-title">
                    💱 Top 10 High-Value Currencies
                    <span class="badge-pill">Exchange Rates vs USD</span>
                </div>
                <div style="height:320px; position:relative;">
                    <canvas id="chartCurrencyRates"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- CHARTS ROW 3: Weather Temp & Rainfall --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-12">
            <div class="section-card">
                <div class="section-title">
                    ☁️ Temperature vs Rainfall by Country
                    <span class="badge-pill">Weather Monitor</span>
                </div>
                <div style="height:350px; position:relative;">
                    <canvas id="chartWeather"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- CHARTS ROW 4: Trade (Export vs Import) --}}
    <div class="row g-4">
        <div class="col-xl-6">
            <div class="section-card">
                <div class="section-title">
                    🚢 Global Trade Volume Trend (Export vs Import)
                    <span class="badge-pill">Trade Balance</span>
                </div>
                <div style="height:320px; position:relative;">
                    <canvas id="chartTradeTrend"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="section-card">
                <div class="section-title">
                    👥 Global Population Trend
                    <span class="badge-pill">Population</span>
                </div>
                <div style="height:320px; position:relative;">
                    <canvas id="chartPopulationTrend"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- CHART SCRIPT INTEGRATION --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Risk Trend Chart
    new Chart(document.getElementById('chartRiskTrend'), {
        type: 'line',
        data: {
            labels: @json($riskLabels),
            datasets: [{
                label: 'Avg Risk Score',
                data: @json($riskValues),
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239,68,68,0.1)',
                borderWidth: 3,
                tension: 0.35,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#64748b' } },
                y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#64748b' }, beginAtZero: true, max: 100 }
            }
        }
    });

    // 2. GDP Trend Chart
    new Chart(document.getElementById('chartGdpTrend'), {
        type: 'bar',
        data: {
            labels: @json($gdpYears),
            datasets: [{
                label: 'Average GDP',
                data: @json($avgGdp),
                backgroundColor: '#2563eb',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#64748b' } },
                y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#64748b' } }
            }
        }
    });

    // 3. Inflation Trend Chart
    new Chart(document.getElementById('chartInflationTrend'), {
        type: 'line',
        data: {
            labels: @json($gdpYears),
            datasets: [{
                label: 'Avg Inflation %',
                data: @json($avgInflation),
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245,158,11,0.1)',
                borderWidth: 3,
                tension: 0.35,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#64748b' } },
                y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#64748b' } }
            }
        }
    });

    // 4. Currency Rates Chart
    new Chart(document.getElementById('chartCurrencyRates'), {
        type: 'bar',
        data: {
            labels: @json($currencyLabels),
            datasets: [{
                label: 'Rate to 1 USD',
                data: @json($currencyValues),
                backgroundColor: '#10b981',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#64748b' } },
                y: { grid: { display: false }, ticks: { color: '#64748b' } }
            }
        }
    });

    // 5. Weather Temperature vs Rainfall Chart
    new Chart(document.getElementById('chartWeather'), {
        type: 'bar',
        data: {
            labels: @json($weatherLabels),
            datasets: [
                {
                    label: 'Temperature (°C)',
                    data: @json($weatherTemp),
                    backgroundColor: '#f43f5e',
                    yAxisID: 'y',
                    borderRadius: 5
                },
                {
                    label: 'Rainfall (mm)',
                    data: @json($weatherRain),
                    backgroundColor: '#0ea5e9',
                    yAxisID: 'y1',
                    borderRadius: 5
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { grid: { display: false }, ticks: { color: '#64748b' } },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    grid: { color: 'rgba(255,255,255,0.05)' },
                    ticks: { color: '#f43f5e' }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    ticks: { color: '#0ea5e9' }
                }
            }
        }
    });

    // 6. Trade Trend Chart (Export vs Import)
    new Chart(document.getElementById('chartTradeTrend'), {
        type: 'line',
        data: {
            labels: @json($gdpYears),
            datasets: [
                {
                    label: 'Avg Export Value',
                    data: @json($avgExport),
                    borderColor: '#10b981',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.3
                },
                {
                    label: 'Avg Import Value',
                    data: @json($avgImport),
                    borderColor: '#ef4444',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#64748b' } },
                y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#64748b' } }
            }
        }
    });

    // 7. Population Trend Chart
    new Chart(document.getElementById('chartPopulationTrend'), {
        type: 'bar',
        data: {
            labels: @json($gdpYears),
            datasets: [{
                label: 'Avg Population',
                data: @json($avgPopulation),
                backgroundColor: '#8b5cf6',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#64748b' } },
                y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#64748b' } }
            }
        }
    });
});
</script>

</x-dashboard-layout>
