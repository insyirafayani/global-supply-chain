@php
    $recommendations = $country->recommendations;
    $rec = $recommendations->last();
@endphp

<div class="card card-dark dashboard-card p-4">
    <h4 class="mb-4 text-info">⭐ Strategic Trade Recommendations</h4>
    
    @if($rec)
        <div class="row g-4 mb-4">
            <!-- Strategic Recommendation -->
            <div class="col-md-6 col-lg-6">
                <div class="p-3 rounded h-100" style="background: rgba(30, 41, 59, 0.4); border: 1px solid #334155;">
                    <small class="text-secondary d-block mb-1">Recommended Action Plan</small>
                    <h5 class="mt-2 text-white" style="line-height: 1.5;">
                        {{ $rec->recommended_action ?? 'Maintain regular inventory levels and monitor macro indicators.' }}
                    </h5>
                </div>
            </div>

            <!-- Risk Mitigation Strategy -->
            <div class="col-md-6 col-lg-6">
                <div class="p-3 rounded h-100" style="background: rgba(30, 41, 59, 0.4); border: 1px solid #334155;">
                    <small class="text-secondary d-block mb-1">Risk Mitigation Strategy</small>
                    <h5 class="mt-2 text-white" style="line-height: 1.5;">
                        {{ $rec->risk_mitigation ?? 'Establish secondary sourcing pipelines and ensure buffer storage.' }}
                    </h5>
                </div>
            </div>

            <!-- Diversification Strategy -->
            <div class="col-md-6 col-lg-6">
                <div class="p-3 rounded h-100" style="background: rgba(30, 41, 59, 0.4); border: 1px solid #334155;">
                    <small class="text-secondary d-block mb-1">Supplier Diversification Index</small>
                    <h5 class="mt-2 text-white" style="line-height: 1.5;">
                        {{ $rec->diversification_strategy ?? 'Active monitoring of alternative regional hubs recommended.' }}
                    </h5>
                </div>
            </div>

            <!-- Sourcing Priority -->
            <div class="col-md-6 col-lg-6">
                <div class="p-3 rounded h-100" style="background: rgba(30, 41, 59, 0.4); border: 1px solid #334155;">
                    <small class="text-secondary d-block mb-1">Sourcing Priority Rating</small>
                    <h4 class="mt-2 @if($rec->sourcing_priority === 'High Priority') text-danger @elseif($rec->sourcing_priority === 'Medium Priority') text-warning @else text-success @endif">
                        {{ $rec->sourcing_priority ?? 'Standard Priority' }}
                    </h4>
                    <small class="text-secondary">Based on aggregate regional threat evaluation</small>
                </div>
            </div>
        </div>

        <div class="p-3 rounded d-flex justify-content-between align-items-center" style="background: rgba(15, 23, 42, 0.6); border: 1px solid #1e293b;">
            <span style="font-size:12px; color:#64748b;">System Recommendation Status: Active</span>
            <span style="font-size:12px; color:#64748b;">Last Updated: {{ $rec->updated_at ? $rec->updated_at->format('Y-m-d H:i:s') : '-' }}</span>
        </div>
    @else
        <div class="text-center py-5 text-secondary">
            <span style="font-size: 40px;">📭</span>
            <p class="mt-2">No strategic recommendations available for this country.</p>
        </div>
    @endif
</div>
