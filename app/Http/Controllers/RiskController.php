<?php

namespace App\Http\Controllers;


use App\Models\Country;
use App\Services\RiskService;


class RiskController extends Controller
{


    public function calculate(
        Country $country,
        RiskService $riskService
    )
    {


        $risk =
        $riskService->calculate($country);



        return back()->with(
            'success',
            'Risk Score berhasil dihitung: '
            .$risk->total_score
            .' ('
            .$risk->risk_level
            .')'
        );


    }


}