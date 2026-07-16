@php
    $newsItems = $country->newsCaches;
@endphp

<div class="card card-dark dashboard-card p-4">
    <h4 class="mb-4 text-info">📰 Global Incidents & Trade Incidents Feed</h4>
    
    @if($newsItems->isNotEmpty())
        <div class="row g-3">
            @foreach($newsItems as $news)
                @php
                    $sentimentLabel = $news->sentiment ?? 'Neutral';
                    $sentimentColor = match($sentimentLabel) {
                        'Positive' => '#22c55e',
                        'Negative' => '#ef4444',
                        default    => '#64748b'
                    };
                    $sentimentBg = match($sentimentLabel) {
                        'Positive' => 'rgba(34,197,94,0.12)',
                        'Negative' => 'rgba(239,68,68,0.12)',
                        default    => 'rgba(100,116,139,0.12)'
                    };
                @endphp
                <div class="col-12">
                    <div class="p-3 rounded d-flex flex-column gap-2" style="background: rgba(30, 41, 59, 0.4); border: 1px solid #1e293b;">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <h6 class="mb-0" style="color: #f1f5f9; font-weight: 600;">
                                @if($news->url)
                                    <a href="{{ $news->url }}" target="_blank" style="text-decoration: none; color: inherit; transition: color 0.2s;" onmouseover="this.style.color='#38bdf8'" onmouseout="this.style.color='inherit'">
                                        {{ $news->title }}
                                    </a>
                                @else
                                    {{ $news->title }}
                                @endif
                            </h6>
                            <span class="badge" style="background: {{ $sentimentBg }}; color: {{ $sentimentColor }}; border: 1px solid {{ $sentimentColor }}44;">
                                {{ $sentimentLabel }}
                            </span>
                        </div>
                        <p class="mb-0 text-secondary" style="font-size: 13px;">
                            {{ Str::limit($news->description ?? 'No details available for this incident.', 200) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-1" style="font-size: 11px; color: #475569;">
                            <span>Source: <strong style="color: #64748b;">{{ $news->source ?? 'Global Intelligence' }}</strong></span>
                            <span>Published: {{ $news->published_at ? \Carbon\Carbon::parse($news->published_at)->format('Y-m-d H:i') : '-' }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5 text-secondary">
            <span style="font-size: 40px;">📭</span>
            <p class="mt-2">No trade incidents or news found for this country.</p>
        </div>
    @endif
</div>
