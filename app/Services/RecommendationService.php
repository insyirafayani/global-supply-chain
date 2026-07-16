<?php

namespace App\Services;

use App\Models\Country;
use App\Models\TradeRecommendation;

class RecommendationService
{

    public function generate(Country $country)
    {
        $risk     = $country->riskScores()->latest()->first();

        // Default values
        $recommendation = 'Monitor Before Investment';
        $priority       = 'Medium';
        $reason         = 'Market condition requires monitoring';
        $action         = 'Monitor economic and logistics indicators';
        $confidence     = 70;

        if ($risk) {
            $score = $risk->total_score;
            $confidence = min(98, max(50, 100 - abs(50 - $score))); // Dynamic confidence calculation based on risk score stability

            if ($score <= 30) {
                $recommendation = 'Suitable for Trade Expansion';
                $priority       = 'Low';
                $reason         = 'Stable supply chain conditions detected across all indicators.';
                $action         = 'Maintain and potentially increase trade activity. Favorable conditions.';
            } elseif ($score <= 60) {
                $recommendation = 'Monitor Before Investment';
                $priority       = 'Medium';
                $reason         = 'Moderate supply chain risk. Market conditions are unstable.';
                $action         = 'Implement risk mitigation measures before major trade commitments.';
            } elseif ($score <= 85) {
                $recommendation = 'Avoid New Shipment';
                $priority       = 'High';
                $reason         = 'High supply chain risk detected. Multiple risk factors are elevated.';
                $action         = 'Delay major trade decisions until risk level decreases. Consider alternative routes.';
            } else {
                $recommendation = 'Delay Shipment Immediately';
                $priority       = 'Critical';
                $reason         = 'Critical level of threat to logistics, currency stability, or weather anomalies.';
                $action         = 'Suspend all pending shipments. Evacuate or secure assets in the region immediately.';
            }
        }

        return TradeRecommendation::updateOrCreate(
            [
                'country_id' => $country->id,
            ],
            [
                'recommendation' => $recommendation,
                'priority'       => $priority,
                'reason'         => $reason,
                'action'         => $action,
                'confidence'     => $confidence,
            ]
        );
    }

}