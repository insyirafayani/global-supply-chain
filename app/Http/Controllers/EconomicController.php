<?php

namespace App\Http\Controllers;


use App\Models\Country;
use App\Models\EconomicData;
use App\Services\EconomicService;



class EconomicController extends Controller
{


    public function sync(
        Country $country,
        EconomicService $service
    )
    {



        $data =
        $service->getEconomicData(
            $country->iso3
        );



        EconomicData::updateOrCreate(

        [

            'country_id'=>$country->id,

            'year'=>date('Y')-1

        ],


        [

            'gdp'=>$data['gdp'] ?? 0,

            'inflation'=>$data['inflation'] ?? 0,

            'population'=>$data['population'] ?? 0,

            'export_value'=>$data['export'] ?? 0,

            'import_value'=>$data['import'] ?? 0


        ]


        );



        return back()
        ->with(
            'success',
            'Economic data updated'
        );


    }


}