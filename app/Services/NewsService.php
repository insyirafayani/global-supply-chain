<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\NewsCache;
use App\Models\Country;


class NewsService
{


    public function fetchNews(
        Country $country,
        SentimentService $sentiment
    )
    {


        $apiKey = env('GNEWS_API_KEY');


        $response = Http::timeout(30)
        ->get(
            'https://gnews.io/api/v4/search',
            [

                'q' => $country->name,

                'lang' => 'en',

                'max' => 10,

                'apikey' => $apiKey

            ]
        );



        if(!$response->successful()){


            return false;


        }



        $articles =
        $response->json()['articles']
        ?? [];




        foreach($articles as $article){



            $text =
            ($article['title'] ?? '')
            .' '
            .
            ($article['description'] ?? '');



            $result =
            $sentiment->analyze($text);



            NewsCache::create([


                'country_id'=>$country->id,


                'title'=>
                $article['title']
                ?? 'No Title',



                'description'=>
                $article['description']
                ?? null,



                'content'=>
                $article['content']
                ?? null,



                'source'=>
                $article['source']['name']
                ?? null,



                'url'=>
                $article['url']
                ?? null,



                'sentiment'=>
                $result['sentiment'],



                'positive_score'=>
                $result['positive_score'],



                'negative_score'=>
                $result['negative_score']


            ]);



        }



        return count($articles);


    }


}