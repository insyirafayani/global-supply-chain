<?php

namespace App\Services;


use App\Models\RiskScore;
use App\Models\Country;


class RiskService
{


    public function calculate(Country $country)
    {
        /*
        ======================
        WEATHER RISK 30%
        ======================
        */
        $weatherRisk = 20; // default low risk
        $weather = $country->weatherData()->latest()->first();

        if ($weather) {
            $temp = $weather->temperature;
            $wind = $weather->wind_speed;
            $rain = $weather->rainfall;

            if ($temp > 35 || $temp < 0 || $wind > 50 || $weather->weather_status === 'Storm Risk' || $weather->weather_status === 'Extreme') {
                $weatherRisk = 90;
            } elseif ($temp > 30 || $temp < 8 || $rain > 15 || $wind > 30 || $weather->weather_status === 'Heavy Rain') {
                $weatherRisk = 60;
            } else {
                $weatherRisk = 20;
            }
        }

        /*
        ======================
        INFLATION RISK 20%
        ======================
        */
        $inflationRisk = 20;
        $economy = $country->economicData()->latest()->first();

        if ($economy) {
            $inf = $economy->inflation;
            if ($inf > 10) {
                $inflationRisk = 90;
            } elseif ($inf > 5) {
                $inflationRisk = 60;
            } elseif ($inf < 0) {
                $inflationRisk = 50; // deflation warning
            } else {
                $inflationRisk = 20;
            }
        }

        /*
        ======================
        CURRENCY RISK 10%
        ======================
        */
        $currencyRisk = 20;
        $currency = $country->currencyRates()->latest()->first();

        if ($currency) {
            $status = $currency->currency_status;
            if ($status === 'Trade Critical') {
                $currencyRisk = 95;
            } elseif ($status === 'Cost Surge') {
                $currencyRisk = 70;
            } elseif ($status === 'Cost Warning') {
                $currencyRisk = 45;
            } else {
                $currencyRisk = 20;
            }
        }

        /*
        ======================
        NEWS SENTIMENT 40%
        ======================
        */
        $newsRisk = 20;
        $totalArticles = $country->news()->count();

        if ($totalArticles > 0) {
            $negativeArticles = $country->news()->where('sentiment', 'Negative')->count();
            $negRatio = $negativeArticles / $totalArticles;

            if ($negRatio >= 0.5 && $negativeArticles >= 3) {
                $newsRisk = 90;
            } elseif ($negativeArticles >= 1) {
                $newsRisk = 50;
            } else {
                $newsRisk = 20;
            }
        }

        /*
        FINAL SCORE
        */
        $totalScore = ($weatherRisk * 0.30)
            + ($inflationRisk * 0.20)
            + ($currencyRisk * 0.10)
            + ($newsRisk * 0.40);

        $totalScore = round($totalScore);

        if ($totalScore <= 30) {
            $level = "Low Risk";
        } elseif ($totalScore <= 60) {
            $level = "Medium Risk";
        } else {
            $level = "High Risk";
        }

        return RiskScore::updateOrCreate(
            [
                'country_id' => $country->id
            ],
            [
                'weather_risk' => $weatherRisk,
                'inflation_risk' => $inflationRisk,
                'news_risk' => $newsRisk,
                'currency_risk' => $currencyRisk,
                'total_score' => $totalScore,
                'risk_level' => $level
            ]
        );
    }



}