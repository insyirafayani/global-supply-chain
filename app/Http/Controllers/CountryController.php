<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;


class CountryController extends Controller
{

    public function index(Request $request)
    {


        $query = Country::query();



        // Search negara

        if($request->search){

            $query->where(
                'name',
                'like',
                '%'.$request->search.'%'
            );

        }



        // Filter region

        if($request->region){

            $query->where(
                'region',
                $request->region
            );

        }



        $countries = $query
            ->orderBy('name')
            ->paginate(20);



        $regions = Country::select('region')
            ->distinct()
            ->whereNotNull('region')
            ->pluck('region');



        return view(
            'countries.index',
            compact(
                'countries',
                'regions'
            )
        );


    }




    public function show($id)
{

    $country = Country::with([

    'economicData',
    'weatherData',
    'currencyRates',
    'news'

])
->findOrFail($id);



    return view(
        'countries.show',
        compact('country')
    );

}


}