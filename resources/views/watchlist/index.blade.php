<x-dashboard-layout>

@section('title', 'Watchlist')

<style>
.watchlist-wrapper { animation: fadeInUp 0.5s ease both; }
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.section-card {
    background: #0f172a;
    border: 1px solid #1e293b;
    border-radius: 18px;
    padding: 24px;
    margin-bottom: 24px;
}
.section-title {
    font-size: 15px;
    font-weight: 700;
    color: #f8fafc;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.badge-pill {
    font-size: 10px;
    background: rgba(56,189,248,0.15);
    color: #38bdf8;
    border: 1px solid rgba(56,189,248,0.25);
    padding: 2px 8px;
    border-radius: 20px;
    font-weight: 600;
}
.page-title {
    font-size: 24px;
    font-weight: 800;
    background: linear-gradient(135deg, #38bdf8, #2563eb);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #475569;
}
.empty-icon {
    font-size: 56px;
    margin-bottom: 16px;
    opacity: 0.35;
}
</style>

<div class="watchlist-wrapper">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <div class="page-title">⭐ Favorite Monitoring List</div>
            <p class="text-secondary mb-0" style="font-size:13px;">Manage your monitored watchlists and receive real-time updates</p>
        </div>
    </div>

    {{-- ADD COUNTRY TO WATCHLIST --}}
    <div class="section-card">
        <form method="POST" action="{{ route('watchlist.store') }}">
            @csrf
            <div class="row g-2 align-items-end">
                <div class="col-md-9">
                    <label class="form-label" style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Add Country to Watchlist</label>
                    <select name="country_id" class="form-select form-select-sm" required>
                        <option value="">Choose Country...</option>
                        @foreach($countries as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-sm btn-primary w-100" style="border-radius:8px; padding: 7.5px;">
                        ➕ Add to Favorites
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- WATCHLIST TABLE --}}
    <div class="section-card">
        <div class="section-title">
            📋 Monitored Favorites
            <span class="badge-pill">{{ $watchlists->count() }} countries</span>
        </div>

        @if($watchlists->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">⭐</div>
                <h5 style="color:#475569;">Your Watchlist is Empty</h5>
                <p class="text-secondary">Start tracking countries by adding them using the selector above.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0" style="--bs-table-bg:#0f172a; --bs-table-border-color:#1e293b; --bs-table-hover-bg:#1e293b;">
                    <thead>
                        <tr style="border-bottom: 1px solid #1e293b;">
                            <th style="font-size:10px; text-transform:uppercase; color:#64748b; letter-spacing:0.8px; padding:10px 12px; background:#020617;">Country</th>
                            <th style="font-size:10px; text-transform:uppercase; color:#64748b; letter-spacing:0.8px; padding:10px 12px; background:#020617;">Risk Level</th>
                            <th style="font-size:10px; text-transform:uppercase; color:#64748b; letter-spacing:0.8px; padding:10px 12px; background:#020617;">Weather</th>
                            <th style="font-size:10px; text-transform:uppercase; color:#64748b; letter-spacing:0.8px; padding:10px 12px; background:#020617;">Currency</th>
                            <th style="font-size:10px; text-transform:uppercase; color:#64748b; letter-spacing:0.8px; padding:10px 12px; background:#020617;">Created At</th>
                            <th style="font-size:10px; text-transform:uppercase; color:#64748b; letter-spacing:0.8px; padding:10px 12px; background:#020617; text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($watchlists as $wl)
                            @php
                                $c = $wl->country;
                                $risk = $c?->riskScores->first();
                                $wea = $c?->weatherData->first();
                                $cur = $c?->currencyRates->first();

                                $riskColor = match($risk?->risk_level ?? 'Low Risk') {
                                    'High Risk' => '#ef4444',
                                    'Medium Risk' => '#f59e0b',
                                    default => '#22c55e',
                                };
                                $riskBg = match($risk?->risk_level ?? 'Low Risk') {
                                    'High Risk' => 'rgba(239,68,68,0.12)',
                                    'Medium Risk' => 'rgba(245,158,11,0.12)',
                                    default => 'rgba(34,197,94,0.12)',
                                };
                            @endphp
                            <tr>
                                <td style="padding:10px 12px;">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($c?->flag)
                                            <img src="{{ $c->flag }}" width="28" height="20" style="border-radius:3px; object-fit:cover;" onerror="this.style.display='none'">
                                        @endif
                                        <div>
                                            <a href="{{ route('countries.show', $c->id) }}" class="fw-semibold text-white text-decoration-none">
                                                {{ $c?->name }}
                                            </a>
                                            <div style="font-size:10px; color:#64748b;">{{ $c?->region }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding:10px 12px;">
                                    <span style="background:{{ $riskBg }}; color:{{ $riskColor }}; border:1px solid {{ $riskColor }}33; padding:3px 8px; border-radius:6px; font-size:11px; font-weight:600;">
                                        {{ $risk?->risk_level ?? 'Low Risk' }} ({{ $risk?->total_score ?? '20' }})
                                    </span>
                                </td>
                                <td style="padding:10px 12px; font-size:13px;">
                                    @if($wea)
                                        <span class="text-info">{{ $wea->temperature }}°C</span> 
                                        <span class="text-secondary" style="font-size:11px;">({{ $wea->weather_status }})</span>
                                    @else
                                        <span class="text-secondary">—</span>
                                    @endif
                                </td>
                                <td style="padding:10px 12px; font-size:13px;">
                                    @if($cur)
                                        <span class="text-warning">{{ $cur->currency_code }}</span> 
                                        <span class="text-secondary" style="font-size:11px;">({{ number_format($cur->exchange_rate, 2) }})</span>
                                    @else
                                        <span class="text-secondary">—</span>
                                    @endif
                                </td>
                                <td style="padding:10px 12px; color:#64748b; font-size:12px;">
                                    {{ $wl->created_at->diffForHumans() }}
                                </td>
                                <td style="padding:10px 12px; text-align:right;">
                                    <form method="POST" action="{{ route('watchlist.destroy', $wl->id) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px; font-size:11px; padding:4px 10px;">
                                            🗑️ Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

</x-dashboard-layout>
