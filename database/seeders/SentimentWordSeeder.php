<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PositiveWord;
use App\Models\NegativeWord;


class SentimentWordSeeder extends Seeder
{


public function run(): void
{


$positive=[

'growth',
'increase',
'profit',
'stable',
'improve',
'success',
'strong',
'recovery'

];


$negative=[

'war',
'crisis',
'inflation',
'delay',
'disaster',
'risk',
'conflict',
'decline'

];



foreach($positive as $word){

PositiveWord::create([
'word'=>$word
]);

}



foreach($negative as $word){

NegativeWord::create([
'word'=>$word
]);

}



}


}