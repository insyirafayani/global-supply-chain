<?php

namespace App\Http\Controllers;


use App\Models\Country;
use App\Models\NewsCache;
use App\Models\WeatherData;
use App\Models\RiskScore;



class DashboardController extends Controller
{


    public function index()
    {


        $totalCountries =
        Country::count();



        $totalNews =
        NewsCache::count();



        $weatherCount =
        WeatherData::count();



        $riskCount =
        RiskScore::count();



        $countries =
        Country::all();



        return view(
            'dashboard.index',
            compact(

                'totalCountries',
                'totalNews',
                'weatherCount',
                'riskCount',
                'countries'

            )
        );


    }


}