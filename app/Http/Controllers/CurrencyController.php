<?php

namespace App\Http\Controllers;


use App\Models\Country;
use App\Models\CurrencyRate;
use App\Services\CurrencyService;


class CurrencyController extends Controller
{


    public function sync(
        Country $country,
        CurrencyService $service
    )
    {


        // ambil currency dari API REST Countries

        $currency = $service->getCurrencyCode(
    $country->name
);


        if(!$currency){

            return back()
            ->with(
                'error',
                'Currency tidak ditemukan'
            );

        }



        // ambil rate Exchange API

        $rate =
        $service->getCurrencyRate(
            $currency
        );



        if(!$rate){


            return back()
            ->with(
                'error',
                'Exchange API gagal'
            );

        }



        // simpan currency ke country

        $country->update([

            'currency_code'=>$currency,

            'currency'=>
            $service->getCurrencyName(
                $country->iso2
            )

        ]);





        CurrencyRate::create([


            'country_id'=>$country->id,


            'base_currency'=>'USD',


            'currency_code'=>$currency,


            'exchange_rate'=>$rate,


            'previous_rate'=>$rate,


            'change_percent'=>0,


            'currency_status'=>'Cost Stable',


            'recorded_at'=>now()


        ]);




        return back()
        ->with(
            'success',
            'Currency berhasil diperbarui'
        );


    }


}