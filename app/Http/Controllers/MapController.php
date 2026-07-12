<?php

namespace App\Http\Controllers;

use App\Models\Country;

class MapController extends Controller
{

    public function index()
    {

        $countries = Country::select(
            'id',
            'name',
            'latitude',
            'longitude'
        )
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get();


        return view(
            'maps.index',
            compact('countries')
        );

    }

}