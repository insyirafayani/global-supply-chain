<?php

namespace App\Http\Controllers;


use App\Models\Country;
use App\Services\RiskService;
use App\Services\RecommendationService;
use App\Services\NewsService;
use App\Services\SentimentService;


class CountryIntelligenceController extends Controller
{


public function generate(
Country $country,
RiskService $risk,
RecommendationService $recommendation,
NewsService $news,
SentimentService $sentiment
)
{


// Risk

$risk->calculate($country);



// Recommendation

$recommendation
->generate($country);



// News

$news
->fetchNews(
$country,
$sentiment
);



return back()
->with(
'success',
'Country intelligence generated successfully'
);


}



}