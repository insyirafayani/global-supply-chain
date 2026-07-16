<x-dashboard-layout>

@section('title', 'Global News')

<style>
.news-wrapper { animation: fadeInUp 0.5s ease both; }
@keyframes fadeInUp {
    from { opacity:0; transform:translateY(20px); }
    to   { opacity:1; transform:translateY(0); }
}
.kpi-card {
    background: linear-gradient(135deg,#0f172a,#1e293b);
    border: 1px solid #1e293b;
    border-radius: 16px;
    padding: 20px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    height: 100%;
}
.kpi-card::after {
    content:''; position:absolute;
    top:-30px; right:-30px;
    width:100px; height:100px;
    border-radius:50%; opacity:0.07;
}
.kpi-card.kpi-info    { border-color:rgba(56,189,248,0.35); }
.kpi-card.kpi-info::after    { background:#38bdf8; }
.kpi-card.kpi-success { border-color:rgba(34,197,94,0.35); }
.kpi-card.kpi-success::after { background:#22c55e; }
.kpi-card.kpi-warning { border-color:rgba(245,158,11,0.35); }
.kpi-card.kpi-warning::after { background:#f59e0b; }
.kpi-card.kpi-danger  { border-color:rgba(239,68,68,0.35); }
.kpi-card.kpi-danger::after  { background:#ef4444; }
.kpi-card:hover { transform:translateY(-4px); }
.kpi-label { font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:8px; }
.kpi-value { font-size:32px; font-weight:800; line-height:1; }
.kpi-sub   { font-size:11px; color:#64748b; margin-top:6px; }
.kpi-info .kpi-value    { color:#38bdf8; }
.kpi-success .kpi-value { color:#22c55e; }
.kpi-warning .kpi-value { color:#f59e0b; }
.kpi-danger .kpi-value  { color:#ef4444; }

.section-card {
    background:#0f172a; border:1px solid #1e293b;
    border-radius:16px; padding:22px; margin-bottom:24px;
}
.section-title {
    font-size:15px; font-weight:700; color:#f8fafc;
    margin-bottom:18px; display:flex; align-items:center; gap:8px;
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
.news-card {
    background:#0f172a; border:1px solid #1e293b;
    border-radius:14px; padding:16px;
    transition:all 0.2s; cursor:pointer;
}
.news-card:hover { background:#1e293b; border-color:#334155; transform:translateY(-2px); }
.sentiment-badge-positive { background:rgba(34,197,94,0.12);  color:#22c55e; border:1px solid rgba(34,197,94,0.25); }
.sentiment-badge-neutral  { background:rgba(100,116,139,0.12); color:#94a3b8; border:1px solid rgba(100,116,139,0.25); }
.sentiment-badge-negative { background:rgba(239,68,68,0.12);  color:#ef4444; border:1px solid rgba(239,68,68,0.25); }
.empty-state { text-align:center; padding:60px 20px; color:#475569; }
.empty-icon  { font-size:56px; margin-bottom:16px; opacity:0.35; }
</style>

<div class="news-wrapper">

{{-- PAGE HEADER --}}
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
    <div>
        <div class="page-title">📰 Global News Intelligence</div>
        <p class="text-secondary mb-0" style="font-size:13px;">Trade, Economic & Political News from GNews API</p>
    </div>
    <div class="d-flex gap-2 align-items-center flex-wrap">
        <span class="badge" style="background:rgba(56,189,248,0.1);border:1px solid rgba(56,189,248,0.2);color:#38bdf8;font-size:11px;">
            🕐 {{ now()->format('d M Y H:i') }}
        </span>
        <span class="badge" style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2);color:#86efac;font-size:11px;">
            🟢 GNews API
        </span>
    </div>
</div>

{{-- KPI CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="kpi-card kpi-info">
            <div class="kpi-label">Total News</div>
            <div class="kpi-value">{{ $totalNews }}</div>
            <div class="kpi-sub">Articles cached</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card kpi-success">
            <div class="kpi-label">Positive</div>
            <div class="kpi-value">{{ $positiveNews }}</div>
            <div class="kpi-sub">🟢 Sentiment</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card kpi-warning">
            <div class="kpi-label">Neutral</div>
            <div class="kpi-value">{{ $neutralNews }}</div>
            <div class="kpi-sub">🟡 Sentiment</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card kpi-danger">
            <div class="kpi-label">Negative</div>
            <div class="kpi-value">{{ $negativeNews }}</div>
            <div class="kpi-sub">🔴 Sentiment</div>
        </div>
    </div>
</div>

{{-- SENTIMENT BAR --}}
@php
    $total = max(1, $totalNews);
    $posPct = round(($positiveNews / $total) * 100);
    $neuPct = round(($neutralNews / $total) * 100);
    $negPct = round(($negativeNews / $total) * 100);
@endphp
<div class="section-card mb-4" style="padding:16px 22px;">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span style="font-size:12px;font-weight:600;color:#94a3b8;">Global Sentiment Overview</span>
        <div class="d-flex gap-3" style="font-size:11px;color:#64748b;">
            <span>🟢 {{ $posPct }}%</span>
            <span>🟡 {{ $neuPct }}%</span>
            <span>🔴 {{ $negPct }}%</span>
        </div>
    </div>
    <div style="height:8px;border-radius:4px;overflow:hidden;background:#1e293b;display:flex;">
        <div style="width:{{ $posPct }}%;background:#22c55e;transition:width 0.8s ease;"></div>
        <div style="width:{{ $neuPct }}%;background:#f59e0b;transition:width 0.8s ease;"></div>
        <div style="width:{{ $negPct }}%;background:#ef4444;transition:width 0.8s ease;"></div>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="section-card mb-4">
    <form method="GET" action="{{ route('news.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-4 col-sm-6">
                <label class="form-label" style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Search News</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Title, description, source..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3 col-sm-6">
                <label class="form-label" style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Country</label>
                <select name="country_id" class="form-select form-select-sm">
                    <option value="">All Countries</option>
                    @foreach($countries as $id => $name)
                        <option value="{{ $id }}" {{ request('country_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 col-sm-6">
                <label class="form-label" style="font-size:11px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Sentiment</label>
                <select name="sentiment" class="form-select form-select-sm">
                    <option value="">All Sentiment</option>
                    <option value="Positive" {{ request('sentiment') == 'Positive' ? 'selected' : '' }}>🟢 Positive</option>
                    <option value="Neutral"  {{ request('sentiment') == 'Neutral'  ? 'selected' : '' }}>🟡 Neutral</option>
                    <option value="Negative" {{ request('sentiment') == 'Negative' ? 'selected' : '' }}>🔴 Negative</option>
                </select>
            </div>
            <div class="col-md-3 col-sm-6 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill" style="border-radius:8px;">
                    🔍 Filter
                </button>
                <a href="{{ route('news.index') }}" class="btn btn-sm flex-fill" style="background:#1e293b;color:#94a3b8;border:1px solid #334155;border-radius:8px;">
                    ✕ Reset
                </a>
            </div>
        </div>
    </form>
</div>

{{-- NEWS CARDS --}}
<div class="section-card">
    <div class="section-title">
        📋 Latest News
        <span class="badge-pill">{{ $news->total() }} articles</span>
    </div>

    @if($news->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <h5 style="color:#475569;">No News Available</h5>
            <p class="text-secondary">News data will appear here after syncing with GNews API.</p>
            <p class="text-secondary" style="font-size:12px;">Open any country's Intelligence Center and click "Sync News" to start.</p>
        </div>
    @else
        <div class="row g-3">
            @foreach($news as $article)
            @php
                $sentimentClass = match($article->sentiment) {
                    'Positive' => 'sentiment-badge-positive',
                    'Negative' => 'sentiment-badge-negative',
                    default    => 'sentiment-badge-neutral',
                };
                $sentimentIcon = match($article->sentiment) {
                    'Positive' => '🟢',
                    'Negative' => '🔴',
                    default    => '🟡',
                };
                $borderLeft = match($article->sentiment) {
                    'Negative' => '3px solid #ef4444',
                    'Positive' => '3px solid #22c55e',
                    default    => '3px solid #f59e0b',
                };
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="news-card" style="border-left:{{ $borderLeft }};"
                     onclick="{{ $article->url ? 'window.open(\'' . $article->url . '\',\'_blank\')' : '' }}">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge {{ $sentimentClass }}" style="font-size:10px;padding:3px 8px;border-radius:6px;font-weight:600;">
                            {{ $sentimentIcon }} {{ $article->sentiment ?? 'Neutral' }}
                        </span>
                        @if($article->country)
                            <div class="d-flex align-items-center gap-1">
                                @if($article->country->flag)
                                    <img src="{{ $article->country->flag }}" width="16" height="11" style="border-radius:2px;object-fit:cover;" onerror="this.style.display='none'">
                                @endif
                                <span style="font-size:10px;color:#64748b;">{{ $article->country->name }}</span>
                            </div>
                        @else
                            <span style="font-size:10px;color:#64748b;">🌐 Global</span>
                        @endif
                    </div>
                    <h6 style="font-size:13px;font-weight:600;color:#f1f5f9;margin-bottom:8px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                        {{ $article->title }}
                    </h6>
                    @if($article->description)
                        <p style="font-size:11px;color:#64748b;margin-bottom:8px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;line-height:1.5;">
                            {{ $article->description }}
                        </p>
                    @endif
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <span style="font-size:10px;color:#475569;">
                            {{ $article->source ? '📰 '.$article->source : '' }}
                        </span>
                        <span style="font-size:10px;color:#475569;">
                            {{ $article->published_at?->diffForHumans() ?? $article->created_at?->diffForHumans() ?? '' }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- PAGINATION --}}
        @if($news->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3" style="border-top:1px solid #1e293b;">
                <div style="font-size:12px;color:#64748b;">
                    Showing {{ $news->firstItem() }}–{{ $news->lastItem() }} of {{ $news->total() }} articles
                </div>
                {{ $news->links('pagination::bootstrap-5') }}
            </div>
        @endif
    @endif
</div>

</div>

</x-dashboard-layout>
