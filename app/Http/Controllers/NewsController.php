<?php

namespace App\Http\Controllers;


use App\Models\Country;
use App\Services\NewsService;
use App\Services\SentimentService;



class NewsController extends Controller
{


    public function sync(
        Country $country,
        NewsService $news,
        SentimentService $sentiment
    )
    {


        $result =
        $news->fetchNews(
            $country,
            $sentiment
        );



        if($result===false){


            return back()
            ->with(
                'error',
                'GNews API gagal mengambil berita'
            );


        }



        return back()
        ->with(
            'success',
            $result.' berita berhasil disimpan'
        );


    }


}