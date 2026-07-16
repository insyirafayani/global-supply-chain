<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\NewsCache;
use App\Models\Country;


class NewsService
{


    public function fetchGlobalNews(
        SentimentService $sentiment
    )
    {


        $apiKey = env('GNEWS_API_KEY');


        $keywords = [


            // Supply Chain

            'supply chain',
            'global trade',
            'shipping',
            'logistics',
            'port disruption',


            // Economy

            'inflation',
            'currency',
            'economic growth',
            'GDP',
            'interest rate',


            // Trade

            'export',
            'import',
            'trade agreement',
            'tariff',


            // Crisis

            'oil price',
            'energy crisis',
            'war',
            'sanction'


        ];



        $total = 0;



        foreach($keywords as $keyword){



            $response = Http::timeout(30)
            ->get(
                'https://gnews.io/api/v4/search',
                [

                    'q'=>$keyword,

                    'lang'=>'en',

                    'max'=>10,

                    'apikey'=>$apiKey

                ]
            );



            if(!$response->successful()){

                continue;

            }




            $articles =
            $response->json()['articles']
            ?? [];




            foreach($articles as $article){



                // cek duplicate

                $exists =
                NewsCache::where(
                    'url',
                    $article['url'] ?? ''
                )->exists();



                if($exists){

                    continue;

                }




                $text =
                ($article['title'] ?? '')
                .' '
                .
                ($article['description'] ?? '');




                $result =
                $sentiment->analyze($text);




                NewsCache::create([



                    'country_id'=>null,



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
                    $result['negative_score'],



                    'published_at'=>
                    $article['publishedAt']
                    ?? null



                ]);



                $total++;


            }



        }




        return $total;



    }

public function fetchCountryNews(
    Country $country,
    SentimentService $sentiment
)
{

    $apiKey = env('GNEWS_API_KEY');

    $response = Http::timeout(30)->get(
        'https://gnews.io/api/v4/search',
        [
            'q'       => '"' . $country->name . '" AND (logistics OR shipping OR trade OR economy OR export OR import OR port OR "supply chain")',
            'lang'    => 'en',
            'max'     => 10,
            'apikey'  => $apiKey
        ]
    );

    if(!$response->successful()){
        return 0;
    }

    $articles = $response->json()['articles'] ?? [];

    $total = 0;

    foreach($articles as $article){

        $exists = NewsCache::where(
            'url',
            $article['url'] ?? ''
        )->exists();

        if($exists){
            continue;
        }

        $text =
            ($article['title'] ?? '')
            .' '.
            ($article['description'] ?? '');

        $result = $sentiment->analyze($text);

        NewsCache::create([

            'country_id'      => $country->id,

            'title'           => $article['title'] ?? 'No Title',

            'description'     => $article['description'] ?? null,

            'content'         => $article['content'] ?? null,

            'source'          => $article['source']['name'] ?? null,

            'url'             => $article['url'] ?? null,

            'sentiment'       => $result['sentiment'],

            'positive_score'  => $result['positive_score'],

            'negative_score'  => $result['negative_score'],

            'published_at'    => $article['publishedAt'] ?? null

        ]);

        $total++;

    }

    return $total;

}
public function sync(
    Country $country,
    SentimentService $sentiment
){

    return $this->fetchCountryNews(
        $country,
        $sentiment
    );

}
}